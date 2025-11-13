// ========================
// validaciones.js
// ========================

// ========================
// Validaciones de formulario de registro
// ========================
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formRegistro");
    if (form) {
        form.addEventListener("submit", e => {
            let username = form.username.value.trim();
            let nombre = form.nombre_completo.value.trim();
            let pass = form.password.value;
            let conf = form.confirm_password.value;
            let errores = [];

            if (username.length < 3 || username.length > 50) {
                errores.push("El nombre de usuario debe tener entre 3 y 50 caracteres");
            }
            if (nombre.length < 3) {
                errores.push("El nombre completo es demasiado corto");
            }
            if (pass.length < 6) {
                errores.push("La contraseña debe tener al menos 6 caracteres");
            }
            if (pass !== conf) {
                errores.push("Las contraseñas no coinciden");
            }

            if (errores.length > 0) {
                e.preventDefault();
                alert(errores.join("\n"));
            }
        });
    }
});

// ========================
// Validaciones de login
// ========================
window.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById("username");
    var input2 = document.getElementById("password");

    if (input) input.onblur = validarInputNombre;
    if (input2) input2.onblur = validarInputContra;

    var formLogin = document.getElementById("form-login");
    if (formLogin) {
        formLogin.onsubmit = function(e) {
            validarInputNombre();
            validarInputContra();
            if (
                document.getElementById("errorUser").innerHTML !== "" ||
                document.getElementById("errorContra").innerHTML !== ""
            ) {
                e.preventDefault();
            }
        };
    }
});

function validarInputNombre(){
    var input = document.getElementById("username");
    var errorUser = document.getElementById("errorUser");
    var valor = input ? input.value : "";

    if(valor == "" || valor == null || valor.length == 0){
        errorUser.innerHTML = "No puedes dejar vacío el campo usuario.";
    } else if (valor.length > 50){
        errorUser.innerHTML = "El usuario debe ser menor o igual a 50 carácteres.";
    } else {
        errorUser.innerHTML = "";
    }
}

function validarInputContra(){
    var input2 = document.getElementById("password");
    var errorContra = document.getElementById("errorContra");
    var valor = input2 ? input2.value : "";

    if(valor == "" || valor == null || valor.length == 0){
        errorContra.innerHTML = "No puedes dejar vacío el campo contraseña.";
    } else {
        errorContra.innerHTML = "";
    }
}

// ========================
// Validaciones Historial
// ========================
document.addEventListener("DOMContentLoaded", () => {
    const formHistorial = document.querySelector(".form-filtros");
    if (!formHistorial) return;

    // Búsqueda al presionar Enter
    formHistorial.addEventListener("keypress", e => {
        if(e.key === "Enter"){
            e.preventDefault();
            formHistorial.querySelector("button[name='buscar']").click();
        }
    });

    formHistorial.addEventListener("submit", e => {
        const fechaDesde = formHistorial.fecha_desde.value;
        const fechaHasta = formHistorial.fecha_hasta.value;
        const horaDesde = formHistorial.hora_desde.value;
        const horaHasta = formHistorial.hora_hasta.value;
        const currentYear = new Date().getFullYear();

        let errores = [];

        // Validar formato y rango fechas
        if(fechaDesde){
            const fecha = new Date(fechaDesde);
            if(fecha.getFullYear() > 2100){
                errores.push("El año de la fecha inicio no puede ser mayor a 2100");
            }
        }

        if(fechaHasta){
            const fecha = new Date(fechaHasta);
            if(fecha.getFullYear() > 2100){
                errores.push("El año de la fecha final no puede ser mayor a 2100");
            }
        }

        // Si se indica hora, se valida formato HH:MM
        const horaRegex = /^([0-1]\d|2[0-3]):([0-5]\d)$/;
        if(horaDesde && !horaRegex.test(horaDesde)){
            errores.push("Hora inicio no tiene un formato válido HH:MM");
        }
        if(horaHasta && !horaRegex.test(horaHasta)){
            errores.push("Hora fin no tiene un formato válido HH:MM");
        }

        if(errores.length > 0){
            e.preventDefault();
            alert(errores.join("\n"));
        }
    });
});

// ========================
// Funciones auxiliares
// ========================
function validarNoVacio(valor) {
    return valor !== null && valor.trim().length > 0;
}

function mostrarError(idElemento, mensaje) {
    document.getElementById(idElemento).innerHTML = mensaje;
}

function limpiarError(idElemento) {
    document.getElementById(idElemento).innerHTML = "";
}
