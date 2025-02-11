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

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$position = $_POST['position'];

$sql = "INSERT INTO candidates (first_name, last_name, position) VALUES ('$first_name', '$last_name', '$position')";

if ($conn->query($sql) === TRUE) {
    echo "New candidate added successfully";
    echo "<br><a href='admin.php'><button>Go Back to Admin</button></a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
