<template>
  <v-container>
    <h1>Votre Panier</h1>
    <div v-if="store.panier.length > 0">
      <v-card
        v-for="(product, index) in store.panier"
        :key="product.id"
        class="my-4"
      >
        <v-card-title>{{ product.name }}</v-card-title>
        <v-card-subtitle>{{ product.price }} $</v-card-subtitle>
        <v-img :src="product.image" max-width="150"></v-img>
        <v-card-actions>
          <v-btn color="error" @click="removeFromCart(index)">
            Supprimer
          </v-btn>
        </v-card-actions>
      </v-card>
      <v-btn color="error" class="mt-4" @click="clearCart"
        >Vider le panier</v-btn
      >
    </div>
    <p v-else>Votre panier est vide.</p>
  </v-container>
</template>

<script setup>
import { useAppStore } from "@/stores/app";

const store = useAppStore();

function removeFromCart(index) {
  store.removeFromCart(index);
}

function clearCart() {
  store.deleteCart();
}
</script>

<style scoped></style>
