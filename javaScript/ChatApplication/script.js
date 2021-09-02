import Message from "./message.js";
let groupName = "";
let onlineUsers = 0;
let loginUserObj = {
  id: "",
  name: "",
};

let loginUserArray = [];

document.addEventListener("onload", init());

function init() {
  console.log("......start initailizing ........");
  document.querySelector("#joinGroup").addEventListener("click", joinGroup);
  document.querySelector("#sendMessage").addEventListener("click", sendMassage);
  document
    .querySelector("#MessageContent")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        sendMassage();
      }
    });
  document
    .querySelector("#groupField")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        joinGroup();
      }
    });
  document.querySelector("#logOutfield").addEventListener("click", logOutfun);
  startLogOutTimer();
  console.log("[.....Initaliazed Done....]]");
}

function startLogOutTimer() {
  let numberOfSec = 0;
  const WAIT_TIME = 120;
  const timer = setInterval(function () {
    numberOfSec++;
    adjustReminingTime(numberOfSec, WAIT_TIME);
    if (numberOfSec >= WAIT_TIME) {
      logOutfun();
      clearInterval(timer);
    }
  }, 1000);
}
2;
function adjustReminingTime(timeleft, WAIT_TIME) {
  document.querySelector("#timer").textContent = WAIT_TIME - timeleft;
}

function logOutfun() {
  console.log("......loging Out ........");
  loginUserObj.name= "LogOut";
  pushMessageToPusher(loginUserObj);
  loginUserObj = null;
  groupName = "";
  document.querySelector("#loginUserField").value = "";
  document.querySelector("#groupField").value = "";
  document.querySelector("#window2").style.display = "none";
  document.querySelector("#window1").style.display = "block";
  loginUserArray=[];
  document.querySelector("#myPane").innerHTML="";
  // to reload all compenent form scratch after loging out
  let windowLoadAfter = 1 * 1000;
  setTimeout(function() {
    location.reload();
}, windowLoadAfter);


}

function joinGroup() {
  let name = document.querySelector("#loginUserField").value;
  groupName = document.querySelector("#groupField").value;
  if (name != "" && groupName != "") {
    console.log("user : " + name + " is joining Group : " + groupName);
    document.querySelector("#window1").style.display = "none";
    document.querySelector("#window2").style.display = "block";
    // create me
    loginUserObj.id = getGUID();
    loginUserObj.name = name;
    subscribeToPusher();
    // put me in the array
    //push login message to pusher
    loginUserArray.push(loginUserObj);
    pushMessageToPusher(loginUserObj);
  }
  document.querySelector("#GroupIDfield").textContent = groupName;
  document.querySelector("#onlineUserField").textContent = "(Pending..)";
}

function sendMassage() {
  console.log("send a message...");
  let msgContent = document.querySelector("#MessageContent").value;
  if (msgContent=="") return;
  let mymessage = new Message(
    loginUserObj.id,
    loginUserObj.name,
    msgContent,
    ""
  );
  mymessage.setDefultTime();
  console.log("Pushing a message to the channel:" + JSON.stringify(mymessage));
  pushMessageToPusher(mymessage);
  document.querySelector("#MessageContent").value = "";
}

function createMessageobject(msg, flout) {
  let myPane = document.querySelector("#myPane");
  console.log("create message disply object : " + JSON.stringify(msg));
  let messageObject = ` <div class="toast solid-bg" role="alert" aria-live="assertive" aria-atomic="true" style="display: block; float:${flout} ; width: 660px;"">
                            <div class="toast-header " >
                            <strong class="me-auto">${msg.sender.name} </strong>
                            <small>${msg.time}</small>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                           <p>${msg.content} </P>
                        </div>
                        </div>
                        </div>`;

  myPane.insertAdjacentHTML("beforeEnd", messageObject);
}

async function pushMessageToPusher(message) {
  console.log("log this message to pusher" + JSON.stringify(message));
  const MY_CHANNEL = "omar-chatApp-343";
  const API_ID = "1257360";
  const API_KEY = "ad224c2ce91e2a2179cb";
  const CLUSTER = "eu";
  const secret = "17c7b527673faa86daa8";
  let body = {
    data: JSON.stringify(message),
    name: groupName,
    channel: MY_CHANNEL,
    info: "subscription_count",
  };
  let timeStamp = Math.floor(new Date().getTime() / 1000).toString();
  let md5 = getMD5(body);
  let url = `https://cors.bridged.cc/https://api-${CLUSTER}.pusher.com/apps/${API_ID}/events?body_md5=${md5}&auth_version=1.0&auth_key=${API_KEY}&auth_timestamp=${timeStamp}&auth_signature=${getAuthSignature(
    md5,
    timeStamp,
    API_ID,
    API_KEY,
    secret
  )}`;
  console.log("URL = " + url);
  let req = await fetch(url, {
    method: "POST",
    body: JSON.stringify(body),
    headers: {
      "Content-Type": "application/json",
    },
  });
}

function getMD5(body) {
  console.log(JSON.stringify(body));
  return CryptoJS.MD5(JSON.stringify(body));
}

function getAuthSignature(md5, timeStamp, appID, key, secret) {
  return CryptoJS.HmacSHA256(
    `POST\n/apps/${appID}/events\nauth_key=${key}&auth_timestamp=${timeStamp}&auth_version=1.0&body_md5=${md5}`,
    secret
  );
}

function getMessgesFromPusher(data) {
  console.log("gitting message from publisher :" + data);
  let msg = data;
  let float = "";
  if (ismymessage(msg)) {
    float = "Right";
    msg.sender.name = "You";
  } else {
    float = "Left";
  }
  createMessageobject(msg, float);
  return data;
}

function ismymessage(msg) {
  return msg.sender.id == loginUserObj.id;
}

function subscribeToPusher() {
  const MY_CHANNEL = "omar-chatApp-343";
  const API_ID = "1257360";
  const API_KEY = "ad224c2ce91e2a2179cb";
  const CLUSTER = "eu";

  var pusher = new Pusher(API_KEY, {
    cluster: CLUSTER,
  });

  var channel = pusher.subscribe(MY_CHANNEL);
  channel.bind(groupName, function (data) {
    // if login message

    if (isLoginMessage(data)) {
      upateLoginUser(data);
    } else {
      getMessgesFromPusher(data);
    }
  });
}
function upateLoginUser(recivedData) {
  // check if data.id=myid if yes then nothing
  //if no then push the new user to array
  // send me to pusher
  if(recivedData.name=="LogOut"){
    let logoutuser =-1;
    loginUserArray.forEach((e) => {
      if (recivedData.id == e.id) {
         logoutuser = loginUserArray.indexOf(e);
      }
    });
    loginUserArray.splice (logoutuser ,1);
  }else if (!loginUserArray.some((e) => e.id === recivedData.id)) {
    let newuser = recivedData;
    loginUserArray.push(newuser);
    pushMessageToPusher(loginUserObj);
  }
  document.querySelector("#onlineUserField").textContent =
    "(" + loginUserArray.length + ")";
  let onlineUsersList = "";
  document.querySelector("#onlineUser").innerHTML = "";
  loginUserArray.forEach((e) => {
    let me= (( e.id == loginUserObj.id ) ? "(ME)":"") ; 
    debugger;
    onlineUsersList =
      onlineUsersList + `<a href="#" class="list-group-item">${e.name} ${me}</a>`;
  });
  document
    .querySelector("#onlineUser")
    .insertAdjacentHTML("beforeEnd", onlineUsersList);
}

function isLoginMessage(data) {
  return true && data.id;
}

function getGUID() {
  return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (c) {
    var r = (Math.random() * 16) | 0,
      v = c == "x" ? r : (r & 0x3) | 0x8;
    return v.toString(16);
  });
}
