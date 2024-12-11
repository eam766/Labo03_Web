<script>
export default {
  data() {
    return {
      utilisateur: null,
      nom: "",
      prenom: "",
      courriel: "",
    };
  },
  created() {
    const utilisateurId = this.$route.params.id;

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
        this.nom = this.utilisateur.nom;
        this.prenom = this.utilisateur.prenom;
        this.courriel = this.utilisateur.courriel;
      })
      .catch((error) => {
        console.error("Erreur :", error);
      });
  },
  methods: {
    logout() {
      this.$router.push("/connexion");
    },
    update() {},
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
        <v-btn class="buttons" @click="update">MODIFIER</v-btn>
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
