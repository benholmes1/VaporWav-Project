<?php
include 'dbconn.php';

$commlquery = $selectComments;
$commlqueryRes = $conn->query(commlquery);

$record_set = array();
while ($row = $commlqueryRes->fetch_assoc()) {
    array_push($record_set, $row);
}
$commlqueryRes->free_result();

$conn->close();
echo json_encode($record_set);
