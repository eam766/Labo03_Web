<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { useAppStore } from "@/stores/app.js";

const route = useRoute();
const productDetails = ref(null);
const productSizes = ref([]);
const productRealSizes = ref([]);
const selectedImage = ref(null);
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

function updateMainImage(imageSrc, index) {
  productDetails.value.image = imageSrc;
  selectedImage.value = index; // Enregistre l'index sélectionné
}

onMounted(async () => {
  fetchProductDetails();
  fetchProductSizes();
});
</script>

<template>
  <v-container class="pa-8" style="color: #ff71ce">
    <div v-if="productDetails" class="d-flex">
      <!-- Section de gauche pour l'image principale et les petites images -->
      <div class="d-flex flex-column align-items-start mr-8">
        <!-- Image agrandie -->
        <v-img :width="400" :height="450" :src="productDetails.image" />

        <!-- Deux petites images en ligne (horizontalement) -->
        <div class="d-flex">
          <v-img
            :src="productDetails.image"
            :width="100"
            :height="120"
            class="my-0 py-0"
            :class="{ selected: selectedImage === 0 }"
            @click="updateMainImage(productDetails.image, 0)"
          />
          <v-img
            :src="productDetails.image"
            :width="100"
            :height="120"
            class="ml-5 my-0 py-0"
            :class="{ selected: selectedImage === 1 }"
            @click="updateMainImage(productDetails.image, 1)"
          />
        </div>
      </div>

      <!-- Section de droite pour la description, titre, prix, etc. -->
      <div class="d-flex flex-column justify-start">
        <h1>{{ productDetails.nom }}</h1>
        <p>{{ productDetails.prix }}</p>
        <p class="my-4">{{ productDetails.description }}</p>

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
        <p v-else class="my-4">Aucune taille disponible pour ce produit.</p>
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

/* Ajout pour aligner les petites images horizontalement */
.d-flex {
  display: flex;
  flex-direction: row;
  align-items: start; /* Centrer les images */
  margin-top: 0px;
}

.selected {
  border: 3px solid #ff71ce;
  box-shadow: 0px 0px 10px #ff71ce;
  border-radius: 5px;
}

.v-img {
  display: block;
}
</style>
