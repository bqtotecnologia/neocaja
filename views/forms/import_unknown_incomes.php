<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';


include_once '../common/header.php';

?>

<div class="row justify-content-center">
    <section class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../views/panel.php'; include_once '../layouts/backButton.php'; ?>
    </section>
    
    <section class="col-12 justify-content-center px-5 mt-4">            
        <div class="x_panel row col-12 justify-content-center m-0 p-2 py-3">
            <div class="col-12">
                <h1 class="h1 text-center">Importar ingresos no identificados</h1>
            </div>

            <div class="row col-12 m-0 p-0 mt-5 text-center align-items-center justify-content-center">
                <label class="h5 align-middle m-0 align-middle col-5 text-right" for="">Subir archivo</label>
                <input id="fileInput" class="col-7 h5 m-0" type="file" accept=".xlsx, .xls">
            </div>

            <div class="row col-12 m-0 p-0 mt-5 text-center align-items-center justify-content-center d-none" id="example-container">
                <h2 class="h2 text-center">
                    Registro seleccionado aleatoriamente
                </h2>
                <table class="col-10 table table-bordered h6">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Monto</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td id="date-example"></td>
                        <td id="ref-example"></td>
                        <td id="price-example"></td>
                        <td id="description-example"></td>
                    </tbody>
                </table>

                <div class="row col-12 m-0 p-0 justify-content-center align-items-center">
                    <button class="btn btn-success" onclick="ConfirmRegister()">
                        Registrar todo
                    </button>
                </div>
            </div>

            <div class="row col-12 m-0 p-0 mt-5 text-center align-items-center justify-content-center">
                <?php include_once '../common/partials/loading_icon.php'; ?>
            </div>

        </div>
    </section>
</div>

<script>    
    let paymentsData = []
    const exampleContainer = document.getElementById('example-container')
    document.getElementById('fileInput').addEventListener('change', function(e) {        
        paymentsData = []        
        ShowLoading()
        const file = e.target.files[0];
        if (!file) {
            HideLoading()
            return;
        }
        HideTestRow()

        const reader = new FileReader();

        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });

            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];

            const rows = XLSX.utils.sheet_to_json(worksheet, { defval: "" });

            const result = rows.map(row => ({
                    date: row['FECHA'] || row['Fecha'] || row['fecha'] || '',
                    price: row['MONTO BS.'] || row['Monto Bs.'] || row['monto bs.'] || '',
                    ref: row['REFERENCIA'] || row['Referencia'] || row['referencia'] || '',
                    description: row['DESCRIPCIÓN'] || row['Descripción'] || row['descripción'] || row['DESCRIPCION'] || row['Descripcion'] || row['descripcion'] || '',
            }));

            const invalidRows = result.filter(
                r => !r.date || !r.price || !r.ref || !r.description
            );

            var error = ''

            result.forEach((r) => {
                if(r.date.length !== 8){
                    error = 'Se encontró una fecha inválida: ' + r.date
                    return
                }
            })

            if (invalidRows.length > 0) {
                error = 'Existen campos vacíos en el archivo Excel'
            }

            if(error !== ''){
                if(result.length < 1)
                    error = 'El archivo excel está vacío'
            }

            if(error !== ''){
                Swal.fire({
                    title: "Error",
                    icon:'error',
                    html: error
                })
            }
            else{
                paymentsData = result
                var randomIdx = Math.floor(Math.random() * result.length);
                DisplayTestRow(result[randomIdx])
            }
            
            HideLoading()
            console.log(result)
        };

        reader.readAsArrayBuffer(file);
    });

    async function ConfirmRegister(){
        var message = "¿Desea registrar " + paymentsData.length + " ingresos no identificados?"
        var response = await Swal.fire({
            title: "Confirmación",
            html: message,
            showCancelButton: true,
            confirmButtonText: "Proceder",
            denyButtonText: 'Cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            }).then((result) => {
            if (result.isConfirmed) {
                return true
            }else{
                return false
            }
        });

        if(response)
            await TryRegister()
    }

    async function TryRegister(){
        if(paymentsData.length < 1){
            Swal.fire({
                title: "Error",
                icon:'error',
                html: 'No se ha cargado el archivo excel'
            })
            return
        }

        var data = {
            'payments': paymentsData
        }

        const url = '<?= $base_url ?>/api/import_unknown_incomes.php'

        var fetchConfig = {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        }

        result = await TryFetch(url, fetchConfig)

        if(result.status === true){
            url = '<?= $base_url ?>/views/panel.php?message=' + result.message
            window.location.href = url
        }
    }

    function ShowLoading(){
        console.log(' ')
        document.getElementById('loading-container').classList.remove('d-none')
    }

    function HideLoading(){
        console.log(' ')
        document.getElementById('loading-container').classList.add('d-none')
    }

    function HideTestRow(){
        exampleContainer.classList.add('d-none')
    }

    function DisplayTestRow(payment){
        document.getElementById('date-example').innerHTML = payment.date
        document.getElementById('price-example').innerHTML = payment.price
        document.getElementById('description-example').innerHTML = payment.description
        document.getElementById('ref-example').innerHTML = payment.ref

        exampleContainer.classList.remove('d-none')
    }

    function ToggleLoading(){
        const loadingElement = document.getElementById('loading-container')
        if(loadingElement.classList.contains('d-none'))
            loadingElement.classList.remove('d-none')
        else 
            loadingElement.classList.add('d-none')
    }
</script>

<?php include_once '../common/footer.php'; ?>