var confirm_forms = document.getElementsByClassName("confirm-form");

Array.from(confirm_forms).forEach(function(form){  
    form.addEventListener("submit", (event) => {
        event.preventDefault()

        Swal.fire({
          title: "¿Desea realizar esta acción?",
          icon:'question',
          showDenyButton: true,
          confirmButtonText: "Sí",
          denyButtonText: "No"
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit()
          }
        });
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

  async function TryFetch(url, fetchConfig){
    let result = await GoFetch(url, fetchConfig)
    

    if(typeof result === "string" ){
      Swal.fire({
        title: "Ha ocurrido un error inesperado, contacte con el personal de tecnología.",
        icon:'error',
        confirmButtonText: "Ok",
        text: result
      })
    }

    return result
  }

  async function GoFetch(url, fetchConfig) {
    try {
      const response = await fetch(url, fetchConfig);
      const json = await response.json();
      result = json
      console.log('recieved data:')
      console.log(result)

      if(result.status === false)
        result = result.message

    } catch (error) {      
      result = error.message
    }
    return result
  }
