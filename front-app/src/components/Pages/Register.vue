<script>
export default {
  data() {
    return {
      username: "",
      email: "",
      password: "",
    };
  },
  methods: {
    async register() {
      try {
        const response = await fetch("http://localhost:8000/user.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            username: this.username,
            email: this.email,
            password: this.password,
          }),
        });

        const textResponse = await response.text(); // Ottieni la risposta come testo
        console.log("Raw response:", textResponse);

        // Verifica se la risposta è JSON
        try {
          const data = JSON.parse(textResponse);
          if (response.ok) {
            console.log("Registrazione avvenuta con successo.");
            this.$router.push("/Login");
          } else {
            alert(`Registration failed: ${data.error}`);
          }
        } catch (e) {
          console.error("JSON parse error:", e);
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
        <h2>Entra a far parte della community &#127757;</h2>
      </div>
      <form class="reglog-form" @submit.prevent="register">
        <div class="form-item">
          <span class="form-item-icon material-symbols-rounded"></span>
          <input
            type="text"
            placeholder="username"
            v-model="username"
            id="usernameForm"
            required
          />
        </div>
        <div class="form-item">
          <span class="form-item-icon material-symbols-rounded"></span>
          <input
            type="email"
            id="emailForm"
            placeholder="mail"
            v-model="email"
            autofocus
            required
          />
        </div>
        <div class="form-item">
          <span class="form-item-icon material-symbols-rounded"></span>
          <input
            type="password"
            id="passwordForm"
            placeholder="password"
            v-model="password"
            required
          />
        </div>
        <div class="reglog-footer">
          Già registrato?
          <a href="#"
            ><router-link to="/login">Effettua il login</router-link></a
          >
        </div>
        <button type="submit">Registrati</button>
      </form>
    </div>
  </div>
</template>

