@extends('layouts.master')
@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
   <div class="box">

                                <!-- DATA TABLE -->
                                <h3 class="title-5 m-b-35">Expenses Details</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <div class="rs-select2--light rs-select2--md">
                                            <select class="js-select2" name="property">
                                                <option selected="selected">All Properties</option>
                                                <option value="">Option 1</option>
                                                <option value="">Option 2</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <div class="rs-select2--light rs-select2--sm">
                                            <select class="js-select2" name="time">
                                                <option selected="selected">Today</option>
                                                <option value="">3 Days</option>
                                                <option value="">1 Week</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <button class="au-btn-filter">
                                            <i class="zmdi zmdi-filter-list"></i>filters</button>
                                    </div>
                                    <div class="table-data__tool-right">
                                 
                                        <a  onclick="addForm()" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                            <i class="zmdi zmdi-plus"></i>New Expenses</a>

                                        <div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                                            <select class="js-select2" name="type">
                                                <option selected="selected">Export</option>
                                                <option value="">Option 1</option>
                                                <option value="">Option 2</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                           <form action="exportexpenses" method="POST" enctype="multipart/form-data">
                           {{ csrf_field() }} {{ method_field('POST') }}
                            <div class="row">
                            <div class="col-md-3">
                            <label for="from" class="col-form-label">From</label>
                             <input type="date" class="form-control input-md" id="from" name="from" required>
                             </div>
              
                              <div class="col-md-3">
                              <label for="from" class="col-form-label">To</label>
                              <input type="date" class="form-control input-md" id="to" name="to"  required>
                              </div>
                        
                             <div class="col-md-3">
                            <div class="form-group">
                            <label>Category</label>
                            <select id="expenses_id" name="expenses_id"  class="form-control" >
                            <option disabled>--select group--</option>
                            <option value="all">All</option>
                            @foreach($expenses as $i)
                            <option value="{{$i->id}}">{{$i->account_name}}</option>
                            @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                        </div>
                              <div class="col-md-3" style="margin-top:28px;">
                            <button type="submit" class="btn btn-primary btn-md" name="search" >Export Report</button>

                             </div>
                
                              </div>
                                </form>
                             <br>
                    <div class="table-responsive table-striped  table-responsive">
                    <table id="user-table"  class="table  table-striped table-data2">
                     <thead>
                     <tr>
                     <th>Date</th>
                         <th>Category</th>
                    <th>Description</th>
                    {{--<th>From Account(Dr)</th>--}}
                
                    <th>Amount</th>
                   
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('expensive.form')
@endsection

@section('bot')

    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

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
        var table = $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/ExpensiveApi') }}",
            columns: [
                {data: 'expensive_date', name: 'expensive_date'},
                // {data: 'from_account', name: 'from_account'},
                {data: 'account_name', name: 'account_name'},
                {data: 'description', name: 'description'},
                {data: 'amount', name: 'amount'},
              
                {data: 'action', name: 'action', orderable: true, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $(".subBtn").attr("disabled",false);
            $('input[name=_method]').val('POST');
            $('#modal-expensive-form').modal('show');
            $('#form-expensive')[0].reset();
            $('.modal-title').text('Add Expenses');

              {{--
                var account_id=$('#account_id').val();
            $.ajax({
                url:"check_balance/"+account_id,
                cache:false,
                success: function(html) {            
                $('#account_balance').val('');
                $('#account_balance').val(html.data[0].account_balance);
              }
     
           })--}}
        }
       {{--
        $(document).change('#account_id',function(){
            var account_id=$('#account_id').val();
            $.ajax({
                url:"check_balance/"+account_id,
                cache:false,
                success: function(html) {            
                $('#account_balance').val('');
                $('#account_balance').val(html.data[0].account_balance);
              }
     
           })
         });
           $("#amount").on('keyup',function(){
            if(Number($('#amount').val()>Number($('#account_balance').val()))){
                alert("Please Balance is not enough");
                $('#amount').val("");
            }
             
               })
               --}}
        function editForm(id) {
            $(".subBtn").attr("disabled",false);
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#form-account')[0].reset();
            $.ajax({
                url: "{{ url('Expensive') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(html) {
                   $('#modal-account-form').modal('show');
                    $('.modal-title').text('Edit Account');
                    $('#id').val(html.data.id);
                    $('#account_name').val(html.data.account_name);
                    $('#account_group').val(html.data.account_group);
                    $('#account_type').val(html.data.account_type);
                  
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('Expensive') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }

        $(function(){
            $('#form-expensive').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('Expensive') }}";
                    else url = "{{ url('Expensive') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#form-expensive').serialize(),
                        data: new FormData($("#form-expensive")[0]),
                        contentType: false,
                        processData: false,
                        beforeSend:function(){
                        $(".subBtn").attr("disabled",true);
                        },
                        success : function(data) {
                            $('#modal-expensive-form').modal('hide');
                            $(".subBtn").attr("disabled",false);
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            $(".subBtn").attr("disabled",false);
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
