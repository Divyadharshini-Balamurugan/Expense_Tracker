<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dailyexpense";

$conn = new mysqli($servername, $username, $password, $dbname);

$user_id = 1; // Replace with session user ID
$month = date('Y-m'); // Current month

$sql = "SELECT target_amount, actual_expense FROM monthly_goals WHERE user_id = '$user_id' AND month_year = '$month'";
$result = $conn->query($sql);

$target = 0;
$actual = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $target = $row['target_amount'];
    $actual = $row['actual_expense'];
}

$conn->close();
?>

<div>
    <canvas id="expenseChart"></canvas>
    <p>
        <?php
        if ($actual <= $target) {
            echo "🎉 Great job! You stayed within your budget!";
        } else {
            echo "😔 You've exceeded your budget. Try again next month!";
        }
        ?>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('expenseChart').getContext('2d');
    const expenseChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Target', 'Actual Expense'],
            datasets: [{
                label: 'Monthly Comparison',
                data: [<?php echo $target; ?>, <?php echo $actual; ?>],
                backgroundColor: ['#6ccf6e', '#f76c6c']
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
