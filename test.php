<?php
// Enable error reporting for debugging purposes (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables for default email values
$defaultEmail1 = "";
$defaultEmail2 = "";

// Define a function to send an email with error handling
function sendEmail($to, $subject, $message) {
    if (mail($to, $subject, $message)) {
        return true;
    } else {
        return false;
    }
}

?>

<!-- Display a message indicating that upload is working -->
Upload is <b><span style="color: green">WORKING</span></b><br>

<!-- Display a message indicating that email checking is in progress -->
Check Mailing ..<br>

<!-- Create a form for users to input email addresses -->
<form method="post">
    <input type="text" name="email1" value="<?php echo $defaultEmail1; ?>" required>
    <input type="text" name="email2" value="<?php echo $defaultEmail2; ?>" required>
    <input type="submit" value="Send test >>">
</form>
<br>

<?php
// Check if the form has been submitted and both email fields are filled
if (!empty($_POST['email1']) && !empty($_POST['email2'])) {
    $xx = mt_rand(); // Generate a random number

    // Send emails and handle errors
    if (sendEmail($_POST['email1'], "Result Report Test - " . $xx, "WORKING !") &&
        sendEmail($_POST['email2'], "Result Report Test - " . $xx, "WORKING !")) {
        // Display a success message along with the email addresses
        echo "<b>Successfully sent a report to [" . $_POST['email1'] . "] and [" . $_POST['email2'] . "] - $xx</b>";
    } else {
        // Display a failure message if sending emails failed
        echo "<b>Failed to send a report to [" . $_POST['email1'] . "] and/or [" . $_POST['email2'] . "]</b>";
    }
}
?>
