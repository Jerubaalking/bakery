@extends('layouts.master')


@section('top')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
<div class="box">

    <h3 class="title-5 m-b-35">Customer's</h3>
    <div class="table-data__tool">

        <div class="table-data__tool-right">

            <a onclick="addForm()" class="au-btn au-btn-icon au-btn--green au-btn--small">
                <i class="zmdi zmdi-plus"></i>Add</a>

        </div>
    </div>
    <br>
    <div class="table-responsive table-striped  table-responsive">
        <table id="customer-table" class="table  table-striped table-data2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Group</th>
                    <th>Name</th>
                    <th>phone</th>
                    <th>location</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
@include('customer.form')
@endsection

@section('bot')
<!-- DataTables -->
<script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
<script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

<script src="{{ asset('assets/validator/validator.min.js') }}"></script>

<script type="text/javascript">
    var table = $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/api/customers') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'group',
                name: 'group'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'location',
                name: 'location'
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
        $('#modal-customer-form').modal('show');
        $('#form-customer')[0].reset();
        $('.modal-title').text('Add Customers');
    }

    function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PATCH');
        $('#form-customer')[0].reset();
        $.ajax({
            url: "{{ url('customers') }}" + '/' + id,
            type: "GET",
            dataType: "JSON",
            success: function(html) {
                console.log({html});
                $('#modal-customer-form').modal('show');
                $('.modal-title').text('Edit Customer');
                $('#id').val(html.data.id);
                $('#name').val(html.data.name);
                $('#phone').val(html.data.phone);
                $('#location').val(html.data.location);
                $('#group').val(html.data.group);
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
                url: "{{ url('customers') }}" + '/' + id,
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
    
    $('#form-customer').validator().on('submit', function(e) {
            
            if (!e.isDefaultPrevented()) {
                var id = $('#id').val();
                if (save_method == 'add') url = "{{ url('customers') }}";
                else url = "{{url('customers') . '/' }}" + id;

                $.ajax({
                    url: url,
                    type: "POST",
                    //hanya untuk input data tanpa dokumen
                    //                      data : $('#modal-customer').serialize(),
                    data: new FormData($("#form-customer")[0]),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#modal-customer-form').modal('hide');
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