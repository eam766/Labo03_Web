// Utilities
import { defineStore } from "pinia";

export const useAppStore = defineStore("app", {
  state: () => {
    //
    if (localStorage.getItem("panier")) {
      return {
        panier: JSON.parse(localStorage.getItem("panier")),
        user: "Client",
      };
    }
    return { panier: [], user: "Client" };
  },
  actions: {
    addToCart(product) {
      const existingProduct = this.panier.find(
        (item) => item.id === product.id
      );
      if (existingProduct) {
        alert("Ce produit est déjà dans le panier.");
      } else {
        this.panier.push(product);
        localStorage.setItem("panier", JSON.stringify(this.panier));
      }
    },

    removeFromCart(index) {
      // const index = this.panier.indexOf(Number(pokemonID))
      this.panier.splice(index, 1);
      localStorage.setItem("panier", JSON.stringify(this.panier));
    },
    deleteCart() {
      this.panier = [];
      localStorage.removeItem("panier");
    },
  },
});
