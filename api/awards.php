<?php
require_once('includes/configure.php');

$where = (isset($_GET['id'])) ? ' WHERE p.id = :id' : '';

$stmt = $db->prepare("SELECT p.id, a.provider, a.award, a.laurel_image, p.name, YEAR(p.date_completed) AS date_completed, p.status, p.role FROM awards_to_projects a LEFT JOIN projects p ON p.id = a.projects_id $where  ORDER BY date_completed DESC");
$stmt->bindParam('id', $_GET['id']);
$stmt->execute();

$awards = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $awards[$row['id']][] = $row;
}

out($awards);
