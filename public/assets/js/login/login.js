import { validationRules } from "../utils/regex.js";

document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const documInput = document.getElementById('docum');
    const passInput = document.getElementById('pass');

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const docum = documInput.value.trim();
        const pass = passInput.value;


        // Valida la expresión regular desde regex.js
        if (!validationRules.documento.regex.test(docum)) {
            alert(validationRules.documento.message);
            loginForm.reset();
            documInput.focus();
            return;
        }

        const url = loginForm.getAttribute('action');
        const formData = new FormData(loginForm);

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.url) {
                window.location.href = data.url;
            } else {
                alert(data.message || "La contraseña no está correcta");
            }
        })
        .catch(error => {
            console.error('Error en la petición', error);
        });
    });
});
