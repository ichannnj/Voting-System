<?php
session_start();
require_once 'db_connection.php'; // Ensure the db_connection.php file is included

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("You must be logged in to vote.");
}

// Ensure the database connection is established
$conn = openConnection(); // Assuming openConnection() is a function in db_connection.php

// Fetch candidates from the database, ordered by position and then by name
$sql = "SELECT id, first_name, last_name, position FROM candidates ORDER BY position, first_name, last_name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<form action="vote.php" method="POST">';
    echo '<h1>Select a candidate to vote for:</h1>';
    
    $currentPosition = '';
    while ($row = $result->fetch_assoc()) {
        if ($row['position'] !== $currentPosition) {
            if ($currentPosition !== '') {
                echo '</section>';
            }
            $currentPosition = $row['position'];
            echo '<section>';
            echo '<h2>' . htmlspecialchars($currentPosition) . '</h2>';
        }
        echo '<div class="form-check">';
        echo '<input class="form-check-input" type="radio" id="candidate_' . htmlspecialchars($row['id']) . '" name="candidate_id_' . htmlspecialchars($currentPosition) . '" value="' . htmlspecialchars($row['id']) . '" required>';
        echo '<label class="form-check-label" for="candidate_' . htmlspecialchars($row['id']) . '">';
        echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']);
        echo '</label>';
        echo '</div>';
    }
    echo '</section>';
    echo '<button type="submit" class="btn btn-primary">Submit Vote</button>';
    echo '</form>';
} else {
    echo 'No candidates available.';
}

$conn->close();
?>
