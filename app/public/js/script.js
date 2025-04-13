/**
 * Script principal pour les fonctionnalités interactives
 */
document.addEventListener("DOMContentLoaded", function () {
  // Activation des fonctionnalités interactives
  initFormValidation();
  initConfirmActions();
});

/**
 * Initialise la validation des formulaires
 */
function initFormValidation() {
  // Validation générique pour tous les formulaires avec la classe 'validate-form'
  const forms = document.querySelectorAll(".validate-form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      let isValid = true;

      // Validation des champs requis
      const requiredFields = form.querySelectorAll("[required]");
      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          isValid = false;
          highlightField(field, "Ce champ est requis");
        } else {
          removeHighlight(field);
        }
      });

      // Validation des emails
      const emailFields = form.querySelectorAll('input[type="email"]');
      emailFields.forEach((field) => {
        if (field.value.trim() && !isValidEmail(field.value)) {
          isValid = false;
          highlightField(field, "Adresse email invalide");
        }
      });

      // Validation des mots de passe
      const passwordFields = form.querySelectorAll(
        'input[type="password"][data-minlength]'
      );
      passwordFields.forEach((field) => {
        const minLength = parseInt(field.getAttribute("data-minlength"));
        if (field.value.trim() && field.value.length < minLength) {
          isValid = false;
          highlightField(
            field,
            `Le mot de passe doit contenir au moins ${minLength} caractères`
          );
        }
      });

      // Validation des confirmations de mot de passe
      const confirmFields = form.querySelectorAll("input[data-confirm]");
      confirmFields.forEach((field) => {
        const targetId = field.getAttribute("data-confirm");
        const targetField = document.getElementById(targetId);
        if (targetField && field.value !== targetField.value) {
          isValid = false;
          highlightField(field, "Les valeurs ne correspondent pas");
        }
      });

      if (!isValid) {
        e.preventDefault();
      }
    });
  });
}

/**
 * Initialise les confirmations d'actions dangereuses
 */
function initConfirmActions() {
  const confirmLinks = document.querySelectorAll("[data-confirm-message]");
  confirmLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      const message = this.getAttribute("data-confirm-message");
      if (!confirm(message)) {
        e.preventDefault();
      }
    });
  });
}

/**
 * Met en évidence un champ de formulaire avec un message d'erreur
 */
function highlightField(field, message) {
  field.classList.add("is-invalid");

  // Supprime l'ancien message d'erreur s'il existe
  const existingError = field.parentNode.querySelector(".error-message");
  if (existingError) {
    existingError.remove();
  }

  // Crée un nouveau message d'erreur
  const errorElement = document.createElement("div");
  errorElement.className = "error-message";
  errorElement.textContent = message;
  field.parentNode.appendChild(errorElement);
}

/**
 * Supprime la mise en évidence d'erreur d'un champ
 */
function removeHighlight(field) {
  field.classList.remove("is-invalid");
  const errorElement = field.parentNode.querySelector(".error-message");
  if (errorElement) {
    errorElement.remove();
  }
}

/**
 * Vérifie si un email est valide
 */
function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

/**
 * Formatage de la monnaie
 */
function formatCurrency(amount, currency = "EUR") {
  return new Intl.NumberFormat("fr-FR", {
    style: "currency",
    currency: currency,
  }).format(amount);
}

/**
 * Formatage de date
 */
function formatDate(dateString, options = {}) {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat("fr-FR", options).format(date);
}
