import { initAlert, toastOptions } from "../utils/cases.js";
import { validationRules } from "../utils/regex.js";

document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const documInput = document.getElementById('docum');
    const passInput = document.getElementById('pass');

    documInput.addEventListener('change', (e)=>{
        const docum = e.target.value.trim();
        e.stopPropagation();
        if (!validationRules.documento.regex.test(docum)) {
            initAlert(validationRules.documento.message,"info", toastOptions);
            loginForm.reset();
            documInput.focus();
            return;
        }
    });

    passInput.addEventListener('blur', (e) => {
        e.stopPropagation();
        const password = e.target.value.trim();

        if (password.length === 0) {
            initAlert("La contraseña no debe estar vacía", "info", toastOptions);
            passInput.focus();
            return;
        }
    });

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const docum = documInput.value.trim();
        const pass = passInput.value.trim();

        if (pass.length === 0) {
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
                initAlert("Usuario y contraseña incorrectos", "error", toastOptions);
            }
        })
        .catch(error => {
            console.error('Error en la petición', error);
        });
    });
});
