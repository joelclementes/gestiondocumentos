class Menu {
    constructor(reset = false) {
        this.urlProceso = "Menu/php/proceso.php";
        if (reset) {
            new Menu().fnCreaMenu();

            $("#linkCerrarSesion").on("click",function(){ localStorage.clear(); })
        }
    }
    
    fnCreaMenu(){
        $.ajax({
            data : {
                metodo : "USUARIO_MENU",
                idUsuario : localStorage.getItem("idUsuario")
            },
            url : this.urlProceso,
            type : "POST",
            success: function(opciones){
                new Menu().fnConstruyeMenu(opciones);
            }
        })
    }
    
    fnConstruyeMenu(opciones){
        opciones = JSON.parse(opciones);
        let aMenu = "";
        for (let o of opciones){
            aMenu += `
            <a class="dropdown-item" id="`+o.idDiv+`" href="index.php?p=`+o.paginaHref+`">`+o.tituloMenu+`</a>
            `;
        }
        // aMenu+=`
        //     <hr class="dropdown-divider">
        //     <a class="dropdown-item" id="sep" href="index.php?p=CambiaContrasena">Cambiar contraseña</a>
        //     <a class="dropdown-item" href="#" onClick="document.location.href='./s.php'" style="text-decoration:none;">Cerrar Sesión de ${localStorage.getItem("nombreUsuario")}</a>
        //     `;

        // $("#MiMenu").html(aMenu);
        // $("#linkCerrarSesion").html(" Cerrar sesión de " + localStorage.getItem("nombreUsuario"));
        $("#lblNombreUsuario").html(" "+localStorage.getItem("nombreUsuario"));
    }
}
window.onload = () => new Menu(true);