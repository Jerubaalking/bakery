{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
    {{--<meta charset="UTF-8">--}}
    {{--<meta name="viewport"--}}
          {{--content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
    {{--<meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
    {{--<link rel="stylesheet" href="asset('css1.css')}}">--}}
    {{--<!-- Font Awesome -->--}}
   
     <!-- Vendor CSS-->
    {{--<link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css')}} ">--}}
    {{--<!-- Ionicons -->--}}
    {{--<link rel="stylesheet" href="{{ asset('assets/bower_components/Ionicons/css/ionicons.min.css')}} ">--}}

    {{--<title>rizone||application form</title>--}}
    <style type="text/css" media="screen">
            html {
                font-family: sans-serif;
                line-height: 1.15;
                margin: 0;
            }
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                text-align: left;
                background-color: #fff;
                font-size: 10px;
                margin: 36pt;
            }
            h4 {
                margin-top: 0;
                margin-bottom: 0;
            }
            p {
                margin-top: 0;
                margin-bottom: 0;
            }
            strong {
                font-weight: bolder;
            }
            img {
                vertical-align: middle;
                border-style: none;
            }
            table {
                border-collapse: collapse;
            }
            th {
                text-align: inherit;
            }
            h4, .h4 {
                margin-bottom: 0.5rem;
                font-weight: 500;
                line-height: 1.2;
            }
            h4, .h4 {
                font-size: 1.5rem;
            }
            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
                border: 1px solid #dee2e6;
            }
            .table th,
            .table td {
                padding: 0.55rem;
                vertical-align: top;
                border: 1px solid #dee2e6;
            }
            .table thead th {
                vertical-align: bottom;
                border: 2px solid #dee2e6;
            }
            .table tbody + tbody {
                border: 2px solid #dee2e6;
            }
            .mt-5 {
                margin-top: 3rem !important;
            }
            .pr-0,
            .px-0 {
                padding-right: 0 !important;
            }
            .pl-0,
            .px-0 {
                padding-left: 0 !important;
            }
            .text-right {
                text-align: right !important;
            }
            .text-center {
                text-align: center !important;
            }
            .text-uppercase {
                text-transform: uppercase !important;
            }
            * {
                font-family: "DejaVu Sans";
            }
            body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
                line-height: 1.1;
            }
            .party-header {
                font-size: 1.5rem;
                font-weight: 400;
            }
            .total-amount {
                font-size: 12px;
                font-weight: 700;
            }
            .border-0 {
                border: none !important;
            }
            .vl {
        border-left: 3px solid black;
     
         margin-top:150px;
         }
         #watermark {
                position: fixed;
                bottom:   0px;
                left:     0px;

                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    21.8cm;
                height:   28cm;

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
        </style>
{{--</head>--}}
{{--<body>--}}
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                    <div id="watermark">
                     <img src="assets/img/misana.png" alt="logo" height="100" width="150" style="float:right;padding-right:62px;margin-top:-25px;">
                     <img src="assets/img/misana.png" height="100%" width="100%" style="opacity: 0.05;"/>
                        </div>
                        
                    <div class="row" style="margin-top:80px;">
                    <center><h1 style="color:red;"><strong>Misana Home Bakery</strong></h1></center>
                    <center><h3 style="margin-left:30px;margin-top:-10px;"><strong style="color:green">{{$supplier[0]->first_name}} Sales  Report<p style="color:red">From</p>{{$from}}<p style="color:red">To</p>{{$to}}</strong></h3></center>
                  
                    <div class="box">
                    <div class="row" style="margin-top:180px;">
                    <div class="col-lg-6">
                                <!-- TOP CAMPAIGN-->
                                <div class="top-campaign">
                                    <h3 class="title-3 m-b-30">Summary</h3>
                                    <div class="table-responsive">
                                        <table class="table table-top-campaign">
                                            <tbody>
                                                <tr>
                                                <td style="color:green">Name:</td>
                                                <td style="color:green">{{$supplier[0]->first_name}}</td>
                                                </tr>
                                                <tr>
                                                <td style="color:green">Phone Number:</td>
                                                <td style="color:green">{{$supplier[0]->phone}}</td>
                                                </tr>
                                                <tr>
                                                <td>Total Task Records</td>
                                                <td>{{$count}}</td>
                                                </tr>
                                                <tr>
                                                  
                                                  <td>Amount Required</td>
                                                  <td>{{number_format($sum_sub,2)}}</td>
                                                
                                              </tr>
                                                <tr>
                                                  
                                                    <td>Amount Received</td>
                                                    <td>{{number_format($sum_recive,2)}}</td>
                                                  
                                                </tr>
                                                <tr>
                                                    <td style="color:red">Cost Returned</td>
                                                    <td  style="color:red">{{number_format($sum_return,2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="color:red">Amount Due</td>
                                                    <td  style="color:red">{{number_format($sum_due,2)}}</td>
                                                </tr>  
                                                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                           
 
        <div class="row" style="margin-top:50px;">
        <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>          
                                 <th scope="col" class="border-0 pl-0">Date</th>
                                 <th scope="col" class="border-0 pl-0">Task Number</th>
                                 <th scope="col" class="border-0 pl-0">Amount Required</th>
                                 <th scope="col" class="border-0 pl-0">Amount Paid</th>
                                 <th scope="col" class="border-0 pl-0">Product Cost Returned</th>
                                 <th scope="col" class="border-0 pl-0">Amount Due</th>
                           
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($supplier as  $supplier)
                                        <tr>
                                        <td class="pl-0">
                                       {{$supplier->created_at}}
                                       </td>
                                      <td class="pl-0">
                                       {{$supplier->task_number}}
                                       </td>
                                       <td class="pl-0">
                                       {{$supplier->sub_total}}
                                         </td>
                                       <td class="pl-0">
                                       {{$supplier->amount_paid}}
                                       </td>
                                      <td class="pl-0">
                                       {{$supplier->returned}}
                                       </td>
                                       <td class="pl-0">
                                       {{$supplier->amount_due}}
                                       </td>
                                         </tr>
                                         @endforeach
                                         <tr>
                                        <td>Total:</td>
                                         <td>
                                         </td>
                                     
                                       
                                     
                                         <td style="color:green">{{number_format($sum_sub,2)}}</td>
                                         <td style="color:red">{{number_format($sum_recive,2)}}</td>
                                         <td style="color:red">{{number_format($sum_return,2)}}</td>
                                         <td style="color:red">{{number_format($sum_due,2)}}</td>
                                   
                                         </tr>
                                        </tbody>
                                   
                                    </table>
                                </div>
        </div>
        </div>
                    {{--<!-- jQuery 3 -->--}}
    {{--<script src="{{  asset('assets/bower_components/jquery/dist/jquery.min.js') }} "></script>--}}
    {{--<!-- Bootstrap 3.3.7 -->--}}
    {{--<script src="{{  asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }} "></script>--}}
    {{--<!-- AdminLTE App -->--}}
    {{--<script src="{{  asset('assets/dist/js/adminlte.min.js') }}"></script>--}}
{{--</body>--}}
{{--</html>--}}
                      