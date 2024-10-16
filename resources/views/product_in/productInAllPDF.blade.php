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
        margin: 30px;
        position: relative;
        /* Ensure body has relative positioning */
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

    h4,
    .h4 {
        margin-bottom: 0.5rem;
        font-weight: 500;
        line-height: 1.2;
    }

    h4,
    .h4 {
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

    .table tbody+tbody {
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

    body,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    table,
    th,
    tr,
    td,
    p,
    div {
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
        margin-top: 150px;
    }

    #watermark {
        position: fixed;
        /* Keep it fixed so it appears on every page */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.05;
        /* Adjust as necessary for visibility */
        z-index: -1;
        /* Behind all content */
        background: url('assets/img/misana.png') no-repeat center center;
        background-size: cover;
        /* Cover the entire background */
    }

    #report-logo {
        position: fixed;
        /* Keep it fixed to top right */
        top: 20px;
        /* Adjust as needed */
        right: 20px;
        /* Adjust as needed */
        z-index: 1;
        /* Above watermark */
    }



    .page {
        position: relative;
        height: auto;
        min-height: 750px;
        min-width: 590px;
        display: block;
        background: rgba(255, 255, 255, 0.9) !important;
        margin: 0 auto 15px;
        page-break-after: always;
        /* This style rule makes every page element start at the top of a new page */
        counter-increment: page;
    }

    /* Page numbers */
    div.page:after {
        content: " PAGE - " counter(page);
        position: absolute;
        bottom: 0px;
        right: 15px;
        z-index: 999;
        padding: 2px 12px;
        border-right: 2px solid #23b8e7;
        font-size: 12px;
    }
</style>
<head>
    <title>Products | Report</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/misana.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/img/misana.png')}}">
</head>
<div class="section__content section__content--p30">
    <div class="container-fluid">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <div style="flex: 1;">
                <span style="font-family: sans-serif; font-size: 10px;">
                    <?php
                    date_default_timezone_set("Africa/Nairobi");
                    echo 'Downloaded by ' . $loggedInUser->name . ' on ' . date("D d, M Y") . ' ' . date("h:i:sa");
                    ?>
                </span>
            </div>
            <h1 style="color: red; margin: 0; text-align: center; flex: 0 0 auto;">
                <strong>Misana Home Bakery</strong>
            </h1>
            <div id="report-logo" style="flex: 0 1 auto;">
                <img src="assets/img/misana.png" alt="logo" height="70" width="120" style="opacity: 1;">
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <center>
                <h3 style="margin-left:30px;margin-top:-10px;"><strong style="color:green">ProductIn Report<p style="color:red">From</p>{{$from}}
                        <p style="color:red">To</p>{{$to}}
                    </strong></h3>
            </center>

            <div class="box">


                <div class="row" style="margin-top:50px;">

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="border-0 pl-0">Date</th>
                                    <th scope="col" class="border-0 pl-0">Item Name</th>
                                    <th scope="col" class="border-0 pl-0">Qty</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($product_in as $product_in)
                                <tr>
                                    <td class="pl-0">
                                        {{$product_in->date_in}}
                                    </td>
                                    <td class="pl-0">
                                        {{$product_in->product_name}}
                                    </td>
                                    <td class="pl-0">
                                        {{$product_in->qty}}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>

                                    <td>Total:</td>
                                    <td></td>
                                    <td>
                                        <strong style="color:#4682B4">{{$sum}}</strong>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>