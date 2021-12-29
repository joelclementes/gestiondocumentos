class Etiquetas {
    constructor(reset = false) {
        this.urlProceso = "CatEtiquetas/php/proceso.php";
        this.nombreTabla = "catetiquetas";
        alertify.defaults.transition = "zoom";
        alertify.defaults.theme.ok = "btn btn-primary";
        alertify.defaults.theme.cancel = "btn btn-danger";
        alertify.defaults.theme.input = "form-control";
        if (reset) {
            // En cada módulo hay que usar new Menu().fnCreaMenu() porque si no, desaparecen las opciones
            new Menu().fnCreaMenu();

            // Con esta función cargamos los datos
            new Etiquetas().fnLimpiaDatos();

            // Funciones de los botones cuando se hace clic en ellos.
            document.querySelector("#btnGuardar").addEventListener("click", () => { new Etiquetas().fnGuarda() })
            document.querySelector("#btnCancelar").addEventListener("click", () => { new Etiquetas().fnLimpiaDatos() })

        }
    }

    fnConsultarTodos(){
        let parametrosAjax = { proceso: "CATETIQUETAS_SELECT"};
        $.ajax({
            data: parametrosAjax,
            url: this.urlProceso,
            type: "POST",
            success: function (datos) {
                new Etiquetas().fnConstruyeLista(datos);
            }
        })
    }

    fnConstruyeLista(datos) {
        datos = JSON.parse(datos);
        let elementos = ``;
        for (let d of datos) {
            let jsonDatos = {};
            jsonDatos = JSON.stringify(d);
            elementos += `
                    <div class="lista_item">
                        <div class="col-10 lista_nombre">${d.nombre}</div>
                        <div class="col-2 lista_botones">
                            <button style="color:#4582EC" class="btn btn-link" onclick='new Etiquetas().fnMuestraDatos(`+ jsonDatos + `)'><i class="fa fa-pencil-alt"></i></button>
                            <button style="color:#FF0000" class="btn btn-link" onclick='new Etiquetas().fnBorraDatos(`+ jsonDatos + `)'><i class="far fa-trash-alt"></i></button>
                        </div>
                    </div>
            `;
        }
        $("#listaElementos").html(elementos);
    }


    fnMuestraDatos(dato) {
        new Etiquetas().fnLimpiaDatos();
        document.querySelector("#txtNombre").value = dato.nombre;
        document.querySelector("#nombreModificado").innerHTML = `Modificando etiqueta <strong>${dato.nombre}</strong>`;
        document.querySelector("#txtNombre").focus();
        localStorage.setItem("idEtiquetaModificada", dato.idEtiqueta);
    }

    fnBorraDatos(depto){
        let resp = alertify.confirm('Atención', 'Se borrarrá la etiqueta ' + depto.nombre
                , () => { new Etiquetas().fnDelete(depto)  }
                , () => {});
    }

    fnDelete(depto){
        let par_idEtiqueta = depto.idEtiqueta;
        let parametrosAjax = {
            proceso: "CATETIQUETAS_DELETE",
            idEtiqueta: par_idEtiqueta,
            tabla: this.nombreTabla
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
                    new Etiquetas().fnLimpiaDatos();
                }
            }
        })
    }

    fnGuarda() {
        let par_idEtiqueta = localStorage.getItem("idEtiquetaModificada") == null ? 0 : parseInt(localStorage.getItem("idEtiquetaModificada"), 10);
        let par_nombre = document.querySelector("#txtNombre").value;

        if (par_nombre == "") {
            alertify.alert('Atención', "No ha capturado nombre").set('modal', false);
            return;
        }

        let parametrosAjax = {
            proceso: "CATETIQUETAS_GUARDA",
            idEtiqueta: par_idEtiqueta,
            nombre: par_nombre,
            tabla: this.nombreTabla
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
                    new Etiquetas().fnLimpiaDatos();
                }
            }
        })
    }

    fnLimpiaDatos() {
        // DATOS DE ETIQUETA
        document.querySelector("#txtNombre").value = "";
        document.querySelector("#nombreModificado").innerHTML = "Etiqueta nueva";
        localStorage.removeItem("idEtiquetaModificada");

        // Se muestran todos los usuarios al cargar la página
        new Etiquetas().fnConsultarTodos();

        document.querySelector("#txtNombre").focus();
    }
}
window.onload = () => new Etiquetas(true);