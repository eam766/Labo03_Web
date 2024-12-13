/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App
 */

// Plugins
import { registerPlugins } from "@/plugins";

// Components
import App from "./App.vue";

// Composables
import { createApp } from "vue";
import { createPinia } from "pinia";

// Import your `userAuth` store
import { useUserAuthStore } from "@/stores/userAuth";

const app = createApp(App);

// Register plugins
registerPlugins(app);

// Create Pinia instance and use it in the app
const pinia = createPinia();
app.use(pinia);

// Hydrate the `userAuth` store from localStorage
const userAuthStore = useUserAuthStore();
userAuthStore.hydrate();

// Mount the app
app.mount("#app");
