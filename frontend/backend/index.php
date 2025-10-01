<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>To-Do List</h2>
        <input type="text" id="taskInput" placeholder="New Task">
        <button onclick="addTask()">Add</button>
        <ul id="todoList"></ul>
        <button onclick="logout()">Logout</button>
    </div>

    <script src="script.js"></script>
</body>
</html>