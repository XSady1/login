<?php
session_start(); // Start the session to check user login status

// Display all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$db_username = "your_db_username"; // Replace with your database username
$db_password = "your_db_password"; // Replace with your database password
$dbname = "your_db_name"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$login = $password = "";
$login_err = $password_err = "";

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate login
    if (empty(trim($_POST["login"]))) {
        $login_err = "Please enter username or phone number.";
    } else {
        $login = trim($_POST["login"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check for errors before querying the database
    if (empty($login_err) && empty($password_err)) {
        // Prepare an SQL statement
        $sql = "SELECT id, username, password FROM users WHERE username = ? OR phone_number = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement
            $stmt->bind_param("ss", $param_login, $param_login);
            $param_login = $login;
            
            // Execute the statement
            if ($stmt->execute()) {
                $stmt->store_result();
                
                // Check if a user exists
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        // Check if the password is correct
                        if ($password === $hashed_password) {
                            // Start a new session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            header("location: index.php");
                            exit();
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $login_err = "No account found with that username or phone number.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close the statement
            $stmt->close();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    
    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <header>
        <h1>Login Page</h1>
    </header>
    <main>
        <form method="post" action="">
            <label for="login">Username or Phone Number:</label>
            <input type="text" id="login" name="login" value="<?php echo htmlspecialchars($login); ?>" required>
            <span><?php echo $login_err; ?></span>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span><?php echo $password_err; ?></span>

            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
