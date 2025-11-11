// ========================
// Validaciones de formulario de registro
// ========================
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formRegistro");
    if (!form) return;

    form.addEventListener("submit", e => {
        let username = form.username.value.trim();
        let nombre = form.nombre_completo.value.trim();
        let pass = form.password.value;
        let conf = form.confirm_password.value;
        let errores = [];

        // ========================
        // Validaciones
        // ========================
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

        // ========================
        // Mostrar errores si los hay
        // ========================
        if (errores.length > 0) {
            e.preventDefault();
            alert(errores.join("\n"));
        }
    });
});
