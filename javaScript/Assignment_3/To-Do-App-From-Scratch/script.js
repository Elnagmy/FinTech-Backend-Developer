let tasksList = [];
document.querySelector("#add").addEventListener('click', addtask);

function addtask() {
    let taskName = document.querySelector("#task_name").value;
    let taskPriorty = document.querySelector("#task_priority").value;
    if (isValidTask(taskName)) {
        tasksList.push({
            taskName: taskName,
            priorty: taskPriorty
        });
    }
    randertabel();
}

let isValidTask = function (taskname) {
    return taskname !== "" || taskName == null || str.match(/^ *$/) !== null;
}


const randertabel = function () {
    let tablebody = document.querySelector("#tasks_tbody");
    tablebody.innerHTML = "";
    tasksList.forEach((t, i) => {
        let row = document.createElement("tr");
        let col1 = createCol(i + 1, row);
        let col2 = createCol(t.taskName, row);
        let col3 = createCol(getPriorty(t.priorty), row);
        let cal4 = createSort(i, row);
        let cal5 = createAction(i, row);
        tablebody.appendChild(row);
    });

}

function createCol(text, row) {
    let cal = document.createElement("td");
    var textnode = document.createTextNode(text);
    cal.appendChild(textnode);
    row.appendChild(cal);
}
function createSort(index, row) {
    let cal = document.createElement("td");
    if (index > 0) {
        let moveup = createBtn("btn-secondary");
        moveup.addEventListener("click", function () {
            moveUp(index);
        });
        moveup.appendChild(document.createTextNode("Up"));
        cal.appendChild(moveup);
    }

    if (index < tasksList.length - 1) {
        let moveDown = createBtn("btn-secondary");
        moveDown.addEventListener("click", function () {
            movedown(index);
        });
        moveDown.appendChild(document.createTextNode("Down"));
        cal.appendChild(moveDown);
    }
    row.appendChild(cal);
}


function createBtn( btnCalss ) {
    let btn = document.createElement("button");
    btn.classList.add("btn");
    btn.classList.add("btn-sm");
    btn.classList.add(btnCalss);
    return btn;
}



function createAction(index, row) {
    let cal = document.createElement("td");
    createEditAction( cal , index );
    createSaveAction( cal , index );
    createCancleAction( cal , index );
    createDeleteAction( cal , index );
    row.appendChild(cal);

}

function createEditAction( cal , index){
    let editbtn = createBtn("btn-primary");
    editbtn.id= "editbtn-"+index
    editbtn.addEventListener("click", function () {
        editActionHandler(index);

    });
    editbtn.appendChild(document.createTextNode("Edit"));
    cal.appendChild(editbtn);
}

function editActionHandler(index) {
    document.querySelector("#editbtn-" + index).style.display = "none";
    document.querySelector("#savebtn-" + index).style.display = "";
    document.querySelector("#canclebtn-" + index).style.display = "";
    let taskNode = document.querySelector("#tasks_tbody").querySelectorAll("tr")[index];
    let taskClns = taskNode.querySelectorAll("td");
    createInputEditfield(index, taskClns);
    createPriortySelectEditfield(index, taskClns);

}

function createInputEditfield(index, taskClns) {
    let inputFiled = document.createElement("input");
    inputFiled.classList.add("form-control");
    inputFiled.value = tasksList[index].taskName;
    inputFiled.id = "editTask-"+index;
    let inputCal = taskClns[1];
    taskClns[1].firstChild.nodeValue = "";
    inputCal.appendChild(inputFiled);
}
                                  
function createPriortySelectEditfield (index, taskClns) {
    let selectFiled = document.createElement("select");
    selectFiled.classList.add("form-control");
    selectFiled.id = "editTask_priority-"+index
    let priortyCal = taskClns[2];
    priortyCal.firstChild.nodeValue = "";
    let option1 = createOpationElement(1 , "High" , selectFiled);
    let option2 = createOpationElement(2 , "Medium", selectFiled);
    let option3 = createOpationElement(3 , "Low", selectFiled);
    priortyCal.appendChild(selectFiled);
} 

function createOpationElement (value , text , selectFiled){
    let opetion = document.createElement("option");
    opetion.value = value ; 
    opetion.textContent = text;
    selectFiled.appendChild( opetion);
}

function createSaveAction( cal , index){
    let savebtn = createBtn("btn-success");
    savebtn.style.display="none";
    savebtn.id= "savebtn-"+index
    savebtn.addEventListener("click", function () {
        let newTaskName = document.querySelector("#editTask-"+index).value;
        let newTaskPriotyy = document.querySelector("#editTask_priority-"+index).value;
        tasksList[index].taskName = newTaskName ;
        tasksList[index].priorty = newTaskPriotyy ;
        randertabel();

    });
    savebtn.appendChild(document.createTextNode("Save"));
    cal.appendChild(savebtn);
}

function createCancleAction( cal , index){
    let canclebtn = createBtn("btn-danger");
    canclebtn.style.display="none";
    canclebtn.id= "canclebtn-"+index
    canclebtn.addEventListener("click", function () {
        randertabel();
    });
    canclebtn.appendChild(document.createTextNode("Cancle"));
    cal.appendChild(canclebtn);
}

function createDeleteAction( cal , index){
    let deletebtn = createBtn("btn-danger");
    deletebtn.addEventListener("click", function () {
        if (confirm("are you sure ?") ){
            tasksList.splice (index , 1);
            randertabel();
        }
        return ;

        
    });
    deletebtn.appendChild(document.createTextNode("Delete"));
    cal.appendChild(deletebtn);
}

function moveUp(index) {
    let temp = tasksList[index]
    tasksList[index] = tasksList[index - 1];
    tasksList[index - 1] = temp;
    randertabel();
}

function movedown(index) {
    let temp = tasksList[index]
    tasksList[index] = tasksList[index + 1];
    tasksList[index + 1] = temp;

    randertabel();
}


function getPriorty(priorty) {
    switch (priorty) {
        case "1":
            return "High";
        case "2":
            return "Medium";
        default:
            return "Low"
    }

}

