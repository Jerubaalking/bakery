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
        <table id="message-table" class="table  table-striped table-data2 text-sm">
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

    function sendForm(id) {
        save_method = "send";
        $('input[name=_method]').val('POST');
        $('#modal-send').modal('show');
        $('#form-send')[0].reset();
        $('#message_id').val(id); // Set the message ID
        $('.modal-title').text('Send Message');

        // Fetch customers and groups data
        $.ajax({
            url: '/api/presend', // Replace with your actual endpoint
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Populate the groups dropdown
                    console.log({
                        response
                    });
                    const groupSelect = $('#group');
                    groupSelect.empty(); // Clear any existing options
                    groupSelect.append('<option disabled>--select group--</option>');
                    response.groups.forEach(function(group) {
                        groupSelect.append(`<option value="${group.value}">${group.name}</option>`);
                    });

                    // Populate the customers checkbox list
                    const customerSelect = $('.customer-checkboxes');
                    customerSelect.empty(); // Clear existing checkboxes
                    response.customers.forEach(function(customer) {
                        customerSelect.append(`
                        <label>
                            <input type="checkbox" id="customers[]" name="customers[]" value="${customer.id}"> ${customer.name}
                        </label><br>
                    `);
                    });

                    // Optionally, you can pre-select customers or groups based on certain conditions.
                    // For example:
                    $('#group').val(response.selectedGroup); // Pre-select a group
                    // $('input[name="customers[]"][value="' + response.selectedCustomers + '"]').prop('checked', true);
                } else {
                    // Handle error if no data or issue with fetching
                    alert('Error fetching data.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                alert('There was an issue retrieving the data.');
            }
        });
    }

    function toggleOption(option) {
        if (option === 'group') {
            document.getElementById('group-select').style.display = 'block';
            document.getElementById('customer-select').style.display = 'none';
        } else if (option === 'customers') {
            document.getElementById('group-select').style.display = 'none';
            document.getElementById('customer-select').style.display = 'block';
        }
    }

    function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PATCH');
        $('#form-message')[0].reset();
        $.ajax({
            url: "{{ url('messages') }}" + '/' + id,
            type: "GET",
            dataType: "JSON",
            success: function(html) {
                $('#modal-message-form').modal('show');
                $('.modal-title').text('Edit Message');
                $('#id').val(html.data.id);
                $('#title').val(html.data.title);
                $('#message').val(html.data.message);
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

    $('#form-send').validator().on('submit', function(e) {

        if (!e.isDefaultPrevented()) {
            const id = $('#message_id').val();
            console.log({id});
            if (save_method == 'send') url = "{{ url('messages') }}/"+id+"/send";

            $.ajax({
                url: url,
                type: "POST",
                //hanya untuk input data tanpa dokumen
                //                      data : $('#modal-message').serialize(),
                data: new FormData($("#form-send")[0]),
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#modal-message-form').modal('hide');
                    table.ajax.reload();
                    swal({
                        title: 'Success!',
                        text: data.message,
                        type: 'success',
                        timer: '10500'
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