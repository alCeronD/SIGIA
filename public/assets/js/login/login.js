document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const documInput = document.getElementById('docum');
    const passInput = document.getElementById('pass');

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const docum = documInput.value.trim();
        const pass = passInput.value;

        // validamos que la cantidad de numeros sea la correcta
        if (!/^\d{5,15}$/.test(docum)) {
            alert("El documento debe contener solo números (5 a 15 caracteres).");
            documInput.focus();
            return;
        }

        // Validar contraseña -mínimo 6 caracteres. Una letra y un numero
        // if (!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/.test(pass)) {
        //     alert("La contraseña debe tener al menos 6 caracteres, incluyendo una letra y un número.");
        //     passInput.focus();
        //     return;
        // }

        
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
            if (data.success) {
                window.location.href = "http://localhost/proyecto_sigia/app/dashboard.php";
            } else {
                alert("La contraseña no está correcta");
            }
        })
        .catch(error => {
            console.error('Error en la petición', error);
        });
    });
});
