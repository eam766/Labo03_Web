<script setup>
import ProductCard from "@/components/ProductCard.vue";
import { ref, onMounted } from "vue";

const URL = "http://localhost:4208/Labo3_Web_EA_AV/api/produits";
let produits = ref([]);
onMounted(() => {
  fetch(URL)
    .then((response) => response.json()) // Transforme la réponse en JSON
    .then((data) => {
      // Utilise les données JSON
      console.log(`DB produits`);
      console.log(data);
      produits.value = data;
    })
    .catch((error) => {
      console.error("Une erreur s'est produite :", error); // Gère les erreurs
    });
});
</script>

<template>
  <v-container class="pa-4" fluid>
    <v-row justify="center" .align="center" class="g-4">
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

<!-- <style>
.listeProduits {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  width: 95%;
  margin: 0 auto;
  justify-content: center !important;
  align-items: center;
}
</style> -->
