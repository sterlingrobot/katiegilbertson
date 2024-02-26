<?php
require_once('includes/configure.php');

$out = array();

$stmt = $db->prepare('SELECT id,
	has_page,
	is_subproject,
	name,
	subtitle,
	slug,
	description,
	YEAR(date_completed) AS date_completed,
	employer,
	customer,
	status,
	role,
	images_folder,
	video_link,
	LENGTH(video_pswd) > 0 AS is_gated,
	social_links,
	sort
	FROM projects
	WHERE is_active = 1
	ORDER BY is_subproject ASC, sort ASC
');
$stmt->execute();

$projects = array();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as &$project) :

	if (is_null($project['slug'])) {
		$parts = array_map(function ($str) {
			return GenerateUrl($str);
		}, [$project['name'], $project['role']]);
		$project['slug'] = implode('/', $parts);
	}

	// SUBPROJECT ARRAY
	$projects[$project['id']]['attributes']['subprojects'] = array();

	// IMAGES ARRAY
	$dir = FS_ROOT . $project['images_folder'];

	$project['images'] = [];

	$stmt9 = $db->prepare('SELECT title, CONCAT("/assets/images/projects/", image) as image
							FROM images_to_projects WHERE projects_id = :id
							ORDER BY sort ASC');
	$stmt9->bindParam('id', $project['id']);
	$stmt9->execute();
	$project['images_new'] = $stmt9->fetchAll(PDO::FETCH_ASSOC);

	// If uses new images table, map images from there
	if ($project['images_new']) {
		$project['images'] = array_map(function ($image) {
			return $image['image'];
		}, $project['images_new']);

		// Else use manually configured images folder
	} else if ($project['images_folder']) {
		$project['images'] = getDirectoryTree($dir, '(jpg|jpeg|png|gif)');
	}

	// PROJECT IMAGE
	$project['image'] =
		// use the first image from the new images table if it exists
		!empty($project['images_new']) && file_exists(FS_ROOT . $project['images_new'][0]['image']) ?

		// use an image named `main_{id}.jpg` if it exists
		$project['images_new'][0]['image'] : (file_exists($dir . DIRECTORY_SEPARATOR . 'main' . '_' . $project['id'] . '.jpg') ?
			$project['images_folder'] . DIRECTORY_SEPARATOR . 'main' . '_' . $project['id'] . '.jpg'

			// use an image named `main_{id}.png` if it exists
			: (file_exists($dir . DIRECTORY_SEPARATOR . 'main' . '_' . $project['id'] . '.png') ?
				$project['images_folder'] . DIRECTORY_SEPARATOR . 'main' . '_' . $project['id'] . '.png'

				// if video_link is a vimeo url, fetch the thumbnail via the vimeo api
				: (preg_match('/vimeo/i', $project['video_link']) && ($vthumb = (string)get_vimeo_thumbnail('https:' . $project['video_link'])) ?
					preg_replace('/https?:/', '', $vthumb)

					// if video_link is a youtube url, construct the thumbnail url
					: (preg_match('/youtube/i', $project['video_link']) && preg_match('/embed\/(.*?)$/', $project['video_link'], $matches) ?
						'https://img.youtube.com/vi/' . $matches[1] . '/mqdefault.jpg'

						// if subproject, use `main_sub.jpg` from the images folder if it exists
						: ($project['is_subproject'] && file_exists($dir . DIRECTORY_SEPARATOR . 'main_sub.jpg') ?
							$project['images_folder'] . DIRECTORY_SEPARATOR . 'main_sub.jpg'

							// if subproject, use `main_sub.png` from the images folder if it exists
							: ($project['is_subproject'] && file_exists($dir . DIRECTORY_SEPARATOR . 'main_sub.png') ?
								$project['images_folder'] . DIRECTORY_SEPARATOR . 'main_sub.png'

								// use `main.jpg` from the images folder if it exists
								: (file_exists($dir . DIRECTORY_SEPARATOR . 'main.jpg') ?
									$project['images_folder'] . DIRECTORY_SEPARATOR . 'main.jpg'

									// use `main.png` from the images folder if it exists
									: (file_exists($dir . DIRECTORY_SEPARATOR . 'main.png') ?
										$project['images_folder'] . DIRECTORY_SEPARATOR . 'main.png'

										// use first image from the images folder
										: $project['images'][0]))))))));

	if (preg_match('/(youtube|vimeo)/i', $project['image'])) {
		list($project['img_width'], $project['img_height']) =
			getimagesize($project['image']);
	} else {
		list($project['img_width'], $project['img_height']) =
			getimagesize($dir . str_replace($project['images_folder'], '', $project['image']));
	}

	$projects[$project['id']] = array(
		'id' => $project['id'],
		'type' => 'project',
		'attributes' => &$project
	);

	// TAGS ARRAY
	$stmt3 = $db->prepare('SELECT t.tag FROM tags_to_projects t2p LEFT JOIN tags t ON t.id = t2p.tags_id WHERE projects_id = :id');

	$stmt3->bindParam('id', $project['id']);
	$stmt3->execute();
	$projects[$project['id']]['attributes']['tags'] = $stmt3->fetchAll(PDO::FETCH_COLUMN, 0);

	// AWARDS ARRAY
	$stmt4 = $db->prepare('SELECT * FROM awards_to_projects WHERE projects_id = :id
													UNION SELECT * FROM awards_to_projects WHERE projects_id IN
															(SELECT subprojects_id FROM subprojects_to_projects WHERE projects_id = :id2)
													ORDER BY award DESC');

	$stmt4->bindParam('id', $project['id']);
	$stmt4->bindParam('id2', $project['id']);
	$stmt4->execute();
	$projects[$project['id']]['attributes']['awards'] = $stmt4->fetchAll(PDO::FETCH_ASSOC);

	// CONTENT ARRAY
	$stmt5 = $db->prepare('SELECT * FROM content_to_projects WHERE projects_id = :id
													ORDER BY sort ASC');

	$stmt5->bindParam('id', $project['id']);
	$stmt5->execute();
	$projects[$project['id']]['attributes']['blocks'] = array_map(function ($block) {
		$block['content'] = htmlspecialchars($block['content']);
		return $block;
	}, $stmt5->fetchAll(PDO::FETCH_ASSOC));

	// LINKS ARRAY
	$stmt7 = $db->prepare('SELECT * FROM links_to_projects WHERE projects_id = :id');

	$stmt7->bindParam('id', $project['id']);
	$stmt7->execute();
	$projects[$project['id']]['attributes']['links'] = $stmt7->fetchAll(PDO::FETCH_ASSOC);

	// SUBPROJECT PARENT
	if ($project['is_subproject']) {

		$stmt2 = $db->prepare('SELECT projects_id FROM subprojects_to_projects WHERE subprojects_id = :id LIMIT 1');
		$stmt2->bindParam('id', $project['id']);
		$stmt2->execute();
		$parent = $stmt2->fetch(PDO::FETCH_ASSOC);

		// bail out if parent project is inactive
		if ($projects[$parent['projects_id']]) {
			// set to parent slug for back link
			$project['is_subproject'] = $projects[$parent['projects_id']]['attributes']['slug'];
			$projects[$parent['projects_id']]['attributes']['subprojects'][] = $project;
		}
	}

endforeach;

usort($projects, 'project_sort');

// Define the custom sort function
function project_sort($a, $b)
{
	if ($a['attributes']['sort'] === $b['attributes']['sort']) {
		return 0;
	}
	return ($a['attributes']['sort'] < $b['attributes']['sort']) ? -1 : 1;
}

$stmt6 = $db->prepare('
	SELECT t.tag, c.count FROM `tags` AS t
		INNER JOIN (SELECT tags_id, COUNT(*) AS count FROM tags_to_projects t2p GROUP BY t2p.tags_id) c
		ON c.tags_id = t.id
		ORDER BY c.count DESC
');
$stmt6->execute();
$tags = $stmt6->fetchAll(PDO::FETCH_ASSOC);

$out['projects'] = $projects;
$out['tags'] = $tags;

out($out);
