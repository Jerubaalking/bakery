@extends('layouts.master')
@section('top')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
<div class="box">

    <!-- DATA TABLE -->
    <h3 class="title-5 m-b-35">User's Details</h3>
    <div class="table-data__tool">
        <div class="table-data__tool-right">

            <a onclick="addForm()" class="au-btn au-btn-icon au-btn--green au-btn--small">
                <i class="zmdi zmdi-plus"></i>New User's</a>
        </div>
    </div>
    <br>
    <div class="box-body">
        <table id="user-table" class="table table-striped table-data2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

@include('user.form')
@endsection

@section('bot')

<!-- DataTables -->
<script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
<script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

{{-- Validator --}}
<script src="{{ asset('assets/validator/validator.min.js') }}"></script>


<script type="text/javascript">
    var table = $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/apiUser') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'role',
                name: 'role'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });

    function addForm() {
        save_method = "add";

        $('input[name=_method]').val('POST');
        $('#modal-user-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Add User');
    }

    function editForm(id) {

        save_method = 'edit';
        $('input[name=_method]').val('PATCH');

        $.ajax({
            url: "{{ url('user') }}" + '/' + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(html) {
                $('#modal-user-form').modal('show');
                $('.modal-title').text('Edit User');
                $('#id').val(html.data.id);
                $('#name').val(html.data.name);
                $('#email').val(html.data.email);
                $('#role').val(html.data.role);
                $('#phone').val(html.data.phone);
                $('#password').val(html.data.password);
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
                url: "{{ url('user') }}" + '/' + id,
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
        $('#form-user').validator().on('submit', function(e) {
            if (!e.isDefaultPrevented()) {
                var id = $('#id').val();
                if (save_method == 'add') url = "{{ url('/user') }}";
                else url = "{{ url('/user') . '/' }}" + id;

                $.ajax({
                    url: url,
                    type: "POST",
                    //hanya untuk input data tanpa dokumen
                    //                      data : $('#modal-form form').serialize(),
                    data: new FormData($("#form-user")[0]),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#modal-user-form').modal('hide');
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
</script>

@endsection