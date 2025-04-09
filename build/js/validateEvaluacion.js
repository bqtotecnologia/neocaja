var myForm = document.getElementById("EvaluacionForm");
myForm.addEventListener('submit', function(event){
    event.preventDefault();

    var gruposRadios = document.querySelectorAll('input[type="radio"]:not(:disabled)');
    var gruposSinSeleccionar = [];
    var no_selected_enunciados = [];
    var selected_enunciados = [];

    gruposRadios.forEach(function(radio) {
        var grupo = radio.name;
        if (!gruposSinSeleccionar.includes(grupo) && !document.querySelector('input[name="' + grupo + '"]:checked')) {
            gruposSinSeleccionar.push(grupo);
            no_selected_enunciados.push('enu-' + grupo)
        }
        else{
            if(!selected_enunciados.includes('enu-' + grupo) && document.querySelector('input[name="' + grupo + '"]:checked'))
                selected_enunciados.push('enu-' + grupo)
        }
    });

    selected_enunciados.forEach((enunciado_id => {
        document.getElementById(enunciado_id).classList.remove('empty-field')
    }))

    var error = false;
    if (gruposSinSeleccionar.length > 0) {
        error = true
        // Al menos un grupo de radio input está vacío
        PrintError("Hay campos sin rellenar");
        document.getElementById(no_selected_enunciados[0])
        .scrollIntoView({
            behavior: 'smooth',
            block: 'center',
            inline: 'center'
        })

        no_selected_enunciados.forEach((enunciado_id) => {
            var enunciado_container = document.getElementById(enunciado_id)
            enunciado_container.classList.add('empty-field')
        })
    } else {
        const suspiciousRegex = /[<>/;\"'\-(){}\[\]$\\|&\?\=\¿\¡!]/;

        // Verificamos que no haya caracteres sospechosos
        var observacion = document.getElementById('observacion');
        if(observacion.value.length > 300){
            error = true;
            PrintError("La observación tiene demasiados caracteres")
        }

        if ((suspiciousRegex.test(observacion.value)) && error === false){
            error = true;
            PrintError('El campo observación contiene caracteres sospechosos: < > / \\ ; " ( ) { } [ ] $ & | ¿ ? ¡ ! - =' + "'")
        }

        if(!error){
            myForm.submit();
        }
    } 
});

function PrintError(error_message){
    var error_space = document.getElementById("error_message");
    error_space.innerHTML = error_message;    
}