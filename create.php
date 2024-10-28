<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$name = "";
$email = "";
$phone = "";
$address = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $errorMessage = "All fields are required.";
    } elseif (!is_numeric($phone)) {
        $errorMessage = "Phone number must contain only numbers.";
    } else {
        // Check if email already exists using prepared statements
        $checkEmailQuery = "SELECT * FROM clients WHERE email = ?";
        $stmt = $connection->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult && $checkResult->num_rows > 0) {
            $errorMessage = "This email is already registered.";
        } else {
            // Check if name already exists
            $checkNameQuery = "SELECT * FROM clients WHERE name = ?";
            $stmt = $connection->prepare($checkNameQuery);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $checkNameResult = $stmt->get_result();

            if ($checkNameResult && $checkNameResult->num_rows > 0) {
                $errorMessage = "This name is already registered.";
            } else {
                // Insert data into the database using prepared statements
                $insertQuery = "INSERT INTO clients (name, email, phone, address) VALUES (?, ?, ?, ?)";
                $stmt = $connection->prepare($insertQuery);
                $stmt->bind_param("ssss", $name, $email, $phone, $address);
                $result = $stmt->execute();

                if (!$result) {
                    $errorMessage = "Error: " . $connection->error;
                } else {
                    // Clear values after successful insert
                    $name = "";
                    $email = "";
                    $phone = "";
                    $address = "";

                    $successMessage = "Client added successfully.";
                    header("location: /myshop/index.php");
                    exit;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Client</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container my-5">
    <h2>New Client</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong><?php echo $errorMessage; ?></strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong><?php echo $successMessage; ?></strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    <?php endif; ?>

    <form method="post" action="create.php">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
