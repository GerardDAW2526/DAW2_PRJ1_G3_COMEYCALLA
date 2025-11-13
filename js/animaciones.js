// alert("Hola");


var inputLogin = document.getElementById("btn-iniciar-sesion");
var buttonMenu = document.getElementById("btn-desplegable");
var desplegable = document.getElementById("desplegable");

if(inputLogin){

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

} else if (buttonMenu){

    buttonMenu.onclick = panelBotones;

    // console.log("Este es el botón: " + buttonMenu);

    // --> Menú botones cuando es responsive

    function panelBotones(){

        var menu = document.getElementById("footer-2");

        // console.log(menu.classList);

        if(menu.classList.contains("active")){

            // menu.style.transform = "translateY(400px)";
            menu.classList.remove("active");

        } else {

            // menu.style.transform = "translateY(0)";
            menu.classList.add("active");
            // desplegable.style.display = "block";

        }

    }

}






