"use strict";
let apiUrl = "api";
let likePost = async (id) => {
  document
    .querySelector(`#likes_btn_${id}`)
    .setAttribute("disabled", "disabled");
  try {
    let req = await fetch(`${apiUrl}/like.php?id=${id}&type=post`);
    if (!req.ok) throw "Request not found";
    console.log(req);
    await req;
    let oldCount = +document.querySelector(`#likes_count_${id}`).innerHTML;
    document.querySelector(`#likes_count_${id}`).innerHTML = oldCount + 1;
    document.querySelector(`#likes_btn_${id}`).style.display = "none";
    document.querySelector(`#unlikes_btn_${id}`).style.display = "block";
  } catch (ex) {
    console.log(ex);
  } finally {
    document.querySelector(`#likes_btn_${id}`).removeAttribute("disabled");
  }
};

let unLikePost = async (id) => {
  document
    .querySelector(`#unlikes_btn_${id}`)
    .setAttribute("disabled", "disabled");
  try {
    let req = await fetch(`${apiUrl}/unlike.php?id=${id}&type=post`);
    if (!req.ok) throw "Request not found";
    console.log(req);
    await req;
    let oldCount = +document.querySelector(`#likes_count_${id}`).innerHTML;
    if (oldCount <= 1) oldCount = 1;
    document.querySelector(`#likes_count_${id}`).innerHTML = oldCount - 1;
    document.querySelector(`#likes_btn_${id}`).style.display = "block";
    document.querySelector(`#unlikes_btn_${id}`).style.display = "none";
  } catch (ex) {
    console.log(ex);
  } finally {
    document.querySelector(`#unlikes_btn_${id}`).removeAttribute("disabled");
  }
};



let likeComment = async (id) => {
  document
    .querySelector(`#likes_comment_btn_${id}`)
    .setAttribute("disabled", "disabled");
  try {
    let req = await fetch(`${apiUrl}/like.php?id=${id}&type=comment`);
    if (!req.ok) throw "Request not found";
    console.log(req);
    await req;
    let oldCount = +document.querySelector(`#likes_comment_count_${id}`).innerHTML;
    document.querySelector(`#likes_comment_count_${id}`).innerHTML = oldCount + 1;
    document.querySelector(`#likes_comment_btn_${id}`).style.display = "none";
    document.querySelector(`#unlikes_comment_btn_${id}`).style.display = "block";
  } catch (ex) {
    console.log(ex);
  } finally {
    document.querySelector(`#likes_comment_btn_${id}`).removeAttribute("disabled");
  }
};

let unLikeComment = async (id) => {
  document
    .querySelector(`#unlikes_comment_btn_${id}`)
    .setAttribute("disabled", "disabled");
  try {
    let req = await fetch(`${apiUrl}/unlike.php?id=${id}&type=comment`);
    if (!req.ok) throw "Request not found";
    console.log(req);
    await req;
    let oldCount = +document.querySelector(`#likes_comment_count_${id}`).innerHTML;
    if (oldCount <= 1) oldCount = 1;
    document.querySelector(`#likes_comment_count_${id}`).innerHTML = oldCount - 1;
    document.querySelector(`#likes_comment_btn_${id}`).style.display = "block";
    document.querySelector(`#unlikes_comment_btn_${id}`).style.display = "none";
  } catch (ex) {
    console.log(ex);
  } finally {
    document.querySelector(`#unlikes_comment_btn_${id}`).removeAttribute("disabled");
  }
};

let follow = async (id) => {
  document
    .querySelector(`#follow_btn_${id}`)
    .setAttribute("disabled", "disabled");
  try {
    let req = await fetch(`${apiUrl}/follow.php?id=${id}&type=comment`);
    if (!req.ok) throw "Request not found";
    console.log(req);
    await req;
    let oldCount = +document.querySelector(`#Followers_count_${id}`).innerHTML;
    document.querySelector(`#Followers_count_${id}`).innerHTML = oldCount + 1;
    document.querySelector(`#unfollow_btn_${id}`).style.display = "block";
    document.querySelector(`#follow_btn_${id}`).style.display = "none";
  } catch (ex) {
    console.log(ex);
  } finally {
    document.querySelector(`#follow_btn_${id}`).removeAttribute("disabled");
  }
};

let unFollow = async (id) => {
  document
    .querySelector(`#unfollow_btn_${id}`)
    .setAttribute("disabled", "disabled");
  try {
    let req = await fetch(`${apiUrl}/unfollow.php?id=${id}&type=comment`);
    if (!req.ok) throw "Request not found";
    console.log(req);
    await req;
    let oldCount = +document.querySelector(`#Followers_count_${id}`).innerHTML;
    if (oldCount <= 1) oldCount = 1;
    document.querySelector(`#Followers_count_${id}`).innerHTML = oldCount - 1;
    document.querySelector(`#follow_btn_${id}`).style.display = "block";
    document.querySelector(`#unfollow_btn_${id}`).style.display = "none";
  } catch (ex) {
    console.log(ex);
  } finally {
    document.querySelector(`#unfollow_btn_${id}`).removeAttribute("disabled");
  }
};

let deleteComment = async (id) => {
  document
    .querySelector(`#delete_comment_btn_${id}`)
    .setAttribute("disabled", "disabled");
  try {
    let req = await fetch(`${apiUrl}/deleteComment.php?id=${id}`);
    if (!req.ok) throw "Request not found";
    console.log(req);
    await req;
    var element = document.querySelector(`#Comment_No_${id}`);
    element.parentNode.removeChild(element);
    let oldCount = +document.querySelector("#comments_cont_span").innerHTML ;
    if (oldCount <= 1) oldCount = 1;
    document.querySelector(`#comments_cont_span`).innerHTML = oldCount - 1;
  } catch (ex) {
    console.log(ex);
  } finally {
  }
};
