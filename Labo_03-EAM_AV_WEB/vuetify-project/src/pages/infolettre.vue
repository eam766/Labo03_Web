<script>
export default {
  data() {
    return {
      courriel: "",
      boolValide: false,
      boolDejaLa: false,
      successMessage: "",
      errorMessage: "",
      emailRules: [
        (value) => {
          if (value) return true;
          return "Courriel requis.";
        },
        (value) => {
          if (/.+@.+\..+/.test(value)) return true;
          return "Le courriel doit être valide.";
        },
      ],
    };
  },
  watch: {
    courriel() {
      this.isValide();
    },
  },
  methods: {
    isValide() {
      this.boolDejaLa = false;
      const pattern =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.boolValide = this.courriel && pattern.test(this.courriel);
      if (this.boolValide) {
        this.successMessage = "";
      }
    },
    async checkEmailExists(courriel) {
      try {
        const response = await fetch(
          `http://localhost:4208/Labo3_Web_EA_AV/api/abonnements/${encodeURIComponent(
            courriel
          )}`
        );
        if (!response.ok) {
          throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }
        const result = await response.json();
        if (result.length > 0) {
          this.boolDejaLa = true;
          return true;
        } // Si un résultat est retourné, le courriel existe
      } catch (error) {
        console.error("Erreur lors de la vérification du courriel :", error);
        return false; // En cas d'erreur, on suppose que le courriel n'existe pas
      }
    },
    async submitForm() {
      if (!this.boolValide) {
        this.errorMessage = "Formulaire invalide.";
        console.error("Formulaire invalide.");
        return;
      }

      try {
        const emailExists = await this.checkEmailExists(this.courriel);
        if (emailExists) {
          this.errorMessage = "Le courriel est déjà inscrit.";
          this.successMessage = "";
          console.error("Le courriel est déjà inscrit.");
          return;
        }

        const response = await fetch(
          "http://localhost:4208/Labo3_Web_EA_AV/api/abonnements",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ courriel: this.courriel }),
          }
        );

        const result = await response.json();
        console.log("Réponse du serveur :", result);

        if (response.ok) {
          this.successMessage = "Inscription réussie.";
          this.errorMessage = "";
          this.courriel = ""; // Réinitialise le champ
          this.boolValide = false; // Réinitialise le bouton
        } else {
          this.errorMessage = "Erreur lors de l'inscription.";
        }
      } catch (error) {
        console.error("Erreur lors de l'envoi :", error);
        this.errorMessage = "Erreur de connexion au serveur.";
        this.successMessage = "";
      }
    },
  },
};
</script>

<template>
  <div class="container">
    <div class="infolettreContainer">
      <img
        src="/src/img/produits/shirt1.webp"
        alt="polo licorne"
        width="230"
        height="260"
      />
      <div class="infolettre">
        <p id="lettrePlusGrande">INSCRIS-TOI À NOTRE INFOLETTRE!</p>
        <p>
          REÇOIS UN ACCÈS EXCLUSIF POUR NOS NOUVEAUTÉS AINSI QUE DES RABAIS
          SURPRISE
        </p>
        <v-form @submit.prevent="submitForm">
          <v-text-field
            clearable
            v-model="courriel"
            :rules="emailRules"
            label="ADRESSE COURRIEL"
            class="fixed-size"
          ></v-text-field>
          <v-btn :disabled="!boolValide" class="button" type="submit"
            >S'INSCRIRE</v-btn
          >
        </v-form>
        <p class="success">{{ successMessage }}</p>
        <p v-if="!boolValide || boolDejaLa" class="error">{{ errorMessage }}</p>
      </div>
      <img
        src="/src/img/produits/tie7.webp"
        alt="cravate avec motif de licornes"
      />
    </div>
  </div>
</template>

<style scoped>
a {
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

.infolettreContainer {
  display: flex;
  flex-direction: row !important;
  align-items: center;
  justify-content: center;
}

.infolettreContainer > img {
  width: 210px;
  height: 240px;
  border-radius: 8px;
  border: 0.2rem solid #fff;
  border-radius: 2rem;
  padding: 0.4em;
  box-shadow: 0 0 0.2rem #fff, 0 0 0.2rem #fff, 0 0 2rem #bc13fe,
    0 0 0.8rem #bc13fe, 0 0 2.8rem #bc13fe, inset 0 0 1.3rem #bc13fe;
  transform: rotate(5deg);
}
.infolettreContainer > img:first-child {
  transform: rotate(-5deg);
}

.infolettre {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-width: 50px;
  border-image: url(/src/img/web/frame_Multicolor.png) 25% stretch;
  border-style: solid;
  height: 330px;
  width: 530px;
  margin: 15px;
}

#confirmation {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: #bfb9ff;
  color: white;
  border: 2px solid white;
  width: 100%;
}

.infolettre > p {
  margin: 9px;
  color: #5cf4c6;
}

#lettrePlusGrande {
  font-weight: 600;
  font-size: 1.5rem;
}

.fixed-size {
  width: 300px;
}

.button {
  height: 34px;
  width: 100px;
  background-color: #b967ff;
  border-radius: 8px;
  border: 2px solid black;
  box-shadow: 6px 6px black;
  color: black;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

p {
  text-align: center;
}

.button:hover {
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

.success {
  color: green;
  font-weight: bold;
}

.error {
  color: red;
  font-weight: bold;
}
</style>
