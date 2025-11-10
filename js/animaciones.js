// alert("Hola");


var inputLogin = document.getElementById("btn-iniciar-sesion");

inputLogin.onclick = iniciarSesion;



// --> Apartado iniciar sesión

function iniciarSesion(){

    // alert("dentro");

    var content = document.getElementById("content");
    var content2 = document.getElementById("left-2");

    content.classList.add("opacityMove");
    content2.classList.add("opacityMoveReverse");
    inputLogin.classList.add("opacityMoveReverse");

}