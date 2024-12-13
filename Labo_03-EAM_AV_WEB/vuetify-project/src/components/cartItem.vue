<script setup>
import { defineProps, defineEmits } from "vue";

// Props to accept product details and index in the cart
const { product, index } = defineProps({
  product: {
    type: Object,
    required: true,
  },
  index: {
    type: Number,
    required: true,
  },
});

// Emit an event to remove the product
defineEmits(["remove"]);
</script>

<template>
  <v-card class="cart-item">
    <v-img :width="150" :height="150" :src="product.image" />
    <v-card-title>{{ product.name }}</v-card-title>
    <v-card-subtitle>{{ product.price + "$" }}</v-card-subtitle>
    <v-card-actions>
      <v-btn color="error" @click="$emit('remove', index)">
        Remove from Cart
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<style scoped>
.cart-item {
  margin: 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>
