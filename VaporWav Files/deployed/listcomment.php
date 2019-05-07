<?php
include 'dbconn.php';

$commlquery = "SELECT * FROM comments ORDER BY created asc";
$commlqueryRes = $conn->query(commlquery);

$record_set = array();
while ($row = $commlqueryRes->fetch_assoc()) {
    array_push($record_set, $row);
}
$commlqueryRes->free_result();

$conn->close();
echo json_encode($record_set);
