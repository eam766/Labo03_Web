<script></script>

<template>
  <div class="container">
    <div class="connexion">
      <p id="titre">CRÉER UN COMPTE</p>
      <form
        action="php/Forms/registrationUser.php"
        id="connexionForm"
        method="POST"
      >
        <input id="prenom" name="prenom" type="text" placeholder="PRENOM" />
        <br />
        <input
          id="nomDeFamille"
          name="nomDeFamille"
          type="text"
          placeholder="NOM DE FAMILLE"
        />
        <br />
        <input
          id="email"
          name="email"
          type="text"
          placeholder="ADRESSE COURRIEL"
        />
        <br />
        <input id="mdp" name="mdp" type="password" placeholder="MOT DE PASSE" />
        <br />
        <input id="buttonInscription" type="submit" value="S'INSCRIRE" />
      </form>
    </div>
  </div>
</template>

<style scoped>
.connexion {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-width: 100px;
  border-image: url(/src/img/web/pale_glowing_pink_blue_border.png) 25% stretch;
  border-style: solid;
  height: 500px;
  width: 700px;
}

#titre {
  font-size: 2rem;
  color: #ff71ce;
  margin: 2px;
  padding: 15px 56px;
  font-style: italic;
  font-weight: 600;
  text-transform: uppercase;
  text-shadow: 3px 4px 0 #b967ff;
}

#mdp,
#email,
#prenom,
#nomDeFamille {
  height: 30px;
  width: 300px;
  padding-left: 10px;
  margin: 13px;
}

input:hover {
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

input {
  background-color: white;
}

.error {
  margin: 0px;
  padding: 0px;
  color: red;
  font-size: 0.8rem;
  margin-left: 15px;
}

#buttonInscription,
#buttonConnexion {
  height: 34px;
  width: 100px;
  margin-left: 10px;
  margin-top: 15px;
  background-color: #b967ff;
  border-radius: 8px;
  border: 2px solid black;
  box-shadow: 6px 6px black;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  color: black;
}

#buttonInscription:hover,
#buttonConnexion:hover {
  box-shadow: 2px 2px black;
  cursor: url(/src/img/web/purple_unicorn_neon.png), auto;
}

.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 70vh;
}
</style>
