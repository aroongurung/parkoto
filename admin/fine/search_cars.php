<?php
include("../../connectdb.php");

$query = $_GET['query'];
$sql = "SELECT register, owner_ssn FROM car WHERE register LIKE '%$query%'"; 
$result = mysqli_query($conn, $sql);

$cars = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cars[] = $row;
}

echo json_encode($cars);
?>
