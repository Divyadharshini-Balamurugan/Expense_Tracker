<?php
// Headers for downloading as Excel file
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; Filename=Expense_Report.xls");

// Start session and include session file
session_start();
include("session.php");

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "dailyexpense"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the current user's identifier from session
$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Ensure the user is logged in
if (!$current_user_id) {
    die("Error: User not logged in.");
}

// Fetch the dates from request (use POST or GET as per your form)
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : null;
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : null;

// Build the query
$query = "SELECT expense, expensedate, expensecategory FROM expenses WHERE user_id = ?";
$params = [$current_user_id];

if ($from_date && $to_date) {
    $query .= " AND expensedate BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
} elseif ($from_date) {
    $query .= " AND expensedate = ?";
    $params[] = $from_date;
}

// Prepare statement
$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Start generating the Excel content
echo "Expense\tExpense Date\tExpense Category\n"; // Header row

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Format the date to ensure compatibility and readability in Excel
        $formattedDate = date("Y-m-d", strtotime($row['expensedate']));
        // Output data
        echo $row['expense'] . "\t" . $formattedDate . "\t" . $row['expensecategory'] . "\n";
    }
} else {
    echo "No data available\n";
}

// Close connection
$conn->close();
?>
