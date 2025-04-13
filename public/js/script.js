document.addEventListener("DOMContentLoaded", function () {
  // Fonction pour afficher/masquer les messages d'alerte
  const alerts = document.querySelectorAll(".alert");

  alerts.forEach((alert) => {
    // Ajouter un bouton de fermeture à chaque alerte
    const closeButton = document.createElement("span");
    closeButton.classList.add("close-alert");
    closeButton.innerHTML = "&times;";
    closeButton.style.float = "right";
    closeButton.style.cursor = "pointer";
    closeButton.style.fontWeight = "bold";

    closeButton.addEventListener("click", function () {
      alert.style.display = "none";
    });

    alert.insertBefore(closeButton, alert.firstChild);

    // Faire disparaître l'alerte après 5 secondes
    setTimeout(() => {
      alert.style.opacity = "0";
      alert.style.transition = "opacity 1s";

      // Supprimer l'alerte du DOM après la transition
      setTimeout(() => {
        if (alert.parentNode) {
          alert.parentNode.removeChild(alert);
        }
      }, 1000);
    }, 5000);
  });

  // Animation du menu au défilement
  let lastScrollTop = 0;
  const header = document.querySelector("header");

  if (header) {
    window.addEventListener("scroll", function () {
      const scrollTop =
        window.pageYOffset || document.documentElement.scrollTop;

      if (scrollTop > lastScrollTop && scrollTop > 100) {
        // Défilement vers le bas
        header.style.transform = "translateY(-100%)";
        header.style.transition = "transform 0.3s ease-in-out";
      } else {
        // Défilement vers le haut
        header.style.transform = "translateY(0)";
      }

      lastScrollTop = scrollTop;
    });
  }

  // Validation de formulaire côté client
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      let hasError = false;
      const requiredFields = form.querySelectorAll("[required]");

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          hasError = true;
          field.classList.add("error");

          // Créer un message d'erreur s'il n'existe pas déjà
          let errorMessage = field.nextElementSibling;
          if (
            !errorMessage ||
            !errorMessage.classList.contains("error-message")
          ) {
            errorMessage = document.createElement("div");
            errorMessage.classList.add("error-message");
            errorMessage.style.color = "red";
            errorMessage.style.fontSize = "0.8rem";
            errorMessage.style.marginTop = "5px";
            field.parentNode.insertBefore(errorMessage, field.nextSibling);
          }

          errorMessage.textContent = "Ce champ est requis";
        } else {
          field.classList.remove("error");

          // Supprimer le message d'erreur s'il existe
          const errorMessage = field.nextElementSibling;
          if (
            errorMessage &&
            errorMessage.classList.contains("error-message")
          ) {
            errorMessage.remove();
          }
        }
      });

      if (hasError) {
        event.preventDefault();
      }
    });
  });
});
