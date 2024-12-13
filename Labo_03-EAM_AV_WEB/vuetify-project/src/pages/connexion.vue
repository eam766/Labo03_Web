<script>
import { useUserAuthStore } from "@/stores/userAuth";

export default {
  data() {
    return {
      courriel: "",
      password: "",
      valid: false,
      successMessage: "",
      errorMessage: "",
      show1: false,
      show2: true,
      boolValide: false,
      rules: {
        required: (value) => !!value || "Requis.",
        emailValid: (value) => {
          if (/.+@.+\..+/.test(value)) return true;
          return "Le courriel doit être valide.";
        },
        max: (v) => v.length < 256 || "Maximum 256 caractères svp",
        min: (v) => v.length >= 8 || "Minimum 8 caractères svp",
      },
    };
  },
  watch: {
    courriel() {
      this.isValide();
    },
    password() {
      this.isValide();
    },
  },
  mounted() {
    if (this.$route.query.message) {
      this.successMessage = this.$route.query.message;
    }
  },
  setup() {
    const userAuthStore = useUserAuthStore();
    return { userAuthStore };
  },
  methods: {
    isPasswordValid() {
      return this.password.length >= 8 && this.password.length < 256;
    },
    isCourrielValide() {
      const pattern =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return (
        this.courriel &&
        pattern.test(this.courriel) &&
        this.courriel.length < 320
      );
    },
    async isValide() {
      if (this.isCourrielValide() && this.isPasswordValid()) {
        this.boolValide = true;
        this.errorMessage = "";
      } else {
        this.boolValide = false;
        this.errorMessage = "Veuillez remplir tous les champs correctement.";
      }
    },
    async login() {
      await this.isValide();

      if (!this.boolValide) {
        console.error("formulaire invalide");
        return;
      }

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
            this.userAuthStore.login(data.utilisateur, data.token);
            this.$router.push(`/utilisateur/${data.utilisateur.id}`);
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
        <p v-if="successMessage" class="success">{{ successMessage }}</p>
        <v-text-field
          clearable
          v-model="courriel"
          :rules="[rules.required, rules.max, rules.emailValid]"
          label="ADRESSE COURRIEL"
          class="fixed-size"
        ></v-text-field>
        <v-text-field
          clearable
          v-model="password"
          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
          :rules="[rules.required, rules.min, rules.max]"
          :type="show1 ? 'text' : 'password'"
          label="MOT DE PASSE"
          class="fixed-size"
          required
          @click:append="show1 = !show1"
        ></v-text-field>
        <br />
        <v-btn class="buttons" :disabled="!boolValide" @click="login"
          >CONNEXION</v-btn
        >
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
