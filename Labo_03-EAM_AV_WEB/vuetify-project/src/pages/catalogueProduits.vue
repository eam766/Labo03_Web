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
  <div class="listeProduits">
    <ProductCard
      v-for="produit in produits"
      :key="produit.id"
      :produit="produit"
    />
  </div>
</template>

<style>
.listeProduits {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  width: 95%;
  margin: 0 auto;
  justify-content: center;
  align-items: center;
}
</style>
