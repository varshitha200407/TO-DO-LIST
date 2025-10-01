const API = "http://localhost:3000";
const username = localStorage.getItem('username');
if (!username) window.location.href = "login.html";

const todoList = document.getElementById('todoList');

function fetchTodos() {
    fetch(${API}/todos/${username})
    .then(res => res.json())
    .then(todos => {
        todoList.innerHTML = '';
        todos.forEach((todo, index) => {
            const li = document.createElement('li');
            li.innerHTML = `<span style="text-decoration:${todo.done ? 'line-through' : 'none'}">${todo.task}</span>
                            <button onclick="toggleTodo(${index})">Toggle</button>
                            <button onclick="deleteTodo(${index})">Delete</button>`;
            todoList.appendChild(li);
        });
    });
}

function addTask() {
    const task = document.getElementById('taskInput').value;
    fetch(${API}/todos/${username}, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ task })
    })
    .then(() => {
        document.getElementById('taskInput').value = '';
        fetchTodos();
    });
}

function toggleTodo(index) {
    fetch(${API}/todos/${username}/${index}, { method: "PUT" })
    .then(() => fetchTodos());
}

function deleteTodo(index) {
    fetch(${API}/todos/${username}/${index}, { method: "DELETE" })
    .then(() => fetchTodos());
}

function logout() {
    localStorage.removeItem('username');
    window.location.href = "login.html";
}

fetchTodos();