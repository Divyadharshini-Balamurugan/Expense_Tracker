<?php
require('config.php');
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get user email from session
$user_email = $_SESSION['email'];

// Fetch user ID based on email
$user_query = "SELECT user_id FROM users WHERE email = '$user_email'";
$user_result = mysqli_query($con, $user_query);
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    echo json_encode(["error" => "User not found."]);
    exit();
}

$user_id = $user['user_id'];

// Get the type of expense data requested (daily, monthly, yearly)
$type = $_GET['type'] ?? 'daily';

// Initialize the query and validation logic based on the type
$query = "";
$params = [];

switch ($type) {
    case 'daily':
        $selected_date = $_GET['value'] ?? date("Y-m-d");

        // Validate the selected date
        if (strtotime($selected_date) > strtotime(date("Y-m-d"))) {
            echo json_encode(["error" => "Invalid date selected."]);
            exit();
        }

        // Query for daily expenses
        $query = "SELECT expensecategory AS category, SUM(expense) AS total 
                  FROM expenses 
                  WHERE user_id = ? AND expensedate = ? 
                  GROUP BY expensecategory";
        $params = [$user_id, $selected_date];
        break;

    case 'monthly':
        $selected_month = $_GET['value'] ?? date("Y-m");

        // Validate the selected month format (YYYY-MM)
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $selected_month)) {
            echo json_encode(["error" => "Invalid month format."]);
            exit();
        }

        // Query for monthly expenses
        $query = "SELECT expensecategory AS category, SUM(expense) AS total 
                  FROM expenses 
                  WHERE user_id = ? AND DATE_FORMAT(expensedate, '%Y-%m') = ? 
                  GROUP BY expensecategory";
        $params = [$user_id, $selected_month];
        break;

    case 'yearly':
        $selected_year = $_GET['value'] ?? date("Y");

        // Validate the selected year
        if (!preg_match('/^\d{4}$/', $selected_year)) {
            echo json_encode(["error" => "Invalid year format."]);
            exit();
        }

        // Query for yearly expenses
        $query = "SELECT expensecategory AS category, SUM(expense) AS total 
                  FROM expenses 
                  WHERE user_id = ? AND YEAR(expensedate) = ? 
                  GROUP BY expensecategory";
        $params = [$user_id, $selected_year];
        break;

    default:
        echo json_encode(["error" => "Invalid type selected."]);
        exit();
}

// Prepare and execute the query
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, str_repeat("s", count($params)), ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch and return the results
$expenses = [];
while ($row = mysqli_fetch_assoc($result)) {
    $expenses[] = $row;
}

echo json_encode($expenses);
?>
