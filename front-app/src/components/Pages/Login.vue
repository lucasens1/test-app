<script>
export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    async login() {
      try {
        const response = await fetch("http://localhost:8000/login.php", {
          method: "POST",
          headers: {
            "Content-type": "application/json",
          },
          body: JSON.stringify({
            email: this.email,
            password: this.password,
          }),
        });
        const data = await response.json();
        // console.log(data);
        if (response.ok) {
          localStorage.setItem("username", data.username);
          console.log("Login Riuscito ");
          this.$router.push("/User");
        } else {
          alert("Login Fallito");
        }
      } catch (e) {
        console.error("Error : ", e);
      }
    },
  },
};
</script>

<template>
  <div class="reglog-container">
    <div class="reglog">
      <div class="reglog-header">
        <h1>Bentornato &#128578;</h1>
      </div>
      <form class="reglog-form" @submit.prevent="login">
        <div class="form-item">
          <span class="form-item-icon material-symbols-rounded"></span>
          <input
            type="email"
            placeholder="Inserisci email"
            id="emailForm"
            v-model="email"
            autofocus
            required
          />
        </div>
        <div class="form-item">
          <span class="form-item-icon material-symbols-rounded"></span>
          <input
            type="password"
            placeholder="Inserisci password"
            id="passwordForm"
            v-model="password"
            required
          />
        </div>
        <button type="submit">Effettua il Login</button>
      </form>
      <div class="reglog-footer ms_mt-1">
        Non hai un account?
        <a href="#"><router-link to="/register">Creane uno</router-link></a>
      </div>
    </div>
  </div>
</template>
