<template>
  <v-container>
    <h1 class="colorBlue">Votre Panier</h1>
    <div v-if="store.panier.length > 0">
      <v-card
        v-for="(product, index) in store.panier"
        :key="product.id"
        class="my-4 cart-item"
      >
        <div class="product-wrapper">
          <v-img
            :src="product.image"
            max-width="150"
            class="product-image"
          ></v-img>
          <div class="product-details">
            <v-card-title class="colorBlue">{{ product.name }}</v-card-title>
            <v-card-subtitle class="colorBlue"
              >{{ product.price }} $</v-card-subtitle
            >
            <v-card-actions>
              <v-btn color="error" @click="removeFromCart(index)">
                Supprimer
              </v-btn>
            </v-card-actions>
          </div>
        </div>
      </v-card>
      <v-btn class="mt-4 buttons" @click="clearCart"> Vider le panier </v-btn>
    </div>
    <p v-else class="colorBlue">Votre panier est vide.</p>
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

<style scoped>
.cart-item {
  padding: 10px;
  background-color: transparent;
  box-shadow: none;
}

.product-wrapper {
  display: flex;
  align-items: center;
}

.product-image {
  margin-right: 20px;
  flex-shrink: 0;
}

.product-details {
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex-grow: 1;
}

.v-card-title,
.v-card-subtitle {
  margin: 0;
}

.v-card-actions {
  margin-top: 10px;
}

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

.colorBlue {
  color: #5cf4c6;
}
</style>
