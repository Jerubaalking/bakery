@extends('layouts.master')


@section('top')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
<div class="box">

    <h3 class="title-5 m-b-35">Message's</h3>
    <div class="table-data__tool">

        <div class="table-data__tool-right">

            <a onclick="addForm()" class="au-btn au-btn-icon au-btn--green au-btn--small">
                <i class="zmdi zmdi-plus"></i>Add</a>

        </div>
    </div>
    <br>
    <div class="table-responsive table-striped  table-responsive">
        <table id="message-table" class="table  table-striped table-data2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>date</th>
                    <th>title</th>
                    <th>message</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
@include('messages.form')
@include('messages.send')
@endsection

@section('bot')
<!-- DataTables -->
<script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
<script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

<script src="{{ asset('assets/validator/validator.min.js') }}"></script>

<script type="text/javascript">
    var table = $('#message-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/api/messages') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'created_at',
                name: 'date'
            },
            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'message',
                name: 'message'
            },
            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: false
            }
        ]
    });

    function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-message-form').modal('show');
        $('#form-message')[0].reset();
        $('.modal-title').text('Add Message');
    }
    function sendForm() {
        save_method = "send";
        $('input[name=_method]').val('POST');
        $('#modal-send').modal('show');
        $('#form-send')[0].reset();
        $('.modal-title').text('Send Message');
    }
    function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PATCH');
        $('#form-message')[0].reset();
        $.ajax({
            url: "{{ url('messages') }}" + '/' + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(html) {
                $('#modal-message-form').modal('show');
                $('.modal-title').text('Edit Message');
                $('#id').val(html.data.id);
                $('#message_name').val(html.data.message_name);
                $('#message_group').val(html.data.message_group);
            },
            error: function() {
                alert("Nothing Data");
            }
        });
    }

    function activateData(id) {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        swal({
            title: 'Are you sure?',
            text: "You will be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: 'green',
            confirmButtonText: 'Yes, status changed!'
        }).then(function() {
            $.ajax({
                url: "{{ url('/activateData') }}" + '/' + id,
                type: "POST",
                data: {
                    '_method': 'POST',
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

    function deleteData(id) {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        swal({
            title: 'Are you sure?',
            text: "You will be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete It!'
        }).then(function() {
            $.ajax({
                url: "{{ url('messages') }}" + '/' + id,
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
    $('#form-message').validator().on('submit', function(e) {
            
            if (!e.isDefaultPrevented()) {
                var id = $('#id').val();
                if (save_method == 'add') url = "{{ url('messages') }}";
                else url = "{{url('messages') . '/' }}" + id;

                $.ajax({
                    url: url,
                    type: "POST",
                    //hanya untuk input data tanpa dokumen
                    //                      data : $('#modal-message').serialize(),
                    data: new FormData($("#form-message")[0]),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#modal-message-form').modal('hide');
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
</script>

@endsection