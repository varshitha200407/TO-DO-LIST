document.addEventListener('DOMContentLoaded', function() {
    const taskList = document.getElementById('task-list');
    const addTaskBtn = document.getElementById('add-task-btn');
    const taskInput = document.getElementById('task-input');
    const taskDatetime = document.getElementById('task-datetime');

    // --- 1. FETCH AND RENDER TASKS ---
    async function fetchTasks() {
        try {
            const response = await fetch('api.php');
            if (!response.ok) {
                throw new Error(`API responded with status: ${response.status}`);
            }
            const tasks = await response.json();
            renderTasks(tasks);
        } catch (error) {
            console.error("Could not fetch tasks:", error);
            taskList.innerHTML = '<p class="error-message">Could not load tasks. Please try logging in again.</p>';
        }
    }

    function renderTasks(tasks) {
        taskList.innerHTML = '';
        if (tasks.length === 0) {
            taskList.innerHTML = '<p class="no-tasks-message">No tasks yet. Add one to get started!</p>';
            return;
        }
        tasks.forEach(task => {
            const li = createTaskElement(task);
            taskList.appendChild(li);
        });
    }

    function createTaskElement(task) {
        const li = document.createElement('li');
        li.className = `task-item ${task.is_completed == 1 ? 'completed' : ''}`;
        li.dataset.id = task.id;
        li.draggable = true;
        li.innerHTML = `
            <input type="checkbox" class="complete-checkbox" ${task.is_completed == 1 ? 'checked' : ''}>
            <div class="task-details">
                <span>${escapeHTML(task.task)}</span>
                ${task.due_datetime ? `<small class="due-date">Due: ${new Date(task.due_datetime).toLocaleString()}</small>` : ''}
            </div>
            <div class="task-actions">
                <button class="delete-btn" title="Delete Task">&#128465;</button>
            </div>
        `;
        return li;
    }

    // --- 2. ADD A NEW TASK ---
    async function addTask() {
        const taskText = taskInput.value.trim();
        if (taskText === '') return;
        const taskData = {
            task: taskText,
            due_datetime: taskDatetime.value || null
        };
        try {
            await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(taskData)
            });
            taskInput.value = '';
            taskDatetime.value = '';
            fetchTasks(); // Re-fetch to show the new task
        } catch (error) {
            console.error("Error adding task:", error);
        }
    }
    addTaskBtn.addEventListener('click', addTask);
    taskInput.addEventListener('keypress', (e) => e.key === 'Enter' && addTask());

    // --- 3. HANDLE TASK ACTIONS (DELETE, COMPLETE) ---
    taskList.addEventListener('click', async function(e) {
        const taskItem = e.target.closest('.task-item');
        if (!taskItem) return;
        const taskId = taskItem.dataset.id;

        if (e.target.classList.contains('delete-btn')) {
            await fetch('api.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: taskId })
            });
            taskItem.remove();
        } else if (e.target.classList.contains('complete-checkbox')) {
            const isCompleted = e.target.checked ? 1 : 0;
            taskItem.classList.toggle('completed', isCompleted);
            await fetch('api.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: taskId, is_completed: isCompleted })
            });
        }
    });

    // --- 4. DRAG AND DROP ---
    taskList.addEventListener('dragstart', e => e.target.classList.add('dragging'));
    taskList.addEventListener('dragend', e => e.target.classList.remove('dragging'));
    taskList.addEventListener('dragover', e => {
        e.preventDefault();
        const afterElement = getDragAfterElement(taskList, e.clientY);
        const currentlyDragged = document.querySelector('.dragging');
        if (afterElement == null) {
            taskList.appendChild(currentlyDragged);
        } else {
            taskList.insertBefore(currentlyDragged, afterElement);
        }
    });

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.task-item:not(.dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            return (offset < 0 && offset > closest.offset) ? { offset, element: child } : closest;
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function escapeHTML(str) {
        const p = document.createElement('p');
        p.textContent = str;
        return p.innerHTML;
    }

    // --- INITIAL LOAD ---
    fetchTasks();
});