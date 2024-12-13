<script>
import { useUserAuthStore } from "@/stores/userAuth";

export default {
  data() {
    return {
      courriel: "",
      password: "",
      valid: false,
      successMessage: "",
    };
  },
  mounted() {
    // Keep the success message logic
    if (this.$route.query.message) {
      this.successMessage = this.$route.query.message;
    }
  },
  setup() {
    const userAuthStore = useUserAuthStore();
    return { userAuthStore };
  },
  methods: {
    login() {
      fetch("http://localhost:4208/Labo3_Web_EA_AV/api/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          courriel: this.courriel,
          password: this.password,
        }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Erreur lors de la connexion.");
          }
          return response.json();
        })
        .then((data) => {
          if (data.success) {
            this.userAuthStore.login(data.utilisateur, data.token); // Store user and token
            this.$router.push(`/utilisateur/${data.utilisateur.id}`); // Redirect
          } else {
            alert("Connexion échouée : " + data.message);
          }
        })
        .catch((error) => {
          alert("Erreur lors de la connexion : " + error.message);
        });
    },
    goToInscription() {
      this.$router.push("/inscription");
    },
  },
};
</script>

<template>
  <div class="container">
    <div class="connexion">
      <p id="titre">CONNEXION</p>
      <br />
      <v-form ref="form" v-model="valid" lazy-validation>
        <!-- Message de succès -->
        <p v-if="successMessage" class="success">{{ successMessage }}</p>
        <v-text-field
          v-model="courriel"
          label="ADRESSE COURRIEL"
          class="fixed-size"
          required
        ></v-text-field>
        <v-text-field
          v-model="password"
          label="MOT DE PASSE"
          type="password"
          class="fixed-size"
          required
        ></v-text-field>
        <br />
        <v-btn class="buttons" @click="login">CONNEXION</v-btn>
        <v-btn class="buttons" @click="goToInscription">S'INSCRIRE</v-btn>
      </v-form>
    </div>
  </div>
</template>
<style scoped>
.connexion {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-width: 100px;
  border-image: url(/src/img/web/pale_glowing_pink_blue_border.png) 25% stretch;
  border-style: solid;
  height: 500px;
  width: 700px;
}

.fixed-size {
  width: 300px;
}

#titre {
  font-size: 2rem;
  color: #ff71ce;
  margin: 2px;
  padding: 15px 56px;
  font-style: italic;
  font-weight: 600;
  text-transform: uppercase;
  text-shadow: 3px 4px 0 #b967ff;
}

.buttons {
  height: 40px;
  width: 120px;
  margin: 10px;
  background-color: #b967ff;
  border-radius: 8px;
  border: 2px solid black;
  box-shadow: 6px 6px black;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  color: black;
}

.buttons:hover {
  box-shadow: 2px 2px black;
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 70vh;
}
</style>
