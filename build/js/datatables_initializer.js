var datatables_ids = [
    'my-datatable',
    'my-datatable-buttons',
]

$('my-datatable').DataTable({    
    responsive: true
}); 

$('my-datatable-buttons').DataTable({
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

$('#products-table').DataTable({    
    language: spanish,
    dom: "Blfrtip",
        buttons: [
            {
                extend: "excel",
                className: "btn-sm"
            },
        ],
    columnDefs: [{
        targets: [3,4],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      }],
    responsive: true
    
}); 

$('#products-history-table').DataTable({    
    language: spanish,
    dom: "Blfrtip",
        buttons: [
            {
                extend: "excel",
                className: "btn-sm"
            },
        ],
    columnDefs: [{
        targets: [3],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      }],
    responsive: true
    
}); 

