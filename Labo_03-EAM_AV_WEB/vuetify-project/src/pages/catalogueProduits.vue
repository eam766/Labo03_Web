<script setup>
import ProductCard from "@/components/ProductCard.vue";
import { ref } from "vue";

const URL = "http://localhost:4208/Labo3_Web_EA_AV/api/produits";
let produits = ref([]);

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
</script>

<template>
  <ProductCard />
</template>
