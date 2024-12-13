<script setup>
import { useAppStore } from "@/stores/app"; // Import the cart store
import { useUserAuthStore } from "@/stores/userAuth"; // Import the userAuth store
import { computed } from "vue";

const appStore = useAppStore(); // Initialize the cart store
const userAuthStore = useUserAuthStore(); // Initialize the userAuth store

// Reactive computed values
const panierCount = computed(() => appStore.panier.length); // Count of cart items
const loggedInUser = computed(() => userAuthStore.user); // Logged-in user data

// Logout function
function logout() {
  userAuthStore.logout(); // Clear the user from the store and localStorage
}

// Dynamic route for "Connexion/Profile" button
const userNavigationRoute = computed(() =>
  loggedInUser.value ? `/utilisateur/${loggedInUser.value.id}` : "/connexion"
);
const accueil = "/";
</script>

<template>
  <header>
    <!-- User Info and Deconnexion -->
    <div id="connexion">
      <!-- Panier Button -->
      <router-link to="/panier" class="mr-3">
        <v-badge :content="panierCount" color="red" class="rounded ma-2">
          <v-icon icon="mdi-bag-personal-outline"></v-icon>
        </v-badge>
      </router-link>
      <router-link :to="userNavigationRoute">
        <span class="mdi mdi-account-circle-outline"></span>
        <span v-if="loggedInUser">{{ loggedInUser.nom }}</span>
      </router-link>
      <v-btn
        v-if="loggedInUser"
        :to="accueil"
        id="buttonDeconnexion"
        @click="logout"
      >
        DECONNEXION
      </v-btn>
    </div>

    <!-- Logo -->
    <v-img :width="500" :height="220" src="/src/img/web/logov23749802.png" />

    <!-- Navigation Links -->
    <nav>
      <router-link to="/">Accueil</router-link>
      <router-link to="/catalogueProduits">Produits</router-link>
      <router-link to="/infolettre">Infolettre</router-link>
    </nav>
  </header>
</template>

<style scoped>
header {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  margin: 0px;
  padding: 0px;
}

#connexion {
  display: flex;
  align-self: end;
  margin-right: 15px;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  margin-top: 15px;
}

.mdi {
  color: #b967ff;
  align-self: end;
  font-size: 30px !important;
  margin-right: 10px;
  text-decoration: none;
}

#buttonDeconnexion {
  height: 34px;
  width: 120px;
  margin-left: 10px;
  background-color: #b967ff;
  border-radius: 8px;
  border: 2px solid black;
  box-shadow: 6px 6px black;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  color: black;
}

#buttonDeconnexion:hover {
  box-shadow: 2px 2px black;
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

nav {
  display: flex;
  justify-content: space-evenly;
  align-items: center;
  width: 100%;
  padding: 0px;
  background-color: #800796;
}

nav > a {
  text-decoration: none;
  font-size: 2rem;
  color: #ff71ce;
  margin: 2px;
  padding: 15px 56px;
  font-style: italic;
  font-weight: 600;
  text-transform: uppercase;
  text-shadow: 3px 4px 0 #b967ff;
}

nav > a:hover {
  text-shadow: 1px 2px 0 #b967ff;
}

.no-background {
  background-color: transparent !important;
}
</style>
