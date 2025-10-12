<?php
session_start();

// Database configuration - adjust values for your environment
$host = 'localhost';
$user = 'root';
$pass = '';           // set your DB password
$db   = 'todo_list';  // set your database name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'], $_POST['repassword'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    if ($password !== $repassword) {
        $message = 'Passwords do not match.';
    } elseif (strlen($username) < 3 || strlen($password) < 6) {
        $message = 'Username must be at least 3 chars and password at least 6 chars.';
    } else {
        // check existing user (prepared statement)
        $stmt = $conn->prepare('SELECT id FROM register WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = 'Username already exists.';
            $stmt->close();
        } else {
            $stmt->close();
            // insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare('INSERT INTO register (username, password) VALUES (?, ?)');
            $ins->bind_param('ss', $username, $hashed_password);

            if ($ins->execute()) {
                $ins->close();
                header('Location: login.php?registered=1');
                exit;
            } else {
                $message = 'Registration failed. Please try again.';
                $ins->close();
            }
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Register</title>
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="center-card" role="main" aria-labelledby="register-title">
    <h2 id="register-title">Register</h2>

    <form action="register.php" method="POST" class="auth-form" novalidate>
      <input type="text" name="username" placeholder="Username" required minlength="3" />
      <input type="password" name="password" placeholder="Password" required minlength="6" />
      <input type="password" name="repassword" placeholder="Re-enter Password" required minlength="6" />
      <button type="submit" class="btn register">Register</button>
    </form>

    <button onclick="window.location.href='login.php'" class="btn login" style="margin-top:0.75rem;">Login</button>

    <?php if (!empty($message)): ?>
      <p id="message" role="status"><?php echo htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
    <?php endif; ?>
  </main>
</body>
</html>