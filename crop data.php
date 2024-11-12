<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root"; // Database username
$password = "";     // Database password
$dbname = "smartdb"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $crop_name = $_POST['crop_name'];
    $crop_type = $_POST['crop_type'];
    $planting_date = $_POST['planting_date'];
    $harvest_date = $_POST['harvest_date'];

    // Insert crop data into database
    $sql = "INSERT INTO crops (crop_name, crop_type, planting_date, harvest_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $crop_name, $crop_type, $planting_date, $harvest_date);

    if ($stmt->execute()) {
        $success_message = "Crop data added successfully!";
    } else {
        $error_message = "Failed to add crop data. Please try again.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Crop - Smart Farming Assistant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Smart Farming Assistant</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="data table.php">data table</a></li>

                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container my-5">
    <h2>Add Crop Data</h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="add_crop.php">
        <div class="mb-3">
            <label for="crop_name" class="form-label">Crop Name</label>
            <input type="text" class="form-control" id="crop_name" name="crop_name" placeholder="Enter crop name" required>
        </div>
        <div class="mb-3">
            <label for="crop_type" class="form-label">Crop Type</label>
            <input type="text" class="form-control" id="crop_type" name="crop_type" placeholder="Enter crop type (e.g., Vegetable, Grain)">
        </div>
        <div class="mb-3">
            <label for="planting_date" class="form-label">Planting Date</label>
            <input type="date" class="form-control" id="planting_date" name="planting_date" required>
        </div>
        <div class="mb-3">
            <label for="harvest_date" class="form-label">Expected Harvest Date</label>
            <input type="date" class="form-control" id="harvest_date" name="harvest_date">
        </div>
        <button type="submit" class="btn btn-success">Add Crop</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
