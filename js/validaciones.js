// alert("Hola");

var input = document.getElementById("nombre");
var input2 = document.getElementById("contra");

input.onblur = validarInputNombre;
input2.onblur = validarInputContra;



// --> Validación de inputs

function validarInputNombre(){

    var valor = input.value;
    var errorUser = document.getElementById("errorUser");

    // --> Usuario
    if(valor == "" || valor == null || valor.length == 0){

        errorUser.innerHTML = "No puedes dejar vacío el campo usuario.";
        return;

    } else if (valor.length > 50){

        errorUser.innerHTML = "El usuario debe ser menor o igual a 50 carácteres.";
        return;

    } else {

        errorUser.innerHTML = "";
        return;

    }


}


// --> Contraseña
function validarInputContra(){

    var valor = input2.value;
    var errorUser = document.getElementById("errorContra");

    // --> Contraseña
    if(valor == "" || valor == null || valor.length == 0){

        errorUser.innerHTML = "No puedes dejar vacío el campo contraseña.";
        return;

    } else {

        errorUser.innerHTML = "";
        return;

    }


}


