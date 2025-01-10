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
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net/css/dataTables.dateTime.min.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net/css/editor.dataTables.min.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net/css/font-awesome.min.css') }} ">
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net/css/select.dataTables.min.css') }} ">
@endsection
@section('content')
<div class="box">
    <h3 class="title-5 m-b-35">Material store</h3>
    <div class="box-body" style="margin-top:-25px;">
        <div>

        </div>
        <div class="table-data__tool ">
            <div class="table-data__tool-right col-md-12">
                <div class="col-md-2 form group" style="margin-left:-30px;">
                    <label>Status</label> <br>
                    <select onchange="populateTable('apiIntoStore')" class="form-control" name="status" id="status">
                        <!--<option value="all">all</option>-->
                        <option value="in" selected>Stock-In</option>
                        <option value="process">Stock-In Process</option>
                        <option value="finished">Finished-Process</option>
                    </select>
                </div>
                <div class="col-md-3 form group">
                    <label>Date Range</label> <br>
                    <input type="text" class="form-control" name="date_range" id="date_range">
                </div>
                <div class="col-md-1 form group">
                    <a id="print_btn" target="_blank" onclick="reportPDF('exportIntoStorePDF');" style="margin-top:22px;"
                        class="btn btn-default au-btn--small">
                        <i class="zmdi zmdi-download"></i> <i class="fa fa-pdf"></i>
                        <span id="pdf_report_text">PDF</span>
                        <span id="pdf_report_loader" class="loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 15px;"></i>
                        </span>
                    </a>
                </div>

                <div class="col-md-2 form group">
                    <a id="batch_print_btn" target="_blank" onclick="batchReport('batchReport');" style="margin-top:22px;"
                        class="btn btn-default au-btn--small">
                        <i class="zmdi zmdi-download"></i> <i class="fa fa-pdf"></i>
                        <span id="batch_report_text">Batch Report</span>
                        <span id="batch_report_loader" class="loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 15px;"></i>
                        </span>
                    </a>
                </div>

                <div class="col-md-1 form group" style="margin-top:2px;">
                    <label> Value</label> <br>
                    <label id="in_total" class="text-success"></label>
                </div>
                <!-- <div class="col-md-2 form group">
                                <label>Used Materials</label> <br>
                                <label id="out_total" class="text-danger"></label>
                                </div> -->
                <a id="use_btn" style="float:right; margin:5px;" class="btn btn-warning  au-btn--small">
                    <i class="zmdi zmdi-edit"></i> Use</a>
                <a id="add_btn" style="float:right;margin:5px;" ; class="btn btn-success au-btn--small">
                    <i class="zmdi zmdi-plus"></i> Add</a>
            </div>

        </div>
        <div class="table-responsive table-striped  table-responsive">
            <table id="task-table" class="table  table-striped table-data2">
                <thead>
                    <tr>
                        <th>Batch</th>
                        <th>Material</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Cost/Measurement</th>
                        <th>Gen. Cost</th>
                        <th>comment</th>
                        <th>date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
    <!-- /.box-body -->
</div>
@include('intoStore.form');
@endsection
@section('bot')
@include('intoStore.jquery');
@endsection