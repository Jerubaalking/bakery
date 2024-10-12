@extends('layouts.master')


@section('top')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- DATA TABLE -->
        <h3 class="title-5 m-b-35">Receivable History</h3>
        <div class="table-responsive table-responsive">
            <form action="exportpay" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="row">
                    <div class="col-md-2">
                        <label for="from" class="col-form-label">From</label>
                        <input type="date" class="form-control input-sm" id="from" name="from" required>
                    </div>

                    <div class="col-md-2">
                        <label for="from" class="col-form-label">To</label>
                        <input type="date" class="form-control input-sm" id="to" name="to" required>
                    </div>


                    <div class="col-md-4" style="margin-top:28px;">
                        <button type="submit" class="btn btn-primary btn-sm" name="search">Export Report</button>

                    </div>

                </div>
            </form>

            <table id="user-table" class="table table-striped table-data2" style="margin-top:40px;">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Amount Paid</th>
                        <th>Date Paid</th>
                        <th>Payment Method</th>
                        <th>Amount Due</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <!-- END DATA TABLE -->
    </div>
</div>


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
        ajax: "{{ url('/apiPayment') }}",
        columns: [

            {
                data: 'first_name',
                name: 'first_name'
            },
            {
                data: 'last_name',
                name: 'last_name'
            },
            {
                data: 'amount',
                name: 'amount'
            },

            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'payment_methode',
                name: 'payment_methode'
            },
            {
                data: 'amount_due',
                name: 'amount_due'
            },
        ]
    });
</script>

@endsection