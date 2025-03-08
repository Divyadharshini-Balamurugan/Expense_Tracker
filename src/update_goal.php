<?php
include("session.php");

if (isset($_POST['goal'])) {
    $new_goal = $_POST['goal'];
    
    // Update the goal in the database
    $update_goal_query = mysqli_query($con, "UPDATE goals SET monthly_goal = '$new_goal' WHERE user_id = '$userid'");

    if ($update_goal_query) {
        header("Location: goals.php");
    } else {
        echo "Error updating goal!";
    }
}
?>
