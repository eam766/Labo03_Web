<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const productDetails = ref(null);
const productSizes = ref([]);
const productRealSizes = ref([]);

console.log(`Récupération des détails pour le produit ID: ${route.params.id}`);
const URL = `http://localhost:4208/Labo3_Web_EA_AV/api/produits/${route.params.id}`;
const URLquantity = `http://localhost:4208/Labo3_Web_EA_AV/api/quantityProduits/${route.params.id}`;
const leChiffre = route.params.id - 1;

// Mapper les ID des tailles avec des tailles réelles
const sizeMapping = {
  1: "Taille Unique",
  2: 44,
  3: 46,
  4: 48,
  5: 50,
  6: 52,
  7: 54,
  8: 56,
};

async function fetchProductDetails() {
  try {
    const response = await fetch(URL);
    if (!response.ok) {
      throw new Error(`Erreur ${response.status}: ${response.statusText}`);
    }
    const data = await response.json();
    console.log("Détails du produit récupérés :", data);

    // Accéder au premier élément du tableau
    productDetails.value = data[leChiffre];
  } catch (error) {
    console.error(
      "Erreur lors de la récupération des détails du produit :",
      error
    );
  }
}

// Fonction pour récupérer les tailles et quantités du produit
async function fetchProductSizes() {
  try {
    const response = await fetch(URLquantity);
    if (!response.ok) {
      throw new Error(`Erreur ${response.status}: ${response.statusText}`);
    }
    const data = await response.json();
    console.log("Tailles et quantités récupérées :", data);

    // Transformer les tailles pour utiliser les noms réels
    productSizes.value = data;
    productRealSizes.value = data.map((size) => ({
      taille: sizeMapping[size.idTaille] || `ID Inconnu (${size.idTaille})`,
      quantity: size.quantity,
    }));
  } catch (error) {
    console.error(
      "Erreur lors de la récupération des tailles et quantités :",
      error
    );
  }
}

onMounted(async () => {
  fetchProductDetails();
  fetchProductSizes();
});
</script>

<template>
  <v-container class="pa-4">
    <div v-if="productDetails">
      <h1>{{ productDetails.nom }}</h1>
      <v-img :width="250" :height="280" :src="productDetails.image" />
      <p>{{ productDetails.description }}</p>
      <p>{{ productDetails.prix }}</p>
      <h2>Tailles disponibles</h2>
      <ul v-if="productRealSizes.length > 0">
        <li v-for="size in productRealSizes" :key="size.taille">
          {{ size.taille }} - Quantité: {{ size.quantity }}
        </li>
      </ul>
      <p v-else>Aucune taille disponible pour ce produit.</p>
    </div>
  </v-container>
</template>
