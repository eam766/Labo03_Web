<template>
  <header>
    <div id="connexion">
      <a href="./connexion"
        ><span class="mdi mdi-account-circle-outline"></span
      ></a>
      <input id="buttonDeconnexion" type="submit" value="DECONNEXION" />
    </div>
    <v-img :width="500" :height="220" src="/src/img/web/logov23749802.png" />
    <nav>
      <a href="./">Accueil</a>
      <a href="./catalogueProduits">Produits</a>
      <a href="./infolettre">Infolettre</a>
    </nav>
  </header>
</template>

<style scoped>
header {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  margin: 0px;
  padding: 0px;
}

#connexion {
  display: flex;
  align-self: end;
  margin-right: 15px;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  margin-top: 15px;
}

.mdi {
  color: #b967ff;
  align-self: end;
  font-size: 30px !important;
  margin-right: 10px;
  text-decoration: none;
}

#buttonDeconnexion {
  height: 34px;
  width: 120px;
  margin-left: 10px;
  background-color: #b967ff;
  border-radius: 8px;
  border: 2px solid black;
  box-shadow: 6px 6px black;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  color: black;
}

#buttonDeconnexion:hover {
  box-shadow: 2px 2px black;
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

nav {
  display: flex;
  justify-content: space-evenly;
  align-items: center;
  width: 100%;
  padding: 0px;
  background-color: #800796;
}

nav > a {
  text-decoration: none;
  font-size: 2rem;
  color: #ff71ce;
  margin: 2px;
  padding: 15px 56px;
  font-style: italic;
  font-weight: 600;
  text-transform: uppercase;
  text-shadow: 3px 4px 0 #b967ff;
}

nav > a:hover {
  text-shadow: 1px 2px 0 #b967ff;
}
</style>
