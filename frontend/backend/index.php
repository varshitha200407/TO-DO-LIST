<?php
session_start();
require_once 'db.php'; // This establishes the $conn connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_details = null;

// Fetch user details
$stmt = $conn->prepare("SELECT username, created_at FROM register WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_details = $result->fetch_assoc();
}
$stmt->close();
// The connection $conn remains open for the rest of the script if needed.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="blob a"></div>
    <div class="blob b"></div>
    <div class="blob c"></div>

    <!-- Main container for all content -->
    <div class="container">
        <header>
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <a href="logout.php" class="logout-btn">Logout</a>
        </header>

        <!-- User Details Card -->
        <?php if ($user_details): ?>
        <div class="profile-card-inline">
            <div class="profile-details">
                <div class="detail-item">
                    <span class="label">Username:</span>
                    <span class="value"><?php echo htmlspecialchars($user_details['username']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Member Since:</span>
                    <span class="value"><?php echo date("M d, Y", strtotime($user_details['created_at'])); ?></span>
                </div>
            </div>
            <div class="datetime-container">
                <p id="current-datetime"></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Task Input Form -->
        <div class="task-input-container">
            <input type="text" id="task-input" placeholder="Add a new task...">
            <input type="datetime-local" id="task-datetime">
            <button id="add-task-btn">&#43; Add Task</button>
        </div>

        <!-- New Task List Container -->
        <div class="task-list-container">
            <h3>My Tasks</h3>
            <ul class="task-list" id="task-list">
                <!-- Tasks are supposed to be rendered here by JavaScript -->
            </ul>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        // Live Date & Time Display
        const datetimeElement = document.getElementById('current-datetime');
        function updateDateTime() {
            if (datetimeElement) {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                datetimeElement.textContent = now.toLocaleDateString(undefined, options);
            }
        }
        setInterval(updateDateTime, 1000);
        updateDateTime(); // Initial call
    </script>
</body>
</html>