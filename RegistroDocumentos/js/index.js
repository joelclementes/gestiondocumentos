class Registro {
  constructor(reset = false) {
    this.urlProceso = "RegistroDocumentos/php/proceso.php";
    this.nombreTabla = "catetiquetas";
    this.ul = document.querySelector("#card_captura_etiquetas_seleccionadas");
    alertify.defaults.transition = "zoom";
    alertify.defaults.theme.ok = "btn btn-primary";
    alertify.defaults.theme.cancel = "btn btn-danger";
    alertify.defaults.theme.input = "form-control";
    if (reset) {
      // En cada módulo hay que usar new Menu().fnCreaMenu() porque si no, desaparecen las opciones
      new Menu().fnCreaMenu();

      document.querySelector("#nombre_usuario").innerHTML =
        localStorage.getItem("nombreUsuario");
      document.querySelector("#usuarioCapturando").innerHTML =
        localStorage.getItem("nombreUsuario");


      new Registro().fnOrigenSelect();
      new Registro().fnEtiquetasSelect();
      new Registro().fnLimpiaDatos();

      document.querySelector("#txtNumeroOficio").focus();

      let NumeroOficio = document.querySelector("#txtNumeroOficio");
      NumeroOficio.addEventListener("change", (dato) => {
        let texto = dato.target.value;
        if (texto != "") {
          document.querySelector("#btnRegistrar").removeAttribute("disabled");
        } else {
          document
            .querySelector("#btnRegistrar")
            .setAttribute("disabled", "disabled");
        }
      });
      // Cuando presionamos el botón Registrar
      document.querySelector("#btnRegistrar").addEventListener("click", () => {
        new Registro().fnGuarda();
      });

      // Cuando presionamos el botón Cancelar
      document.querySelector("#btnCancelar").addEventListener("click", () => {
        new Registro().fnLimpiaDatos();
      });

      // Cuando seleccionamos un archivo
      document.getElementById("archNombre").onchange = function () {
        document.getElementById("archivoSeleccionado").innerHTML =
          document.getElementById("archNombre").files[0].name;
      };
    }
  }

  documentos_select_all(
    par_idUsuario = 0,
    par_etiqueta = "",
    par_idOrigen = 0
  ) {
    let parametrosAjax = {
      proceso: "DOCUMENTOS_SELECT_ALL",
      idUsuario: par_idUsuario,
      etiqueta: par_etiqueta,
      idOrigen: par_idOrigen,
    };
    $.ajax({
      data: parametrosAjax,
      url: this.urlProceso,
      type: "POST",
      success: function (datos) {
        datos = JSON.parse(datos);
        let documentCards = ``;
        let notas;
        let etiquetas;
        let strEtiquetas;
        let btnPdf;
        let btnAdjuntaPdf;
        for (let d of datos) {
          notas = ``;
          btnPdf = ``;
          btnAdjuntaPdf = ``;

          // Si hay notas u observaciones, se crea la leyenda
          if (d.notas != "") {
            notas = `
                            <div>Notas:</div>
                            <p class="card_dato">${d.notas}</p>
                        `;
          }
          // Si existe un archivo adjunto, se crea el botón para visualizarlo
          if (d.nombreArchivo != "" && d.nombreArchivo != null) {
            btnPdf = `
                        <label class="card_nota_archivo" onClick="Abrir_ventana('${d.archivo}')" >Ver documento
                        <img
                          src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAAAXNSR0IArs4c6QAAAfNJREFUWEft2EFO20AUBuD/PQu6bNYEqekeN5E5QHODhhuEE9ScgOQEhBuYE5AbNFzADfUB2BB1C8ugzntonLgEEdexGVcgZaRsPDN+37wZezwhvLFCb8yD9wO66XQau2zOAPQANEpmMmrGyXHJPmnz3AzNDv0xFN+q3HTZpxIqHxT4+gpM1rU0yilIoecE6gJorwymFMopCKDhg/Bol82kKso5qBn/GiwfiEqoWkB2uqqiagNVRdUKqoKqHZSHasbJ2thOQaSYKKldzC+KghoEhFnFfwGVeZFuQUXZ2mZom6GiDBTVO1hDNLQbpw30u9NpCZuxAnf7cWI/N9K9a4dNj4GRANPsuq2bBV8GgJ6uIp2AhGXCQpfCepR+bgoNbOBZ4Kt9Kc7VO/rA0hdoz16/DQ5GrNRWQgtIf3+LE5CKGYN5BJGQPW6JIsxAi0g0ZOHIsIkWIL9PIAvpA/rJOchOWTpdMG0wIgWuV0EEOpkLRztsxk8gC6F+LRlaTtmPbKQKXK2Arh/E6z6fMn9CwNd1C9zJlOWBnnb0P98BCrPM3Qa1gooe5HL1DjJULmBR6y3oXxki4H4vTtYez+s8SueaSHGx9zOxr4IXJReUHmM8E5Giq8DHojWxab3FzNULP0+nd6VAmwZw3e79/D/keuSb3u8RfZdCNHHZH20AAAAASUVORK5CYII=" />
                        </label>
                        `;
          }

          // Se crean las etiquetas capturadas
          etiquetas = JSON.parse(d.etiquetasEntrada);
          strEtiquetas = `<ul class="etiquetas_documento">`;
          for (let e of etiquetas) {
            let jsonDatos = {};
            jsonDatos = JSON.stringify(d);
            strEtiquetas += `<li class="etiquetas_documento_item">${e}</li>`;
          }
          strEtiquetas += `</ul>`;

          // En caso de que no tenga archivo adjunto, se crea el icono para poderlo adjuntar
          if (d.nombreArchivo == "" || d.nombreArchivo == null) {
            btnAdjuntaPdf = `
                    <div class="image-upload">
                    <label for="archNombre${d.idDocumento}">Adjuntar documento.
                      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAAAXNSR0IArs4c6QAAAfNJREFUWEft2EFO20AUBuD/PQu6bNYEqekeN5E5QHODhhuEE9ScgOQEhBuYE5AbNFzADfUB2BB1C8ugzntonLgEEdexGVcgZaRsPDN+37wZezwhvLFCb8yD9wO66XQau2zOAPQANEpmMmrGyXHJPmnz3AzNDv0xFN+q3HTZpxIqHxT4+gpM1rU0yilIoecE6gJorwymFMopCKDhg/Bol82kKso5qBn/GiwfiEqoWkB2uqqiagNVRdUKqoKqHZSHasbJ2thOQaSYKKldzC+KghoEhFnFfwGVeZFuQUXZ2mZom6GiDBTVO1hDNLQbpw30u9NpCZuxAnf7cWI/N9K9a4dNj4GRANPsuq2bBV8GgJ6uIp2AhGXCQpfCepR+bgoNbOBZ4Kt9Kc7VO/rA0hdoz16/DQ5GrNRWQgtIf3+LE5CKGYN5BJGQPW6JIsxAi0g0ZOHIsIkWIL9PIAvpA/rJOchOWTpdMG0wIgWuV0EEOpkLRztsxk8gC6F+LRlaTtmPbKQKXK2Arh/E6z6fMn9CwNd1C9zJlOWBnnb0P98BCrPM3Qa1gooe5HL1DjJULmBR6y3oXxki4H4vTtYez+s8SueaSHGx9zOxr4IXJReUHmM8E5Giq8DHojWxab3FzNULP0+nd6VAmwZw3e79/D/keuSb3u8RfZdCNHHZH20AAAAASUVORK5CYII=" />
                    </label>
                    <input id="archNombre${d.idDocumento}" type="file" accept="application/pdf" onChange="new Registro().documentos_update_adjunto(${d.idDocumento})" />
                  </div>
                  `;
          }

          // Se crea la tarjeta del documento
          documentCards += `
                    <div class="card_documento">
                        <div>
                            <div>Registró: <span class="card_dato">${d.recibio}</span> el <span class="card_dato">${d.fechaRegistro}</span> a las <span class="card_dato">${d.hora}</span></div>
                            <div>Documento: <span class="card_dato">${d.numeroOficio} </span> de fecha:<span class="card_dato">${d.fechaOficio}</span> Firmado por: <span class="card_dato">${d.firmadoPor}</span></div>
                            <div>Procedente de: <span class="card_dato">${d.origen}</span></div>
                            <div>Asunto:</div>
                            <p class="card_dato">${d.asunto}</p>
                            ${notas}
                            ${btnAdjuntaPdf}
                            ${btnPdf}
                        </div>
                        <hr>
                        ${strEtiquetas}
                        <hr>
                        <div id="historialDocumento${d.idDocumento}"></div>
                        <div>
                            <input class="form-input" type="text" id="txtNota${d.idDocumento}" placeholder="Escribe un comentario..." onChange="new Registro().fnAgregaNota(this,${d.idDocumento})">
                        </div>
                    </div>
                    
                    `;
          document.querySelector("#documentosGuardados").innerHTML =
            documentCards;
          new Registro().documentos_historial_select(d.idDocumento);
        }
      },
    });
  }

  documentos_update_adjunto(par_idDocumento) {
    let el = `archNombre${par_idDocumento}`;
    let inputFile = document.getElementById(el);
    let par_archivo = inputFile.files[0];
    let nombreArchivo;
    let tipoArchivo;

    if (par_archivo != undefined) {
      nombreArchivo = par_archivo.name;
      tipoArchivo = par_archivo.type;
    }

    if (
      nombreArchivo != undefined &&
      (nombreArchivo.includes("á") ||
        nombreArchivo.includes("à") ||
        nombreArchivo.includes("ä") ||
        nombreArchivo.includes("â") ||
        nombreArchivo.includes("é") ||
        nombreArchivo.includes("è") ||
        nombreArchivo.includes("ë") ||
        nombreArchivo.includes("ê") ||
        nombreArchivo.includes("í") ||
        nombreArchivo.includes("ì") ||
        nombreArchivo.includes("ï") ||
        nombreArchivo.includes("î") ||
        nombreArchivo.includes("ó") ||
        nombreArchivo.includes("ò") ||
        nombreArchivo.includes("ö") ||
        nombreArchivo.includes("ô") ||
        nombreArchivo.includes("ú") ||
        nombreArchivo.includes("ù") ||
        nombreArchivo.includes("ü") ||
        nombreArchivo.includes("û") ||
        nombreArchivo.includes("ñ") ||
        nombreArchivo.includes("~") ||
        nombreArchivo.includes("¿") ||
        nombreArchivo.includes(" ") ||
        nombreArchivo.includes("'"))
    ) {
      alertify
        .alert(
          "Atención",
          "Nombre de archivo inválido</br>(No debe tener espacios, «ñ», ni caracteres especiales)."
        )
        .set("modal", false);
      return;
    }

    if (tipoArchivo != "application/pdf") {
      alertify
        .alert("Atención", "Tipo de archivo inválido (Debe ser PDF).")
        .set("modal", false);
      return;
    }

    var parametrosAjax = new FormData();
    if (nombreArchivo != undefined) {
      parametrosAjax.append("proceso", "DOCUMENTOS_UPDATE_DOCUMENTO");
      parametrosAjax.append("idDocumento", par_idDocumento);
      parametrosAjax.append("archivo", par_archivo);
    } else {
      return;
    }

    $.ajax({
      url: this.urlProceso,
      type: "POST",
      data: parametrosAjax,
      contentType: false,
      cache: false,
      processData: false,
      success: function (resultado) {
        if (resultado != 1) {
          alertify.alert("Ocurrió un error", resultado).set("modal", false);
          return;
        } else {
          new Registro().fnLimpiaDatos();
        }
      },
    });
  }

  documentos_historial_select(par_idDocumento) {
    let parametrosAjax = {
      proceso: "DOCUMENTOS_HISTORIAL_SELECT",
      idDocumento: par_idDocumento,
    };
    $.ajax({
      data: parametrosAjax,
      url: this.urlProceso,
      type: "POST",
      success: function (datos) {
        datos = JSON.parse(datos);
        let hist = ``;
        let trash;
        for (let d of datos) {
          trash = ``;
          if (d.idUsuario == localStorage.getItem("idUsuario")) {
            trash = `<i class='bx bx-trash bx-flashing-hover card_nota_borrar' onClick="new Registro().fnBorraComentario(${d.id})"></i>`;
          }
          hist += `
                    <p class="card_nota">
                        <span class="card_nota_usuario">${d.nombreUsuario}</span>
                        <span class="card_nota_fecha">${d.fecha} a las ${d.hora}</span>
                        <span class="card_nota_texto">${d.nota}<span></br>
                        ${trash}
                    </p>
                    `;
        }
        let sel = "#historialDocumento" + par_idDocumento;
        document.querySelector(sel).innerHTML = hist;
      },
    });
  }

  fnBorraComentario(par_id) {
    let parametrosAjax = {
      proceso: "DOCUMENTOS_BORRA_COMENTARIO",
      idComentario: par_id,
    };

    $.ajax({
      data: parametrosAjax,
      url: this.urlProceso,
      type: "POST",
      success: function (datos) {
        new Registro().documentos_select_all();
      },
    });
  }

  fnAgregaNota(dato, par_idDocumento) {
    let parametrosAjax = {
      proceso: "DOCUMENTOS_ACTUALIZA_HISTORIAL",
      idDocumento: par_idDocumento,
      nota: dato.value,
      idUsuario: localStorage.getItem("idUsuario"),
    };
    $.ajax({
      data: parametrosAjax,
      url: this.urlProceso,
      type: "POST",
      success: function (datos) {
        let txtNota = `#txtNota${par_idDocumento}`;
        document.querySelector(txtNota).value = "";
        new Registro().documentos_historial_select(par_idDocumento);
      },
    });
  }

  fnOrigenSelect() {
    let parametrosAjax = { proceso: "CATORIGEN_SELECT" };
    $.ajax({
      data: parametrosAjax,
      url: this.urlProceso,
      type: "POST",
      success: function (datos) {
        new Registro().fnConstruyeComboOrigen(datos);
      },
    });
  }

  fnConstruyeComboOrigen(datos) {
    datos = JSON.parse(datos);
    let strOpciones = `<option value=0>Seleccione</option>`;
    for (let d of datos) {
      strOpciones += `<option value=${d.idOrigen}>${d.nombre}</option>`;
    }
    document.querySelector("#cboOrigen").innerHTML = strOpciones;
  }

  fnEtiquetasSelect() {
    let parametrosAjax = { proceso: "CATETIQUETAS_SELECT" };
    $.ajax({
      data: parametrosAjax,
      url: this.urlProceso,
      type: "POST",
      success: function (datos) {
        new Registro().fnConstruyePanelEtiquetas(datos);
      },
    });
  }

  fnConstruyePanelEtiquetas(datos) {
    datos = JSON.parse(datos);
    let elementos = `<ul class="card_captura_etiquetas">`;
    for (let d of datos) {
      let jsonDatos = {};
      jsonDatos = JSON.stringify(d);
      elementos += `<li onclick="new Registro().fnAgregaEtiquetaSeleccionada('${d.nombre}')"><div class="card_captura_etiquetas_item">${d.nombre}</div></li>
`;
    }
    elementos += `</ul>`;
    document.querySelector("#card_captura_etiquetas").innerHTML = elementos;
  }

  fnAgregaEtiquetaSeleccionada(nombre) {
    let li = document.createElement("li");
    li.appendChild(new Registro().fnAgregaEvento(nombre));
    this.ul.appendChild(li);
  }

  fnAgregaEvento(nombreEtiqueta) {
    let deleteBtn = document.createElement("span");

    deleteBtn.className = "card_captura_etiquetas_item_seleccionado";

    deleteBtn.innerHTML = nombreEtiqueta;

    deleteBtn.addEventListener("click", (e) => {
      let item = e.target.parentElement;
      this.ul.removeChild(item);
    });
    return deleteBtn;
  }

  fnMuestraDatos(dato) {
    new Registro().fnLimpiaDatos();
    document.querySelector("#txtNombre").value = dato.nombre;
    document.querySelector(
      "#nombreModificado"
    ).innerHTML = `Modificando etiqueta <strong>${dato.nombre}</strong>`;
    document.querySelector("#txtNombre").focus();
    localStorage.setItem("idEtiquetaModificada", dato.idEtiqueta);
  }

  fnArregoDeUnaLista(id) {
    /**
     * Función que recibe como parámetro el id de un elemento ul
     * y obtiene el texto de cada elemento
     */

    //Array con cada texto de la lista:
    let dataElementos = [...document.querySelectorAll(`#${id} li`)].map(
      (element) => element.innerText
    );

    return dataElementos;
  }

  fnGuarda() {
    let par_numeroOficio = document.querySelector("#txtNumeroOficio").value;
    let par_fechaOficio = document.querySelector("#txtFechaOficio").value;
    let par_asunto = document.querySelector("#txtAsunto").value;
    let par_firmadoPor = document.querySelector("#txtFirmadoPor").value;
    let par_idOrigen = document.querySelector("#cboOrigen").value;
    let par_notas = document.querySelector("#txtNotas").value;
    let par_etiquetasEntrada = JSON.stringify(
      new Registro().fnArregoDeUnaLista("card_captura_etiquetas_seleccionadas")
    );
    let par_idRecibio = localStorage.getItem("idUsuario");
    let inputFile = document.getElementById("archNombre");
    let par_archivo = inputFile.files[0];
    let nombreArchivo;
    let tipoArchivo;

    if (par_archivo != undefined) {
      nombreArchivo = par_archivo.name;
      tipoArchivo = par_archivo.type;
    }
    if (par_fechaOficio == "") {
      alertify
        .alert("Atención", "Capture la fecha del documento")
        .set("modal", false);
      return;
    }
    if (par_asunto == "") {
      alertify
        .alert("Atención", "No ha capturado el asunto del documento")
        .set("modal", false);
      return;
    }
    if (par_firmadoPor == "") {
      alertify
        .alert("Atención", "No ha capturado quién firma el documento")
        .set("modal", false);
      return;
    }
    if (par_idOrigen <= 0) {
      alertify
        .alert("Atención", "No ha capturado el origen del documento")
        .set("modal", false);
      return;
    }

    if (
      nombreArchivo != undefined &&
      (nombreArchivo.includes("á") ||
        nombreArchivo.includes("à") ||
        nombreArchivo.includes("ä") ||
        nombreArchivo.includes("â") ||
        nombreArchivo.includes("é") ||
        nombreArchivo.includes("è") ||
        nombreArchivo.includes("ë") ||
        nombreArchivo.includes("ê") ||
        nombreArchivo.includes("í") ||
        nombreArchivo.includes("ì") ||
        nombreArchivo.includes("ï") ||
        nombreArchivo.includes("î") ||
        nombreArchivo.includes("ó") ||
        nombreArchivo.includes("ò") ||
        nombreArchivo.includes("ö") ||
        nombreArchivo.includes("ô") ||
        nombreArchivo.includes("ú") ||
        nombreArchivo.includes("ù") ||
        nombreArchivo.includes("ü") ||
        nombreArchivo.includes("û") ||
        nombreArchivo.includes("ñ") ||
        nombreArchivo.includes("~") ||
        nombreArchivo.includes("¿") ||
        nombreArchivo.includes(" ") ||
        nombreArchivo.includes("'"))
    ) {
      alertify
        .alert(
          "Atención",
          "Nombre de archivo inválido</br>(No debe tener espacios, «ñ», ni caracteres especiales)."
        )
        .set("modal", false);
      return;
    }

    if (nombreArchivo != undefined && tipoArchivo != "application/pdf") {
      alertify
        .alert("Atención", "Tipo de archivo inválido (Debe ser PDF).")
        .set("modal", false);
      return;
    }

    var parametrosAjax = new FormData();
    if (nombreArchivo == undefined) {
      parametrosAjax.append("proceso", "DOCUMENTOS_GUARDA_SIN_ARCHIVO");
    } else {
      parametrosAjax.append("proceso", "DOCUMENTOS_GUARDA");
    }
    parametrosAjax.append("numeroOficio", par_numeroOficio);
    parametrosAjax.append("fechaOficio", par_fechaOficio);
    parametrosAjax.append("asunto", par_asunto);
    parametrosAjax.append("firmadoPor", par_firmadoPor);
    parametrosAjax.append("idOrigen", par_idOrigen);
    parametrosAjax.append("notas", par_notas);
    parametrosAjax.append("etiquetasEntrada", par_etiquetasEntrada);
    parametrosAjax.append("idRecibio", par_idRecibio);
    parametrosAjax.append("archivo", par_archivo);

    $.ajax({
      url: this.urlProceso,
      type: "POST",
      data: parametrosAjax,
      contentType: false,
      cache: false,
      processData: false,
      success: function (resultado) {
        if (resultado != 1) {
          alertify.alert("Ocurrió un error", resultado).set("modal", false);
          return;
        } else {
          new Registro().fnLimpiaDatos();
        }
      },
    });
  }

  fnLimpiaDatos() {
    // DATOS DE ETIQUETA
    document.querySelector("#txtNumeroOficio").value = "";
    document.querySelector("#txtFechaOficio").value = "";
    document.querySelector("#txtAsunto").value = "";
    document.querySelector("#txtFirmadoPor").value = "";
    document.querySelector("#cboOrigen").value = 0;
    document.querySelector("#txtNotas").value = "";
    document.querySelector("#card_captura_etiquetas_seleccionadas").innerHTML =
      "";
    document.querySelector("#archivoSeleccionado").innerHTML = "";
    document
      .querySelector("#btnRegistrar")
      .setAttribute("disabled", "disabled");

    new Registro().documentos_select_all();
    new Registro().fnFiltrosRecibidoPor();
    new Registro().fnFiltrosEtiquetas();
    new Registro().fnFiltrosOrigen();
  }

  fnFiltrosRecibidoPor() {
    let parametrosAjax = { proceso: "USUARIOS_RECIBIO_SELECT" };
    $.ajax({
      data: parametrosAjax,
      url: this.urlProceso,
      type: "POST",
      success: function (datos) {
        datos = JSON.parse(datos);
        let lista = `<div class="filtros">`;
        for (let d of datos) {
          lista += `
                    <div class="filtro" onClick="new Registro().documentos_select_all(${d.idUsuario},'',0)">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAPRJREFUSEvNlWENwjAQhb8pAAmgAJCAA3AACpAAKCAoQAJIQAIOQAIogLxkDdC1vWVlZPfnsqx53/Xd7lbQchQt61MXMAbWgLLiAmzLnKyxDmAGHCMqc+CUIliAPnAFlENxB4aAcjAsQKp6JzgFzk0Bm9L7lAvqhc518wby/gb0IgU+gEFOD6Tb6lfkCtf3L58/50DPmofsObA0ugtQg1dlD5w1frWySJO8jzU6NmgLYJeYYB+kSV6G1kYIIPFDQ+Mru8kHWLvH4lZ2kw+osxosyNfq8AFq2shSMN5LY+LO+IBnpnhF9++AH13gLWP9cLKBrQNeWrEoGRxI2IoAAAAASUVORK5CYII="/>
                        <span>${d.nombreUsuario}</span>
                        <span><li>${d.tantos} recibidos</li></span>
                    </div>
                    `;
        }
        lista += `</div>`;
        document.querySelector("#quienRecibio").innerHTML = lista;
      },
    });
  }

  fnFiltrosEtiquetas() {
    let parametrosAjax = { proceso: "ETIQUETASENTRADA_SELECT" };
    $.ajax({
      data: parametrosAjax,
      url: this.urlProceso,
      type: "POST",
      success: function (datos) {
        datos = JSON.parse(datos);

        let etiquetasEntrada = datos.datEtiquetasEntrada;
        etiquetasEntrada = JSON.parse(etiquetasEntrada);
        let etiquetas = datos.datEtiquetas;
        etiquetas = JSON.parse(etiquetas);

        let et = "";
        let contador;
        let jsonEtiquetas = [];
        for (let e of etiquetas) {
          if (e.nombre != et) {
            et = e.nombre;
            contador = 0;
          }
          for (let ee of etiquetasEntrada) {
            let encontrado = 0;
            if (ee.etiquetasEntrada.indexOf(e.nombre) > 0) {
              contador++;
              encontrado = 1;
            }
          }
          jsonEtiquetas.push({ etiqueta: e.nombre, tantos: contador });
        }

        let lista = `
                <div class="filtros">
                `;
        for (let e of jsonEtiquetas) {
          if (e.tantos > 0) {
            lista += `
                        <div class="filtro" onClick="new Registro().documentos_select_all(0,'${e.etiqueta}',0)">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAOxJREFUSEvF1X0RwjAMh+F3DpCAA5AADpCCAyQhAXAACkACKIDLbnDd2qQJY8f+2t16v6cfadYw8dNMnE8JmAEHYFnAz8AauHsnNgSs8HdmCEkBT3gYSQGZ2cK7dMC1khR4FsIfwM2Aq4gFSLhsmzwbYK+szkQs4ASsutA5cDW2T0VqWyQzF2gHbCvnU0RqQODM26EZMqaKNLyHDO/BMViqGiI5cuOzViFV8wvkUyBaLxqDXLrqa/uV1k2/XUkv3ALkWxTJwmtABCmGewAPooZ7AQsxwyNACamGR4EUkXdphNVf519++tEGZ45/ATndQRn/BNZDAAAAAElFTkSuQmCC"/>
                            <span>${e.etiqueta}</span>
                            <span><li>${e.tantos} documentos</li></span>
                        </div>
                        `;
          }
        }
        lista += `</div>`;
        document.querySelector("#etiquetasUsadas").innerHTML = lista;
      },
    });
  }

  fnFiltrosOrigen() {
    let parametrosAjax = { proceso: "ORIGENENTRADA_SELECT" };
    $.ajax({
      data: parametrosAjax,
      url: this.urlProceso,
      type: "POST",
      success: function (datos) {
        datos = JSON.parse(datos);
        let lista = `<div class="filtros">`;
        for (let d of datos) {
          lista += `
                    <div class="filtro" onClick="new Registro().documentos_select_all(0,'',${d.idOrigen})">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAARRJREFUSEvdlNFxwjAQRB8VJB0AFZAS0gmkg1BJ6ADSSUoIHUAHpAKYzdxlNLLkk834g+jHY/u073ZPoxkTr9nE+vxPwCuwBxYj4zsBb8CX9pciUsF8pLhvk8ayBrha1QXYAodG2Ab4AJ6t/rf5kgMHuK4AAglYWhKUsADpCgHKcQc8Ad+Wq57perF56fkDvNv7X/N9DvRPG+VgZQ7SyNJIjuZADXgCoQOHKwI5WVvrPhOP5NM69whDQG2mEvTIVOOR5IegCsiH2xlYEpn+CZjPRN/vArSc2NERtYj3OnCBWlStF2PoYHJAFEXfYfC9Z78sI9u5mOojgMR1wqq3aeqgBIgcdi+kQTsGFkcRDZTrlj8+4AaNzzgZuJBekwAAAABJRU5ErkJggg=="/>
                        <span>${d.nombreOrigen}</span>
                        <span><li>${d.tantos} recibidos</li></span>
                    </div>
                    `;
        }
        lista += `</div>`;
        document.querySelector("#origenDocumento").innerHTML = lista;
      },
    });
  }
}
window.onload = () => new Registro(true);
