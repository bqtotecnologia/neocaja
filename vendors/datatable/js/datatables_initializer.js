new DataTable('#generic_datatable', {
    language: spanish,
    order:[],
});

new DataTable('#purchase_datatable', {
    language: spanish,
    order:[],
    columnDefs: [{
        targets: 3,
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      }]
});

new DataTable('#movements_datatable', {
    language: spanish,
    order:[],
    columnDefs: [{
        targets: 2,
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      }]
});

new DataTable('#movements_datatable_export', {
    language: spanish,
    layout: {
        topStart: {
            buttons: ['excel']
        }
    },
    order:[],
    columnDefs: [{
        targets: 2,
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss')
      }]
});

