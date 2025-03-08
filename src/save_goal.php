<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dailyexpense";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $month = $_POST['month'];
    $target = $_POST['target'];
    $user_id = 1; // Replace with session or logged-in user's ID

    $sql = "INSERT INTO monthly_goals (user_id, month_year, target_amount)
            VALUES ('$user_id', '$month', '$target')
            ON DUPLICATE KEY UPDATE target_amount = '$target'";

    if ($conn->query($sql) === TRUE) {
        echo "Goal saved successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Goal Tracker</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Set Your Monthly Goal</h1>
        <form method="POST" action="save_goal.php">
            <label for="month">Month:</label>
            <input type="month" id="month" name="month" required>
            <label for="target">Target Amount (₹):</label>
            <input type="number" id="target" name="target" required>
            <button type="submit">Set Goal</button>
        </form>

        <h2>Monthly Expense Comparison</h2>
        <div id="chart-container">
            <canvas id="bulletChart"></canvas>
            <?php
            $sql = "SELECT month_year, target_amount, 
            (SELECT SUM(expense) FROM expenses WHERE user_id = 1 AND DATE_FORMAT(expensedate, '%Y-%m') = month_year) AS spent 
      FROM monthly_goals 
      WHERE user_id = 1";

            $result = $conn->query($sql);
            $chartData = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $chartData[] = [
                        'month' => $row['month_year'],
                        'target' => (int)$row['target_amount'],
                        'spent' => (int)$row['spent'] ?: 0
                    ];
                }
            }
            ?>
        </div>
    </div>

    <script>
        const data = <?php echo json_encode($chartData); ?>;

        const labels = data.map(item => item.month);
        const datasets = data.map(item => ({
            target: item.target,
            spent: item.spent
        }));

        const ctx = document.getElementById('bulletChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar', // Chart.js does not natively support bullet charts; we'll mimic it
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Target Amount',
                        data: datasets.map(d => d.target),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Spent Amount',
                        data: datasets.map(d => d.spent),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                indexAxis: 'y', // Horizontal chart for a bullet chart-like appearance
                scales: {
                    x: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ₹${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
