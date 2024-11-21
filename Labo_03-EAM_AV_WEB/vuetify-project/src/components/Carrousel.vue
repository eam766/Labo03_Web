<script setup>
import { ref, onMounted } from "vue";

const URL = "http://localhost:4208/Labo3_Web_EA_AV/api/produitsExclusifs";
let produits = ref([]);
let isReady = ref(false);
onMounted(() => {
  fetch(URL)
    .then((response) => response.json()) // Transforme la réponse en JSON
    .then((data) => {
      // Utilise les données JSON
      console.log(`DB produits`);
      console.log(data);
      produits.value = data;
      isReady.value = true;
    })
    .catch((error) => {
      console.error("Une erreur s'est produite :", error); // Gère les erreurs
    });
});
</script>
<template>
  <v-carousel
    v-if="isReady"
    height="400"
    show-arrows="hover"
    cycle
    hide-delimiter-background
  >
    <v-carousel-item v-for="(produit, index) in produits" :key="index">
      <img :src="produit.image" alt="Image produit" class="d-block w-100" />
    </v-carousel-item>
  </v-carousel>
</template>
<style>
img {
  object-fit: contain;
  height: 100%;
  width: 100%;
}
</style>
