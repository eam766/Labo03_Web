<script setup>
import Carrousel from "@/components/Carrousel.vue";
import PiecePhares from "@/components/PiecePhares.vue";
</script>

<template>
  <div class="container">
    <div class="introduction">
      <v-img :width="260" :height="260" src="/src/img/web/rich.png" />
      <div class="window">
        <p>
          Rich Ricasso, maître des cravates et chemises de luxe, est une figure
          majeure de la mode contemporaine. Reconnu pour ses créations en soie
          aux tons pastel comme le bleu ciel, le rose poudré et le violet
          lavande, il s'inspire du mouvement vaporwave et vaporfashion. Son
          style avant-gardiste, mêlant élégance et raffinement, a conquis
          l'élite de l'ultra-luxe.
        </p>
        <p>
          Il a débuté sa carrière en collaborant avec des maisons de couture
          renommées, où il a perfectionné son savoir-faire dans les textiles de
          luxe. Rapidement, il a lancé sa propre marque, proposant des
          collections unisexes en soie qui allient modernité artistique et
          élégance classique. Ses créations sont aujourd'hui prisées dans le
          monde de la mode de luxe.
        </p>
      </div>
    </div>
    <Carrousel></Carrousel>
    <div class="summerCollection">
      <p>Lookbook Été 2024</p>
      <PiecePhares></PiecePhares>
    </div>
  </div>
</template>

<style>
.introduction {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  margin: 60px;
}

.window {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-image: url(/src/img/web/windowframe.png);
  background-size: cover;
  background-repeat: no-repeat;
  width: 100%;
  height: 120%;
  padding: 14px;
}

.window > p {
  padding-top: 20px;
  margin: 20px;
  color: white;
}

.summerCollection {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: #5cf4c6;
  width: 100%;
  margin-top: 20px;
  margin-left: 30px;
}

.summerCollection > p {
  font-weight: 600;
  font-size: 1.5rem;
  align-self: flex-start;
  margin-left: 15px;
  text-decoration: underline;
}
</style>
