<template>
  <header class="header">
    <div class="header-logo">
      <img src="../assets/img/logo.png" alt="SHAREロゴ">
    </div>
    <nav>
      <ul class="header-lists">
      <li><NuxtLink to="/">新規登録</NuxtLink></li>
      <li><NuxtLink to="/login">ログイン</NuxtLink></li>
    </ul>
    </nav>
  </header>
</template>

<script>
</script>

<style scoped>
header{
  width: 100%;
  color: #eee;
  display: flex;
  justify-content: space-between;
  padding: 20px;
}
.header-logo {
  width: 130px;
}
.header-logo img {
  width: 100%;
}
nav{
  padding-right: 15px;
}
.header-lists {
  display: flex;
  align-items: center;
  padding-right: 20px;
  color: #eee;
}
a {
  color: #eee;
  text-decoration: none;
}
.header-lists > li:first-child {
  padding-right: 20px;
}
</style>
