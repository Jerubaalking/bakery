@extends('layouts.master')
@section('top')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<link rel="stylesheet"
    href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
<div class="box" style="margin-top:30px;">
    <div class="box-body">
        <h3 class="title-5 m-b-35">Sales Accounts</h3>
        <div class="table-data__tool">
            <div class="table-data__tool-right">
                <a id="add_btn" class="au-btn au-btn-icon au-btn--green au-btn--small">
                    <i class="zmdi zmdi-plus"></i>Add</a>
            </div>
        </div>
        <div class="box-body" style="margin-top:12px;">
            <form action="exportTask" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="row">
                    <!-- <div class="col-md-2">
                        <label for="from" class="col-form-label">From</label>
                        <input type="date" class="form-control input-sm" id="from" name="from" required>
                    </div>
                    <div class="col-md-2">
                        <label for="from" class="col-form-label">To</label>
                        <input type="date" class="form-control input-sm" id="to" name="to" required>
                    </div> -->
                    <div class="col-md-2 form group" style="margin-left:0px;">
                        <label>Account</label> <br>
                        <select class="form-control" onchange="populateTable('api/task')" name="employeeId" id="employeeId">
                            <option value="all" selected>All</option>
                            @foreach($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->first_name}} {{$employee->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form group" style="margin-left:0px;">
                        <label>Dispatch</label> <br>
                        <select class="form-control" onchange="populateTable('api/task')" name="status" id="status">
                            <!--<option value="all">all</option>-->
                            <option value="task" selected>Active Dispatchs</option>
                            <option value="closed">Closed Dispatch</option>
                            <option value="damaged">Damages</option>

                            @if(\Auth::user()->role=='Superadministrator')
                            <option value="accounts">Accounts</option>
                            @endif
                        </select>
                    </div>


                    <div class="col-md-3 form group">
                        <label>Date Range</label> <br>
                        <input type="text" class="form-control" onchange="populateTable('api/task')" name="date_range" id="date_range">
                    </div>
                    <div class="col-md-4" style="margin-top:28px;">
                        <button type="submit" class="btn btn-primary btn-sm" name="search">Export Report</button>
                    </div>

                </div>
            </form>

            <br>
            <br>
            <div class="table-responsive table-striped  table-responsive">
                <table id="task-table" class="table  table-striped table-data2">
                    <thead>

                        <tr>
                            <th>Sales Account</th>
                            <!-- <th>Supplier Number</th> -->
                            <!--<th>Task Number</th>-->
                            <th>Total Amount</th>
                            <th>Amount Paid</th>
                            <th>Amount due</th>
                            <th>Lastest Date</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>


                </table>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="box col-md-6">
        @include('receive.pay');
      
    </div>

    @include('task.form');



    @endsection

    @section('bot')

    @include('stock_return.form');


    @include('task.jquery');

    @endsection