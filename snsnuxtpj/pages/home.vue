<template>
  <div class="container">
      <div class="posts">
        <div class="header-logo">
          <img src="~/assets/img/logo.png" alt="SHAREロゴ">
        </div>
        <div class="home-logo">
          <img src="~/assets/img/home.png" alt="ホームロゴ">
          <p>ホーム</p>
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
        <ul>
          <li><h2>ホーム</h2></li>
          <li v-for="(messageList,index) in messageLists" :key="messageList.id"><p class="user-name">{{messageList.user.name}}</p>
          <p><img v-show="messageList.count>=0" key="messageList.id" @click="counter(index)" class="icon" src="~/assets/img/heart.png" alt="ハートのアイコン">{{messageLists[index].count}}</p>
          <p><img @click="deleteMessage(messageList.id)" class="icon" src="~/assets/img/cross.png" alt="削除アイコン"></p>
          <NuxtLink v-bind:to="{name: 'comment-id',params:{messageList}}">
          <img class="icon comment" src="~/assets/img/detail.png" alt="コメントのアイコン">
          </NuxtLink>
          <br><p>{{messageList.message}}</p>
          </li>
        </ul>
      </div>
  </div>
</template>

<script>
import firebase from '~/plugins/firebase'
export default {
  data() {
    return {
      userLists: [],
      messageLists: [
        {
        count:0,
        user:{
        id:'',
        name:'',
        }
      }
      ],
      currentUser: {},
      currentEmail: '',
      message: '',
      count: 0,
      isLike:false,
      updateId:'',
      updatecCount:'',
      post: 'ログインできていません'
    }
  },
  methods: {
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
    async deleteMessage(id) {
      await this.$axios.delete("http://127.0.0.1:8000/api/message/" + id);
      this.getMessage();
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
    counter(index){
      console.log(this.messageLists[index].count);
      console.log(this.updateId,this.updateCount);
      if (this.isLike==false) {
        this.isLike=true;
        this.messageLists[index].count++;
        console.log(this.isLike);
      }else{
        this.isLike=false;
        this.messageLists[index].count--;
        console.log(this.isLike);
      }
        this.updateId = this.messageLists[index].id;
        this.updateCount = this.messageLists[index].count;
      console.log(this.updateId,this.updatecCount);
      this.updateMessage(this.updateId,this.updatecCount);
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
  },
}
</script>

<style scoped>
body {
  background: #000;
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
/* input {
  display: block;
  padding: 50px;
} */
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
li {
  color: #eee;
  border: 1px solid #eee;
  border-top: none;
  border-right: none;
  list-style: none;
  padding-left: 15px;
}
.user-name{
  font-weight: bold;
}
.icon {
  width: 50px;
  cursor: pointer;
}
.comment {
  margin-left: 40px;
}
</style>
