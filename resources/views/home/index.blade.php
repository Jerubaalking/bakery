@extends('layouts.master')


@section('top')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1">overview</h2>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-blue">
                    <div class="inner">
                        <h3 class="title-3 m-b-30">{{$count_cat}}</h3>
                        <span>Product Category</span>

                    </div>
                    <div class="icon">
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-red">
                    <div class="inner">
                        <h3 class="title-3 m-b-30">{{number_format($products_demmage_sum,2)}}</h3>
                        <span>Products Demage({{$products_demmage_count}})</span>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-orange">
                    <div class="inner">
                        <h3 class="title-3 m-b-30">{{$count_stock}}</h3>
                        <span>All products stock</span>


                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>


        <!-- <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-blue">
                    <div class="inner">
                        <h3 class="title-3 m-b-30">{{number_format($products_return_sum,2)}}</h3>
                        <span>Products Returned({{$products_return_count}})</span>

                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-red">
                    <div class="inner">
                        <h3 class="title-3 m-b-30">{{$stock_in}}</h3>
                        <span>Products In</span>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-blue">
                    <div class="inner">
                        <h3 class="title-3 m-b-30">{{number_format($sale_amt,2)}}</h3>
                        <span>Product Out({{$sale_sum}})</span>


                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div> -->


        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-orange">
                    <div class="inner">

                        <h3 class="title-3 m-b-30">{{$count_suppliers}}</h3>
                        <span>Suppliers</span>


                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-red">
                    <div class="inner">
                        <h3 class="title-3 m-b-30">{{number_format($sum_balance,2)}}</h3>
                        <span>Account Balance</span>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-orange">
                    <div class="inner">

                        <h3 class="title-3 m-b-30">{{number_format($sum_task,2)}}</h3>
                        <span>total Task({{$count_task}})</span>


                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-red">
                    <div class="inner">
                        <h3 class="title-3 m-b-30">{{number_format($task_amount_paid,2)}}</h3>
                        <span>Tasks Repayment({{$task_amount_paid_count}})</span>

                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-green">
                    <div class="inner">
                        <h3 class="title-3 m-b-30">{{number_format($task_amount_due,2)}}</h3>
                        <span>Tasks Amount Due({{$task_amount_due_count}})</span>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>


        </div>
    </div>
    <!-- <div class="row">
        <div class="col-lg-6">
            <div class="au-card recent-report">
                <div class="au-card-inner">
                    <h3 class="title-2">Income and Expenses Overview</h3>
                    <div class="row no-gutters">

                        <div class="col-xl-6">
                            <div class="percent-chart">
                                <div id="chartContainer" style="height: 270px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="au-card chart-percent-card">
                <div class="au-card-inner">
                    <h3 class="title-2 tm-b-5">Amount Paid Vs Amount Due Pie Chart Report</h3>
                    <div class="row no-gutters">

                        <div class="col-xl-6">
                            <div class="percent-chart">
                                <div id="chartContainer1" style="height: 270px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="row">
        <!-- <div class="col-lg-7">
            <div class="au-card chart-percent-card">
                <div class="au-card-inner">
                    <h3 class="title-2 tm-b-5">Monthly Sales Collection Chart Report</h3>
                    <div class="row no-gutters">

                        <div class="col-xl-12">

                            <div id="myChart" style="height: 370px; width: 100%;"></div>


                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        
        <div class="col-lg-5">
            <div class="au-card bg-blue au-card-top-countries m-b-40">
                <div class="au-card-inner">
                    <div class="table-responsive">
                        <table class="table table-top-countries">
                            <tbody>
                                <caption><h3 class="title-1 m-b-25" style="color:white;">Current Product Stock</h3></caption>
                                @foreach($current_stock as $current)
                                <tr>
                                    <td>{{$current->product_name}}</td>
                                    <td class="text-right">{{$current->stock}}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="au-card card-box bg-orange au-card-top-countries m-b-40">
                <div class="au-card-inner">
                    <div class="table-responsive">
                        <table class="table table-top-countries">
                                <caption><h3 class="title-1 m-b-25" style="color:white;">Current Material Stock</h3></caption>
                            <tbody>
                                @foreach($materials as $material)
                                <tr>
                                    <td>{{$material->name}}</td>
                                    <td class="text-right">{{$material->available}} {{$material->symbol}}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="copyright">
                <p>Copyright ©
                    <?php $date=date('Y-m-d'); echo $date;?> misana home bakery. All rights reserved.by <a
                        href="https://misanabakery.com">misana home bakery</a>.
                </p>
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

<script>


    window.onload = function () {

        var dps1 = [];
        var dps = [];
        var cData1 = JSON.parse('<?php echo $data['chart_data']; ?>');
        var monthlyReport = JSON.parse('<?php echo $mydata['monthly_report']; ?>');
        var inco_expes = JSON.parse('<?php echo $expenses['expenses']; ?>');


        // for(var j=0;j<cData.data.length; j++){
        // dps.push({
        // y: cData.data[j], label:cData.label[j]
        //  });
        //  }

        for (var i = 0; i < monthlyReport.amt.length; i++) {
            dps1.push({
                y: monthlyReport.amt[i], label: monthlyReport.monthly[i]
            });
        }

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            data: [{
                type: "pie",
                startAngle: 240,
                yValueFormatString: "##0.00",
                indexLabel: "{label} {y}",
                dataPoints: [
                    { y: inco_expes.expenses.income, label: "expenses", color: "brown" },
                    { y: inco_expes.expenses.expenses, label: "income", color: "green" },
                    { y: inco_expes.expenses.idel, label: "idiol income", color: "blue" },

                ],
            }]
        });
        chart.render();

        var chart1 = new CanvasJS.Chart("chartContainer1", {
            animationEnabled: true,
            data: [{
                type: "pie",
                startAngle: 240,
                yValueFormatString: "##0.00",
                indexLabel: "{label} {y}",
                dataPoints: [
                    { y: cData1.data.paid, label: "Amount Paid", color: "#1f77b4" },
                    { y: cData1.data.due, label: "Amount Due", color: "red" },
                    { y: cData1.data.return, label: "Product Cost Demage", color: "black" },
                    { y: cData1.data.demage, label: "Product Cost Demage", color: "brown" },
                ],
            }]
        });
        chart1.render();

        var chart3 = new CanvasJS.Chart("myChart", {
            animationEnabled: true,
            data: [{
                type: "bar",
                startAngle: 240,
                yValueFormatString: "##0.00",
                indexLabel: "{label} {y}",
                dataPoints: dps1
            }]
        });
        chart3.render();



    }

</script>

@endsection