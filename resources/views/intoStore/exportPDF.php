<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="asset('css1.css')}}">
    <!-- Font Awesome -->
   
     <!-- Vendor CSS-->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css')}} ">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/Ionicons/css/ionicons.min.css')}} ">

    <title> Misana Reports|| PDF </title>
</head>
<body>
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
    </head>

    <body>
    
       
    <div id="watermark">
                     <img src="assets/img/misana.png" alt="logo" height="100" width="150" style="float:right;padding-right:62px;margin-top:-25px;">
                     <img src="assets/img/misana.png" height="100%" width="100%" style="opacity: 0.05;"/>
                        </div>
      <section>
      <div class="box" style="margin-top:80px;">
    
      <center><h1 style="color:red;"><strong>MISANA HOME BAKERY</strong></h1></center>
      <center><h3 style="margin-left:30px;margin-top:-10px;"><strong style="color:green">Material  Report <br> Stock-<?php echo $status ?><p style="color:red">From</p><?php echo $from; ?><p style="color:red">To</p><?php echo $to; ?></strong></h3></center>

     

        <table class="table table table-striped">
        <thead>
            <tr>
                   <th scope="col" class="border-0 pl-0">Batch</th>
                   <th scope="col" class="border-0 pl-0">Status</th>
                   <th scope="col" class="border-0 pl-0">Material</th>
                   <th scope="col" class="border-0 pl-0">Category</th>
                   <th scope="col" class="border-0 pl-0">Quantity</th>
                   <th scope="col" class="border-0 pl-0">Cost</th>
                   <th scope="col" class="border-0 pl-0">Entry-Date</th>
                   
                </tr>
            </thead>
            <tbody>
            <?php 
            $total = 0;
            $totalQty = 0;
            foreach($data as $i){ 
                $total += $i->cost;
                $totalQty += $i->qty;
                ?>
                <tr>
                     <td class="pl-0">
                         <?php  echo $i->batch_number?>
                     </td>
                     <td class="pl-0">
                         <?php  echo $i->status?>
                     </td>
                     <td class="pl-0">
                         <?php  echo $i->name?>
                     </td>
                     <td class="pl-0">
                         <?php  echo $i->category_name?>
                     </td>
                     <td class="pl-0">
                         <?php echo $i->qty.''.$i->symbol?>
                     </td>
                     <td class="pl-0">
                         <?php  echo $i->cost?>
                     </td>
                     <td class="pl-0">
                         <?php  echo $i->created_at?>
                     </td>
                </tr>
                <?php } ?>
                @endforeach
                <tr>
                      <td >
                       Total Amount:
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo number_format($totalQty,2) ?></td>
                      <td >
                       <strong style="color:#4682B4"><?php echo number_format($total,2) ?></strong>
                        </td>
                      
                    
                </tr>    
                </tbody>
               </table>

        </div>
   
    <!-- jQuery 3 -->
    <script src="{{  asset('assets/bower_components/jquery/dist/jquery.min.js') }} "></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{  asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }} "></script>
    <!-- AdminLTE App -->
    <script src="{{  asset('assets/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
