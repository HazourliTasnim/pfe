<template>
  <div class="form-wrapper">
    <form @submit.prevent="submitForm" class="form-container">
      <h2>Créer un Appel d'Offre</h2>

   
      <div class="form-group">
        <label>Entreprise émettrice :</label>
        <select v-model="form.entreprise_id" required>
          <option value="" disabled selected>Choisissez une entreprise</option>
          <option v-for="(entreprise, index) in entreprises" :key="index" :value="entreprise.id">
            {{ entreprise.name }}
          </option>
        </select>
      </div>

      <div class="row">
        <div class="form-group">
          <label>Langue :</label>
          <select v-model="form.langue" required>
            <option value="francais">Français</option>
            <option value="arabe">Arabe</option>
          </select>
        </div>
        <div class="form-group">
          <label>État de l'offre :</label>
          <select v-model="form.etat_offre" required>
            <option>En Cours</option>
            <option>Terminé</option>
          </select>
        </div>
        <div class="form-group">
          <label>Wilaya :</label>
          <select v-model="form.wilaya" required>
            <option value="" disabled selected>Choisissez une wilaya</option>
            <option v-for="(wilaya, index) in wilayas" :key="index" :value="wilaya">
              {{ wilaya }}
            </option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="form-group">
          <label>Numéro d'offre :</label>
          <input v-model="form.numero_offre" type="text" required placeholder="Ex: 2024-001" />
        </div>
      </div>

      <div class="form-group">
        <label>Titre de l'offre :</label>
        <input v-model="form.titre_offre" type="text" required placeholder="Ex: Fourniture de matériel télécom" />
      </div>

      <div class="row">
        <div class="form-group">
          <label>Type d'offre :</label>
          <input v-model="form.type_offre" type="text" required placeholder="Ex: Fourniture, Service, Travaux..." />
        </div>
        <div class="form-group">
          <label>Niveau :</label>
          <select v-model="form.niveau" required>
            <option selected>National</option>
            <option>International</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="form-group">
          <label>Date de publication :</label>
          <input v-model="form.date_publication" type="date" required />
        </div>
        <div class="form-group">
          <label>Date limite :</label>
          <input v-model="form.date_limite" type="date" required />
        </div>
      </div>

      <div class="form-group">
        <label>Date de prorogation :</label>
        <input v-model="form.date_prorogation" type="date" />
      </div>

      <div class="form-group">
        <label>Description :</label>
        <textarea v-model="form.description" required placeholder="Détaillez ici les informations de l'appel d'offre"></textarea>
      </div>

      <div class="form-group">
        <label>État :</label>
        <select v-model="form.etat" required>
          <option value="En rédaction">En rédaction</option>
          <option value="Annulé">Annulé</option>
          <option value="Publié">Publié</option>
        </select>
      </div>

      <div class="form-group">
        <label>Document PDF :</label>
        <input type="file" @change="fileSelected" accept=".pdf" required />
      </div>

      <button type="submit">Envoyer</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const form = ref({
  entreprise_id: "",
  langue: "francais",
  etat_offre: "En Cours",
  wilaya: "",
  numero_offre: "",
  titre_offre: "",
  type_offre: "",
  niveau: "National",
  date_publication: "",
  date_limite: "",
  date_prorogation: "",
  description: "",
  etat: "En rédaction",
  document_pdf: null,
  
});
const entreprises = ref([
  { id: '1', name: 'Ministère de la Poste et des Télécommunications' },
  { id: '2', name: 'ANF' },
  { id: '3', name: 'Algérie Poste' },
  { id: '4', name: 'INPTIC' },
  { id: '5', name: 'INTTIC' },
  { id: '6', name: 'Groupe GTA' },
  { id: '7', name: 'Algérie Télécom' },
  { id: '8', name: 'Mobilis' },
  { id: '9', name: 'ATS' },
  { id: '10', name: 'SATICOM' },
  { id: '11', name: 'COMINTAL' },
  { id: '12', name: 'ANPT' },
  { id: '13', name: 'EADN' },
  { id: '14', name: 'ECI TIC' },
  { id: '15', name: 'NetBeOpen Prime' },
  { id: '16', name: 'WhiteBay Limited' },
  { id: '17', name: 'EMS' }
]);
const wilayas = [
  "Adrar", "Chlef", "Laghouat", "Oum El Bouaghi", "Batna", "Béjaïa", "Biskra", "Béchar", "Blida",
  "Bouira", "Tamanrasset", "Tébessa", "Tlemcen", "Tiaret", "Tizi Ouzou", "Alger", "Djelfa",
  "Jijel", "Sétif", "Saïda", "Skikda", "Sidi Bel Abbès", "Annaba", "Guelma", "Constantine",
  "Médéa", "Mostaganem", "M'Sila", "Mascara", "Ouargla", "Oran", "El Bayadh", "Illizi",
  "Bordj Bou Arréridj", "Boumerdès", "El Tarf", "Tindouf", "Tissemsilt", "El Oued",
  "Khenchela", "Souk Ahras", "Tipaza", "Mila", "Aïn Defla", "Naâma", "Aïn Témouchent",
  "Ghardaïa", "Relizane"
];

const fileSelected = (event) => {
  form.value.document_pdf = event.target.files[0];
};

const submitForm = async () => {
  try {
    if (!form.value.document_pdf) {
      alert("Veuillez sélectionner un fichier PDF.");
      return;
    }

    const formData = new FormData();
    Object.keys(form.value).forEach((key) => {
      formData.append(key, form.value[key]);
    });

    const token = localStorage.getItem("token");
    if (!token) {
      alert("Utilisateur non authentifié.");
      return;
    }
    await axios
  .post("http://127.0.0.1:8000/api/appel-offre", formData, {
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "multipart/form-data",
    },
  })
  .then((response) => {
     alert("Appel d'offre créé avec succès !");
    console.log("Succès:", response.data);
  })
  .catch((error) => { 
    alert("Erreur lors de l'envoi.");
    if (error.response) {

      console.error("Erreurs de validation:", error.response.data.errors);
    } else {
      console.error("Erreur réseau:", error.message);
    }
  });


   
  } catch (error) {
   
  }
};
</script>

<style scoped>
.form-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  background: #f4f4f4;
  padding: 20px;
}

.form-container {
  width: 1100px;
  padding: 30px;
  margin-left: 10px;
  background: #FFFFFF;
  box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
  border-radius: 20px;
}

h2 {
  text-align: left;
  margin-bottom: 40px;
}

.form-group {
  margin-bottom: 20px;
}

.row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
}

label {
  display: flex;
  margin-bottom: 5px;
  color: #636363d0;
}

input,
select,
textarea {
  width: 80%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

textarea {
  height: 80px;
}

button {
  width: 20%;
  background: #200774;
  color: white;
  padding: 12px;
  border: none;
  border-radius: 5px;
  display: flex;
  justify-content: center;
  cursor: pointer;
  font-size: 16px;
  transition: 0.3s;
}

button:hover {
  background: #0056b3;
}
</style>
