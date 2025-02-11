<!-- filepath: /C:/laragon/www/VOTING SYSTEM/get_candidates.php -->
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

$sql = "SELECT id, first_name, last_name, position FROM candidates";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$candidates = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $candidates[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($candidates);
?>