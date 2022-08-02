@extends('layouts.master')


@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="box">
 
    <h3 class="title-5 m-b-35">Account's</h3>
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
                                            <i class="zmdi zmdi-plus"></i>Add</a>
                                          
                                    </div>
                                </div>
                                <br>
                                <div class="table-responsive table-striped  table-responsive">
                                    <table id="user-table"  class="table  table-striped table-data2">
                <thead>
                    <tr>
                    <th>ID</th>
                    <th>Account Group</th>
                    <th>Name</th>
                    <th>Account Balance</th>
                    <th>Status</th>
                    <!-- <th>Account Type</th> -->
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
 @include('account.form')
@endsection

@section('bot')
@include('account.deposite')
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
            ajax: "{{ url('/AccountApi') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'account_group', name: 'account_group'},
                {data: 'account_name', name: 'account_name'},
                {data: 'account_balance', name: 'account_balance'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: true, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-account-form').modal('show');
            $('#form-account')[0].reset();
            $('.modal-title').text('Add Accounts');
        }
        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#form-account')[0].reset();
            $.ajax({
                url: "{{ url('Account') }}" + '/' + id + "/edit",
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

        function activateData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You will be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: 'green',
                confirmButtonText: 'Yes, status changed!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('/activateData') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'POST', '_token' : csrf_token},
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
        
        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You will be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete It!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('Account') }}" + '/' + id,
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
        $(document).on('click','.deposite',function(){
            $('#form-deposite')[0].reset();
            save_method = "add";
             $('input[name=_method]').val('POST'); 
            var id=$(this).attr("id");
            $.ajax({
                url:"check_account/"+id,
                cache:false,
                success: function(html) {
                   if(html.data=="Income"){
                    $('#modal-deposite').modal('hide');
                    alert("you can't Deposite in this Account");
                   }
                   if(html.data!="Income"){
                    $('#modal-deposite').modal('show');
                    $('#account_id').val(id);
                   }
                }
            });
         
          
        })
//         $(function(){
//             $('#form-deposite').validator().on('submit', function (e) {
//                 if (!e.isDefaultPrevented()){
//                     var id = $('#id').val();
//                     if (save_method == 'add') url = "{{ url('Account') }}";
//                     else url = "{{ url('Account') . '/' }}" + id;

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
            $('#form-deposite').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('deposite') }}";
                    else url = "{{ url('deposite') . '/' }}" + id;
                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#form-account').serialize(),
                        data: new FormData($("#form-deposite")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-deposite').modal('hide');
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
                    if (save_method == 'add') url = "{{ url('Account') }}";
                    else url = "{{ url('Account') . '/' }}" + id;

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
