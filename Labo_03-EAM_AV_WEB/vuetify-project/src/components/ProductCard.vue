<script setup>
const { produit } = defineProps({
  produit: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <v-card class="produit">
    <v-img :width="250" :height="280" :src="produit.image" />
    <v-card-title class="description realistic-marker-highlight">{{
      produit.nom
    }}</v-card-title>
    <v-card-subtitle>{{ produit.prix + "$" }}</v-card-subtitle>
  </v-card>
</template>

<style scoped>
.produit {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 5px;
  margin-bottom: 30px;
  color: #ff71ce;
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
  background-color: transparent !important;
  max-width: 250px;
  box-shadow: none;
}

.realistic-marker-highlight {
  background-image: url("/src/img/web/surligne.png");
  background-repeat: repeat-x;
  background-size: 100% 100%;
  background-position: center 75%;
  padding: 0 5px;
  display: inline-block;
}

.description {
  font-weight: 600;
  font-size: 1.3rem;
}
</style>
