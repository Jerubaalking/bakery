@extends('layouts.master')


@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="box">
        <!-- /.box-header -->
        
                                <!-- DATA TABLE -->
                                <h3 class="title-5 m-b-35">Deposite History</h3>
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
                                            <i class="zmdi zmdi-plus"></i>New Deposite</a>

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
                          <form action="exportdeposite" method="POST" enctype="multipart/form-data">
                           {{ csrf_field() }} {{ method_field('POST') }}
                            <div class="row">
                            <div class="col-md-2">
                            <label for="from" class="col-form-label">From</label>
                             <input type="date" class="form-control input-sm" id="from" name="from" required>
                             </div>
                              <div class="col-md-2">
                              <label for="from" class="col-form-label">To</label>
                              <input type="date" class="form-control input-sm" id="to" name="to"  required>
                              </div>
                    
                              <div class="col-md-4" style="margin-top:28px;">
                            <button type="submit" class="btn btn-primary btn-sm" name="search" >Export Report</button>

                             </div>
                
                              </div>
                                </form>
                                <br></br>
            <table id="user-table" class="table table-striped table-data2">
                   <thead>
                   <tr>
                    <th>Account Name</th>
                    <!-- <th>Account Group</th>
                    <th>From</th> -->
                    <th>Amount</th>
                    <th>Check Number</th>
                    <th>Memo</th>
                    <th>Deposite Date</th>
                    <th></th>
                    <!-- <th>Account Type</th> -->
                 
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

@endsection

@section('bot')
      @include('deposite.form');

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
            ajax: "{{ url('/DepositeApi') }}",
            columns: [
            
                {data: 'account_name', name: 'account_name'},
                // {data: 'account_group', name: 'account_group'},
                // {data: 'from_where', name: 'from_where'},
                {data: 'amount', name: 'amount'},
                {data: 'check_number', name: 'check_number'},
                {data: 'memo', name: 'memo'},
           
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
              
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-depo').modal('show');
            $('#form-depo')[0].reset();
            $('.modal-title').text('Make Deposite');
        }
        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#form-account')[0].reset();
            $.ajax({
                url: "{{ url('account_staff') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(html) { 
                   $('#modal-account-form').modal('show');
                    $('.modal-title').text('Edit Account');
                    $('#id').val(html.data.id);
                    $('#account_name').val(html.data.account_name);
                    $('#account_group').val(html.data.account_group); 
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
                text: "You won't able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, i want to delete!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('deposite_asali') }}" + '/' + id,
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
  
//         $(function(){
//             $('#form-deposite').validator().on('submit', function (e) {
//                 if (!e.isDefaultPrevented()){
//                     var id = $('#id').val();
//                     if (save_method == 'add') url = "{{ url('account_staff') }}";
//                     else url = "{{ url('account_staff') . '/' }}" + id;

//                     $.ajax({
//                         url : url,
//                         type : "POST",
//                         //hanya untuk input data tanpa dokumen
// //                      data : $('#form-account').serialize(),
//                         data: new FormData($("#form-deposite")[0]),
//                         contentType: false,
//                         processData: false,
//                         success : function(data) {
//                             $('#modal-deposite').modal('hide');
//                             table.ajax.reload();
//                             swal({
//                                 title: 'Success!',
//                                 text: data.message,
//                                 type: 'success',
//                                 timer: '1500'
//                             })
//                         },
//                         error : function(data){
//                             swal({
//                                 title: 'Oops...',
//                                 text: data.message,
//                                 type: 'error',
//                                 timer: '1500'
//                             })
//                         }
//                     });
//                     return false;
//                 }
//             });
//         })
            $(function(){
            $('#form-depo').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('deposite') }}";
                    else url = "{{ url('deposite') . '/' }}" + id;
                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#form-account').serialize(),
                        data: new FormData($("#form-depo")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-depo').modal('hide');
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
            $('#form-account').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('account_staff') }}";
                    else url = "{{ url('account_staff') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#form-account').serialize(),
                        data: new FormData($("#form-account")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-account-form').modal('hide');
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
