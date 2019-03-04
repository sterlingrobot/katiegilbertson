<?php
require_once('includes/configure.php');

$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_DEFAULT . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$stmt = $db->prepare("SELECT id, is_subproject, name, description, YEAR(date_completed) AS date_completed, employer, status, role, images_folder, video_link, social_links, sort FROM projects ORDER BY is_subproject ASC");
$stmt->execute();

$projects = array();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($results as &$project) :

    if(!$project['is_subproject']) {


        $projects[$project['id']] = array(
            'id' => $project['id'],
            'type' => "project",
            'attributes' => &$project);


        $projects[$project['id']]['attributes']['subprojects'] = array();
        $stmt3 = $db->prepare("SELECT * FROM awards_to_projects WHERE projects_id = :id
                                UNION SELECT * FROM awards_to_projects WHERE projects_id IN
                                    (SELECT subprojects_id FROM subprojects_to_projects WHERE projects_id = :id2)
                                ORDER BY award DESC");

        $stmt3->bindParam('id', $project['id']);
        $stmt3->bindParam('id2', $project['id']);
        $stmt3->execute();
        $projects[$project['id']]['awards'] = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        $dir = dirname($_SERVER['DOCUMENT_ROOT']) . '/public/assets/' . $project['images_folder'];

        $project['images'] = getDirectoryTree($dir,'(jpg|jpeg|png|gif)');
        $project['image'] = '/assets/' . $project['images_folder'] . DIRECTORY_SEPARATOR . 'main.jpg' ?: $project['images'][0];

    } else {

        $stmt2 = $db->prepare("SELECT projects_id FROM subprojects_to_projects WHERE subprojects_id = :id LIMIT 1");
        $stmt2->bindParam('id', $project['id']);
        $stmt2->execute();
        $parent = $stmt2->fetch(PDO::FETCH_ASSOC);

        if(!isset($projects[$parent['projects_id']])) {

        } else {
            $projects[$parent['projects_id']]['attributes']['subprojects'][] = $parent;
        }
    }

endforeach;

usort($projects, "project_sort");

// Define the custom sort function
function project_sort($a,$b) {
    if ($a['attributes']['sort'] == $b['attributes']['sort']) {
        return 0;
    }
    return ($a['attributes']['sort'] < $b['attributes']['sort']) ? -1 : 1;
}

function getDirectoryTree($outerDir, $x) {

    $dirs = array_diff(scandir($outerDir), array('.', '..'));
    $dir_array = array();
    foreach($dirs as $d) {

        if(is_dir($outerDir . DIRECTORY_SEPARATOR . $d)) {
            $dir_array[] = getDirectoryTree($outerDir . '/' . $d , $x);
        } else {
            if ($x ? preg_match('/' . $x .'$/i', $d) : 1) {
                $outerDir = str_replace('public', 'api', $outerDir );
                $dir_array[] = str_replace($_SERVER['DOCUMENT_ROOT'], '/', $outerDir) . '/' . $d;
            }
        }
    }

    $return = array();
    array_walk_recursive($dir_array, function($a) use (&$return) { $return[] = $a; });

    return $return;
}

echo json_encode($projects);

?>
