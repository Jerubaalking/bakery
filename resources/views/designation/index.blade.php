@extends('layouts.master')


@section('top')
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
<div class="box">

    <div class="box-header">
        <h3 class="title-5 m-b-35">Designation & Account Management</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="">
            <!-- <div class="form-group col-md-6">
                <label><i class="fa fa-filter"></i> Filter designation</label>
                <select class="form-control" id="account_id" name="account_id">
                    <option disabled>--select designation--</option>
                    <option value="All">All</option>
                    @foreach($accounts as $p)
                    <option value="{{$p->id}}">{{$p->account_name}}</position>
                        @endforeach
                        <span class="help-block with-errors"></span>
                </select>

            </div> -->
            <div class="table-data__tool-right">

                <a onclick="addForm()" class="au-btn au-btn-icon au-btn--green au-btn--small">
                    <i class="zmdi zmdi-plus"></i>Add</a>
            </div>
        </div>
        <div class="table-responsive table-striped  table-responsive">
            <table id="cash-table" class="table  table-striped table-data2"">
                <thead>
                <tr>
                    
                    <th>Designation</th>
                  
                    <th>Account group</th>
                    <th>Status</th>
                    <th>Balance</th>
                    <th>Action</th>
                  
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
        </div>
        <!-- /.box-body -->
    </div>



@endsection

@section('bot')
@include('designation.form')
@include('designation.report_form');
<script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src=" {{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>


    <!-- InputMask -->
    <script src=" {{ asset('assets/plugins/input-mask/jquery.inputmask.js') }}">
                </script>
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


                <script type="text/javascript">
                    var table = $('#cash-table').DataTable({
                        processing: true,
                        serverSide: true,
                        dom: 'lBfrtip',
                        "ScrollX": "100%",
                        "scrollCollapse": true,
                        buttons: [
                            'excel', 'pdf', 'print'
                        ],
                        "lengthMenu": [10, 20, 50, 100, 500],
                        ajax: "{{ url('/apiDesignation') }}",
                        columns: [{
                                data: 'account_name',
                                name: 'account_name'
                            },
                            {
                                data: 'account_group',
                                name: 'account_group'
                            },
                            {
                                data: 'status',
                                name: 'status'
                            },
                            {
                                data: 'account_balance',
                                name: 'account_balance'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ]
                    });


                    function exportReport(id) {
                        $('#form-report')[0].reset();
                        $('#suppier_id').val(id);

                        $('input[name=_method]').val('POST');

                        $.ajax({
                            url: "{{ url('check') }}" + '/' + id,
                            success: function(html) {
                                if (html.data > 0) {
                                    $('#modal-report').modal('show');

                                } else if (html.data == 0) {
                                    alert("Your any task recorded")
                                }

                            },
                            error: function() {
                                alert("Nothing Data");
                            }
                        });

                    }

                    function deleteData(id) {
                        var csrf_token = $('meta[name="csrf-token"]').attr('content');
                        swal({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            type: 'warning',
                            showCancelButton: true,
                            cancelButtonColor: '#d33',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then(function() {
                            $.ajax({
                                url: "{{ url('cash_advance') }}" + '/' + id,
                                type: "POST",
                                data: {
                                    '_method': 'DELETE',
                                    '_token': csrf_token
                                },
                                success: function(data) {
                                    table.ajax.reload();
                                    swal({
                                        title: 'Success!',
                                        text: data.message,
                                        type: 'success',
                                        timer: '1500'
                                    })
                                },
                                error: function() {
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

                    $(function() {
                        $('#modal-form form').validator().on('submit', function(e) {
                            if (!e.isDefaultPrevented()) {
                                var id = $('#id').val();
                                if (save_method == 'add') url = "{{ url('cash_advance') }}";
                                else url = "{{url('cash_advance') . '/' }}" + id;

                                $.ajax({
                                    url: url,
                                    type: "POST",
                                    //hanya untuk input data tanpa dokumen
                                    //                      data : $('#modal-form form').serialize(),
                                    data: new FormData($("#modal-form form")[0]),
                                    contentType: false,
                                    processData: false,
                                    success: function(data) {
                                        $('#modal-form').modal('hide');
                                        table.ajax.reload();
                                        swal({
                                            title: 'Success!',
                                            text: data.message,
                                            type: 'success',
                                            timer: '1500'
                                        })
                                    },
                                    error: function(data) {
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

                    function addForm() {
                        var save_method = "add";
                        $('input[name=_method]').val('POST');
                        $('#modal-account').modal('show');
                        $('#form-account')[0].reset();
                        $('.modal-title').text('Add Account');
                    }

                    function editForm(id) {

                        $('#form-account')[0].reset();
                        save_method = 'edit';
                        $('input[name=_method]').val('PATCH');

                        var tr = $(this).parent().parent();

                        count_charge = '';
                        $.ajax({
                            url: "{{ url('/designation') }}" + '/' + id + "/edit",
                            type: "GET",
                            dataType: "JSON",
                            success: function(html) {
                                console.log("response --->>", html)
                                $('#modal-account').modal('show');
                                $('.modal-title').text(' Edit Designation');
                                $('#id').val(html.data.id);
                                $('#account_id').val(html.data.account_id);

                                $('#account_group').val(html.data.account_group);
                                $('#status').val(html.data.status);
                                $('#department_id').val(html.data.department_id);

                            },
                            error: function(e) {
                                console.log(e);
                                // alert("Nothing Data");
                            }
                        });

                    }

                    $(function() {
                        $('#modal-account form').validator().on('submit', function(e) {
                            if (!e.isDefaultPrevented()) {
                                var id = $('#id').val();
                                if (save_method == 'add') url = "{{ url('designation') }}";
                                else url = "{{ url('designation') . '/' }}" + id;

                                $.ajax({
                                    url: url,
                                    type: "POST",
                                    //hanya untuk input data tanpa dokumen
                                    //                      data : $('#modal-form form').serialize(),
                                    data: new FormData($("#modal-form form")[0]),
                                    contentType: false,
                                    processData: false,
                                    beforeSend: function() {
                                        $(".subBtn").attr("disabled", true);
                                    },
                                    success: function(data) {
                                        $('#modal-form').modal('hide');
                                        table.ajax.reload();
                                        $(".subBtn").attr("disabled", false);
                                        $('.subBtn').html("Please wait...");
                                        swal({
                                            title: 'Success!',
                                            text: data.message,
                                            type: 'success',
                                            timer: '1500'
                                        })
                                    },
                                    error: function(data) {
                                        $(".subBtn").attr("disabled", false);
                                        $('.subBtn').html("submit");
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