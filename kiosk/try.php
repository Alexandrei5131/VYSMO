<!DOCTYPE html>
<html>
<head>
    <title>Time Input Example</title>
</head>
<body>

<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected time from the form
    $selectedTime = $_POST['selectedTime'];

    // Format the time to be in "h:i a" format
    $formattedTime = date("h:i a", strtotime($selectedTime));

    // Display the formatted time
    echo "Selected time: " . $formattedTime;
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="timeInput">Select a time:</label>
    <input type="time" id="timeInput" name="selectedTime" required>
    <input type="submit" value="Submit">
</form>

</body>
</html>
