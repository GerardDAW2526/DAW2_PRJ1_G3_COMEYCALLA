// alert("hola");


var libre = document.querySelectorAll(".libre");
var ocupada = document.querySelectorAll(".ocupada");

libre.forEach(element => {
  element.onclick = libreVal;
  // console.log(element);
});

ocupada.forEach(element => {
  element.onclick = ocupadaVal;
  // console.log(element);
});

// element.classList.add("ocupado");

// console.log(getComputedStyle(document.querySelector('.swal2-icon.swal2-success')).display);
// console.log(mesa);


function libreVal(element){

  // console.log(element);

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger"
    },
    buttonsStyling: true
  });

  swalWithBootstrapButtons.fire({
    title: "¿Quieres liberar la mesa?",
    text: "No puedes volver atrás.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Liberar",
    cancelButtonText: "Cancelar",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      swalWithBootstrapButtons.fire({
        title: "Libre",
        text: "La mesa está libre.",
        icon: "success",
      });

      window.location.href="../proc/liberar_mesa.proc.php?id_mesa=" + element.target.id + "&id_sala=" + element.target.name;

    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire({
        title: "Cancelado",
        text: "Proceso cancelado.",
        icon: "error"
      });

    }
  });

}

function ocupadaVal(element){

  // console.log(element);

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger"
    },
    buttonsStyling: true
  });

  swalWithBootstrapButtons.fire({
    title: "¿Quieres ocupar la mesa?",
    text: "No puedes volver atrás.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ocupar",
    cancelButtonText: "Cancelar",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      swalWithBootstrapButtons.fire({
        title: "Libre",
        text: "La mesa está libre.",
        icon: "success",
      });

      window.location.href="../proc/ocupar_mesa.proc.php?id_mesa=" + element.target.id + "&&id_sala=" + element.target.name;
      // console.log(element.target.name);

    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire({
        title: "Cancelado",
        text: "Proceso cancelado.",
        icon: "error"
      });

    }
  });

}