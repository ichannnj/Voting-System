<?php
session_start();
require_once 'db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("You must be logged in to vote.");
}

// Ensure the database connection is established
$conn = openConnection(); // Assuming openConnection() is a function in db_connection.php

// Check if user_id is set
if (!isset($_SESSION['user_id'])) {
    die("Invalid request.");
}

$user_id = $_SESSION['user_id'];

// Check if the user has already voted
$query = "SELECT has_voted FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($has_voted);
$stmt->fetch();
$stmt->close();

if ($has_voted) {
    die("You have already voted.");
}

// Fetch positions from the database
$positions_query = "SELECT DISTINCT position FROM candidates";
$positions_result = $conn->query($positions_query);

$success = false;

if ($positions_result->num_rows > 0) {
    while ($position_row = $positions_result->fetch_assoc()) {
        $position = $position_row['position'];
        $candidate_id_key = 'candidate_id_' . $position;

        // Check if candidate_id for the position is set
        if (!isset($_POST[$candidate_id_key])) {
            continue; // Skip this position if no candidate was selected
        }

        $candidate_id = $_POST[$candidate_id_key];

        // Check if the user has already voted for this position
        $query = "SELECT * FROM votes WHERE user_id = ? AND position = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $user_id, $position);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "You have already voted for the position of " . htmlspecialchars($position) . ".<br>";
            $stmt->close();
            continue;
        }

        // Insert the vote into the database
        $query = "INSERT INTO votes (user_id, candidate_id, position) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $user_id, $candidate_id, $position);

        if ($stmt->execute()) {
            echo "Your vote for the position of " . htmlspecialchars($position) . " has been recorded.<br>";
            $success = true;
        } else {
            echo "There was an error recording your vote for the position of " . htmlspecialchars($position) . ".<br>";
        }

        $stmt->close();
    }
} else {
    echo "No positions available.";
}

if ($success) {
    // Update the has_voted column in the users table
    $query = "UPDATE users SET has_voted = TRUE WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    echo '<div class="alert alert-success" role="alert">All your votes have been successfully recorded!</div>';
    echo '<a href="results.php" class="btn btn-primary">View Results</a>';
}

$conn->close();
?>