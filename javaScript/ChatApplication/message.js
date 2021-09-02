class Message {
  constructor(senderID, senderName, content, time) {
    (this.sender = {
      id: senderID,
      name: senderName,
    }),
      (this.content = content),
      (this.time = time);
  }
  getSender() {
    return this.sender;
  }
  getContent() {
    return this.content;
  }
  getTime() {
    return this.time;
  }
  setDefultTime() {
    let date = new Date();
    this.time =
      date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
  }
}

export default Message;
