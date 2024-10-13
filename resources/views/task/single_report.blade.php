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
        bottom: 0px;
        left: 0px;

        /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
        width: 21.8cm;
        height: 28cm;

        /** Your watermark should be behind every content**/
        z-index: -1000;
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
        /*This style rule makes every page element start at the top of a new page:*/
        counter-increment: page
    }

    /*page numbers*/
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
    <title>{{$dispatch}}|Report</title>
</head>
<div class="section__content section__content--p30">
    <div class="container-fluid">
        <div>
            <span style="font-family:sans-serif; font-size:10px;">
                <?php
                date_default_timezone_set("Africa/Nairobi");
                echo 'Downloaded by ' . $loggedInUser->name . ' on ' . date("D d, M Y") . ' ' . date("h:i:sa");
                ?>
            </span>
        </div>

        <div id="watermark">
            <img src="assets/img/misana.png" alt="logo" height="40" width="80" style="float:right;padding-right:62px;margin-top:5px;">
            <!-- <img src="assets/img/misana.png" height="70%" width="80%" style="opacity: 0.05;"/> -->
        </div>
        <div class="row top_tag" style="margin-top:0px;">
            <div class="col-lg-12" style="text-align:center;">
                <h1 style="color:red;"><strong>Misana Home Bakery</strong></h1>
            </div>
            <div class="col-lg-12" style="text-align:center;">
                <h3 style="margin-left:30px;margin-top:-10px;">
                    <strong style="color:green">{{$employee}} -- 
                        <span style="color:red"> {{$dispatch}} Report</span>
                    </strong>
                </h3>
            </div>
        </div>
        <div class="row top_tag" style="position:relative;">
            <div class=" row col-lg-12" >
                <!-- TOP CAMPAIGN-->
                <div class="top-campaign">
                    <h3 class="title-3 m-b-3">Summary</h3>
                    <div class="table-responsive">
                        <table class="table table-top-campaign">
                            <tbody>
                                <tr>
                                    <td>Date:</td>
                                    <td style=" text-align:right;">{{$dates}}</td>
                                </tr>
                                <tr>
                                    <td>Sales Qty</td>
                                    <td style=" text-align:right;">{{$sum_qty}}</td>

                                </tr>
                                <tr>
                                    <td>Amount Required</td>
                                    <td style=" text-align:right;">{{number_format($sum_amt,2)}}</td>

                                </tr>
                                <tr>

                                    <td>Amount Received</td>
                                    <td style="text-align:right;">{{number_format($sum_recive,2)}}</td>

                                </tr>
                                <tr>
                                    <td style="color:red">Amount Due</td>
                                    <td style="color:red; text-align:right;">{{number_format($sum_due,2)}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row col-lg-12" style="margin-top:10px;">
                <h3 class="title-5 m-b-3" style="color:red">Sales Information</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr style="text-align:right;">
                                <th scope="col" class="border-0 pl-0">Date</th>
                                <th scope="col" class="border-0 pl-0">Item</th>
                                <th scope="col" class="border-0 pl-0">Qty</th>
                                <th scope="col" class="border-0 pl-0">Retail-Qty</th>
                                <th scope="col" class="border-0 pl-0">Retail-Price</th>
                                <th scope="col" class="border-0 pl-0">Bulk-Qty</th>
                                <th scope="col" class="border-0 pl-0">Bulk-Price</th>
                                <th scope="col" class="border-0 pl-0">Total</th>
                                <th scope="col" class="border-0 pl-0">Received</th>
                            </tr>
                        </thead>
                        <tbody style="text-align:right;">
                            @foreach($product_out as $product_out)
                            <tr>
                                <td class="pl-0">
                                    {{$product_out->created_at}}
                                </td>
                                <td class="pl-0">
                                    {{$product_out->product_name}}
                                </td>
                                <td class="pl-0" style="text-align:right;">
                                    {{$product_out->qty}}
                                </td>
                                <td class="pl-0" style="text-align:right;">
                                    {{$product_out->retail}}
                                </td>
                                <td class="pl-0">
                                    {{number_format($product_out->retail_price, 2)}}
                                </td>
                                <td class="pl-0">
                                    {{$product_out->bulk}}
                                </td>
                                <td class="pl-0" style="text-align:right;">
                                    {{number_format($product_out->price,2)}}
                                </td>
                                <td class="pl-0" style="text-align:right;">
                                    {{number_format(($product_out->bulk*$product_out->price)+($product_out->retail*$product_out->retail_price),2)}}
                                </td>
                                <td class="pl-0" style="text-align:right;">
                                    {{number_format($product_out->amount_paid,2)}}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>Total:</td>
                                <td>
                                </td>
                                <td style="color:green">{{$sum_qty}}</td>
                                <td style="color:green">{{$sum_retail}}</td>
                                <td>
                                </td>
                                <td style="color:green">{{$sum_bulk}}</td>
                                <td></td>
                                <td style="color:green; text-align:right;">{{number_format($sum_amt,2)}}/=</td>
                                <td style="color:green; text-align:right;">{{number_format($sum_recive,2)}}/=</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
        <div class="row top_tag col-lg-12" style="margin-top:50px;">
            <h3 class="title-5 m-b-35" style="color:red">Payment Information</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="border-0 pl-0">Date</th>
                            <th scope="col" class="border-0 pl-0">Account Number</th>
                            <th scope="col" class="border-0 pl-0">Dispatch</th>
                            <th scope="col" class="border-0 pl-0">Amount Paid</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($x as $x)
                        <tr>
                            <td class="pl-0">
                                {{$x->created_at}}
                            </td>
                            <td class="pl-0">
                                {{$x->employee_number}}
                            </td>


                            <td class="pl-0">
                                {{$x->task_number}}
                            </td>
                            <td class="pl-0" style="text-align:right;">
                                {{number_format($x->amount,2)}}/=
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td>Total:</td>
                            <td>
                            </td>
                            <td></td>
                            <td style="font-weight:bold;color:green; text-align:right;">
                                {{number_format($sum_recive,2)}}
                            </td>

                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>