<template>
  <div class="container">
    <div class="posts">
        <div class="header-logo">
          <img src="~/assets/img/logo.png" alt="SHAREロゴ">
        </div>
        <div class="home-logo">
          <img class="home" @click="home" src="~/assets/img/home.png" alt="ホームロゴ">
          <p class="home" @click="home">ホーム</p>
          </div>
        <div class="home-logo">
          <img class="logout" @click="logout" src="~/assets/img/logout.png" alt="ログアウト">
          <p class="logout" @click="logout">ログアウト</p>
        </div>
        <p>シェア</p>
          <validation-observer ref="obs" v-slot="ObserverProps">
          <validation-provider v-slot="{errors}" rules="required|max:120">
          <textarea v-model="message" name="message"  cols="35" rows="10" required="required" maxlength="120"></textarea>
          <div class="error">{{errors[0]}}</div>
          </validation-provider>
          <button @click="insertMessage()" class="btn" type="button" :disabled="ObserverProps.invalid || !ObserverProps.validated">シェアする</button>
        </validation-observer>
      </div>

      <div class="posts-list">
          <li><h2>コメント</h2></li>
        <ul>
          <li>
            <p class="user-name">{{$route.params.messageList.user.name}}</p>
            <p><img @click="counter()" class="icon" src="~/assets/img/heart.png" alt="ハートのアイコン">{{$route.params.messageList.count}}</p>
            <p><img @click="deleteMessage($route.params.messageList.id)" class="icon" src="~/assets/img/cross.png" alt="削除アイコン"></p>
            <br><p>{{$route.params.messageList.message}}</p></li>
        </ul>
        <ul>
          <li><h2 class="comment">コメント</h2></li>
          <li v-for="commentList in commentLists" :key="commentList.id">
            <p v-if="$route.params.messageList.id === commentList.message_id" class="comment-title">{{commentList.user.name}}</p>

          <p v-if="$route.params.messageList.id === commentList.message_id" class="comment-content">{{commentList.comment}}</p>
          </li>
        </ul>
        <validation-observer ref="obs" v-slot="ObserverProps">
          <validation-provider v-slot="{errors}" rules="required|max:120">
          <input v-model="comment" name="comment"  cols="35" required="required" maxlength="120" />
          <div class="error">{{errors[0]}}</div>
          </validation-provider>
          <button @click="insertComment()" class="btn" type="button" :disabled="ObserverProps.invalid || !ObserverProps.validated">コメント</button>
        </validation-observer>
      </div>
  </div>
</template>

<script>
import firebase from '~/plugins/firebase'
export default {
  data() {
    return {
      userLists: [],
      messageLists: [{
        user:{
        id:'',
        name:'',
        }
      }],
      currentUser: {},
      currentEmail: '',
      messageUser: {},
      message: '',
      count: 0,
      comment:'',
      isLike:false,
      post: 'ログインできていません',
      commentLists:[{
        user_id: '',
        message_id: '',
        comment: '',
      }],
    }
  },
  methods: {
    home() {
      alert('ホームへ戻ります')
        this.$router.replace('/home')
    },
    logout() {
      firebase
      .auth()
      .signOut()
      .then(() => {
        alert('ログアウトが完了しました')
        this.$router.replace('/login')
      })
    },
    async getUser() {
      const resData = await this.$axios.get(
        "http://127.0.0.1:8000/api/user/"
      );
      this.userLists = resData.data.data;
      this.userLists.forEach((user) => {
        if (user.email === this.currentEmail) {
          this.currentUser = user;
        }
      });
    },
    async getMessage() {
      const resData = await this.$axios.get(
        "http://127.0.0.1:8000/api/message/"
      );
      this.messageLists = resData.data.data;
    },
    async insertMessage() {
      const sendData = {
        user_id: this.currentUser.id,
        message: this.message,
        count: this.count,
      };
      await this.$axios.post("http://127.0.0.1:8000/api/message/", sendData);
      this.getMessage();
      this.message = '';
      alert('メッセージが投稿されました。');
    },
    async getComment() {
      const resData = await this.$axios.get(
        "http://127.0.0.1:8000/api/comment/"
      );
      this.commentLists = resData.data.data;
    },
    async insertComment() {
      const sendData = {
        user_id: this.currentUser.id,
        message_id: this.$route.params.messageList.id,
        comment: this.comment,
      };
      await this.$axios.post("http://127.0.0.1:8000/api/comment/", sendData);
      this.getComment();
      this.comment = '';
      alert('コメントが投稿されました。');
    },
    async deleteMessage(id) {
      await this.$axios.delete("http://127.0.0.1:8000/api/message/" + id);
      this.getMessage();
      alert('メッセージを削除しました。');
      this.$router.replace('/home')
    },
    async updateMessage() {
      const sendData = {
        count: this.updateCount
      };
      await this.$axios.put(
        "http://127.0.0.1:8000/api/message/"+this.updateId,
        sendData
      );
    },
    counter(){
      console.log(this.$route.params.messageList.id,this.$route.params.messageList.count);
      if (this.isLike==false) {
        this.isLike=true;
        this.$route.params.messageList.count++;
        console.log(this.isLike);
      }else{
        this.isLike=false;
        this.$route.params.messageList.count--;
        console.log(this.isLike);
      }
      this.updateId = this.$route.params.messageList.id;
      this.updateCount = this.$route.params.messageList.count;
      console.log(this.$route.params.messageList.id,this.$route.params.messageList.count);
      this.updateMessage(this.$route.params.messageList.id,this.$route.params.messageList.count);
    }
  },
  created() {
    firebase.auth().onAuthStateChanged((user) => {
      if (user) {
        this.post = 'ログインしました。'
        this.currentEmail = user.email
      }
    });
    this.getUser();
    this.getMessage();
    this.getComment();
  },
}
</script>

<style scoped>
body {
  background-color: #000;
  height: 100vh;
}
.container {
  display: flex;
  background: #000;
}
.posts {
  padding: 10px;
  width: 290px;
}
.header-logo img{
  width: 130px;
}
.home-logo img {
  width: 50px;
}
.home {
  cursor: pointer;
}
.logout {
  cursor: pointer;
}
p {
  display: inline-block;
  color: #fff;
  padding: 10px 0;
}
textarea {
  background: #000;
  border: 1px solid #eee;
  border-radius: 10px;
  color: #fff;
}
.btn {
  width: 100px;
  padding: 5px;
  text-align: center;
  text-decoration: none;
  color: #fff;
  background: blueviolet;
  border-bottom: 2px solid #005601;
  border-radius: 30px;
  transition: .0s;
  display: block;
  margin-top: 5px;
  margin-left: auto;
}
.btn:hover {
  cursor: pointer;
}
.btn:active {
  background: #005601;
  transform: translate3d(0, 2px,0);
  transition: .0s;
  border-bottom: none;
}
a {
  color: #eee;
  text-decoration: none;
}
.posts-list {
  width: 900px;
  margin-left: 15px;
}
h2 {
  padding: 10px 0;
}
ul {
  padding-left: 0px;
}
.user-name {
  font-weight: bold;
}
li {
  color: #eee;
  border: 1px solid #eee;
  border-top: none;
  border-right: none;
  list-style: none;
  padding-left: 10px;
}
.icon {
  width: 50px;
}
.comment {
  text-align: center;
}
.comment-title {
  font-weight: bold;
  font-size: 14px;
}
.comment-content {
  display: block;
  font-size: 14px;
}
input {
  padding: 10px;
  text-align: center;
  margin: 10px auto;
  margin-bottom: 10px;
  display: block;
  width: 530px;
  background: #000;
  border: 1px solid #eee;
  border-radius: 10px;
  color: #fff;
}
</style>
