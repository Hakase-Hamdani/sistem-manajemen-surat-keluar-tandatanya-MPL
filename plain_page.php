<?php
session_start();
include 'db.php';

// Get the user ID from the URL
$user_id = isset($_GET['id']) ? $_GET['id'] : "";

// Sanitize the user ID
$user_id = mysqli_real_escape_string($conn, $user_id);

// Fetch user details
$sql = mysqli_query($conn, "SELECT * FROM penerbit WHERE id_user='$user_id'");
$row = mysqli_fetch_array($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="themes/midnight-green.css">
    <title>Plain Page</title>
</head>
<body>
    <h2>Your Data</h2>
    <p>Nama: <?php echo $row['nama']; ?></p>
    <p>NIP: <?php echo $row['NIP']; ?></p>
    <p>Jabatan: <?php echo $row['jabatan']; ?></p>
    <p>Status: <?php echo $row['status']; ?></p>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>