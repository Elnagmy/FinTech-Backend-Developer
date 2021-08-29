

import Message from "./message.js";

document.addEventListener("onload", init());
let groupName;
let onlineUsers = 5;
let loginUser;
let messageArray = [];
/* const message = {
    sender: "you",
    content: "Hello this is a message",
    time: "" } */



function init() {
    console.log("initailizing ........");
    document.querySelector("#joinGroup").addEventListener('click', joinGroup);
    document.querySelector("#sendMessage").addEventListener('click', sendMassage);
    document.querySelector("#MessageContent").addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMassage();
        }
    });
    document.querySelector("#logOutfield").addEventListener('click', logOutfun);
    subscribeToPusher();


}

function logOutfun() {
    loginUser = "";
    groupName = "";
    document.querySelector("#loginUserField").value = "";
    document.querySelector("#groupField").value = "";
    document.querySelector("#window2").style.display = "none";
    document.querySelector("#window1").style.display = "block";
}


function joinGroup() {
    loginUser = document.querySelector("#loginUserField").value;
    groupName = document.querySelector("#groupField").value;
    if (loginUser != "" && groupName != "") {
        console.log("user : " + loginUser + " is joining Group : " + groupName);
        document.querySelector("#window1").style.display = "none";
        document.querySelector("#window2").style.display = "block";
    }
    document.querySelector("#GroupIDfield").textContent = groupName;
    document.querySelector("#onlineUserField").textContent = onlineUsers;
}




function sendMassage() {
    console.log("send a message...");
    let msgContent = document.querySelector("#MessageContent").value;
    let mymessage = new Message(loginUser ,msgContent,"");
    mymessage.setDefultTime();
    createMessageobject(mymessage, "Right");
    pushMessageToPusher(mymessage);
    let othermessage = getTestMasseg();
    addOthersMessage(othermessage);
}

function getTestMasseg() {
   
    let content = " How are You ?";
    let sender = "Ahmed";
    let othermessage = new Message(sender , content ,"");
    othermessage.setDefultTime();
    return othermessage;
}

function createMessageobject(msg, flout) {
    let myPane = document.querySelector("#myPane");
    console.log("create message disply object : " + JSON.stringify(msg))
    let messageObject = ` <div id="message-1" class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="display: block; float:${flout} ; width: 660px;"">
                            <div class="toast-header " >
                            <strong class="me-auto">${msg.sender} </strong>
                            <small>${msg.time}</small>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                           ${msg.content}
                        </div>
                        </div>
                        </div>`

    myPane.insertAdjacentHTML("beforeEnd", messageObject);
}

function getCurrntTime() {
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    return time;
}

function addOthersMessage(message) {
    createMessageobject(message, "Left");

}


function pushMessageToPusher(message) {
    messageArray.push(message);
    console.log("Pushing a message to the channel:" + JSON.stringify(message));

}

function getMessgesFromPusher(data) {
    const recivedmsg = JSON.parse(data);
    console.log("gitting message from publisher :" + recivedmsg);
    return recivedmsg;
}

function subscribeToPusher() {
    console.log("Subscribe in a channel");
}