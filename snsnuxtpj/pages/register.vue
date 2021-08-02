<template>
<div class="container">
  <Header />
  <div class="register">
      <h2>新規登録</h2>
    <form>
      <ul class="from-lists">
        <li><input type="text" name="name" placeholder="ユーザーネーム"></li>
        <li><input type="email" name="email" placeholder="メールアドレス"></li>
        <li><input type="password" name="password" placeholder="パスワード"></li>
        <li><button class="btn">新規登録</button></li>
      </ul>
    </form>
  </div>
</div>
</template>

<script>
import firebase from '~/plugins/firebase'
// import  'firebase/app'
// import  'firebase/auth'
export default {
  data() {
    return {
      name: null,
      email: null,
      password: null,
    }
  },
  methods: {
    register() {
      if (!this.email || !this.password) {
        alert('メールアドレスまたはパスワードが入力されていません。')
        return
      }
      firebase
        .auth()
        .createUserWithEmailAndPassword(this.email, this.password)
        .then((data) => {
          data.user.sendEmailVerification().then(() => {
            this.$router.replace('/confirm')
          })
        })
        .catch((error) => {
          switch (error.code) {
            case 'auth/invalid-email':
              alert('メールアドレスの形式が違います。')
              break
            case 'auth/email-already-in-use':
              alert('このメールアドレスはすでに使われています。')
              break
            case 'auth/weak-password':
              alert('パスワードは6文字以上で入力してください。')
              break
            default:
              alert('エラーが起きました。しばらくしてから再度お試しください。')
              break
          }
        })
    },
  },
}
</script>

<style scoped>
.container {
  display: flex;
  background: #000;
}
.register {
  width: 600px;
  padding: 20px;
  background-color: #fff;
  margin: 0 auto;
  text-align: center;
}
.from-lists {
  padding-left: 0px;
}
.register li {
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
</style>
