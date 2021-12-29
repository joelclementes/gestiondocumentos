class Inicio {
    constructor(reset = false) {
        this.urlProceso = "accInicio/php/proceso.php";
        if (reset) {
            new Menu().fnCreaMenu();

            new Inicio().fnCreaPanel();

            $("#linkCerrarSesion").on("click",function(){ localStorage.clear(); })
        }
    }
    
    fnCreaPanel(){
        $.ajax({
            data : {
                proceso : "USUARIO_MENU",
                idUsuario : localStorage.getItem("idUsuario")
            },
            url : this.urlProceso,
            type : "POST",
            success: function(opciones){
                new Inicio().fnConstruyePanel(opciones);
            }
        })
    }
    
    fnConstruyePanel(opciones){
        opciones = JSON.parse(opciones);
        let aPanel = "";
        for (let o of opciones){
            aPanel += `
                <div class="card itemPanelPrincipal" onclick='window.location.href = "index.php?p=`+o.paginaHref+`"'>
                    <div class="card-body">
                    <img class="imgPanelPrincipal" style="height:100px" src="./images/${o.paginaHref}.svg"><br>
                    <!--<i class="${o.iconoDelMenu} icono-opcion "> </i>-->
                        <span>`+o.tituloMenu+`</span>
                    </div> 
                </div>
            `;
        }
        document.querySelector("#panelOpciones").innerHTML= aPanel;
    }

}
window.onload = () => new Inicio(true);