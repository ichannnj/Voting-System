<!-- filepath: /C:/laragon/www/VOTING SYSTEM/delete_candidate.php -->
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

// Delete related rows in the votes table
$stmt_votes = $conn->prepare("DELETE FROM votes WHERE candidate_id = ?");
$stmt_votes->bind_param("i", $id);
$stmt_votes->execute();
$stmt_votes->close();

// Prepare and bind
$stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute() === TRUE) {
    echo "Candidate deleted successfully";
} else {
    error_log("Error deleting candidate: " . $stmt->error);
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>