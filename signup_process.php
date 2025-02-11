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

$email = $_POST['email'];
$password = md5($_POST['password']);
$role = 'voter'; // Default role for new users

$sql = "INSERT INTO users (email, password, role) VALUES ('$email', '$password', '$role')";

if ($conn->query($sql) === TRUE) {
    echo "New user registered successfully";
    echo "<br><a href='login.php'><button>Go to Login</button></a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
    
$conn->close();
?>
