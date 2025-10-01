<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = ""; // your DB password
$db = "to do list"; // your DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"], $_POST["password"])) {
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // If you store hashed passwords, use password_verify
        if (password_verify($password, $row["password"])) {
            $_SESSION["username"] = $username;
            header("Location: index.html");
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="passwor d" name="password" placeholder="Password" required>
            <button type="submit" class="login-btn">Login</button>
            <button type="submit" class="register-btn">Register</button>

        </form> 
        <!-- <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="register-btn">Register</button>
        </form> -->
        <?php
            if (!empty($message)) {
                echo '<p id="message">' . htmlspecialchars($message) . '</p>';
            }
            if (isset($_GET['message'])) {
                echo '<p id="message">' . htmlspecialchars($_GET['message']) . '</p>';
            }
        ?>
    </div>
</body>
</html>