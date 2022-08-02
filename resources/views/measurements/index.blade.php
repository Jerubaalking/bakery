@extends('layouts.master')
@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
    <div class="box">     
    <h3 class="title-5 m-b-35">Measurement Details</h3>  
         <div class="box-body" style="margin-top:-25px;"> 
         <div> 

         </div> 
            <div class="table-data__tool " >
                    <div class="table-data__tool-right col-md-12">
                                 
                                 <a  id="add_btn" style="float:right"; class="au-btn au-btn-icon au-btn--green au-btn--small">
                                     <i class="zmdi zmdi-plus"></i>Add</a>
                    </div>
                                  
            </div>
           <div class="table-responsive table-striped  table-responsive">
           <table id="task-table" class="table  table-striped table-data2">
                <thead>
                <tr>
              
                <th>Name</th>
                <th>Symbol</th>
                <th>Type</th>
                <th>Description</th>
                <th>Date</th>
                <th></th>
                
                </tr>
                </thead>
                <tbody></tbody>
             </table>
           </div>
         
        </div>
        <!-- /.box-body -->
    </div>
@include('measurements.form');



@endsection

@section('bot')



    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>


    <!-- InputMask -->
    <script src="{{ asset('js/submition.js') }}"></script>
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
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>
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

 
    <script type="text/javascript">
            var table = $('#task-table').DataTable({
                processing: true,
                serverSide: true,
                dom:'lBfrtip',
                "ScrollX": "100%",
                "scrollCollapse": true,
                    buttons: [
                    'excel', 'pdf', 'print'
                ],
                "lengthMenu": [5,10,25,50,100],
                ajax:  "{{ url('apiMeasurements') }}",
                columns: [
                
                    {data: 'measurement', name: 'name'},
                    {data: 'symbol', name: 'symbol'},
                    {data: 'type', name: 'type'},
                    {data: 'description', name: 'description'},
                    {data: 'created_at', name: 'created_at'}, 
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                
                ]
            });
            $('#add_btn').on('click',function(){
        
                save_method = "add";
                $('input[name=_method]').val('POST');
                $('#modal-form').modal('show');
                $('#form-item')[0].reset();
                $('.modal-title').text('Add Measurement');
        
            });


    function editForm(id) {
        
        save_method = 'edit';
           
            $('input[name=_method]').val('PATCH');
            $('#form-item')[0].reset();
            $.ajax({
                url:"{{ url('measurements') }}" + '/' + id + "/edit",
                
                success: function(html) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Measurement');
                    $('#id').val(html.data.id);
                    $('#measurement').val(html.data.measurement);
                    $('#symbol').val(html.data.symbol);
                    $('#type').val(html.data.type);
                    $('#description').val(html.data.description);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
 
     }
         
        function deleteData(id){
            console.log(id)
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "Delete measurement!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('measurements') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        if(data.success){
                            
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                        }else{
                            console.log(data)
                            swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '5500'
                        })
                        }
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '5500'
                        })
                    }
                });
            });
        }
   

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                e.preventDefault()  
                if (e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('measurements') }}";
                    else url = "{{ url('measurements') . '/' }}" + id;
                    $.ajax({
                        url : url,
                        type : "POST",
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        beforeSend:function(){
                        $(".subBtn").attr("disabled",true);
                        },
                        success : function(data) {
                            
                            console.log(data)
                            if(data.success){
                                
                                $('#modal-form').modal('hide');
                                table.ajax.reload();
                                swal({
                                    title: 'Success!',
                                    text: data.message,
                                    type: 'success',
                                    timer: '1500'
                                })
                            }else{
                                if(data.message.errorInfo){
                                    if(data.message.errorInfo[2].includes('Duplicate')){
                                        swal({
                                            title: 'Oops...',
                                            text: "Duplicate data!",
                                            type: 'error',
                                            timer: '3000'
                                        })
                                    }else{
                                        swal({
                                            title: 'Oops...',
                                            text: data.message,
                                            type: 'error',
                                            timer: '3000'
                                        })
                                    }
                                }else{
                                    swal({
                                        title: 'Oops...',
                                        text: data.message,
                                        type: 'error',
                                        timer: '3000'
                                    })

                                }
                            }
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data,
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
