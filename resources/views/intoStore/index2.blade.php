<script>
    var editor; // use a global for the submit and return data rendering in the examples
    let colums = [
                    
                    {data: 'batch_number', name: 'batch_name'},
                    {data: 'name', name: 'name'},
                    {data: 'category_name', name: 'category_name'},
                    {data: 'type', name: 'type'},
                    {data: 'status', name: 'status'},
                    {data: 'qty', ender: $.fn.dataTable.render.number( ',', '.', 0, 'TZS' ), name: 'qty'},
                    {data: 'symbol', name: 'symbol'},
                    {data: 'unit_cost', name: 'unit_cost'},
                    {data: 'sam', name: 'sam'},
                    {data: 'comments', name: 'comments'},
                    {data: 'created_at', name: 'created_at'},
                    {
                        data: null,
                        defaultContent: '<i class="fa fa-pencil"/>',
                        className: 'row-edit dt-center',
                        orderable: false
                    },
                    {
                        data: null,
                        defaultContent: '<i class="fa fa-trash"/>',
                        className: 'row-remove dt-center',
                        orderable: false
                    },
                    // {data: 'action', name: 'action', orderable: false, searchable: false}
                
    ]
        let fields =  [ {
                 label: "Batch:",
                 name: "batch_number"
             }, {
                 label: "Name:",
                 name: "name"
             }, {
                 label: "Category:",
                 name: "category_name"
             }, {
                 label: "Type:",
                 name: "type"
             }, {
                 label: "Status:",
                 name: "status"
             }, {
                 label: "Qty:",
                 name: "qty"
             }, {
                 label: "Symbol:",
                 name: "status"
             }, {
                 label: "Unit cost:",
                 name: "unit_cost"
             }, {
                 label: "Gen Cost:",
                 name: "sam"
             }, {
                 label: "Comment:",
                 name: "comments"
             },{
                 label: "Start date:",
                 name: "start_date",
                 type: "datetime"
             }, 
        ]


 function datatableSain(router,tableId,fields, colums) {

     editor = new $.fn.dataTable.Editor( {
         ajax: router,
         table: tableId,
         fields: fields,
     } );
  
     // Activate an inline edit on click of a table cell
     $(tableId).on( 'click', 'tbody td.row-edit', function (e) {
         editor.inline( table.cells(this.parentNode, '*').nodes(), {
             submitTrigger: this,
             submitHtml: '<i class="fa fa-play"/>'
         } );
     } );
  
     // Delete row
     $(tableId).on( 'click', 'tbody td.row-remove', function (e) {
         editor.remove( this.parentNode, {
             title: 'Delete record',
             message: 'Are you sure you wish to delete this record?',
             buttons: 'Delete'
         } );
     } );
  
     var table = $(tableId).DataTable( {
         dom: "Bfrtip",
         ajax: "../php/staff.php",
         order: [[ 1, 'asc' ]],
         columns: [
             {
                 data: null,
                 defaultContent: '',
                 className: 'select-checkbox',
                 orderable: false
             },
             { data: "first_name" },
             { data: "last_name" },
             { data: "position" },
             { data: "office" },
             { data: "start_date" },
             { data: "salary", render: $.fn.dataTable.render.number( ',', '.', 0, '$' ) },
             {
                 data: null,
                 defaultContent: '<i class="fa fa-pencil"/>',
                 className: 'row-edit dt-center',
                 orderable: false
             },
             {
                 data: null,
                 defaultContent: '<i class="fa fa-trash"/>',
                 className: 'row-remove dt-center',
                 orderable: false
             },
         ],
         select: {
             style:    'os',
             selector: 'td:first-child'
         },
         buttons: [
             { extend: "create", editor: editor },
             { extend: "edit",   editor: editor },
             { extend: "remove", editor: editor }
         ]
     } );
 };
</script>