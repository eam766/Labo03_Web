<script>
export default {
  data() {
    return {
      courriel: "",
    };
  },
  methods: {
    async submitForm() {
      try {
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

        console.log("Réponse du serveur :", await response.json());
      } catch (error) {
        console.error("Erreur lors de l'envoi :", error);
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
            v-model="courriel"
            label="Courriel"
            class="flex-grow-1"
          ></v-text-field>
          <v-btn class="ml-2" type="submit" color="primary">S'INSCRIRE</v-btn>
        </v-form>
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

#email {
  height: 30px;
  width: 300px;
  padding-left: 10px;
}

input {
  background-color: white;
  color: black;
}

input:hover {
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

.error {
  margin: 0px;
  padding: 0px;
  color: red;
  font-size: 0.8rem;
  margin-bottom: 10px;
}

#button {
  height: 34px;
  width: 100px;
  margin-left: 10px;
  background-color: #b967ff;
  border-radius: 8px;
  border: 2px solid black;
  box-shadow: 6px 6px black;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

p {
  text-align: center;
}

#button:hover {
  box-shadow: 2px 2px black;
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 70vh; /* S'assurer qu'il occupe tout l'écran */
}
</style>
