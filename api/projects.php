<?php
require_once('includes/configure.php');

$stmt = $db->prepare('SELECT id,
	is_subproject,
	name,
	slug,
	description,
	YEAR(date_completed) AS date_completed,
	employer,
	status,
	role,
	images_folder,
	video_link,
	video_pswd IS NOT NULL AS is_gated,
	social_links,
	sort
	FROM projects
	ORDER BY is_subproject ASC
');
$stmt->execute();

$projects = array();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($results as &$project) :

		$dir = FS_ROOT . $project['images_folder'];

		// PROJECT IMAGE
		$project['image'] = $project['images_folder'] . DIRECTORY_SEPARATOR .
			'main' . ($project['is_subproject'] ? '_' . $project['id'] : '') . '.jpg' ?: $project['images'][0];

		list($project['img_width'], $project['img_height']) =
				getimagesize($dir . str_replace($project['images_folder'], '', $project['image']));

		$projects[$project['id']] = array(
				'id' => $project['id'],
				'type' => 'project',
				'attributes' => &$project);

		// SUBPROJECT ARRAY
		$projects[$project['id']]['attributes']['subprojects'] = array();

		// AWARDS ARRAY
		$stmt3 = $db->prepare('SELECT * FROM awards_to_projects WHERE projects_id = :id
														UNION SELECT * FROM awards_to_projects WHERE projects_id IN
																(SELECT subprojects_id FROM subprojects_to_projects WHERE projects_id = :id2)
														ORDER BY award DESC');

		$stmt3->bindParam('id', $project['id']);
		$stmt3->bindParam('id2', $project['id']);
		$stmt3->execute();
		$projects[$project['id']]['awards'] = $stmt3->fetchAll(PDO::FETCH_ASSOC);

		// CONTENT ARRAY
		$stmt4 = $db->prepare('SELECT * FROM content_to_projects WHERE projects_id = :id
														ORDER BY sort ASC');

		$stmt4->bindParam('id', $project['id']);
		$stmt4->execute();
		$projects[$project['id']]['blocks'] = array_map(function($block) {
			$block['content'] = htmlspecialchars($block['content']);
			return $block;
		}, $stmt4->fetchAll(PDO::FETCH_ASSOC));

		// IMAGES ARRAY
		$project['images'] = getDirectoryTree($dir,'(jpg|jpeg|png|gif)');

		// SUBPROJECT PARENT
		if($project['is_subproject']) {

				$stmt2 = $db->prepare('SELECT projects_id FROM subprojects_to_projects WHERE subprojects_id = :id LIMIT 1');
				$stmt2->bindParam('id', $project['id']);
				$stmt2->execute();
				$parent = $stmt2->fetch(PDO::FETCH_ASSOC);

				// set to parent slug for back link
				// $project['is_subproject'] = $projects[$parent['projects_id']]['attributes']['slug'];
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

out($projects);

?>
