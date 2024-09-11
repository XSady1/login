<?php
session_start(); // Start the session to check user login status

$servername = "localhost";
$username = "ads_user";
$password = "Koumbares2024!";
$dbname = "ads_board";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all ads
$sql = "SELECT * FROM ads ORDER BY created_at DESC"; // Adjust table and columns as needed
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="styles/styles.css"> <!-- Ensure this path is correct -->
</head>
<body>
    <header>
        <h1>Welcome to Koumbares</h1>
        <div class="button-container">
            <?php if (isset($_SESSION['username'])): ?>
                <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <button class="button" onclick="window.location.href='logout.php'">Logout</button>
            <?php else: ?>
                <button class="button" onclick="window.location.href='https://koumbares.com/ads_board/login.php'">Login</button>
                <button class="button" onclick="window.location.href='https://koumbares.com/ads_board/registration.php'">Register</button>
            <?php endif; ?>
        </div>
    </header>
    <main>
        <h2>All Ads</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><em>Posted on: <?php echo htmlspecialchars($row['created_at']); ?></em></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No ads available.</p>
        <?php endif; ?>
    </main>
    <footer>
        <section>
            <h2>About</h2>
            <p>Current server time is: <?php echo date('Y-m-d H:i:s'); ?></p>
        </section>
        <p>&copy; <?php echo date('Y'); ?> koumbares.com All rights reserved.</p>
    </footer>
    <?php
    // Close connection
    $conn->close();
    ?>
</body>
</html>
