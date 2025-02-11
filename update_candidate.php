<!-- filepath: /C:/laragon/www/VOTING SYSTEM/update_candidate.php -->
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

$id = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$position = $_POST['position'];

// Prepare and bind
$stmt = $conn->prepare("UPDATE candidates SET first_name = ?, last_name = ?, position = ? WHERE id = ?");
$stmt->bind_param("sssi", $first_name, $last_name, $position, $id);

if ($stmt->execute() === TRUE) {
    echo "Candidate updated successfully";
    echo '<br><a href="admin.php"><button>Go Back to Admin</button></a>';
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>