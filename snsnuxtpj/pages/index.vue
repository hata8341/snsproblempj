<template>
  <div class="container">
    <Header/>
    <div class="register">
        <h2>新規登録</h2>
          <ul class="from-lists">
          <validation-provider v-slot="{errors}" rules="required|max:20">
            <li><input v-model="name" type="text" name="ユーザーネーム" placeholder="ユーザーネーム" />
            <div class="error">{{errors[0]}}</div></li>
          </validation-provider>

          <validation-provider v-slot="{errors}" rules="required">
          <li><input  v-model="email" type="email" name="メールアドレス" placeholder="メールアドレス" />
          <div class="error">{{errors[0]}}</div></li>
          </validation-provider>

          <validation-provider v-slot="{errors}" rules="required">
          <li><input v-model="password" type="password" name="パスワード" placeholder="パスワード" />
          <div class="error">{{errors[0]}}</div></li>
          </validation-provider>
          <li><button class="btn" @click="register(); insertUser()" type="button">新規登録</button></li>
        </ul>

    </div>
  </div>
</template>

<script>
import firebase from '~/plugins/firebase'
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
      if (!this.name||!this.email || !this.password) {
        alert('メールアドレスまたはパスワードが入力されていません。')
        return
      }
      firebase
        .auth()
        .createUserWithEmailAndPassword(this.email, this.password)
        .then((data) => {
          data.user.sendEmailVerification().then(() => {
            this.$router.replace('/login')
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

    async insertUser() {
      const sendData = {
        name: this.name,
        email: this.email,
        password: this.password,
      };
      await this.$axios.post("http://127.0.0.1:8000/api/user/", sendData);
    },
  },
}
</script>

<style>
body {
  max-width: 1200px;
  background-color: #000;
}
.register {
  width: 600px;
  padding: 20px;
  background-color: #fff;
  margin: 30px auto;
  text-align: center;
  border-radius: 10px;
}
.error {
  color: red;
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
