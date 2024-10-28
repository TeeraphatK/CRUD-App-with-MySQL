<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>

    <?php include_once('navbar.php'); ?>

    <div class="content-wrapper">
        <div class="container main-content">
            <h2 class="text-center my-4">CRUD App with MySQL</h2>
            <div class="text-center mb-3">
                <a class="btn btn-primary" href="/myshop/create.php" role="button">New Client</a>
            </div>

            <?php
            // Display success/error messages
            if (isset($_GET['message'])) {
                $messageType = isset($_GET['type']) ? $_GET['type'] : 'info';
                echo "<div class='alert alert-$messageType alert-dismissible fade show' role='alert'>
                        <strong>" . htmlspecialchars($_GET['message']) . "</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>";
            }
            ?>

            <div class="table-responsive table-container">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $database = "myshop";

                        // Create connection
                        $connection = new mysqli($servername, $username, $password, $database);

                        // Check connection
                        if ($connection->connect_error) {
                            die("Connection failed: " . htmlspecialchars($connection->connect_error));
                        }

                        // Fetch clients
                        $sql = "SELECT * FROM clients";
                        $result = $connection->query($sql);

                        if (!$result) {
                            die("Invalid query: " . htmlspecialchars($connection->error));
                        }

                        // Check if there are clients
                        if ($result->num_rows > 0) {
                            // Initialize a counter for sequential IDs
                            $counter = 1; 
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                    <tr>
                                        <td>" . htmlspecialchars($counter++) . "</td> <!-- Use counter for sequential ID -->
                                        <td>" . htmlspecialchars($row['name']) . "</td>
                                        <td>" . htmlspecialchars($row['email']) . "</td>
                                        <td>" . htmlspecialchars($row['phone']) . "</td>
                                        <td>" . htmlspecialchars($row['address']) . "</td>
                                        <td>" . htmlspecialchars($row['created_at']) . "</td>
                                        <td>
                                            <a class='btn btn-primary btn-sm' href='/myshop/edit.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a>
                                            <a class='btn btn-danger btn-sm' href='/myshop/delete.php?id=" . htmlspecialchars($row['id']) . "' onclick='return confirmDelete();'>Delete</a>
                                        </td>
                                    </tr>
                                ";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No clients found.</td></tr>";
                        }

                        // Close the connection
                        $connection->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this client?");
        }
    </script>
</body>

</html>
