<?php
session_start();

//Database connection
$host = "localhost";
$user = "root";
$pass = ""; // your DB password
$db = "todo_list"; // your DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
$message = "";
if (isset($_GET['registered'])) {
    $message = "Registration successful. Please login.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"], $_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM register WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row['id'];
            $_SESSION["username"] = $username;
            // Redirect to a main application page, e.g., todo.php
            header("Location: ../../frontend\backend\index.php"); 
            exit();
        } else {
            $message = "Invalid username or password.";
        }
    } else {
        $message = "Invalid username or password.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login</title>
    <!-- <link rel="stylesheet" href="frontend\backend\style.css"> -->
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <main class="center-card" role="main" aria-labelledby="login-title">
        <h2 id="login-title">Login</h2>
        <form action="login.php" method="POST" class="auth-form" novalidate>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn login" >Login</button>
        </form> 
        <button onclick="window.location.href='register.php'" class="btn register" style="margin-top:0.75rem;">Register</button>
        
        <?php if (!empty($message)): ?>
            <p id="message" role="status"><?php echo htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
        <?php endif; ?>
    </main>
</body>
</html>