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

$('.datatable-date-2').DataTable({    
    language: spanish,
    dom: "Blfrtip",
        buttons: [
            {
                extend: "excel",
                className: "btn-sm"
            },
        ],
    columnDefs: [{
        targets: [2],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      }],
    responsive: true
    
}); 

$('.datatable-date-2-datetime-5').DataTable({    
    language: spanish,
    dom: "Blfrtip",
        buttons: [
            {
                extend: "excel",
                className: "btn-sm"
            },
        ],
    columnDefs: [
      {
        targets: [5],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      },
      {
        targets: [2],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY')
      }
    ],
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

$('.datatable-date-4').DataTable({    
    language: spanish,
    dom: "Blfrtip",
        buttons: [
            {
                extend: "excel",
                className: "btn-sm"
            },
        ],
    columnDefs: [{
        targets: [4],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      }],
    responsive: true,
    scrollX: true,
    
}); 

$('.datatable-date-5').DataTable({    
    language: spanish,
    dom: "Blfrtip",
        buttons: [
            {
                extend: "excel",
                className: "btn-sm"
            },
        ],
    columnDefs: [{
        targets: [5],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      }],
    //responsive: true,
    scrollX: true,
    
}); 

$('.datatable-date-6').DataTable({    
    language: spanish,
    dom: "Blfrtip",
        buttons: [
            {
                extend: "excel",
                className: "btn-sm"
            },
        ],
    columnDefs: [{
        targets: [6],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      }],
    responsive: true
    
}); 