// console.log('Archivo de validación cargado');

const erSoloLetras   = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
const erCedula = /^[0-9]{6,9}$/;
const erCorreo       = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const erFechaDdMm    = /^(0?[1-9]|[12][0-9]|3[01])-(0?[1-9]|1[0-2])$/;
const erTextoGeneral = /^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ\s.,;:()\-]+$/;


function mostrarErrores(idContenedor, mensajes) {
    const contenedor = document.getElementById(idContenedor);
    if (!contenedor) return;

    contenedor.innerHTML = mensajes
        .map(msg => `<p class="error-js">${msg}</p>`)
        .join('');
    
    if (mensajes.length > 0) {
        contenedor.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
        
}


function validarLogin() {
    const correo   = document.getElementById('correo');
    const password = document.getElementById('password');
    const mensajes = [];

    if (correo.value.trim() === '') {
        mensajes.push('El correo es obligatorio');
    } else if (!erCorreo.test(correo.value.trim())) {
        mensajes.push('El correo no tiene un formato válido');
    }

    if (password.value.trim() === '') {
        mensajes.push('La contraseña es obligatoria');
    }

    mostrarErrores('errores-login', mensajes);
    return mensajes.length === 0;
}


function validarEstudiante() {
    const nombre                = document.getElementById('nombre');
    const apellido              = document.getElementById('apellido');
    const cedula                = document.getElementById('cedula');
    const categoriaId           = document.getElementById('categoria_id');
    const fechaNacimiento       = document.getElementById('fecha_nacimiento');
    const lugarNacimiento       = document.getElementById('lugar_nacimiento');
    const nombreRepresentante   = document.getElementById('nombre_representante');
    const apellidoRepresentante = document.getElementById('apellido_representante');
    const cedulaRepresentante = document.getElementById('cedula_representante');
    const profesion             = document.getElementById('profesion');
    const domicilio             = document.getElementById('domicilio');
    const mensajes              = [];

    if (nombre.value.trim() === '') {
        mensajes.push('El nombre es obligatorio');
    } else if (!erSoloLetras.test(nombre.value.trim())) {
        mensajes.push('El nombre solo puede contener letras');
    }

    if (apellido.value.trim() === '') {
        mensajes.push('El apellido es obligatorio');
    } else if (!erSoloLetras.test(apellido.value.trim())) {
        mensajes.push('El apellido solo puede contener letras');
    }

    if (cedula.value.trim() === '') {
        mensajes.push('La cédula es obligatoria');
    } else if (!erCedula.test(cedula.value.trim())) {
        mensajes.push('La cédula La cédula debe contener entre 6 y 9 dígitos');
    }

    if (fechaNacimiento.value.trim() === '') {
        mensajes.push('La fecha de nacimiento es obligatoria');
    }

    if (lugarNacimiento.value.trim() === '') {
        mensajes.push('El lugar de nacimiento es obligatorio');
    }

    if (categoriaId.value === '') {
        mensajes.push('Debe seleccionar una categoría');
    }

    if (nombreRepresentante.value.trim() === '') {
        mensajes.push('El nombre del representante es obligatorio');
    } else if (!erSoloLetras.test(nombreRepresentante.value.trim())) {
        mensajes.push('El nombre del representante solo puede contener letras');
    }

    if (apellidoRepresentante.value.trim() === '') {
        mensajes.push('El apellido del representante es obligatorio');
    } else if (!erSoloLetras.test(apellidoRepresentante.value.trim())) {
        mensajes.push('El apellido del representante solo puede contener letras');
    }

    if (cedulaRepresentante.value.trim() === '') {
        mensajes.push('La cédula del representante es obligatoria');
    } else if (!erCedula.test(cedulaRepresentante.value.trim())) {
        mensajes.push('La cédula del representante debe contener entre 6 y 9 dígitos');
    }

    if (profesion.value.trim() === '') {
        mensajes.push('La profesión del representante es obligatoria');
    }

    if (domicilio.value.trim() === '') {
        mensajes.push('El domicilio es obligatorio');
    }

    mostrarErrores('errores-estudiante', mensajes);
    return mensajes.length === 0;
}


function validarEvento() {
    const nombre   = document.getElementById('nombre');
    const fecha    = document.getElementById('fecha');
    const lugar    = document.getElementById('lugar');
    const mensajes = [];

    if (nombre.value.trim() === '') {
        mensajes.push('El nombre del evento es obligatorio');
    } else if (!erTextoGeneral.test(nombre.value.trim())) {
        mensajes.push('El nombre del evento contiene caracteres no válidos');
    }

    if (fecha.value.trim() === '') {
        mensajes.push('La fecha es obligatoria');
    }

    if (lugar.value.trim() === '') {
        mensajes.push('El lugar es obligatorio');
    } else if (!erTextoGeneral.test(lugar.value.trim())) {
        mensajes.push('El lugar contiene caracteres no válidos');
    }

    mostrarErrores('errores-evento', mensajes);
    return mensajes.length === 0;
}


function validarConstancia() {
    const nombreAtleta        = document.getElementById('nombre_atleta');
    const apellidoAtleta      = document.getElementById('apellido_atleta');
    const cedulaAtleta        = document.getElementById('cedula_atleta');
    const nombreRepresentante = document.getElementById('nombre_representante');
    const cedulaRepresentante = document.getElementById('cedula_representante');
    const nombreDirector      = document.getElementById('nombre_director');
    const cargoDirector       = document.getElementById('cargo_director');
    const fechaInicioEntreno  = document.getElementById('fecha_inicio_entreno');
    const fechaFinEntreno     = document.getElementById('fecha_fin_entreno');
    const fechaTorneoInicio   = document.getElementById('fecha_torneo_inicio');
    const fechaTorneoFin      = document.getElementById('fecha_torneo_fin');
    const institucionDestino  = document.getElementById('institucion_destino');
    const anioEscolar         = document.getElementById('anio_escolar');
    const seccion             = document.getElementById('seccion');
    const estadoSeleccion     = document.getElementById('estado_seleccion');
    const nombreTorneo        = document.getElementById('nombre_torneo');
    const estadoTorneo        = document.getElementById('estado_torneo');
    const diaEmision          = document.getElementById('dia_emision');
    const mesEmision          = document.getElementById('mes_emision');
    const mensajes            = [];

    if (institucionDestino.value.trim() === '') {
        mensajes.push('La institución destino es obligatoria');
    }

    if (nombreAtleta.value.trim() === '') {
        mensajes.push('El nombre del atleta es obligatorio');
    } else if (!erSoloLetras.test(nombreAtleta.value.trim())) {
        mensajes.push('El nombre del atleta solo puede contener letras');
    }

    if (apellidoAtleta.value.trim() === '') {
        mensajes.push('El apellido del atleta es obligatorio');
    } else if (!erSoloLetras.test(apellidoAtleta.value.trim())) {
        mensajes.push('El apellido del atleta solo puede contener letras');
    }

    if (cedulaAtleta.value.trim() === '') {
        mensajes.push('La cédula del atleta es obligatoria');
    } else if (!erCedula.test(cedulaAtleta.value.trim())) {
        mensajes.push('La cédula del atleta La cédula debe contener entre 6 y 9 dígitos');
    }

    if (anioEscolar.value.trim() === '') {
        mensajes.push('El año escolar es obligatorio');
    }

    if (seccion.value.trim() === '') {
        mensajes.push('La sección es obligatoria');
    }

    if (estadoSeleccion.value.trim() === '') {
        mensajes.push('El estado de selección es obligatorio');
    }

    if (nombreTorneo.value.trim() === '') {
        mensajes.push('El nombre del torneo es obligatorio');
    }

    if (estadoTorneo.value.trim() === '') {
        mensajes.push('El estado del torneo es obligatorio');
    }

    if (fechaInicioEntreno.value.trim() === '') {
        mensajes.push('La fecha de inicio de entrenamientos es obligatoria');
    } else if (!erFechaDdMm.test(fechaInicioEntreno.value.trim())) {
        mensajes.push('Inicio de entrenamientos debe tener formato dd-mm, ejemplo: 27-04');
    }

    if (fechaFinEntreno.value.trim() === '') {
        mensajes.push('La fecha de fin de entrenamientos es obligatoria');
    } else if (!erFechaDdMm.test(fechaFinEntreno.value.trim())) {
        mensajes.push('Fin de entrenamientos debe tener formato dd-mm, ejemplo: 07-05');
    }

    if (fechaTorneoInicio.value.trim() === '') {
        mensajes.push('La fecha de inicio del torneo es obligatoria');
    } else if (!erFechaDdMm.test(fechaTorneoInicio.value.trim())) {
        mensajes.push('Fecha inicio del torneo debe tener formato dd-mm');
    }

    if (fechaTorneoFin.value.trim() === '') {
        mensajes.push('La fecha de fin del torneo es obligatoria');
    } else if (!erFechaDdMm.test(fechaTorneoFin.value.trim())) {
        mensajes.push('Fecha fin del torneo debe tener formato dd-mm');
    }

    if (nombreRepresentante.value.trim() === '') {
        mensajes.push('El nombre del representante es obligatorio');
    }

    if (cedulaRepresentante.value.trim() === '') {
        mensajes.push('La cédula del representante es obligatoria');
    } else if (!erCedula.test(cedulaRepresentante.value.trim())) {
        mensajes.push('La cédula del representante La cédula debe contener entre 6 y 9 dígitos');
    }

    if (diaEmision.value.trim() === '') {
        mensajes.push('El día de emisión es obligatorio');
    }

    if (mesEmision.value.trim() === '') {
        mensajes.push('El mes de emisión es obligatorio');
    }

    if (nombreDirector.value.trim() === '') {
        mensajes.push('El nombre del director es obligatorio');
    }

    if (cargoDirector.value.trim() === '') {
        mensajes.push('El cargo del director es obligatorio');
    }

    mostrarErrores('errores-constancia', mensajes);
    return mensajes.length === 0;
}