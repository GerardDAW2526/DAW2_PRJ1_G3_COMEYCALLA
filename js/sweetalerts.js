// alert("hola");


var mesa = document.querySelectorAll(".btn-mesa");

mesa.forEach(element => {
  element.onclick = mesaVal;
});

// element.classList.add("ocupado");

// console.log(getComputedStyle(document.querySelector('.swal2-icon.swal2-success')).display);
// console.log(mesa);


function mesaVal(element){

  // console.log(element);

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger"
    },
    buttonsStyling: true
  });

  swalWithBootstrapButtons.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Desocupar",
    cancelButtonText: "Ocupar",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      swalWithBootstrapButtons.fire({
        title: "Libre",
        text: "La mesa está libre.",
        icon: "success",
      });

    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire({
        title: "Ocupado",
        text: "La mesa está ocupada.",
        icon: "error"
      });

    }
  });

}
