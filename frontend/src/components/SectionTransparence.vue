<template>
  <div class="section-container">
    <div class="content">
      <!-- Texte -->
      <div class="text-box">
        <h2>
          <i class="fas fa-balance-scale-right"></i>
          Transparence, équité et libre accès à la commande publique
          Encouragement de l’initiative des jeunes
        </h2>
        <p>
          Vous souhaitez décrocher un marché dans le secteur de la poste et des télécommunications ?
          Grâce à ce portail, vous pouvez consulter tous les marchés publics portant sur la réalisation
          des projets des entreprises et organismes du secteur.
        </p>
        <p>
          Ce portail assure plus d'égalité, de transparence et de compétitivité, permettant aux jeunes
          entrepreneurs, chefs de micro-entreprises et startups d'avoir accès à la demande du secteur public.
        </p>
        <button class="cta-button">
          <i class="fas fa-sign-in-alt"></i> Accéder au portail
        </button>
      </div>

      <!-- Image avec animation -->
      <div class="image-container" @mouseenter="startAnimation" @mouseleave="stopAnimation">
        <img
          v-if="showFirst"
          src="@/assets/intro-img-02.jpg"
          alt="Illustration"
          class="image"
          @click="redirectTo('/Register')"
        />
        <img
          v-else
          src="@/assets/intro-img-03.jpg"
          alt="Illustration"
          class="image"
          @click="redirectTo('/Login')"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SectionTransparence",
  data() {
    return {
      showFirst: true,
      interval: null
    };
  },
  methods: {
    startAnimation() {
      this.interval = setInterval(() => {
        this.showFirst = !this.showFirst;
      }, 3000);
    },
    stopAnimation() {
      clearInterval(this.interval);
    },
    redirectTo(route) {
      this.$router.push({ name: route }).catch(() => {});
    }
  }
};
</script>

<style scoped>
.section-container {
  padding: 60px 20px;
  display: flex;
  justify-content: center;
  background: linear-gradient(135deg, #ffffff, #ffffff);
}

.content {
  display: flex;
  align-items: center;
  max-width: 1200px;
  width: 100%;
  gap: 30px;
}

.text-box {
  background: #fff;
  color: #333;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  transition: transform 0.3s ease;
}

.text-box:hover {
  transform: translateY(-5px);
}

.text-box h2 {
  font-size: 24px;
  margin-bottom: 15px;
  color: #cc5500;
  display: flex;
  align-items: center;
  gap: 10px;
}

.text-box p {
  font-size: 15px;
  margin-bottom: 10px;
  line-height: 1.6;
}

.cta-button {
  background-color: #b04300;
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  margin-top: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: background 0.3s;
}

.cta-button:hover {
  background-color: #1a7722;
}

.image-container {
  width: 50%;
  display: flex;
  justify-content: center;
}

.image {
  width: 100%;
  max-width: 500px;
  height: auto;
  border-radius: 15px;
  box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
  transition: opacity 0.5s ease;
  cursor: pointer;
}
</style>
