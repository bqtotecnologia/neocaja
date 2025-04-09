var datatables_ids = [
    'coordinador',
    'docente',
    'temporal',
    'fijo'
]
datatables_ids.forEach(function(id) {
    $("#datatable-" + id).DataTable({
        dom: "Blfrtip",
        buttons: [
            {
                extend: "excel",
                className: "btn-sm"
            },
            {
                extend: "pdfHtml5",
                className: "btn-sm"
            }
        ],
        responsive: true
    }); 
});
