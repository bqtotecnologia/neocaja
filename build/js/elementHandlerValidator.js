var myForm = document.getElementById("criterio-form");
myForm.addEventListener('submit', function(event){
    event.preventDefault()
    var error = false
    var element_name = myForm['element_name'].value
    const suspiciousRegex = /[<>/;\"'{}\[\]$\\|&\?\=\¿!]/;

    if(element_name === ''){
        error = true
        PrintError("Rellene todos los campos")
    }

    // Verificamos que no haya caracteres sospechosos
    if(element_name.length > 300 && error === false){
        error = true;
        PrintError("El campo nombre tiene demasiados caracteres")
    }

    if ((suspiciousRegex.test(element_name)) && error === false){
        error = true;
        PrintError('El campo nombre contiene caracteres sospechosos: < > / \\ ; " { } [ ] $ & | ¿ ? ! =' + "'")
    }
    if(error === false)
        myForm.submit()
})