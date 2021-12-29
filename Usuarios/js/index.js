class Usuarios {
    constructor(reset = false) {
        this.urlProceso = "Usuarios/php/proceso.php";
        if (reset) {
            // En cada módulo hay que usar new Menu().fnCreaMenu() porque si no, desaparecen las opciones
            new Menu().fnCreaMenu();

            // Se muestran todos los usuarios al cargar la página
            new Usuarios().fnUsuariosConsultarTodos();

            // Funciones de los botones cuando se hace clic en ellos.
            $("#btnGuardaUsuario").on("click",function(){ new Usuarios().fnUsuarioGuarda(); })
            $("#btnCancelar").on("click",function(){ new Usuarios().fnUsuarioLimpiaDatos(); })
            $("#btnEliminarUsuario").on("click",function(){ new Usuarios().fnUsuarioElimina(); })
        }
    }

    fnUsuariosConsultarTodos(){
        let parametros = {
            proceso : "USUARIOS_TODOS"
        }
        $.ajax({
            data : parametros,
            url : this.urlProceso,
            type : "POST",
            success: function(usuarios){
                new Usuarios().fnUsuariosConstruyeTabla(usuarios);
            }
        })
    }

    fnUsuariosConstruyeTabla(usuarios){
        usuarios = JSON.parse(usuarios);
        let listaUsuarios = "";
        // listaUsuarios += `<div class="list-group">`;
        listaUsuarios += ``;
        // Se crea la lista de usuarios asignando una función que se ejecuta cuando se hace clic en alguno de ellos.
        for (let u of usuarios){
            listaUsuarios += `
            <a href="#"  class="list-group-item list-group-item-action list-group-item-light" onclick="new Usuarios().fnUsuarioMuestraDatos(`+u.idUsuario+`)">`+u.nombreUsuario+`</a>`;
        }
        // listaUsuarios += `</div>`;
        $("#ListaUsuarios").html(listaUsuarios);

    }

    fnUsuarioGuarda(){
        let nomUsuario = $("#txtNombreDelUsuario").val();
        let claUsuario = $("#txtClave").val();
        let pwdUsuario1 = $("#txtPwd").val();
        let pwdUsuario2 = $("#txtPwd2").val();
        let esNuevo = (localStorage.getItem("idUsuarioModificado")==null ? 1 : 0);

        //Validamos la información
        if(nomUsuario=="" || claUsuario==""){
            $("#mensajesDeError").html("Faltan datos del usuario.");
            $("#mensajesDeError").attr("class","alert alert-danger");
            return;
        }

        if(esNuevo==1 && pwdUsuario1==""){
            $("#mensajesDeError").html("Falta contraseña.");
            $("#mensajesDeError").attr("class","alert alert-danger");
            return;
        }

        if(pwdUsuario1!=pwdUsuario2){
            $("#mensajesDeError").html("No coincide la confirmación de contraseña.");
            $("#mensajesDeError").attr("class","alert alert-danger");
            return;
        }

        let parametros = {
            proceso : "USUARIO_GUARDA",
            id : localStorage.getItem("idUsuarioModificado"),
            nombre : nomUsuario,
            clave : claUsuario,
            pwd : pwdUsuario1,
            nuevo : esNuevo
        }

        $.ajax({
            data : parametros,
            url : this.urlProceso,
            type : "POST",
            success : function(resultado){
                if (resultado!=1){
                    $("#mensajesDeError").html(resultado);
                    $("#mensajesDeError").attr("class","alert alert-danger");
                    return;
                } else {
                    new Usuarios().fnUsuarioLimpiaDatos();
                }
            }
        })
    }

    fnUsuarioLimpiaDatos(){
        $("#txtNombreDelUsuario").val("");
        $("#txtClave").val("");
        $("#txtPwd").val("");
        $("#txtPwd2").val("");
        $("#btnEliminarUsuario").attr("class","btn btn-danger invisible");
        $("#nombreUsuarioModificado").html("Usuario nuevo. Guarda y después especifica permisos.");
        $("#ListaPermisos").html("");
        $("#ListaOpciones").html("");
        localStorage.removeItem("nombreUsuarioModificado");
        localStorage.removeItem("idUsuarioModificado");

        new Usuarios().fnUsuariosConsultarTodos();

        $("#mensajesDeError").attr("class","alert alert-danger invisible");    
    }

    fnUsuarioMuestraDatos(idUsuario){
        $("#mensajesDeError").attr("class","alert alert-danger invisible");
        $("#btnEliminarUsuario").attr("class","btn btn-danger");
        $("#mensajesDeError").html("");
        $("#ListaPermisos").html("");
        $("#ListaOpciones").html("");
        let parametros = {
            proceso : "USUARIO_SELECT_DATOS",
            idDelUsuario : idUsuario
        }
        $.ajax({
            data : parametros,
            url : this.urlProceso,
            type : "POST",
            success: function(usuario){
                usuario = JSON.parse(usuario);
                let usuarioDatos  = usuario.usuDatos; usuarioDatos = JSON.parse(usuarioDatos);
                let usuarioPermisos = usuario.usuPermisos; usuarioPermisos=JSON.parse(usuarioPermisos);
                let opcionesDisponibles = usuario.opcionesDisponibles; opcionesDisponibles=JSON.parse(opcionesDisponibles);
                //Colocamos información en divs que nos será de utilidad
                localStorage.setItem("idUsuarioModificado",usuarioDatos.idUsuario);

                $("#nombreUsuarioModificado").html("Modificando a: <b>"+usuarioDatos.nombreUsuario+"</b>");
                
                // Colocamos los datos del Usuario en el formulario
                $("#txtNombreDelUsuario").val(usuarioDatos.nombreUsuario);
                $("#txtClave").val(usuarioDatos.clave);

                // Mostramos las opciones a las que tiene permiso
                let listaPermisos = "";
                listaPermisos += `
                <ul class="list-group">`;
                    for (let usuarioPermiso of usuarioPermisos){
                        listaPermisos += `
                        <li type="button" class="list-group-item list-group-item-action list-group-item-light" onClick="new Usuarios().fnUsuarioBorraPermiso(`+usuarioPermiso.id+`)">`+usuarioPermiso.tituloMenu+`</li>`;
                    }
                    listaPermisos += `
                </ul>`;
                $("#ListaPermisos").html(listaPermisos);
                
                // Mostramos las opciones disponibles del sistema
                let listaOpciones = "";
                listaOpciones += `
                <ul class="list-group">`;
                    for (let opcionDisponible of opcionesDisponibles){
                        listaOpciones += `
                        <li type="button" class="list-group-item list-group-item-action list-group-item-light" onClick="new Usuarios().fnUsuarioAgregaPermiso(`+opcionDisponible.idMenu+`)">`+opcionDisponible.tituloMenu+`</li>`;
                    }
                    listaOpciones += `
                </ul>`;
                $("#ListaOpciones").html(listaOpciones);
            }
        })
    }

    fnUsuarioBorraPermiso(id){
        let parametros = {
            proceso : "USUARIO_BORRA_OPCION_MENU",
            idQueSeBorra : id
        }
        $.ajax({
            data : parametros,
            url : this.urlProceso,
            type : "POST",
            success : function(resultado){
                // Después de que se borra el registro se vuelven a mostrar los datos en pantalla
                new Usuarios().fnUsuarioMuestraDatos(localStorage.getItem("idUsuarioModificado"));
                new Menu().fnCreaMenu();
            }
        })
    }

    fnUsuarioAgregaPermiso(idMenu){
        $.ajax({
            data : {
                proceso : "USUARIO_AGREGA_OPCION_MENU",
                idUsuario : localStorage.getItem("idUsuarioModificado"),
                idMenu : idMenu
            },
            url : this.urlProceso,
            type : "POST",
            success : function(resultado){
                // Después de que se borra el registro se vuelven a mostrar los datos en pantalla
                new Usuarios().fnUsuarioMuestraDatos(localStorage.getItem("idUsuarioModificado"));
                new Menu().fnCreaMenu();
            }
        }) 
    }

    fnUsuarioElimina(){
        $.ajax({
            data : {
                proceso : "USUARIO_ELIMINA",
                idUsuario : localStorage.getItem("idUsuarioModificado")
            },
            url : this.urlProceso,
            type : "POST",
            success : function(resultado){
                // Después de que se borra el registro se vuelven a mostrar los datos en pantalla
                new Usuarios().fnUsuarioLimpiaDatos();
            }
        })
    }

}
window.onload = () => new Usuarios(true);