/* Expresiones regulares */


const erNombreApellido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ]+ [A-Za-zÁÉÍÓÚáéíóúÑñ]+$/;
const erCedula = /^[0-9]{7,9}$/;
const erTelefonoVzla = /^(0414|0424|0416|0426|0412|0422)[0-9]{7}$/;


const form = document.getElementById('formulario');
const nombres = document.getElementById('nombres');
const apellidos = document.getElementById('apellidos');
const cedula = document.getElementById('cedula');
const telPrincipal = document.getElementById('tel_principal');

form.addEventListener('submit', (event) => {

  let valido = true;            
  const mensajes = [];          


  if (!erNombreApellido.test(nombres.value.trim())) { 
    valido = false;
    mensajes.push('Nombres: máximo 2 palabras, solo letras.');
  }


  if (!erNombreApellido.test(apellidos.value.trim())) {
    valido = false;
    mensajes.push('Apellidos: máximo 2 palabras, solo letras.');
  }


  if (!erCedula.test(cedula.value.trim())) {
    valido = false;
    mensajes.push('Cédula: solo números, sin puntos ni espacios y de 7 a 9 dígitos.');
  }


  if (!erTelefonoVzla.test(telPrincipal.value.trim())) {
    valido = false;
    mensajes.push('Teléfono principal: use un operador válido y 7 dígitos (ej: 04141234567).');
  }


  const checkboxesDias = document.querySelectorAll('input[name="dias[]"]:checked');

  if (checkboxesDias.length < minDiasLaborales || checkboxesDias.length > maxDiasLaborales) {
    valido = false;
    mensajes.push(`Selecciona de ${minDiasLaborales} a ${maxDiasLaborales} días laborales.`);
    
  }


  if (!valido) {
    event.preventDefault();           
    alert(mensajes.join('\n'));       
  }
});