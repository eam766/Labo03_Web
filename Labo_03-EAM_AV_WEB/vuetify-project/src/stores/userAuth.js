import { defineStore } from "pinia";

export const useUserAuthStore = defineStore("userAuth", {
  state: () => ({
    user: null,
    token: null,
  }),
  actions: {
    login(user, token) {
      this.user = user;
      this.token = token;
      localStorage.setItem(
        "userAuth",
        JSON.stringify({ user: this.user, token: this.token })
      );
    },
    logout() {
      this.user = null;
      this.token = null;
      localStorage.removeItem("userAuth");
    },
    hydrate() {
      const storedData = JSON.parse(localStorage.getItem("userAuth"));
      if (storedData) {
        this.user = storedData.user || null;
        this.token = storedData.token || null;
      }
    },
  },
});
