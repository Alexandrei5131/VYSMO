<?php

include('../../database.php');
    include('../session.php');

// Fetch color values from the database
$sql = "SELECT * FROM theme_color";
$result = $connection->query($sql);

$colors = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $colors[] = $row['color'];
    }
}

var_dump($colors);

echo json_encode($colors);

$connection->close();

?>