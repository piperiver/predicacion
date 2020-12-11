var telefono = {};

$(document).on("click", ".btn-estado", function () {
  $("#content-text-estado").hide();

  if (typeof telefono.id == "undefined") {
    return;
  }

  let estado = $(this).data("estado");
  let color = $(this).data("color");

  $.post(
    dominio + "CambiarEstado/" + telefono.id,
    { estado: estado },
    function (response) {

      redirect_for_session(response);

      if (!response.status) {
        swal(response.message, "", "error");
        return;
      }

      $("#text-estado").text(response.message);
      $("#content-text-estado").removeClass("text-amarillo");
      $("#content-text-estado").removeClass("text-verde");
      $("#content-text-estado").removeClass("text-rojo");
      $("#content-text-estado").addClass("text-" + color);
      $("#content-text-estado").show();

      swal(response.message, "", "success");
    }
  );
});

function bloquear_botones() {
  $(".btn-estado").attr("disabled", true);
}

function desbloquear_botones() {
  $(".btn-estado").attr("disabled", false);
}

function confirmacion() {
  swal({
    text: "",
    title: "Presione ACEPTAR si desea obtener el siguiente número de teléfono",
    icon: "warning",
    buttons: true,
  }).then((result) => {
    if (result) {
      obtenerTelefono();
    }
  });
}

function obtenerTelefono() {
  $.post(dominio + "ObtenerTelefono", function (response) {
    redirect_for_session(response);
    if (!response.status) {
      bloquear_botones();
      swal(response.message, "", "error");
      return;
    }

    if (!response.data) {
      bloquear_botones();
      $("#text-telefono").text("No hay más números");
      $("#accion-llamada").attr("href", "#?");
      
      swal(response.message, "", "warning");
      return;
    }


    $("#content-text-estado").hide();
    desbloquear_botones();
    telefono = response.data;

    swal(response.message, "", "success").then((value) => {
      $("#text-telefono").fadeOut(function () {
        $("#text-telefono").text(telefono.telefono);
        let complemento = (telefono.telefono.length <= 7)? "032" : "";
        $("#accion-llamada").attr("href", "tel:"+complemento+""+telefono.telefono);
      });
      $("#text-telefono").fadeIn();
    });
  });
}

$(document).on("click", "#btn-get-telefono", function () {
  const init = $(this).data("init");
  if (init) {
    $(this).text("Siguiente Número");
    $(this).data("init", false);
    obtenerTelefono();
  } else {
    confirmacion();
  }
});

$(document).on("change", ".admin-estado", function () {
  const data = JSON.parse($(this).val());

  $.post(
    dominio + "CambiarEstado/" + data.idtelefono,
    { estado: data.estado },
    function (response) {
      redirect_for_session(response);
      if (!response.status) {
        swal(response.message, "", "error");
        return;
      }

      swal(response.message, "", "success");
    }
  );
});

function guardarTelefonos(value) {
  $.post(dominio + "GuardarTelefonos", { telefonos: value }, function (
    response
  ) {
    redirect_for_session(response);
    if (!response.status) {
      swal(response.message, "", "error");
      return;
    }

    swal(response.data.title, response.data.message, "success").then(
      (value) => {
        window.location.reload();
      }
    );
  });
}

$(document).on("click", "#btn-create", function () {
  swal(
    "Escriba los números de teléfono que desea guardar, separados por coma(,) ejemplo: 3384829,3155078746.:",
    {
      content: {
        element: "input",
        attributes: {
          type: "text",
          id: "inputTelefonos",
          required: true,
        },
      },
    }
  ).then((value) => {
    if (value != null) {
      guardarTelefonos(value);
    }
  });
});

$(document).on("change, keyup", "#inputTelefonos", function () {
  var telefonos = $.trim($(this).val());
  if (telefonos.length == 0) {
    return;
  }

  if (isNaN(telefonos)) {
    telefonos = telefonos.replace(/[^0-9,]/g, "");
    if (telefonos.split(",,").length)
      telefonos = telefonos.replace(/\,,+$/, ",");
  }

  $(this).val(telefonos);
});

$(document).on("click", "#btn-create-user", function () {
  dialog.showModal();
});

function mostrarErroresUsuario(mensaje) {
  $(".errores").text(mensaje);
  $(".errores").fadeIn();
}

$(document).on("click", "#btn-save-user", function () {
  $(".errores").hide();

  const username = $("#username").val();
  const password = $("#password").val();
  const admin = $("#is_admin").is(":checked");

  if ($.trim(username) == "" || $.trim(password) == "") {
    mostrarErroresUsuario(
      "Asegúrece de diligenciar todos los campos del formulario"
    );
  }

  $.post(
    dominio + "GuardarUsuario",
    {
      username: username,
      password: password,
      admin: admin ? 1 : 0,
    },
    function (response) {
      redirect_for_session(response);
      if (!response.status) {
        mostrarErroresUsuario(response.message);
        return;
      }

      dialog.close();
      swal(response.message, "", "success").then((value) => {
        $("#username").val("");
        $("#password").val("");
        $("#is_admin").attr("checked", false);
        window.location.reload();
      });
    }
  );
});

$(document).on("click", ".delete-item", function () {
  if(!confirm('¿Confirma que desea eliminar este elemento?')){
    return false;
  }

  let id = $(this).data('id');
  let type = $(this).data('type');
  let item = $(this);

  $.post(
    dominio + "EliminarUsuario",
    {
      element_id: id,
      type: type
    },
    function (response) {
      redirect_for_session(response);
      if (!response.status) {
        swal(response.message, "", "error");
        return;
      }
      if(type == 'hermano'){
        item.parent().hide('slow', function(){
          $(this).remove();
        })
      }else{
        item.parent().parent().hide('slow', function(){
          $(this).remove();
        })
      }

      swal(response.message, "", "success");
    }
  );
})

$(document).on("change", ".updateUser", function () {
  const user = $(this).data("user");
  const update_admin = $(this).parent().parent().find(".admin").val();
  const update_active = $(this).parent().parent().find(".active").val();
    
  $.post(
    dominio + "ActualizarUsuario",
    {
      user: user,
      active: update_active,
      admin: update_admin
    },
    function (response) {
      redirect_for_session(response);
      if (!response.status) {
        swal(response.message, "", "error");
        return;
      }

      swal(response.message, "", "success");
    }
  );
});

$(document).on("click", "#btn-login", function(){
  const username = $("#login_username").val();
  const password = $("#login_password").val();

  if ($.trim(username) == "" || $.trim(password) == "") {
    swal("Asegurece de escribir el usuario y la contraseña", "", "error");
      return;
  }

  $.post(
    dominio + "IniciarSesion",
    {
      username: username,
      password: password
    },
    function (response) {
      redirect_for_session(response);
      if (!response.status) {
        swal(response.message, "", "error");
        return;
      }

      swal(response.message, "", "success").then((value) => {
        window.location.href = response.data.redirect;
      });
    }
  );


})

function redirect_for_session(response){
  if(typeof response.data.redirect_for_session != "undefined"){
    window.location.href = response.data.redirect_for_session;
  }
}


function initTable(id, clone = true){

  if(clone){
    $(id+' thead tr').clone(true).appendTo( id+' thead' );
      $(id+' thead tr:eq(1) th').each( function (i) {
          var title = $(this).text();
          var select = $(this).data('select');
          var nofilter = $(this).data('nofilter');
          if(typeof select != 'undefined' && select == true && nofilter != true){
            $(this).html( ` 
                            <select class="myselect search_filter">
                              <option value=''>Todos</option> 
                              <option value='SI'>SI</option>
                              <option value='NO'>NO</option>
                            </select>
            ` );

          }else if(nofilter != true){

            $(this).html( `<input type="text" class="search_filter" placeholder="Filtrar" />`);
          }else if(nofilter == true){
            $(this).html('');
          }

          
          $(this).addClass('content-filter');
          
    });

  }

  let table = $(id).DataTable({
    orderCellsTop: true,
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo:
        "Mostrando del _START_ al _END_ de _TOTAL_ registros",
      sInfoEmpty: "Mostrando del 0 al 0 de 0 registros",
      sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
      sInfoPostFix: "",
      sSearch: "Buscar:",
      sUrl: "",
      sInfoThousands: ",",
      sLoadingRecords: "Cargando...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior",
      },
      oAria: {
        sSortAscending:
          ": Activar para ordenar la columna de manera ascendente",
        sSortDescending:
          ": Activar para ordenar la columna de manera descendente",
      },
      buttons: {
        copy: "Copiar",
        colvis: "Visibilidad",
      },
    },
  });

  setFiltersEvents(table, id);
}

function setFiltersEvents(table, id){

    $(id+' thead tr:eq(1) th').each( function (i) {
      
      if($(this).children().length == 0){
        return;
      }

      let node = $(this).children()[0].nodeName;
      
      if(node == 'INPUT'){

        $( 'input', this ).on( 'keyup change', function () {
          if ( table.column(i).search() !== this.value ) {
              table
                  .column(i)
                  .search( this.value )
                  .draw();
          }
        });

      }else if(node == 'SELECT'){

        $( 'select', this ).on( 'change', function () {
          if ( table.column(i).search() !== this.value ) {
              table
                  .column(i)
                  .search( this.value )
                  .draw();
          }
        });

      }
    })
}

$(document).on("change", "input[name=perfil]", function(){
  let perfil = $(this).val();
  if(perfil == 'bautizado'){
    $(".content-fecha-bautismo").show();
    $(".content-fecha-bautismo #fecha_bautismo").attr('required', true);
  }else{
    $(".content-fecha-bautismo").hide();
    $(".content-fecha-bautismo #fecha_bautismo").attr('required', false);
  }
})





$(document).on('click', '.dsp-info', function(){
  let id = $(this).data('id');
  $(`#hermano_${id} .content-info`).slideToggle();
  $(`#hermano_${id}`).toggleClass('open')
})

$(document).on('click', '#desplegarTodos', function(){
  let desplegar = $(this).data('desplegar');
  if(desplegar){
    //preparamos la contraccion para el siguiente click
    $(this).data('desplegar', false);
    $(this).text('CONTRAER TODOS');

    //desplegamos y agregamos la clase pertinente
    $('.content-info').show();
    $('.info-hermano').addClass('open')
  }else{
    //preparamos el despliegue para el siguiente click
    $(this).data('desplegar', true);
    $(this).text('DESPLEGAR TODOS');

    //contraemos y agregamos la clase pertinente
    $('.content-info').hide();
    $('.info-hermano').removeClass('open')
  }
})

const templateNombres = () => {
  return `
    <label for="filterName"><strong>Nombre</strong></label>
    <div class="buttonInput">
    <div>
      <input type="text" id="filterName" placeholder="Escriba el nombre aquí">
      </div>
      <div>
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" id="filterNameButton">
          BUSCAR
      </button>
      </div>
    </div>
  `;
}
const templateSelect = (object) => {
  return `
  <label for="filterColumn"><strong>${filterColumnSelected.toUpperCase()}</strong></label>
  <select id="filterColumn">
    <option value="">Mostrar Todos</option>
    ${
      Object.entries(object).map((data) => {
        let [key, value] = data;
        return `<option value='${key}'>${value}</option>`
      }).join('')
    }
  </select>
  `;
}

let filterColumnSelected = '';

$(document).on('change', '#filterFor', function(){
  $('#contentFilterHermanos').html('');
  let value = $(this).val();

  if(value == ''){
    updateCards(hermanos);
    return;
  }

  let html = '';

  if(value == 'nombres'){
    html = templateNombres();
  }else if(value == 'grupo'){
    filterColumnSelected = 'grupo';
    html = templateSelect(grupos);
    
  }else if(value == 'perfil'){
    filterColumnSelected = 'perfil';
    html = templateSelect(perfiles);
  }

  $('#contentFilterHermanos').html(html);
  updateCards(hermanos);
})

const filterForColumn = (column, value) => {
  if(typeof hermanos == 'undefined'){
    return false;
  }

  return hermanos.filter(hermano => hermano[column].toLowerCase().indexOf(value)  >= 0);
}

const filterForName = (value) => {
  if(typeof hermanos == 'undefined'){
    return false;
  }

  return hermanos.filter(hermano => {
    let nombreCompleto = `${hermano.nombres} ${hermano.apellidos}`;
    let resultado = nombreCompleto.toLowerCase().indexOf(value.toLowerCase());
    return resultado !== -1;
  });
}

$(document).on('change', '#filterColumn', function(){
  let val = $(this).val();
  if(val == ''){
    updateCards(hermanos);
    return;
  }
  
  const hermanosFiltering = filterForColumn(filterColumnSelected, val);
  updateCards(hermanosFiltering);

})

$(document).on('click', '#filterNameButton', function(){
  let val = $('#filterName').val();
  if(val == ''){
    updateCards(hermanos);
    return;
  }

  const hermanosFiltering = filterForName(val);
  updateCards(hermanosFiltering);
})

const desplegarToast = (mensaje) => {
  const snackbarContainer = document.querySelector('#toast');
  const data = {
    message: mensaje,
    timeout: 3000
  };
  snackbarContainer.MaterialSnackbar.showSnackbar(data);
}

const updateCards = (hermanosFiltering) => {
  
  if(hermanosFiltering === false){
    return false;
  }

  $('.info-hermano').hide();

  if(hermanosFiltering.length === 0){
    desplegarToast('No hay resultados que coincidan con los filtros especificados');
    return;
  }
  
  hermanosFiltering.map((hermano) => {
    $(`#hermano_${hermano.id}`).fadeIn();
  })
}

$(document).ajaxStart(function() {
  $(".contet-loading").show();
});

$(document).ajaxComplete(function() {
  setTimeout(() => {
    $(".contet-loading").slideUp();
  }, 2000);
});

function initCalendar(){
  $( ".my-datepicker" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    yearRange: '1920:2030',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
  });
}

$(function () {
  setTimeout(() => {
    $(".contet-loading").slideUp();
  }, 2000);

  if(typeof login != "undefined" && login){
    swal("Escriba el usuario y la contraseña, luego presione el botón 'ENTRAR'", "", "warning");
  }

  initTable("#tableUsuarios");
  initTable("#tableTelefonos", false);
  

  initCalendar();
});
