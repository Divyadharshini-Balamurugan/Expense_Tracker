<?php
include("config.php");

// Check if the session is already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$sess_email = $_SESSION["email"];
$sql = "SELECT user_id, firstname, lastname, email, profile_path FROM users WHERE email = '$sess_email'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userid = $row["user_id"];
        $firstname = $row["firstname"];
        $lastname = $row["lastname"];
        $username = $row["firstname"] . " " . $row["lastname"];
        $useremail = $row["email"];
        $userprofile = "uploads/" . $row["profile_path"];
        
        // Set user ID into the session
        $_SESSION["user_id"] = $userid;
    }
} else {
    $userid = "GHX1Y2";
    $username = "John Doe";
    $useremail = "mailid@domain.com";
    $userprofile = "Uploads/default_profile.png";

    // Ensure default user ID exists in the session
    if (!isset($_SESSION["user_id"])) {
        $_SESSION["user_id"] = $userid;
    }
}
?>
