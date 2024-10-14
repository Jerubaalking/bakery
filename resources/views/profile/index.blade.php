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
@endsection

@section('bot')


@endsection