const monthNumberToName = {
  '1': 'Enero',
  '2': 'Febrero',
  '3': 'Marzo',
  '4': 'Abril',
  '5': 'Mayo',
  '6': 'Junio',
  '7': 'Julio',
  '8': 'Agosto',
  '9': 'Septiembre',
  '10': 'Octubre',
  '11': 'Noviembre',
  '12': 'Diciembre',
}    

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

      if(result.status === false)
        result = result.message

    } catch (error) {      
      result = error.message
    }
    return result
  }

  function ForceUserToConfirmWritting(confirmText){
      var message = 'Para llevar a cabo la acción, se requiere que escriba exactamente el siguiente texto "' + confirmText + '"'
      var result = prompt(message)

      return result === confirmText
  }

  function GetMonthNameByNumber(number){
    var strNumber = String(number)
    return monthNumberToName[strNumber]
  }

  function GetMonthNumberByName(name){
    return Object.keys(monthNumberToName).find(key => monthNumberToName[key] === String(name));
  }

  function GetPrettyCiphers(cipher){
      buffer = parseFloat(cipher).toFixed(2)
      var strCipher = String(buffer).replace('.', ',')
      var splits = strCipher.split(',')
      var intPart = splits[0]
      var decimalPart = splits[1]
      

      var finalStr = ''
      var positionCount = 0
      for(let i = intPart.length - 1; i >= 0; i--){
          var display = intPart[i]

          positionCount++
          if(positionCount === 4)
              display = display + '.'

          finalStr = display + finalStr
      }

      if(decimalPart !== undefined)
          finalStr = finalStr + ','  + decimalPart
      return finalStr
  }