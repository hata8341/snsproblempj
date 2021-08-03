<template>
<div class="container">
  <Header />
  <div class="login">
      <h2>ログイン</h2>
      <ul class="from-lists">
        <validation-provider v-slot="{errors}" rules="required">
        <li><input v-model="email" type="email" name="email" placeholder="メールアドレス" required />
        <div class="error">{{errors[0]}}</div></li>
        </validation-provider>
        <validation-provider v-slot="{errors}" rules="required">
        <li><input v-model="password" type="password" name="password" placeholder="パスワード" required />
        <div class="error">{{errors[0]}}</div></li>
          </validation-provider>
        <li><button @click="login" class="btn">ログイン</button></li>
      </ul>
  </div>
</div>
</template>

<script>
import firebase from '~/plugins/firebase'
export default {
  data() {
    return {
      email: null,
      password: null,
    }
  },
  methods: {
    login() {
      if (!this.email||!this.password) {
        alert('メールアドレスまたはパスワードが入力されていません。')
        return
      }
      firebase
      .auth()
      .signInWithEmailAndPassword(this.email,this.password)
      .then(() => {
        this.$router.push({path:'/home',query:{email:this.email}});
        alert('ログインが完了しました')
      })
      .catch((error) => {
        switch (error.code) {
          case 'auth/invalid-email':
            alert('メールアドレスの形式が違います。');
            this.$router.push({path:'/login',});
            break;
          case 'auth/user-disabled':
            alert('ユーザーが無効になっています。');
            this.$router.push({path:'/login',});
            break;
          case 'auth/user-not-found':
            alert('メールアドレスの形式が違います。');
            this.$router.push({path:'/login',});
            break;
          case 'auth/wrond-password':
            alert('パスワードが間違っております。');
            this.$router.push({path:'/login',});
            break;
          default:
            alert('エラーが起きました。しばらくしてからお試しください。');
            this.$router.push({path:'/login',});
            break;
        }
      })
    },
  },
}
</script>

<style scoped>
.container {
  background: #000;
}
.login {
  width: 600px;
  padding: 20px;
  background-color: #fff;
  margin: 30px auto;
  text-align: center;
  border-radius: 10px;
}
.from-lists {
  padding-left: 0px;
}
li {
  padding-top: 10px;
  list-style: none;
}
input {
  width: 400px;
  padding: 10px;
  border-radius: 5px;
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
</style>
