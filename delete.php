<?php
if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); // Sanitize input

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "myshop";

    // Create connection
    $connection = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Prepare and execute delete statement
    $stmt = $connection->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" indicates the type is integer
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Deletion was successful
        $successMessage = "Client deleted successfully.";
    } else {
        // No rows affected, deletion failed
        $errorMessage = "Error: Client not found or deletion failed.";
    }

    // Close statement and connection
    $stmt->close();
    $connection->close();
}

// Redirect to index.php
header("location: /myshop/index.php");
exit;
?>
