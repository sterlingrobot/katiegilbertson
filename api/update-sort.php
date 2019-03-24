<?php
require_once('includes/configure.php');

$records = $_POST['records'];

$vals = array_map(function($record) {
	return '(' . $record['id'] . ', ' . $record['sort'] . ', "", "")';
}, $records);

$query = 'INSERT INTO projects
		(id, sort, name, slug)
		VALUES ';
$query .= implode(',', $vals);
$query .=
	'ON DUPLICATE KEY UPDATE
		sort=VALUES(sort)';

$stmt = $db->prepare($query);

$stmt->execute();

if($stmt->rowCount()) {
	$stmt2 = $db->query('SELECT * FROM projects');
  out($stmt2->fetchAll(PDO::FETCH_OBJ));
} else {
	echo 'error';
}

