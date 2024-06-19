<?php
include('../../database.php');

// Check if the color and type parameters are set
if (isset($_POST['color']) && isset($_POST['type'])) {
    $color = $_POST['color'];
    $type = $_POST['type'];

    // Update the theme color in the database based on the type
    if ($type === 'color') {
        $updateQuery = "UPDATE theme_color SET color = '$color' WHERE id = 1";
    } elseif ($type === 'colorBG') {
        $updateQuery = "UPDATE theme_color SET colorBG = '$color' WHERE id = 1";
    } elseif ($type === 'colorBG1') {
        $updateQuery = "UPDATE theme_color SET colorBG1 = '$color' WHERE id = 1";
    } elseif ($type === 'colorBG2') {
        $updateQuery = "UPDATE theme_color SET colorBG2 = '$color' WHERE id = 1";
    }

    mysqli_query($connection, $updateQuery);
    error_log("Theme color updated successfully!");
}
?>