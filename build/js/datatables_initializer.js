$('.my-datatable-buttons').DataTable({
    language: spanish,
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

$('.datatable-date-3-4').DataTable({    
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

$('.datatable-date-3').DataTable({    
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