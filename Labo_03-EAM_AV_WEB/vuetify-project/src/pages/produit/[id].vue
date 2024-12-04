<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const productDetails = ref(null);
console.log(`Récupération des détails pour le produit ID: ${route.params.id}`);
const URL = `http://localhost:4208/Labo3_Web_EA_AV/api/produits/${route.params.id}`;
onMounted(async () => {
  try {
    const response = await fetch(URL);
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
});
</script>

<template>
  <v-container class="pa-4">
    <div v-if="productDetails">
      <h1>{{ productDetails.nom }}</h1>
      <v-img :width="250" :height="280" :src="productDetails.image" />
      <p>{{ productDetails.description }}</p>
      <p>{{ productDetails.prix }}</p>
    </div>
  </v-container>
</template>
