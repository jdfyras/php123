// Form validation utilities
const ValidationUtils = {
    // Email validation
    isValidEmail: (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    // Password validation
    isValidPassword: (password) => {
        // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return passwordRegex.test(password);
    },

    // Name validation
    isValidName: (name) => {
        // Only letters, spaces, and hyphens allowed
        const nameRegex = /^[a-zA-Z\s-]{2,50}$/;
        return nameRegex.test(name);
    }
};

// Registration form validation
document.addEventListener('DOMContentLoaded', function() {
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Get form fields
            const firstname = document.getElementById('firstname').value.trim();
            const lastname = document.getElementById('lastname').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Clear previous errors
            clearErrors();

            // Validate fields
            let isValid = true;
            const errors = [];

            // Firstname validation
            if (!ValidationUtils.isValidName(firstname)) {
                errors.push({
                    field: 'firstname',
                    message: 'Le prénom doit contenir entre 2 et 50 caractères (lettres, espaces et tirets uniquement)'
                });
                isValid = false;
            }

            // Lastname validation
            if (!ValidationUtils.isValidName(lastname)) {
                errors.push({
                    field: 'lastname',
                    message: 'Le nom doit contenir entre 2 et 50 caractères (lettres, espaces et tirets uniquement)'
                });
                isValid = false;
            }

            // Email validation
            if (!ValidationUtils.isValidEmail(email)) {
                errors.push({
                    field: 'email',
                    message: 'Veuillez entrer une adresse email valide'
                });
                isValid = false;
            }

            // Password validation
            if (!ValidationUtils.isValidPassword(password)) {
                errors.push({
                    field: 'password',
                    message: 'Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial'
                });
                isValid = false;
            }

            // Confirm password validation
            if (password !== confirmPassword) {
                errors.push({
                    field: 'confirm_password',
                    message: 'Les mots de passe ne correspondent pas'
                });
                isValid = false;
            }

            // Display errors or submit form
            if (!isValid) {
                displayErrors(errors);
            } else {
                registrationForm.submit();
            }
        });

        // Add real-time validation
        const fields = ['firstname', 'lastname', 'email', 'password', 'confirm_password'];
        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.addEventListener('input', function() {
                    validateField(field, this.value);
                });
            }
        });
    }

    // Login form validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Get form fields
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            // Clear previous errors
            clearErrors();

            // Validate fields
            let isValid = true;
            const errors = [];

            // Email validation
            if (!ValidationUtils.isValidEmail(email)) {
                errors.push({
                    field: 'email',
                    message: 'Veuillez entrer une adresse email valide'
                });
                isValid = false;
            }

            // Password validation
            if (!password) {
                errors.push({
                    field: 'password',
                    message: 'Le mot de passe est requis'
                });
                isValid = false;
            }

            // Display errors or submit form
            if (!isValid) {
                displayErrors(errors);
            } else {
                loginForm.submit();
            }
        });

        // Add real-time validation
        const fields = ['email', 'password'];
        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.addEventListener('input', function() {
                    validateField(field, this.value);
                });
            }
        });
    }
});

// Helper functions
function validateField(field, value) {
    const errorElement = document.getElementById(`${field}_error`);
    if (!errorElement) return;

    let isValid = true;
    let message = '';

    switch (field) {
        case 'firstname':
        case 'lastname':
            isValid = ValidationUtils.isValidName(value);
            message = 'Doit contenir entre 2 et 50 caractères (lettres, espaces et tirets uniquement)';
            break;
        case 'email':
            isValid = ValidationUtils.isValidEmail(value);
            message = 'Veuillez entrer une adresse email valide';
            break;
        case 'password':
            if (field === 'password' && document.getElementById('registrationForm')) {
                isValid = ValidationUtils.isValidPassword(value);
                message = 'Doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial';
            } else {
                isValid = value.length > 0;
                message = 'Le mot de passe est requis';
            }
            break;
        case 'confirm_password':
            const password = document.getElementById('password').value;
            isValid = value === password;
            message = 'Les mots de passe ne correspondent pas';
            break;
    }

    if (!isValid && value) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    } else {
        errorElement.style.display = 'none';
    }
}

function displayErrors(errors) {
    errors.forEach(error => {
        const errorElement = document.getElementById(`${error.field}_error`);
        if (errorElement) {
            errorElement.textContent = error.message;
            errorElement.style.display = 'block';
        }
    });
}

function clearErrors() {
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(element => {
        element.style.display = 'none';
    });
} 