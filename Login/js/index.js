class Login {
    constructor(reset = false) {
        this.urlProceso = "Login/php/proceso.php";
        if (reset) {
            new Login().fnBorraLocalStorage();
            $("#efectoLogin" ).hide();
            $("#efectoLogin" ).show("fold", {}, 400);

            let usuario = document.querySelector("#usu");
            let clave = document.querySelector("#cla");
            usuario.addEventListener("keydown", (e) => {
                if(e.key == 'Enter') {
                    clave.focus();
                }
                if(e.key=='Escape'){
                    usuario.value="";
                }
            })
            clave.addEventListener("keydown", (e) => {
                if(e.key == 'Enter') {
                    new Login().fnConsultar();
                }
                if(e.key == 'Escape') {
                    usuario.focus();
                    usuario.select();
                }
            })

            $("#btnIniciar").on("click",function(){ new Login().fnConsultar(); })
        }
    }

    fnBorraLocalStorage(){
        localStorage.clear();
    }

    fnConsultar() {
        let claveLogin = $("#usu").val();
        let pwdLogin = md5($("#cla").val());
        if (claveLogin == "" || pwdLogin == ""){
            $( "#efectoLogin" ).effect( "shake", {}, 200);
            // console.log("Algo falta");
        } else {
            let parametros = {
                proceso : "USUARIO_SELECT",
                datosLogin : {claveLogin,pwdLogin}
            }
            $.ajax({
                data : parametros,
                url : this.urlProceso,
                global:false,
                type:"POST",
                dataType:"json",
                async:true,
                cache:false,
                success: function(informacion){
                    if (informacion==null){
                        // $("#mensajeLogin").html("No existe el usuario.<br> Intente de nuevo.");
                        $( "#efectoLogin" ).effect( "shake", {}, 200);
                        return;
                        }
                    if (pwdLogin!=informacion.pwd){
                        // $("#mensajeLogin").html("Contraseña incorrecta.<br> Intente de nuevo.");
                        $( "#efectoLogin" ).effect( "shake", {}, 200);
                        return;
                    }
                    
                    new Login().fnGrabaVariablesDeSesion(informacion);

                },error: function(err){
                    //alert(err);
                    // console.log(err);
                }
            })
            // console.log("Usuario "+claveLogin+" Contraseña "+pwdLogin);
        }
    }

    fnEfecto(){

    }

    fnGrabaVariablesDeSesion(informacion){
        let parametros = {
            proceso : "VARIABLES_SESION",
            usuario : informacion
        }
        localStorage.setItem("idUsuario",informacion.idUsuario);
        localStorage.setItem("nombreUsuario",informacion.nombreUsuario);
        localStorage.setItem("claveUsuario",informacion.clave);
        localStorage.setItem("logueadoEn",new Date());
        $.ajax({
            data : parametros,
            url : this.urlProceso,
            global : false,
            type:"POST",
            dataType:"html",
            async:true,
            cache:false,
            success: function(){
                location.reload();
            }
        })
    }
}
window.onload = () => new Login(true);