class Origen {
    constructor(reset = false) {
        this.urlProceso = "CatOrigen/php/proceso.php";
        alertify.defaults.transition = "zoom";
        alertify.defaults.theme.ok = "btn btn-primary";
        alertify.defaults.theme.cancel = "btn btn-danger";
        alertify.defaults.theme.input = "form-control";
        if (reset) {
            // En cada módulo hay que usar new Menu().fnCreaMenu() porque si no, desaparecen las opciones
            new Menu().fnCreaMenu();

            // Con esta función cargamos los datos
            
            new Origen().fnOrigenLimpiaDatos();

            // Funciones de los botones cuando se hace clic en ellos.
            document.querySelector("#btnGuardaOrigen").addEventListener("click", () => { new Origen().fnOrigenGuarda() })
            document.querySelector("#btnCancelar").addEventListener("click", () => { new Origen().fnOrigenLimpiaDatos() })

        }
    }

    fnOrigenConsultarTodos(){
        let parametrosAjax = { proceso: "CATORIGEN_SELECT"};
        $.ajax({
            data: parametrosAjax,
            url: this.urlProceso,
            type: "POST",
            success: function (origen) {
                new Origen().fnOrigenConstruyeLista(origen);
            }
        })
    }

    fnOrigenConstruyeLista(origen) {
        origen = JSON.parse(origen);
        let listaOrigen = ``;
        let nombre = ``;
        for (let d of origen) {
            let jsonOrigen = {};
            jsonOrigen = JSON.stringify(d);
            listaOrigen += `
            <div class="lista_item">
                <div class="col-10 lista_nombre">${d.nombre}</div>
                <div class="col-2 lista_botones">
                    <button style="color:#4582EC" class="btn btn-link" onclick='new Origen().fnOrigenMuestraDatos(`+ jsonOrigen + `)'><i class="fa fa-pencil-alt"></i></button>
                    <button style="color:#FF0000" class="btn btn-link" onclick='new Origen().fnOrigenBorraDatos(`+ jsonOrigen + `)'><i class="far fa-trash-alt"></i></button>
                </div>
            </div>
            `;
        }
        $("#cardsDepartamentos").html(listaOrigen);
    }


    fnOrigenMuestraDatos(depto) {
        new Origen().fnOrigenLimpiaDatos();
        document.querySelector("#txtNombre").value = depto.nombre;
        document.querySelector("#nombreOrigenModificado").innerHTML = `Modificando a <strong>${depto.nombre}</strong>`;
        document.querySelector("#txtNombre").focus();
        localStorage.setItem("idOrigenModificado", depto.idOrigen);
    }

    fnOrigenBorraDatos(origen){
        let resp = alertify.confirm('Atención', 'Se borrarrá el registro ' + origen.nombre
                , () => { new Origen().fnCatOrigenDelete(origen)  }
                , () => {});
    }

    fnCatOrigenDelete(origen){
        let par_idOrigen = origen.idOrigen;
        let par_nombre = origen.nombre;
        let parametrosAjax = {
            proceso: "CATORIGEN_DELETE",
            idOrigen: par_idOrigen
        }
        $.ajax({
            data: parametrosAjax,
            url: this.urlProceso,
            type: "POST",
            success: function (resultado) {
                if (resultado != 1) {
                    alertify.alert('Ocurrió un error', resultado).set('modal', false);
                    return;
                } else {
                    alertify.success('Se borró con éxito')
                    new Origen().fnOrigenLimpiaDatos();
                }
            }
        })
    }

    fnOrigenGuarda() {
        let par_idOrigen = localStorage.getItem("idOrigenModificado") == null ? 0 : parseInt(localStorage.getItem("idOrigenModificado"), 10);
        let par_nombre = document.querySelector("#txtNombre").value;

        if (par_nombre == "") {
            alertify.alert('Atención', "No ha capturado nombre").set('modal', false);
            return;
        }

        let parametrosAjax = {
            proceso: "CATORIGEN_GUARDA",
            idOrigen: par_idOrigen,
            nombre: par_nombre
        }
        $.ajax({
            data: parametrosAjax,
            url: this.urlProceso,
            type: "POST",
            success: function (resultado) {
                if (resultado != 1) {
                    alertify.alert('Ocurrió un error', resultado).set('modal', false);
                    return;
                } else {
                    new Origen().fnOrigenLimpiaDatos();
                }
            }
        })
    }

    fnOrigenLimpiaDatos() {
        // DATOS DE ORIGEN
        document.querySelector("#txtNombre").value = "";
        document.querySelector("#nombreOrigenModificado").innerHTML = "Origen nuevo";
        localStorage.removeItem("idOrigenModificado");

        // Se muestran todos los usuarios al cargar la página
        new Origen().fnOrigenConsultarTodos();

        document.querySelector("#txtNombre").focus();
    }
}
window.onload = () => new Origen(true);