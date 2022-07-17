<?php
require_once('includes/configure.php');

$out = array();

$stmt = $db->prepare('SELECT id,
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

foreach($results as &$project) :

	// SUBPROJECT ARRAY
	$projects[$project['id']]['attributes']['subprojects'] = array();

	$dir = FS_ROOT . $project['images_folder'];

	// IMAGES ARRAY
	$project['images'] = getDirectoryTree($dir,'(jpg|jpeg|png|gif)');

	// PROJECT IMAGE
	$project['image'] =
		file_exists($dir . DIRECTORY_SEPARATOR . 'main' . '_' . $project['id'] . '.jpg') ?
		$project['images_folder'] . DIRECTORY_SEPARATOR . 'main' . '_' . $project['id'] . '.jpg'
		: (file_exists($dir . DIRECTORY_SEPARATOR . 'main' . '_' . $project['id'] . '.png') ?
					$project['images_folder'] . DIRECTORY_SEPARATOR . 'main' . '_' . $project['id'] . '.png'
					: (preg_match('/vimeo/i', $project['video_link']) && ($vthumb = get_vimeo_thumbnail('https:' . $project['video_link'])) ?
									preg_replace('/https?:/', '', $vthumb)
							: (preg_match('/youtube/i', $project['video_link']) && preg_match('/embed\/(.*?)$/', $project['video_link'], $matches) ?
											'https://img.youtube.com/vi/' . $matches[1] . '/mqdefault.jpg'
									: ($project['is_subproject'] && file_exists($dir . DIRECTORY_SEPARATOR . 'main_sub.jpg') ?
													$project['images_folder'] . DIRECTORY_SEPARATOR . 'main_sub.jpg'
											: ($project['is_subproject'] && file_exists($dir . DIRECTORY_SEPARATOR . 'main_sub.png') ?
															$project['images_folder'] . DIRECTORY_SEPARATOR . 'main_sub.png'
													: (file_exists($dir . DIRECTORY_SEPARATOR . 'main.jpg') ?
																	$project['images_folder'] . DIRECTORY_SEPARATOR . 'main.jpg'
																: (file_exists($dir . DIRECTORY_SEPARATOR . 'main.png') ?
																				$project['images_folder'] . DIRECTORY_SEPARATOR . 'main.png'
																				: $project['images'][0])))))));
		// 'main' . ($project['is_subproject'] ? '_' . $project['id'] : '') . '.jpg' ?: $project['images'][0];

	if(preg_match('/(youtube|vimeo)/i', $project['image'])) {
		list($project['img_width'], $project['img_height']) =
				getimagesize($project['image']);
	} else {
		list($project['img_width'], $project['img_height']) =
				getimagesize($dir . str_replace($project['images_folder'], '', $project['image']));
	}

	$projects[$project['id']] = array(
			'id' => $project['id'],
			'type' => 'project',
			'attributes' => &$project);

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
	$projects[$project['id']]['attributes']['blocks'] = array_map(function($block) {
		$block['content'] = htmlspecialchars($block['content']);
		return $block;
	}, $stmt5->fetchAll(PDO::FETCH_ASSOC));

	// LINKS ARRAY
	$stmt7 = $db->prepare('SELECT * FROM links_to_projects WHERE projects_id = :id');

	$stmt7->bindParam('id', $project['id']);
	$stmt7->execute();
	$projects[$project['id']]['attributes']['links'] = $stmt7->fetchAll(PDO::FETCH_ASSOC);

	// SUBPROJECT PARENT
	if($project['is_subproject']) {

			$stmt2 = $db->prepare('SELECT projects_id FROM subprojects_to_projects WHERE subprojects_id = :id LIMIT 1');
			$stmt2->bindParam('id', $project['id']);
			$stmt2->execute();
			$parent = $stmt2->fetch(PDO::FETCH_ASSOC);

			// set to parent slug for back link
			$project['is_subproject'] = $projects[$parent['projects_id']]['attributes']['slug'];
			$projects[$parent['projects_id']]['attributes']['subprojects'][] = $project;
	}

endforeach;

usort($projects, 'project_sort');

// Define the custom sort function
function project_sort($a,$b) {
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

?>
