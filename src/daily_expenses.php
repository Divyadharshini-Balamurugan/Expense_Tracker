<?php 
include("session.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Daily Expenses Tracker">
    <meta name="author" content="">
    <title>Daily Expenses</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Chart.js and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="border-right" id="sidebar-wrapper">
            <div class="user">
                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="120">
                <h5><?php echo $username ?></h5>
                <p><?php echo $useremail ?></p>
            </div>
            <div class="sidebar-heading">Management</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action"><span data-feather="home"></span> Dashboard</a>
                <a href="add_expense.php" class="list-group-item list-group-item-action"><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action"><span style="font-size: 1.5rem;">₹</span> Manage Expenses</a>
                <a href="daily_expenses.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="calendar"></span> Daily Expense</a>
                <a href="excel_export.php" class="list-group-item list-group-item-action"><span data-feather="table"></span> Export Excel</a>
                <a href="save_goal.php" class="list-group-item list-group-item-action"><span data-feather="target"></span> Set Goals</a>
            </div>
            <div class="sidebar-heading">Settings </div>
            <div class="list-group list-group-flush">
                <a href="profile.php" class="list-group-item list-group-item-action"><span data-feather="user"></span> Profile</a>
                <a href="logout.php" class="list-group-item list-group-item-action"><span data-feather="power"></span> Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light border-bottom">
                <button class="toggler" type="button" id="menu-toggle" aria-expanded="false">
                    <span data-feather="menu"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="25">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="profile.php">Your Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid">
                <h3 class="mt-4">Expenses Overview</h3>

                <!-- Daily Expense Section -->
                <div class="text-center my-4">
                    <h5>Daily Expense</h5>
                    <label for="expense-date">Select Date: </label>
                    <input type="date" id="expense-date"  min="2000-01-01" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>">
                </div>
                <div class="chart-container" style="width: 50%; margin: auto;">
                    <canvas id="dailyExpenseChart"></canvas>
                </div>

                <!-- Monthly Expense Section -->
                <div class="text-center my-4">
                    <h5>Monthly Expense</h5>
                    <label for="expense-month">Select Month and Year: </label>
                    <input type="month" id="expense-month" min="2000-01" max="<?= date('Y-m'); ?>" value="<?= date('Y-m'); ?>">
                </div>
                <div class="chart-container" style="width: 50%; margin: auto;">
                    <canvas id="monthlyExpenseChart"></canvas>
                </div>

                <!-- Yearly Expense Section -->
                <div class="text-center my-4">
                    <h5>Yearly Expense</h5>
                    <label for="expense-year">Select Year: </label>
                    <input type="number" id="expense-year" min="2000" max="<?= date('Y'); ?>" value="<?= date('Y'); ?>">
                </div>
                <div class="chart-container" style="width: 50%; margin: auto;">
                    <canvas id="yearlyExpenseChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and Feather Icons -->
    <script src="js/bootstrap.min.js"></script>
    <script>
        feather.replace();

        let dailyChart, monthlyChart, yearlyChart;
        const dailyCtx = document.getElementById('dailyExpenseChart').getContext('2d');
        const monthlyCtx = document.getElementById('monthlyExpenseChart').getContext('2d');
        const yearlyCtx = document.getElementById('yearlyExpenseChart').getContext('2d');

        function fetchExpenses(type, value) {
            $.getJSON(`fetch_expenses.php?type=${type}&value=${value}`, function (data) {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                const labels = data.map(expense => expense.category);
                const amounts = data.map(expense => expense.total);

                if (type === 'daily') {
                    if (dailyChart) dailyChart.destroy();
                    dailyChart = new Chart(dailyCtx, generateChartConfig(labels, amounts));
                } else if (type === 'monthly') {
                    if (monthlyChart) monthlyChart.destroy();
                    monthlyChart = new Chart(monthlyCtx, generateChartConfig(labels, amounts));
                } else if (type === 'yearly') {
                    if (yearlyChart) yearlyChart.destroy();
                    yearlyChart = new Chart(yearlyCtx, generateChartConfig(labels, amounts));
                }
            });
        }

        function generateChartConfig(labels, data) {
            return {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Expenses',
                        data: data,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            };
        }

        $('#expense-date').on('change', function () {
            fetchExpenses('daily', $(this).val());
        });

        $('#expense-month').on('change', function () {
            fetchExpenses('monthly', $(this).val());
        });

        $('#expense-year').on('change', function () {
            fetchExpenses('yearly', $(this).val());
        });

        // Fetch initial data
        const today = new Date().toISOString().split('T')[0];
        const thisMonth = new Date().toISOString().slice(0, 7);
        const thisYear = new Date().getFullYear();
        fetchExpenses('daily', today);
        fetchExpenses('monthly', thisMonth);
        fetchExpenses('yearly', thisYear);
    </script>
</body>

</html>
