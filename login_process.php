<?php
session_start();

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

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['loggedin'] = true;
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $row['role'];
    if ($row['role'] == 'admin') {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: vote_form.php");
        exit();
    }
} else {
    echo "Invalid email or password";
    echo "<br><a href='login.php'><button>Go Back to Login</button></a>";
}

$conn->close();
?>
