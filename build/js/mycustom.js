var confirm_forms = document.getElementsByClassName("confirm-form");

Array.from(confirm_forms).forEach(function(form){
    form.addEventListener("submit", (event) => {
        event.preventDefault()
        if (confirm("¿Estás seguro de borrar este usuario?"))
            form.submit()
    })
});

$(document).ready(function () {
      $(".select2").select2({width:'100%'});
  });

  
function ExportToPDF(){
    // Mostramos los elementos ocultos
    $("#export_as_pdf .print-hidden").css({
    display: "flex",
    visibility: "visible",
    opacity: "1",
    });
    
    // Creamos el pdf
    $("#export_as_pdf").print({
    addGlobalStyles: true,
    stylesheet: null,
    rejectWindow: true,
    noPrintSelector: ".no-print",
    iframe: true,
    append: null,
    prepend: null,
    });

    // Volvemos a ocultar los elementos ocultos
    $("#export_as_pdf .print-hidden").css({
    display: "none",
    visibility: "hidden",
    opacity: "0",
    });
  }

  function PrintError(error){
    document.getElementById('error-displayer').innerHTML = error
  }