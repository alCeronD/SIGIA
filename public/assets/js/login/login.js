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

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const docum = documInput.value.trim();
        const pass = passInput.value.trim();
        
        if (pass.length === 0 && docum.length === 0)  {
            initAlert("Por favor llene todos los campos", "info", toastOptions);
            return;
        }
        
        if (pass.length === 0 && docum.length > 0) {
            initAlert("La contraseña es obligatoria", "info", toastOptions);
            return;
        }

        if (docum.length === 0 && pass.length > 0) {
            initAlert("El No de documento es obligatorio", "info", toastOptions);
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
                initAlert("Usuario y contraseña incorrectos, Contactarse con el administrador.", "info", toastOptions);
            }
        })
        .catch(error => {
            console.error('Error en la petición', error);
        });
    });
});

