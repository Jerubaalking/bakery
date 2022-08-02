@extends('layouts.master')
@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')

                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                    <div class="row">
                    <div class="col-lg-6">
                                <!-- TOP CAMPAIGN-->
                                <div class="top-campaign">
                                    <h3 class="title-3 m-b-30">Cash Flow</h3>
                                    <div class="table-responsive">
                                        <table class="table table-top-campaign">
                                            <tbody>
                                            <tr>
                                                <td>Capital(A) </td>
                                                    <td>{{number_format($capital_sum,2)}}</td>
                                                </tr>
                                                <tr>
                                                  
                                                    <td>Sales(B)</td>
                                                    <td>{{number_format($income_sum,2)}}</td>
                                                </tr>
                                             
                                             
                                                <tr>
                                                    <td style="color:red">Expenses(C)</td>
                                                    <td style="color:red">{{number_format($expenses_sum,2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color:green">Total Cash Balance((A+B)-C)</td>
                                                    <td style="color:green">{{number_format($balance,2)}}</td>
                                                </tr>
                                             
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--  END TOP CAMPAIGN-->
                            </div>
                    </div>
                
                        </div>
                        <div class="row">
                    <div class="col-lg-6">
                                <!-- TOP CAMPAIGN-->
                     <div class="top-campaign">
                     <h3 class="title-3 m-b-30">Export</h3>
                           <form action="export_flow" method="POST" enctype="multipart/form-data">
                           {{ csrf_field() }} {{ method_field('POST') }}
                            <div class="row">
                            <div class="col-md-4">
                            <label for="from" class="col-form-label">From</label>
                             <input type="date" class="form-control input-sm" id="from" name="from" required>
                             </div>
              
                              <div class="col-md-4">
                              <label for="from" class="col-form-label">To</label>
                              <input type="date" class="form-control input-sm" id="to" name="to"  required>
                              </div>
                        
                    
                              <div class="col-md-4" style="margin-top:28px;">
                            <button type="submit" class="btn btn-primary btn-sm" name="search" >Export Report</button>

                             </div>
                
                              </div>
                                </form>
                                <br></br>
                    </div>
                     </div>
                    </div>
                    </div>
                </div>
        
@endsection
@section('bot')
    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    {{--<script>--}}
    {{--$(function () {--}}
    {{--$('#items-table').DataTable()--}}
    {{--$('#example2').DataTable({--}}
    {{--'paging'      : true,--}}
    {{--'lengthChange': false,--}}
    {{--'searching'   : false,--}}
    {{--'ordering'    : true,--}}
    {{--'info'        : true,--}}
    {{--'autoWidth'   : false--}}
    {{--})--}}
    {{--})--}}
    {{--</script>--}}

@endsection
