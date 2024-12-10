<script>
export default {
  data() {
    return {
      nom: "",
      prenom: "",
      password: "",
      courriel: "",
      boolValide: false,
      successMessage: "",
      show1: false,
      show2: true,
      rules: {
        required: (value) => !!value || "Requis.",
        min: (v) => v.length >= 8 || "Minimum 8 caractères svp",
        max: (v) => v.length < 256 || "Maximum  256 caractères svp",
        emailValid: (value) => {
          if (/.+@.+\..+/.test(value)) return true;

          return "Le courriel doit être valide.";
        },

        noNumbers: (value) => {
          // Check if the value contains numbers
          const numberRegex = /\d/;
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

  methods: {
    async fetchProductDetails() {
      try {
        const response = await fetch();
        if (!response.ok) {
          throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }
        const data = await response.json();
        console.log("Détails du produit récupérés :", data);

        // Accéder au premier élément du tableau
        productDetails.value = data[0];
      } catch (error) {
        console.error(
          "Erreur lors de la récupération des détails du produit :",
          error
        );
      }
    },

    isNameAndLastNameValid() {
      if (
        this.nom.length > 0 &&
        this.nom.length < 256 &&
        this.prenom.length > 0 &&
        this.prenom.length < 256
      ) {
        return true;
      } else {
        return false;
      }
    },
    isPasswordValid() {
      if (this.password.length > 7 && this.password.length < 256) {
        return true;
      } else {
        return false;
      }
    },
    isCourrielValide() {
      // Vérifie si le courriel est valide
      const pattern =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if (!this.courriel || !pattern.test(this.courriel)) {
        return false; // Désactive le bouton si le courriel est invalide
      } else {
        return true; // Active le bouton si tout est correct
      }
    },
    isValide() {
      if (
        this.isCourrielValide() &&
        this.isNameAndLastNameValid() &&
        this.isPasswordValid()
      ) {
        this.boolValide = true;
      } else {
        this.boolValide = false;
      }
    },
    async submitForm() {
      if (!this.boolValide) {
        console.error("Formulaire invalide.");
        return; // Stoppe la soumission si le formulaire est invalide
      }

      try {
        const response = await fetch(
          "http://localhost:4208/Labo3_Web_EA_AV/api/utilisateurs",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              nom: this.nom,
              prenom: this.prenom,
              password: this.password,
              courriel: this.courriel,
            }),
          }
        );

        const result = await response.json();
        console.log("Réponse du serveur :", result);

        if (response.ok) {
          this.successMessage = "Inscription reussi";
          this.nom = "";
          this.prenom = "";
          this.password = "";
          this.courriel = ""; // Réinitialise le champ
          this.boolValide = false; // Réinitialise le bouton
        }
      } catch (error) {
        console.error("Erreur lors de l'envoi :", error);
      }
    },
  },
};
</script>

<template>
  <div class="container">
    <div class="connexion">
      <p id="titre">CRÉER UN COMPTE</p>
      <v-form>
        <v-container fluid>
          <v-row justify="center" align="center">
            <!-- Prénom -->
            <v-col cols="12" md="8">
              <v-text-field
                clearable
                v-model="prenom"
                :rules="[rules.required, rules.max, rules.noNumbers]"
                hint="Votre prénom"
                label="Entrer votre prénom"
                name="prenom"
              ></v-text-field>
            </v-col>

            <!-- Nom -->
            <v-col cols="12" md="8">
              <v-text-field
                clearable
                v-model="nom"
                :rules="[rules.required, rules.max, rules.noNumbers]"
                hint="Votre Nom"
                label="Entrer votre nom"
                name="nom"
              ></v-text-field>
            </v-col>

            <!-- Password -->
            <v-col cols="12" md="8">
              <v-text-field
                clearable
                v-model="password"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                :rules="[rules.required, rules.min, rules.max]"
                :type="show1 ? 'text' : 'password'"
                hint="Votre mot de passe de 8 caractères minimum."
                label="Choose a password"
                name="password"
                @click:append="show1 = !show1"
              ></v-text-field>
            </v-col>

            <!-- Courriel -->
            <v-col cols="12" md="8">
              <v-text-field
                clearable
                v-model="courriel"
                :rules="[rules.required, rules.max, rules.emailValid]"
                hint="Entrer votre courriel"
                label="Courriel"
                name="courriel"
              ></v-text-field>
            </v-col>

            <!-- Submit Button -->
            <v-col cols="12" md="8" class="text-center">
              <v-btn
                variant="outlined"
                class="mt-4"
                type="submit"
                color="primary"
                :disabled="!boolValide"
                @click="submitForm"
              >
                S'INSCRIRE
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
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
  border: 10px solid transparent;
  border-image: url(/src/img/web/pale_glowing_pink_blue_border.png) 30% stretch;
  height: auto;
  width: 70%;
  padding: 20px;
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

#mdp,
#email,
#prenom,
#nomDeFamille {
  height: 30px;
  width: 300px;
  padding-left: 10px;
  margin: 13px;
}

input:hover {
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

input {
  background-color: white;
}

.error {
  margin: 0px;
  padding: 0px;
  color: red;
  font-size: 0.8rem;
  margin-left: 15px;
}

#buttonInscription,
#buttonConnexion {
  height: 34px;
  width: 100px;
  margin-left: 10px;
  margin-top: 15px;
  background-color: #b967ff;
  border-radius: 8px;
  border: 2px solid black;
  box-shadow: 6px 6px black;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  color: black;
}

#buttonInscription:hover,
#buttonConnexion:hover {
  box-shadow: 2px 2px black;
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: auto;
  margin-top: 20px;
}
</style>
