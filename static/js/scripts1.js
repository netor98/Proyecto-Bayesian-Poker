// Obtén todos los elementos necesarios
const combobox = document.querySelector('.custom-combobox');
const inputField = combobox.querySelector('.input-field');
const optionsList = combobox.querySelector('.options-list');
const options = combobox.querySelectorAll('.options-list li');

// Agrega un evento de clic al campo de entrada de texto
inputField.addEventListener('click', function() {
  combobox.classList.toggle('open');
});

// Agrega un evento de clic a cada opción de la lista
options.forEach(function(option) {
  option.addEventListener('click', function() {
    inputField.value = option.textContent;
    combobox.classList.remove('open');
  });
});

str = "";
function validarNombres() {
  const nombresInput = document.querySelector('input[name="nombre"]');
  const nombres = nombresInput.value.trim();


  // Expresión regular que valida uno o varios nombres
  const nombresRegex = /^[a-zA-Z]+(?: [a-zA-Z]*)*$/;

  if (!nombresRegex.test(nombres)) {
    nombresInput.setCustomValidity('Debe ingresar al menos un nombre');
    nombresError.textContent = 'Debe ingresar al menos un nombre';
  } else {
    nombresInput.setCustomValidity('');
  }
}

function validarApellidos() {
  const apellidosInput = document.querySelector('input[name="apellido"]');
  const apellidos = apellidosInput.value.trim();

  // Expresión regular que valida dos apellidos separados por un espacio
  const apellidosRegex = /^[a-zA-Z]+ [a-zA-Z]+$/;

  if (!apellidosRegex.test(apellidos)) {
    apellidosInput.setCustomValidity('Debe ingresar sus dos apellidos separados por un espacio');
    apellidosError.textContent = 'Debe ingresar sus dos apellidos separados por un espacio';
  } else {
    apellidosInput.setCustomValidity('');
  }
}

function validarEdad() {
  const edadInput = document.querySelector('input[name="edad"]');
  const edad = edadInput.value.trim();

  // Expresión regular que valida cualquier número positivo
  const edadRegex = /^[1-9][0-9]*$/;

  if (!edadRegex.test(edad)) {
    edadInput.setCustomValidity('Debe ingresar un número positivo');
    edadError.textContent = 'Debe ingresar un número positivo';
  } else {
    edadInput.setCustomValidity('');
  }
}




const form = document.querySelector('form');
const btnSiguiente = document.querySelector('#btnSiguiente');

btnSiguiente.addEventListener('click', function(event) {
  
  validarNombres();
  validarApellidos();
  validarEdad();

  if (!form.checkValidity()) {
    event.preventDefault();
  }
});

