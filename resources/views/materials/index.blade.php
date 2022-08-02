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
    <h3 class="title-5 m-b-35">Materials Details</h3>  
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
                <th>Type</th>
                <th>Category</th>
                <th>Cost/Measurement</th>
                <th>Measurement</th>
                <th>Availabale</th>
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
@include('materials.form');



@endsection

@section('bot')



    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>
    <script src=" {{ asset('assets/bower_components/datatables.net/js/dataTables.rowReorder.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.responsive.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.select.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.dateTime.min.js') }} "></script>


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
                    rowReorder: {
                        selector: 'td:nth-child(3)'
                    },
                    "autoWidth": false,
                    // serverSide: true,
                    dom:'lBfrtip',
                    "scrollCollapse": true,
                        buttons: ['pdf', 'excel'],
                    "lengthMenu": [5,10,25,50,100],
                    
                    responsive: true,
                ajax:  "{{ url('apiMaterial') }}",
                columns: [
                
                    {data: 'name', name: 'name'},
                    {data: 'type', name: 'type'},
                    {data: 'category_name', name: 'category'},
                    {data: 'unit_cost', name: 'cost'},
                    {data: 'measurement', name: 'measurement'},
                    {data: 'available', name: 'available'},
                    {data: 'created_at', name: 'date'}, 
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                
                ]
            });
            $('#add_btn').on('click',function(){
        
                save_method = "add";
                $('input[name=_method]').val('POST');
                $('#modal-form').modal('show');
                $('#form-item')[0].reset();
                $('.modal-title').text('Add Material');
        
            });


    function editForm(id) {
        save_method = 'edit';
           
            $('input[name=_method]').val('PATCH');
            $('#form-item')[0].reset();
            $.ajax({
                url:"{{ url('materials') }}" + '/' + id + "/edit",
                
                success: function(html) {
                    console.log(JSON.parse(JSON.stringify(html[0])))
                    let data =JSON.parse(JSON.stringify(html[0]))
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Material');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#measurement_id').val(data.measurement_id);
                    $('#category_id').val(data.material_category_id);
                    $('#unit_cost').val(data.unit_cost);
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
                    url : "{{ url('materials') }}" + '/' + id,
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
                e.preventDefault();
                if (e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('materials') }}";
                    else url = "{{ url('materials') . '/' }}" + id;
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
                                            text: data.message.errorInfo,
                                            type: 'error',
                                            timer: '3000'
                                        })
                                    }
                                }else{
                                swal({
                                title: 'Oops...',
                                text: "internal server error",
                                type: 'error',
                                timer: '3000'
                            })

                                }
                            }
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
