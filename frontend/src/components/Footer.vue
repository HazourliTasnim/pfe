<template>
  <footer class="footer">
    <div class="footer-container">
      <span class="footer-item suggestion" @click="openModal">Suggestion</span>
      <span class="footer-separator">|</span>
      <a href="/cadre_legal.pdf" target="_blank" rel="noopener">Cadre Legal</a>
      <span class="footer-separator">|</span>
      <span class="footer-item year">2025</span>
    </div>

    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal">
        <span class="close" @click="closeModal">&times;</span>
        <h2>Veuillez saisir vos suggestions</h2>
        <form @submit.prevent="submitSuggestion">
          <input type="email" v-model="email" placeholder="Votre email" required />
          <textarea v-model="suggestion" placeholder="Votre suggestion" required></textarea>
          <button type="submit">Envoyer</button>
        </form>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "FooterComponent",
  data() {
    return {
      showModal: false,
      email: "",
      suggestion: ""
    };
  },
  methods: {
    openModal() {
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
    submitSuggestion() {
      if (this.email.trim() === "" || this.suggestion.trim() === "") {
        alert("Veuillez remplir tous les champs.");
        return;
      }
      console.log("Suggestion envoyée :", { email: this.email, suggestion: this.suggestion });
      alert("Merci pour votre suggestion !");
      this.email = "";
      this.suggestion = "";
      this.closeModal();
    }
  }
};
</script>

<style scoped>
.footer {
  background: #000d30;
  color: #faf7ff;
  padding: 10px 0;
  text-align: center;
  font-family: Arial, sans-serif;
  font-size: 18px;
}

.footer-container {
  display: inline-block;
}

.footer-item {
  cursor: default;
  padding: 0 5px;
}

.footer-item.suggestion {
  cursor: pointer;
  text-decoration: underline;
}

.footer-separator {
  padding: 0 5px;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
h2{
  color:#333;

  font-size: 20px;
}
.modal {
  background: #fff;
  padding: 20px 30px;
  border-radius: 8px;
  width: 90%;
  max-width: 400px;
  position: relative;
  text-align: center;
}

.modal h2 {
  margin-top: 0;
  font-size: 18px;
}

.modal form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.modal input[type="email"],
.modal textarea {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
  box-sizing: border-box;
}

.modal button {
  padding: 10px;
  border: none;
  background: #007BFF;
  color: #fff;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
}

.modal button:hover {
  background: #0056b3;
}

.close {
  position: absolute;
  top: 8px;
  right: 12px;
  font-size: 24px;
  cursor: pointer;
  color: #333;
}
</style>
