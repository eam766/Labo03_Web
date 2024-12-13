<script>
import { useUserAuthStore } from "@/stores/userAuth"; // Import the userAuth store

export default {
  data() {
    return {
      utilisateur: null,
      nom: "",
      prenom: "",
      courriel: "",
      password: "",
      isLoading: false,
      valid: true,
      boolValide: false,
      successMessage: "",
      errorMessage: "",
      show1: false,
      rules: {
        required: (value) => !!value || "Requis.",
        min: (v) => v.length >= 8 || "Minimum 8 caractères svp",
        max: (v) => v.length < 256 || "Maximum 256 caractères svp",
        emailValid: (value) => {
          if (/.+@.+\..+/.test(value)) return true;
          return "Le courriel doit être valide.";
        },
        noNumbers: (value) => {
          const numberRegex = /\d/; // Vérifie si une chaîne contient des chiffres
          return !numberRegex.test(value) || "Seulement des lettres svp";
        },
      },
    };
  },
  watch: {
    prenom() {
      this.isValide(); // Call validation on prenom change
    },
    nom() {
      this.isValide(); // Call validation on nom change
    },
    courriel() {
      this.isValide(); // Call validation on courriel change
    },
    password() {
      this.isValide(); // Call validation on password change
    },
  },
  created() {
    const utilisateurId = parseInt(this.$route.params.id); // ID from URL
    const userAuthStore = useUserAuthStore(); // Access Pinia store
    const loggedInUserId = userAuthStore.user?.id; // Get logged-in user ID
    const loggedInUserEmail = userAuthStore.user?.courriel;
    const emailAdmin = "admin@rich.ricasso";

    // Check if the user is logged in and the IDs match
    if (
      (!loggedInUserId || utilisateurId !== loggedInUserId) &&
      loggedInUserEmail !== emailAdmin
    ) {
      console.error("Unauthorized access: Redirecting to Accueil.");
      this.$router.push("/"); // Redirect to Accueil if IDs don't match
      return;
    }

    // Fetch user data if the IDs match
    this.fetchUserData(utilisateurId);
  },
  methods: {
    isNameAndLastNameValid() {
      return (
        this.nom.length > 0 &&
        this.nom.length < 256 &&
        this.prenom.length > 0 &&
        this.prenom.length < 256
      );
    },

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
      if (
        this.isNameAndLastNameValid() &&
        this.isPasswordValid() &&
        this.isCourrielValide()
      ) {
        this.boolValide = true;
        this.errorMessage = "";
      } else {
        this.boolValide = false;
        this.errorMessage = "Veuillez remplir tous les champs correctement.";
      }
    },
    fetchUserData(utilisateurId) {
      this.isLoading = true;
      fetch(
        `http://localhost:4208/Labo3_Web_EA_AV/api/utilisateurs/${utilisateurId}`
      )
        .then((response) => {
          if (!response.ok) {
            throw new Error(
              "Erreur lors de la récupération des données utilisateur."
            );
          }
          return response.json();
        })
        .then((data) => {
          console.log("Données de l'utilisateur : ", data);
          this.utilisateur = data;
          this.nom = data.nom;
          this.prenom = data.prenom;
          this.courriel = data.courriel;
        })
        .catch((error) => {
          console.error("Erreur :", error);
          this.$router.push("/"); // Redirect to Accueil on error
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    logout() {
      const userAuthStore = useUserAuthStore(); // Access Pinia store
      userAuthStore.logout(); // Clear userAuth store
      this.$router.push("/connexion"); // Redirect to Connexion
    },
    update() {
      if (this.isLoading) return;

      this.isLoading = true;
      const utilisateurId = this.$route.params.id;

      // Include the password as it is now required
      const dataToUpdate = {
        nom: this.nom,
        prenom: this.prenom,
        courriel: this.courriel,
        password: this.password, // Password is always included
      };

      console.log("Données à mettre à jour : ", dataToUpdate);

      fetch(
        `http://localhost:4208/Labo3_Web_EA_AV/api/utilisateurs/${utilisateurId}`,
        {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(dataToUpdate),
        }
      )
        .then((response) => {
          if (!response.ok) {
            throw new Error("Erreur lors de la mise à jour des données.");
          }
          return response.json();
        })
        .then((data) => {
          if (data.success) {
            alert("Profil mis à jour avec succès !");
            this.fetchUserData(utilisateurId); // Refresh user data
          } else {
            alert("Échec de la mise à jour.");
          }
        })
        .catch((error) => {
          console.error("Erreur lors de la mise à jour :", error);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
  },
};
</script>

<template>
  <div class="container">
    <div class="profilUtilisateur" v-if="utilisateur">
      <p class="bienvenue">
        Bonjour, {{ utilisateur.prenom }} {{ utilisateur.nom }}, <br />
        Bienvenue sur votre profil utilisateur!
      </p>
      <br />
      <v-form ref="form" v-model="valid" lazy-validation>
        <v-text-field
          clearable
          v-model="nom"
          :rules="[rules.max, rules.noNumbers]"
          label="NOM DE FAMILLE"
          class="fixed-size"
          required
        ></v-text-field>
        <v-text-field
          clearable
          v-model="prenom"
          :rules="[rules.max, rules.noNumbers]"
          label="PRENOM"
          class="fixed-size"
          required
        ></v-text-field>
        <v-text-field
          clearable
          :rules="[rules.max, rules.emailValid]"
          v-model="courriel"
          label="ADRESSE COURRIEL"
          class="fixed-size"
          required
        ></v-text-field>
        <v-text-field
          clearable
          v-model="password"
          :rules="[rules.required, rules.min, rules.max]"
          :type="show1 ? 'text' : 'password'"
          hint="Votre mot de passe doit contenir au moins 8 caractères."
          label="MOT DE PASSE"
          @click:append="show1 = !show1"
        />

        <v-btn class="buttons" :disabled="!boolValide" @click="update">
          MODIFIER
        </v-btn>

        <v-btn class="buttons" @click="logout">DECONNEXION</v-btn>
      </v-form>
    </div>
  </div>
</template>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 80vh;
}
.profilUtilisateur {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-width: 100px;
  border-image: url(/src/img/web/pale_glowing_pink_blue_border.png) 25% stretch;
  border-style: solid;
  height: 570px;
  width: 700px;
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
.bienvenue {
  font-weight: 600;
  font-size: 1.5rem;
  color: #5cf4c6;
  text-align: center;
  margin: 0px;
  margin-top: 8px;
}
.fixed-size {
  width: 300px;
}
</style>
