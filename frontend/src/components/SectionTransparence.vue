<template>
    <div class="section-container">
      <div class="content">
        <!-- Texte -->
        <div class="text-box">
          <h2>Transparence, équité et libre accès à la commande publique</h2>
          <p class="highlight">Encouragement de l’initiative des jeunes</p>
          <p>
            Vous souhaitez décrocher un marché dans le secteur de la poste et des télécommunications ?
            Grâce à ce portail, vous pouvez consulter tous les marchés publics portant sur la réalisation
            des projets des entreprises et organismes du secteur.
          </p>
          <p>
            Ce portail assure plus d'égalité, de transparence et de compétitivité, permettant aux jeunes
            entrepreneurs, chefs de micro-entreprises et startups d'avoir accès à la demande du secteur public.
          </p>
          <button class="cta-button">Accéder au portail</button>
        </div>
  
        <!-- Image -->
        <div class="image-box">
          <img src="@/assets/intro-img-02.jpg" alt="Illustration">
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "SectionTransparence",
  };
  </script>
  
  <style scoped>
  .section-container {
    
    padding: 50px 20px;
    display: flex;
    justify-content: center;
  }
  
  .content {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 1200px;
    width: 100%;
    gap: 20px;
  }
  
  .text-box {
    background-color: #003366;
    color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    max-width: 600px;
  }
  
  .text-box h2 {
    font-size: 24px;
    margin-bottom: 10px;
    color:white;
  }
  
  .text-box .highlight {
    font-weight: bold;
    color: #ffcc00;
    margin-bottom: 15px;
    
  }
  
  .text-box p {
    font-size: 14px;
    margin-bottom: 10px;
    line-height: 1.6;
  }
  
  .cta-button {
    background-color: #ff6600;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
  }
  
  .cta-button:hover {
    background-color: #e65c00;
  }
  
  .image-box img {
    width: 100%;
    height: 380px;
    max-width: 600px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
  }
  
  @media (min-width: 768px) {
    .content {
      flex-direction: row;
      justify-content: space-between;
    }
  
    .text-box {
      max-width: 50%;
    }
  
    .image-box {
      max-width: 45%;
    }
  }
  </style>
  