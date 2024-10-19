<?php
include("../../connectdb.php");

$query = $_GET['query'];
$sql = "SELECT ssn, person_name FROM person WHERE person_name LIKE '%$query%'";
$result = mysqli_query($conn, $sql);

$persons = [];
while ($row = mysqli_fetch_assoc($result)) {
    $persons[] = $row;
}

echo json_encode($persons);
?>
