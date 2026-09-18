// script.js: BYU IT&C 210a JavaScript

// Class definitions
class Task {
    constructor({ text, date, done, id }) {
        this.text = text;
        this.date = date;
        this.done = done;
        this.id = id;
        this.dateStamp = new Date(this.date).getTime()
    }

    toHTML() {
        let newHTML = `
                    <li id=${this.id}>
                        <input type="checkbox" ${this.getChecked()} class="task-done checkbox-icon"/>
                        <span class = "task-description">${this.text}</span>
                        <span class = "task-date">${this.prettyDate()}</span>
                        <button class="task-delete material-icon" onclick="deleteEvent(event)">delete</button>
                    </li>
                    `
        if (this.done) {
            newHTML = `
                    <li id=${this.id}>
                        <input type="checkbox" ${this.getChecked()} class="task-done checkbox-icon"/>
                        <span class = "task-description"><s>${this.text}</s></span>
                        <span class = "task-date"><s>${this.prettyDate()}</s></span>
                        <button class="task-delete material-icon" onclick="deleteEvent(event)">delete</button>
                    </li>
                    `
        }

        return newHTML;
    }

    getChecked() {
        if (this.done) {
            return "checked";
        } else {
            return "unchecked";
        }
    }

    prettyDate() {
        let year = this.date.slice(0,4);
        let month = this.date.slice(5,7);
        let day = this.date.slice(8,10);
        return `${month}/${day}/${year}`;
    }

    toggle() {
        if (this.done === true) {
            this.done = false;
        } else {
            this.done = true;
        }
    }
}

// Global Variables
let tasks = [ // tasks is given an example task to demonstrate. This task is overwritten immediately
    new Task({
        text: "First task",
        done: false,
        date: "2020-02-10",
        id: Date.now()
    })
]

let sortedTasks = []
let filteredTasks = []
let sortedFilteredTasks = []

let sorted = false;
let filtered = false;


// Non-CRUD and non-event-handler methods
function updateStorage(newData) {
    localStorage.setItem('database', JSON.stringify(newData));
}

function readStorage() {
    tasks = (JSON.parse(localStorage.getItem('database')) || []).map(taskData => new Task(taskData));
}

function toggleFilter() {
    if (filtered === true) {
        filtered = false;
    } else {
        filtered = true;
    }
    readTasks();
}

function toggleSort() {
    if (sorted === true) {
        sorted = false;
    } else {
        sorted = true;
    }
    readTasks();
}

function addToList(task) {
    if (!(task instanceof Task)) return;
    document.getElementById("tasklist").insertAdjacentHTML("beforeend", task.toHTML());
}

function updateLists() {
    filteredTasks = tasks.slice();
    sortedTasks = tasks.slice();

    filteredTasks = filteredTasks.filter((a) => !a.done);
    sortedTasks = sortedTasks.sort((a,b) => a.dateStamp - b.dateStamp);

    sortedFilteredTasks = filteredTasks.slice();
    sortedFilteredTasks = sortedFilteredTasks.sort((a,b) => a.dateStamp - b.dateStamp);
    updateStorage(tasks);
}

function resetCheckboxes() {
    sorted = false;
    filtered = false;
    document.getElementById("cb-sort").checked = false;
    document.getElementById("cleanup").checked = false;
}

// CRUD Functions
function createTask(event) {
        event.preventDefault();
    let formData = new FormData(event.currentTarget);
    let addedTask = new Task({
        text: formData.get("description"),
        date: formData.get("date"),
        done: false,
        id: Date.now()
    })

    tasks.push(addedTask);
    updateLists();
    readTasks();
}

function readTasks() {
    document.getElementById("tasklist").innerText = ""
    let taskList;
    if (filtered) {
        if (sorted) {
            taskList = sortedFilteredTasks;
        } else {
            taskList = filteredTasks;
        }
    } else if (sorted) {
        taskList = sortedTasks;
    } else {
        taskList = tasks;
    }
    taskList.forEach(addToList);
}

function updateTask(id) {
    const task = tasks.find(t => t.id === id)
    if (!task.done) {
        task.done = true;
    } else {
        task.done = false;
    }

    updateLists();
    readTasks();
}

function deleteTask(id) {
    const task = tasks.find(t => t.id === id)
    const taskIndex = tasks.indexOf(task);
    if (taskIndex > -1) {
        tasks.splice(taskIndex, 1);
    }

    updateLists();
    readTasks();
}

// Event handlers, except createTask()
document.addEventListener("load", resetCheckboxes());
document.addEventListener("load", readStorage());
document.addEventListener("load", updateLists());
document.addEventListener("load", readTasks());
document.addEventListener("change", (event) => {
    if (event.target.classList.contains("task-done")) {
        const taskID = Number(event.target.closest("li").id);
        updateTask(taskID);
    }
})

function deleteEvent(event) {
    const taskID = Number(event.target.closest("li").id);
    deleteTask(taskID);
}