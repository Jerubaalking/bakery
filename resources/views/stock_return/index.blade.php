@extends('layouts.master')
@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"> Task Details</h3>
        </div>

        <div class="box-header">
            <button class='btn btn-primary' id='add_btn'>Create New</button>
        
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <div class="table-responsive">
              <table id="task-table" class="table table-striped">
                <thead>
                <tr>
                <th>Supplier Name</th>
                <th>Supplier Number</th>
              
                <th>Total Amount</th>
                <th>Amount Paid</th>
                <th>Amount due</th>
                 <th>Date</th>
                <th><th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        </div>
        <!-- /.box-body -->
        
  

    <div class="box col-md-6">
  
    {{--<div class="box-header">--}}
    {{--<a onclick="addForm()" class="btn btn-primary">Create</a>--}}
    <!-- {{--<a href="" class="btn btn-danger">Export PDF</a>--}}
    {{--<a href="" class="btn btn-success">Export Excel</a>--}} -->
    {{--</div>--}}   
    </div>




@endsection

@section('bot')




    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>


    <!-- InputMask -->
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('assets/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('assets/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    {{--<script>--}}
    {{--$(function () {--}}
    {{--$('#items-table').DataTable()--}}
    {{--$('#example2').DataTable({--}}
    {{--'paging'      : true,--}}
    {{--'lengthChange': false,--}}
    {{--'searching'   : false,--}}
    {{--'ordering'    : true,--}}
    {{--'info'        : true,--}}
    {{--'autoWidth'   : false--}}
    {{--})--}}
    {{--})--}}
    {{--</script>--}}

    <script>
        $(function () {

            //Date picker
            $('#tanggal').datepicker({
                autoclose: true,
                // dateFormat: 'yyyy-mm-dd'
            })

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false
            })
        })
    </script>

    <script type="text/javascript">
         var table = $('#task-table').DataTable({
            processing: true,
            serverSide: true,
            ajax:  "{{ url('apiTask') }}",
            columns: [
                {data: 'first_name', name: 'first_name'},
                {data: 'employee_number', name: 'employee_number'},
                {data: 'sub_total', name: 'sub_total'},
                {data: 'amount_paid', name: 'amount_paid'},
                {data: 'amount_due', name: 'amount_due'}, 
                {data: 'created_at', name: 'created_at'}, 
                // {data: 'adds', name: 'adds'}, 
          
                {data: 'action', name: 'action', orderable: false, searchable: false}
               
            ]
        });
     $(document).on('click','#add_btn',function(){
  
          save_method = "add";
          $('input[name=_method]').val('POST');
          $('#modal-form').modal('show');
          $('#modal-form form')[0].reset();
          $('.modal-title').text('Tasks Form');
          $('#expensive').html('');
          add_expensive_row();
          calculate(0,0);

    
  
        });

        

 $("#expensive").delegate("#qty","keyup",function(){
    var tr = $(this).parent().parent();


   // tr.find("#amt").html(qty.val() * tr.find(".price").val());
    tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
     
})
$("#expensive").delegate("#price","keyup",function(){
    var tr = $(this).parent().parent();

   // tr.find("#amt").html(qty.val() * tr.find(".price").val());
    tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
     
})






        $(function(){
            $('#form-labour').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                 
                    if (save_method == 'add') url = "{{ url('labour1') }}";
                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#form-labour")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-labour').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
      });


        $(function(){
            $('#form-item').validator().on('submit', function (e) {
      
            
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('task') }}";
                    else url = "{{ url('task') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#form-item")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });

        });

        $(function(){
            $('#form-item-payment').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                 
                    if (save_method == 'add') url = "{{ url('payment1') }}";
           

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#form-item-payment")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal_payment').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });

        });
    </script>
   
@endsection
