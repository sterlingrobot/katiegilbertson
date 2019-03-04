<?php
require_once('includes/configure.php');
ini_set('display_errors', 1);
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_DEFAULT . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$where = (isset($_GET['id'])) ? ' WHERE p.id = :id' : '';

$stmt = $db->prepare("SELECT p.id, a.provider, a.award, a.laurel_image, p.name, YEAR(p.date_completed) AS date_completed, p.status, p.role FROM awards_to_projects a LEFT JOIN projects p ON p.id = a.projects_id $where  ORDER BY date_completed DESC");
$stmt->bindParam('id', $_GET['id']);
$stmt->execute();

$awards = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $awards[$row['id']][] = $row;
}

echo json_encode($awards);

?>
