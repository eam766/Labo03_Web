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
    };
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
      const dataToUpdate = {
        nom: this.nom,
        prenom: this.prenom,
        courriel: this.courriel,
        password: this.password,
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
          v-model="nom"
          label="NOM DE FAMILLE"
          class="fixed-size"
          required
        ></v-text-field>
        <v-text-field
          v-model="prenom"
          label="PRENOM"
          class="fixed-size"
          required
        ></v-text-field>
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
        <v-btn class="buttons" :disabled="isLoading" @click="update"
          >MODIFIER</v-btn
        >
        <v-btn class="buttons" @click="logout">DECONNEXION</v-btn>
      </v-form>
    </div>
  </div>
</template>
