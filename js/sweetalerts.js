document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll(".mesa-form");
    forms.forEach(form => {
        form.addEventListener("submit", e => {
            e.preventDefault();
            const idMesa = form.dataset.idMesa;

            fetch(`registrar_ocupacion.php?id_mesa=${idMesa}&check=1`)
            .then(res => res.json())
            .then(data => {
                const ocupada = data.estado === "ocupada";
                Swal.fire({
                    title: `Mesa ${idMesa}`,
                    text: ocupada ? "¿Liberar esta mesa?" : "¿Ocupar esta mesa?",
                    icon: ocupada ? "warning" : "info",
                    showCancelButton: true,
                    confirmButtonText: ocupada ? "Liberar" : "Ocupar"
                }).then(result => {
                    if(result.isConfirmed){
                        window.location.href = ocupada
                            ? `liberar_mesa.php?id_mesa=${idMesa}&id_sala=${idSala}`
                            : `ocupar_mesa.php?id_mesa=${idMesa}&id_sala=${idSala}`;
                    }
                });
            });
        });
    });
});
