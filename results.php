<!-- filepath: /C:/laragon/www/VOTING SYSTEM/results.php -->
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT candidates.position, candidates.first_name, candidates.last_name, COUNT(votes.id) as vote_count
        FROM votes
        JOIN candidates ON votes.candidate_id = candidates.id
        GROUP BY candidates.position, votes.candidate_id";
$result = $conn->query($sql);

$results = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $results[$row['position']][] = [
            'name' => $row['first_name'] . ' ' . $row['last_name'],
            'vote_count' => $row['vote_count']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 60%;
            margin: auto;
        }
    </style>
</head>
<body>
    <h1>Voting Results</h1>
    <?php foreach ($results as $position => $candidates): ?>
        <h2><?php echo htmlspecialchars($position); ?></h2>
        <div class="chart-container">
            <canvas id="chart-<?php echo htmlspecialchars($position); ?>"></canvas>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('chart-<?php echo htmlspecialchars($position); ?>').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode(array_column($candidates, 'name')); ?>,
                        datasets: [{
                            label: 'Votes',
                            data: <?php echo json_encode(array_column($candidates, 'vote_count')); ?>,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: '<?php echo htmlspecialchars($position); ?>'
                            }
                        }
                    }
                });
            });
        </script>
    <?php endforeach; ?>
</body>
</html>