<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { useAppStore } from "@/stores/app.js";

const route = useRoute();
const productDetails = ref(null);
const productSizes = ref([]);
const productRealSizes = ref([]);
const store = useAppStore();

console.log(`Récupération des détails pour le produit ID: ${route.params.id}`);
const URL = `http://localhost:4208/Labo3_Web_EA_AV/api/produit/${route.params.id}`;
const URLquantity = `http://localhost:4208/Labo3_Web_EA_AV/api/quantityProduits/${route.params.id}`;

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
    productDetails.value = data[0];
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
function addToCart() {
  if (productDetails.value) {
    store.addToCart({
      id: productDetails.value.id,
      name: productDetails.value.nom,
      price: productDetails.value.prix,
      image: productDetails.value.image,
    });
    alert(`${productDetails.value.nom} ajouté au panier.`);
  }
}

onMounted(async () => {
  fetchProductDetails();
  fetchProductSizes();
});
</script>

<template>
  <v-container class="pa-8" style="color: #ff71ce">
    <div v-if="productDetails" class="d-flex">
      <!-- Image agrandie à gauche -->
      <v-img
        :width="400"
        :height="450"
        :src="productDetails.image"
        class="mr-8"
      />

      <div class="d-flex flex-column justify-start">
        <!-- Titre avec marge verticale -->
        <h1>{{ productDetails.nom }}</h1>

        <!-- Prix avec marge verticale -->
        <p>{{ productDetails.prix }}</p>

        <!-- Description avec marge verticale -->
        <p class="my-4">{{ productDetails.description }}</p>

        <!-- Tailles disponibles -->
        <h2 class="my-2">Tailles disponibles</h2>

        <ul v-if="productRealSizes.length > 0">
          <li
            class="ml-10 my-2"
            v-for="size in productRealSizes"
            :key="size.taille"
          >
            {{ size.taille }} - Quantité: {{ size.quantity }}
          </li>
        </ul>

        <!-- Message si aucune taille n'est disponible -->
        <p v-else class="my-4">Aucune taille disponible pour ce produit.</p>

        <!-- Bouton Ajouter au panier avec marge verticale -->
        <v-btn class="buttons my-4" @click="addToCart">Ajouter au panier</v-btn>
      </div>
    </div>
  </v-container>
</template>

<style scoped>
.buttons {
  height: 40px;
  width: 150px;
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
</style>
