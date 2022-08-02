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
    <h3 class="title-5 m-b-35">Production Material store</h3>  
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
                <th>Batch</th>
                <th>Material</th>
                <th>Category</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Cost/Measurement</th>
                <th>Gen. Cost</th>
                <th>comment</th>
                <th>date</th>
                <th></th>
                </tr>
                </thead>
                <tbody></tbody>
             </table>
           </div>
         
        </div>
        <!-- /.box-body -->
    </div>
@include('intoStore.form');



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
    {{--'searching'   : true,--}}
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
            ajax:  "{{ url('apiIntoStore') }}",
            columns: [
            
                {data: 'batch_number', name: 'batch'},
                {data: 'name', name: 'material', ordeable:true},
                {data: 'category_name', name: 'category'},
                {data: 'type', name: 'type'},
                {data: 'qty', name: 'quantity'},
                {data: 'symbol', name: 'Unit'},
                {data: 'unit_cost', name: 'cost/unit'},
                {data: 'sam', name: 'gen. cost'},
                {data: 'comments', name: 'comments'},
                {data: 'created_at', name: 'created_at'}, 
                {data: 'action', name: 'action', orderable: false, searchable: false}
            
            ]
        });
        $('#add_btn').on('click',function(){
    
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#form-item')[0].reset();
            $('.modal-title').text('Stock Material');
            getBatch();
            $('#materials').html('');
            add_expensive_row();
        });

        function add_expensive_row(count_charge = ''){
            var tr = $(this).parent().parent();
                var html = '';
                html += '<span class="col-md-12" id="row'+count_charge +'"><div class="row" style="margin-top:5px;">';
                html += '<div class="col-md-3">';
                html +=  '<label>Materials</label>';
                html += '<select  name="material_id[]" id="material_id[]" class="form-control"><option disabled>--select material--</option> @foreach($materials as $x)<option value="{{$x->id}}">{{$x->type}} {{$x->name}} in {{$x->symbol}}</option>@endforeach</select>';
                html += '<span class="help-block with-errors"></span>';
                html += '</div>';   
                html += '<div class="col-md-2">';
                html +=  '<label>Quantity</label>';
                html += '<input type="text" name="qty[]"  id="qty[]" class="form-control" required placeholder="qty" value="1"/>';

                html += '</div>';
                html += '<div class="col-md-3">';
                html +=  '<label>Comment</label>';
                html += '<textarea type="text" name="comments[]"  id="comments[]" class="form-control" required placeholder="comment" ></textarea>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html +=  '<label>action</label><br>';
                if(count_charge == '')
                {
                    html += '<button type="button" name="more_charge" id="more_charge"  onclick="add_expensive_row(1)" class="btn btn-success btn-xs">+</button>';
                }
                else
                {
                    html += '<button type="button" name="remove" id="'+count_charge +'" class="btn btn-danger btn-xs remove">-</button>';
                }
                html += '</div>';
                html += '</div></div><br /></span>';
            
                $('#materials').append(html);
        }
        function edit_expensive_row(count_charge = ''){
            var tr = $(this).parent().parent();
                var html = '';
                html += '<span class="col-md-12" id="row'+count_charge +'"><div class="row">';
                html += '<div class="col-md-3">';
                html +=  '<label>Materials</label>';
                html += '<select  name="material_id[]" id="material_id[]" class="form-control"><option disabled>--select material--</option> @foreach($materials as $x)<option value="{{$x->id}}">{{$x->type}} {{$x->name}} in {{$x->symbol}}</option>@endforeach</select>';
                html += '<span class="help-block with-errors"></span>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html +=  '<label>Date</label>';
                html += '<input type="date" class="form-control stock" name="date[]"  id="date[]" required/>';

                html += '</div>';
                html += '<div class="col-md-2">';
                html +=  '<label>Quantity</label>';
                html += '<input type="text" name="qty[]"  id="qty[]" class="form-control" required placeholder="qty" value="1"/>';

                html += '</div>';
                html += '<div class="col-md-3">';
                html +=  '<label>Comment</label>';
                html += '<textarea type="text" name="comments[]"  id="comments[]" class="form-control" required placeholder="comment" ></textarea>';
                html += '</div>';
                html += '</div></div><br /></span>';
            
                $('#materials').append(html);
        }

        $(document).on('click', '.remove', function(){
      
            var tr = $(this).parent().parent();
            
            // tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
            // calculate(0,0);
            var row_no = $(this).attr("id");
            $('#row'+row_no).html('');
            $('#row'+row_no).remove();
            tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
                calculate(0,0);
        });

        function editForm(id) {
            save_method = 'edit';
            
                $('input[name=_method]').val('PATCH');
                $('#form-item')[0].reset();
                $.ajax({
                    url:"{{ url('intoStore') }}" + '/' + id + "/edit",
                    
                    success: function(html) {

                        $('#modal-form').modal('show');
                        $('.modal-title').text('Edit Material');
                        $('#materials').html('');
                        edit_expensive_row();
                        $('#id').attr('value',html.data.id);
                        $('#batch_number').attr('value',html.data.batch_number);
                        $('#material_id').attr('value', html.data.material_id);
                        $('#qty').attr('value', html.data.qty);
                        $('#comments').attr('value',html.data.comments);
                        $('#date').attr('value',html.data.date);
                    },
                    error : function() {
                        alert("Nothing Data");
                    }
                });
    
        }
        function getBatch(){
            var theRandomNumber = `BCH-IN${Date.now()+Math.floor(Math.random() * 99999999999) + 111111000000}`;
            $('#batch_number').attr('value',theRandomNumber);
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
                    url : "{{ url('intoStore') }}" + '/' + id,
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
                    if (save_method == 'add') url = "{{ url('intoStore') }}";
                    else url = "{{ url('intoStore') . '/' }}" + id;
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
