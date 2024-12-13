<script setup>
import { ref, onMounted, watchEffect } from "vue";
import ProductCard from "@/components/ProductCard.vue"; // Your ProductCard component

// API URLs
const URL_PRODUITS = "http://localhost:4208/Labo3_Web_EA_AV/api/produits";
const URL_DISTINCT =
  "http://localhost:4208/Labo3_Web_EA_AV/api/distinct/produits";
const URL_TAILLES =
  "http://localhost:4208/Labo3_Web_EA_AV/api/distinct/tailles/taille";

// Reactive variables
const produits = ref([]); // Products to display
const types = ref(["Tous"]); // Options for the "Type" filter
const couleurs = ref(["Tous"]); // Options for the "Couleur" filter
const tailles = ref(["Tous"]); // Options for the "Taille" filter

// Selected filters
const selectedType = ref("Tous");
const selectedCouleur = ref("Tous");
const selectedTaille = ref("Tous");
const prixMin = ref(0);
const prixMax = ref(200);

// Fetch distinct values for filters
async function fetchDistinctValues(column, destinationArray) {
  try {
    const response = await fetch(`${URL_DISTINCT}/${column}`);
    const data = await response.json();
    destinationArray.value = ["Tous", ...data];
  } catch (error) {
    console.error(`Error fetching distinct ${column} values:`, error);
  }
}

async function fetchTailles() {
  try {
    const response = await fetch(URL_TAILLES);
    let data = await response.json();

    // Map the data to objects with `value` and `label`
    tailles.value = [
      { value: "Tous", label: "Tous" },
      ...data.map((taille) => ({
        value: taille, // Keep the original value for filtering
        label: taille === 1 ? "Unique" : taille.toString(),
      })),
    ];
  } catch (error) {
    console.error("Error fetching tailles:", error);
  }
}

// Fetch products based on filters
async function fetchProduits(filters = {}) {
  const query = new URLSearchParams(filters).toString();
  try {
    const response = await fetch(`${URL_PRODUITS}?${query}`);
    console.log(`${URL_PRODUITS}?${query}`);
    produits.value = await response.json();
  } catch (error) {
    console.error("Error fetching products:", error);
  }
}

// Initialize filters and products on component mount
onMounted(() => {
  fetchDistinctValues("type", types); // Fetch distinct "Type" values
  fetchDistinctValues("couleur", couleurs); // Fetch distinct "Couleur" values
  fetchTailles();
  fetchProduits(); // Fetch all products by default
});

// Apply filters dynamically whenever any filter value changes
watchEffect(() => {
  const filters = {};
  if (selectedType.value !== "Tous") filters.type = selectedType.value;
  if (selectedCouleur.value !== "Tous") filters.couleur = selectedCouleur.value;
  if (selectedTaille.value !== "Tous") filters.taille = selectedTaille.value; // Use the `value` directly
  filters.prix_min = prixMin.value;
  filters.prix_max = prixMax.value;

  fetchProduits(filters);
});
</script>

<template>
  <v-container>
    <!-- Filter Controls -->
    <v-row>
      <!-- Type Dropdown -->
      <v-col cols="12" sm="6" md="4">
        <v-select
          v-model="selectedType"
          :items="types"
          label="Filter by Type"
        ></v-select>
      </v-col>

      <!-- Couleur Dropdown -->
      <v-col cols="12" sm="6" md="4">
        <v-select
          v-model="selectedCouleur"
          :items="couleurs"
          label="Filter by Couleur"
        ></v-select>
      </v-col>

      <!-- Taille Dropdown -->
      <v-col cols="12" sm="6" md="4">
        <v-select
          v-model="selectedTaille"
          :items="tailles"
          item-value="value"
          item-title="label"
          label="Filter by Taille"
        >
          <template v-slot:item="{ props, item }">
            <v-list-item v-bind="props">
              <v-list-item-content>
                <v-list-item-title>{{ item.label }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>
        </v-select>
      </v-col>

      <!-- Price Range -->
      <v-col cols="12" sm="6" md="4">
        <v-text-field
          v-model.number="prixMin"
          label="Min Price"
          type="number"
        ></v-text-field>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <v-text-field
          v-model.number="prixMax"
          label="Max Price"
          type="number"
        ></v-text-field>
      </v-col>
    </v-row>

    <!-- Display Products -->
    <v-row justify="center" align="center" class="g-4">
      <v-col
        v-for="produit in produits"
        :key="produit.id"
        cols="12"
        sm="6"
        md="4"
        lg="3"
      >
        <ProductCard :produit="produit" />
      </v-col>
    </v-row>
  </v-container>
</template>
