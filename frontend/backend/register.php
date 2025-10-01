<?php
<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = ""; // your DB password
$db = "to do list";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"], $_POST["password"], $_POST["repassword"])) {
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $_POST["password"];
    $repassword = $_POST["repassword"];

    if ($password !== $repassword) {
        $message = "Passwords do not match.";
    } else {
        // Check if user exists
        $sql = "SELECT * FROM register WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $message = "Username already exists.";
        } else {
            // Hash password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO register (username, password) VALUES ('$username', '$hashed_password')";
            if ($conn->query($insert)) {
                $message = "Registration successful! You can now login.";
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="repassword" placeholder="Re-enter Password" required>
            <button type="submit" class="register-btn">Register</button>
        </form>
        <?php
            if (!empty($message)) {
                echo '<p id="message">' . htmlspecialchars($message) . '</p>';
            }
        ?>
    </div>
</body>
</html>