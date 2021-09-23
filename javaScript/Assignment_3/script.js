//"use strict";
let tasks = [];

const getPriorityName = function (priority) {
  switch (priority) {
    case "1":
      return "High";
    case "2":
      return "Medium";
    case "3":
      return "Low";
    default:
      return "";
  }
};

const deleteTask = function (i) {
  if (!confirm("Are you sure ?")) return;
  tasks.splice(i, 1);
  renderTable();
};
const moveUp = function (i) {
  if (i == 0) return;
  const oldTask = tasks[i];
  tasks[i] = tasks[i - 1];
  tasks[i - 1] = oldTask;
  renderTable();
};
const moveDown = function (i) {
  if (i == tasks.length - 1) return;
  const oldTask = tasks[i];
  tasks[i] = tasks[i + 1];
  tasks[i + 1] = oldTask;
  renderTable();
};

const renderTable = function () {
  const tbody = document.querySelector("#tasks_tbody");
  tbody.innerHTML = "";
  tasks.forEach((t, i) => {
    const row = `
        <tr>
        <td>${i + 1}</td>
        <td>${t.name}</td>
        <td>${getPriorityName(t.priority)}</td>
        <td>
        ${
          i > 0
            ? `<button class="btn btn-sm btn-secondary" onclick="moveUp(${i})">Up</button>`
            : ``
        }
        ${
          i < tasks.length - 1
            ? `<button class="btn btn-sm btn-secondary" onclick="moveDown(${i})">Down</button>`
            : ``
        }
        </td>
        <td>
        <button class="btn btn-primary btn-sm" onclick="editTask(${i})">Edit</button>
        <button class="btn btn-success btn-sm" style="display:none;">Save</button>
        <button class="btn btn-danger btn-sm" style="display:none;">Cancel</button>
        <button class="btn btn-danger btn-sm" onclick="deleteTask(${i})">Delete</button></td>
        </tr>
        `;
    tbody.insertAdjacentHTML("beforeEnd", row);
  });
};



const addTask = function () {
  console.log(this);
  const taskName = document.querySelector("#task_name").value;
  const priority = document.querySelector("#task_priority").value;
  if (taskName !== "" && priority > 0) {
    tasks.push({
      name: taskName,
      priority: priority,
    });
    renderTable();
  }
};

const editTask = function (taskid){

  let targetTaskRow = document.querySelector("#tasks_tbody").querySelectorAll("tr") [taskid];
  let targetRowChilderin = targetTaskRow.children;

  targetRowChilderin[1].innerHTML=`<input type='text' id="editTask_name-${taskid}" class='form-control' value='${tasks[taskid].name}' />`;
  targetRowChilderin[2].innerHTML=`<select id="editTask_priority-${taskid}" class="form-control">
                                  <option value="1">High</option>
                                  <option value="2">Medium</option>
                                  <option value="3">Low</option>
                                  </select>` ;
  targetRowChilderin[4].innerHTML=`<button class="btn btn-primary btn-sm" style="display:none;" onclick="editTask(${taskid})">Edit</button>
                                   <button class="btn btn-success btn-sm" onclick="saveTask(${taskid})">Save</button>
                                   <button class="btn btn-danger btn-sm" onclick="cancelTask(${taskid})">Cancel</button>
                                   <button class="btn btn-danger btn-sm" onclick="deleteTask(${taskid})">Delete</button> ` ;
  
}


let saveTask = function (taskid){

let newTaskValue = document.querySelector (`#editTask_name-${taskid}`).value;
let newTaskPriorty = document.querySelector (`#editTask_priority-${taskid}`).value;
tasks.splice(taskid, 1, {
  name: newTaskValue,
  priority: newTaskPriorty,
} );
  renderTable();

}


let cancelTask = function (taskid){
  renderTable();
}


document.querySelector("#add").addEventListener("click", addTask);
var name = "Test3";
var age = 22;
const calcFunction = () => {
  console.log(this);
  console.log(`My name is ${this.name} I'm ${this.age} years old`);
};
const obj = {
  name: "Test",
  age: 35,
  cal: calcFunction,
};

const obj2 = {
  name: "Test2",
  age: 22,
  cal: calcFunction,
};

function thisTest() {
  let obj1 = "Ramy";
  var obj2 = "Ahmed";
  console.log(this);
  const x = () => {
    console.log(this);
  };
  x();
}
 