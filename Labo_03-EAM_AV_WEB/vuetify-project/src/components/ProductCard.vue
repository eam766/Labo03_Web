<template>
  <v-card>
    <v-img :width="250" :height="280" src="/src/img/produits/shirt1.webp" />
    <v-card-title>Polo de licornes</v-card-title>
    <v-card-subtitle>146,99$</v-card-subtitle>
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
  cursor: url(../img/web/purple_unicorn_neon.png), auto;
}

.realistic-marker-highlight {
  background-image: url("../img/web/surligne.png");
  background-repeat: repeat-x;
  background-size: 100% 100%;
  background-position: center 75%;
  padding: 0 5px;
  display: inline-block;
}

.description {
  font-weight: 600;
  font-size: 1.3rem;
  margin-top: 5px !important;
}

.produit {
  margin: 20px;
}

.produit > p {
  margin: 0px;
}
</style>
