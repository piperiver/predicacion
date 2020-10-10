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
    if (!response.status) {
      bloquear_botones();
      swal(response.message, "", "error");
      return;
    }

    if (!response.data) {
      bloquear_botones();
      $("#text-telefono").text("No hay más números");
      swal(response.message, "", "warning");
      return;
    }

    $("#content-text-estado").hide();
    desbloquear_botones();
    telefono = response.data;

    swal(response.message, "", "success").then((value) => {
      $("#text-telefono").fadeOut(function () {
        $("#text-telefono").text(telefono.telefono);
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

$(function () {
  $(".myTable").DataTable({
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo:
        "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
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
});
