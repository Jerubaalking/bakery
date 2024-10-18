@extends('layouts.master')

@section('top')
<!-- DataTables CSS -->
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap datepicker -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css"> -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<style>
    /* Custom styles for the activity log page */


    .activity-header {
        margin-bottom: 20px;
    }

    .activity-header h1 {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .activity-header p {
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>
@endsection
@section('content')
<div class="box">
    <h3 class="title-5 m-b-35">Activities </h3>
    <div class="box-body">
        <div class="activity-header text-center">
            <h1>Activity Log</h1>
            <p>View the activities performed by users along with the details.</p>
        </div>
        <div class="table-responsive">
            <table id="activities-table" class="table table-striped table-data2 table-responsive nowrap">
                <thead>
                    <tr>
                        <th col="1" >Action</th>
                        <th col="1">User</th>
                        <th col="1">IP Address</th>
                        <th col="1">Time</th>
                        <th col="1">Status</th>
                        <th col="1">Output</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
    <!-- /.box-body -->
</div>

<script>
    // alert('am here loading');
    // $(document).ready(()=>{
    //     alert('am ready')
    // });
    var table = $('#activities-table').DataTable({
        processing: true,
        rowReorder: {
            selector: 'td:nth-child(3)'
        },
        "autoWidth": false,
        // serverSide: true,
        dom: 'lBfrtip',
        "scrollCollapse": true,
        buttons: ['pdf', 'excel'],
        "lengthMenu": [10, 20, 50, 100],
        responsive: true,
        ajax: {
            type: "GET",
            dataType: "json",
            contentType: "application/json",
            url: "/api/activities"
        },
        columns: [
            
            
            {
                data: 'action',
                name: 'actions'
            },
            {
                data: 'done_by',
                name: 'user'
            },
            {
                data: 'ip',
                name: 'IP'
            },
            {
                data: 'created_at',
                name: 'time'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'output',
                name: 'info'
            },

        ]
    });
</script>
@endsection

@section('scripts')

<!-- DataTables JS -->
<!-- DataTables -->
<script src=" {{ asset('assets/bower_components/jquery/dist/jquery.js') }} "></script>
<script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
<script src=" {{ asset('assets/bower_components/datatables.net/js/dataTables.responsive.min.js') }} "></script>
<script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>
<script src=" {{ asset('assets/bower_components/datatables.net/js/dataTables.rowReorder.min.js') }} "></script>
<script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.responsive.min.js') }} "></script>
<script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.select.min.js') }} "></script>
<script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.dateTime.min.js') }} "></script>


<!-- InputMask -->
<!-- date-range-picker -->
<script src="{{ asset('assets/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- bootstrap time picker -->
<script src="{{ asset('assets/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

<script>
    // alert('am here loading');
    // $(document).ready(()=>{
    //     alert('am ready')
    // });
    var table = $('#activities-table').DataTable({
        processing: true,
        rowReorder: {
            selector: 'td:nth-child(3)'
        },
        "autoWidth": false,
        // serverSide: true,
        dom: 'lBfrtip',
        "scrollCollapse": true,
        buttons: ['pdf', 'excel'],
        "lengthMenu": [10, 20, 50, 100],
        responsive: true,
        ajax: {
            type: "GET",
            dataType: "json",
            contentType: "application/json",
            url: "/api/activities"
        },
        columns: [{
                data: 'action',
                name: 'action'
            },
            {
                data: 'done_by',
                name: 'User'
            },
            {
                data: 'created_at',
                name: 'time'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'output',
                name: 'output'
            },

        ]
    });
</script>