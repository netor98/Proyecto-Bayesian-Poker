function validarUsuario() {
    const usuarioInput = document.querySelector('input[name="usuario"]');
    const usuario = usuarioInput.value.trim();

    // Expresión regular que valida el usuario (mínimo 4 caracteres, máximo 16, solo letras y números)
    const usuarioRegex = /^[a-zA-Z0-9]{4,16}$/;

    if (!usuarioRegex.test(usuario)) {
        usuarioInput.setCustomValidity(
            "El usuario debe contener entre 4 y 16 caracteres, y solo puede contener letras y números"
        );
        usuarioError.textContent =
            "El usuario debe contener entre 4 y 16 caracteres, y solo puede contener letras y números";
    } else {
        usuarioInput.setCustomValidity("");
    }
}

function validarContrasena() {
    const contrasenaInput = document.querySelector('input[name="contrasena"]');
    const contrasena = contrasenaInput.value;

    // Expresión regular que valida la contraseña (mínimo 8 caracteres, al menos una letra mayúscula, una minúscula y un número)
    const contrasenaRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

    if (!contrasenaRegex.test(contrasena)) {
        contrasenaInput.setCustomValidity(
            "La contraseña debe contener al menos 8 caracteres, una letra mayúscula, una minúscula y un número"
        );
        contrasenaError.textContent =
            "La contraseña debe contener al menos 8 caracteres, una letra mayúscula, una minúscula y un número";
    } else {
        contrasenaInput.setCustomValidity("");
    }
}

function validarConfirmarContrasena() {
    const contrasenaInput = document.querySelector('input[name="contrasena"]');
    const confirmarContrasenaInput = document.querySelector(
        'input[name="confirmarContrasena"]'
    );
    const confirmarContrasena = confirmarContrasenaInput.value;

    if (confirmarContrasena !== contrasenaInput.value) {
        confirmarContrasenaInput.setCustomValidity(
            "La contraseña y la confirmación de contraseña no coinciden"
        );
        confirmarContrasenaError.textContent =
            "La contraseña y la confirmación de contraseña no coinciden";
    } else {
        confirmarContrasenaInput.setCustomValidity("");
    }
}

function validarEmail() {
    const emailInput = document.querySelector('input[name="email"]');
    const email = emailInput.value.trim();

    // Expresión regular que valida el correo electrónico
    const emailRegex = /^\S+@\S+\.\S+$/;

    if (!emailRegex.test(email)) {
        emailInput.setCustomValidity(
            "Debe ingresar un correo electrónico válido"
        );
        emailError.textContent = "Debe ingresar un correo electrónico válido";
    } else {
        emailInput.setCustomValidity("");
    }
}

const form = document.querySelector("form");
const btnSiguiente2 = document.querySelector("#btnSiguiente2");

btnSiguiente2.addEventListener("click", function (event) {
    validarUsuario();
    validarContrasena();
    validarConfirmarContrasena();
    validarEmail();

    if (!form.checkValidity()) {
        event.preventDefault();
    }
});
