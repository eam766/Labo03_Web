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
      errorMessage: "",
      show1: false,
      show2: true,
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

  methods: {
    async checkEmailExists(courriel) {
      try {
        const response = await fetch(
          `http://localhost:4208/Labo3_Web_EA_AV/api/utilisateurs/courriel/${encodeURIComponent(
            courriel
          )}`
        );
        if (!response.ok) {
          throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }
        const result = await response.json();
        // Si un résultat est retourné, le courriel existe
        return result.length > 0;
      } catch (error) {
        console.error("Erreur lors de la vérification du courriel :", error);
        return false; // En cas d'erreur, suppose que le courriel n'existe pas
      }
    },

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
        this.courriel.legnth < 320
      );
    },

    async isValide() {
      // Valide chaque champ individuellement
      if (
        this.isNameAndLastNameValid() &&
        this.isPasswordValid() &&
        this.isCourrielValide()
      ) {
        // Vérifie si le courriel existe dans la base de données
        const emailExists = await this.checkEmailExists(this.courriel);
        if (emailExists) {
          this.errorMessage = "Le courriel est déjà utilisé.";
          this.boolValide = false;
        } else {
          this.errorMessage = "";
          this.boolValide = true;
        }
      } else {
        this.errorMessage = "Veuillez remplir tous les champs correctement.";
        this.boolValide = false;
      }
    },

    async submitForm() {
      await this.isValide(); // Vérifie si tous les champs sont valides

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

        if (response.ok && result.success) {
          this.$router.push({
            path: "/connexion",
            query: { message: "Inscription réussie" },
          });
        } else {
          this.errorMessage = result.message || "Une erreur est survenue.";
        }
      } catch (error) {
        this.errorMessage = "Erreur de connexion au serveur.";
      }
    },
  },
};
</script>

<template>
  <div class="container">
    <div class="connexion">
      <p id="titre">CRÉER UN COMPTE</p>
      <v-form @submit.prevent="submitForm">
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
            <v-col cols="12" md="8">
              <p class="success">{{ successMessage }}</p>
            </v-col>
            <v-col cols="12" md="8">
              <p v-if="!boolValide" class="error">
                {{ errorMessage }}
              </p>
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
