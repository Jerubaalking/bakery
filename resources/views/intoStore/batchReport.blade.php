<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Misana Reports|PDF </title>
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
            line-height: 1;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 8px;
            margin: 10pt;
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
            word-wrap: break-word;
        }

        th {
            text-align: inherit;
            inline-size: 30px;
            word-wrap: break-word;
            /** overflow-wrap: break-word; */
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
            width: 100vw;
            margin-bottom: 1rem;
            color: #212529;
            border: 1px solid #dee2e6;
        }

        .table th,
        .table td {
            padding: 0.55rem;
            vertical-align: top;
            border: 1px solid #dee2e6;
            word-break: break-word;
            /** inline-size:30px;
                overflow-wrap: break-word; */
        }

        .table thead th {
            vertical-align: bottom;
            border: 1px solid #dee2e6;
            word-break: break-all;
            inline-size: 30px;
            overflow-wrap: break-word;
        }

        .table tbody+tbody {
            border: 1px solid #dee2e6;
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
            line-height: 1;
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


        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: left;
            font-size: 10px;
            color: #777;
            padding: 10px;
            background-color: transparent;
        }

        .page-break {
            page-break-after: always;
        }

        /* For multi-page PDFs */
        @media print {
            footer {
                position: fixed;
                bottom: 0;
            }
        }
    </style>
    </head>

    <body>
        <div id="watermark">
            <img src="assets/img/misana.ico" alt="logo" height="40" width="80" style="float:right;padding-right:62px;margin-top:5px;">
            <!-- <img src="assets/img/misana.png" height="70%" width="80%" style="opacity: 0.05;"/> -->
        </div>
        <section class="container">
            <div class="box" style="margin-top:0px;">
            <div>
                    <span style="font-family:sans-serif; font-size:10px;">
                        <?php
                        date_default_timezone_set("Africa/Nairobi");
                        echo 'Downloaded by ' . $loggedInUser->name . ' on ' . date("D d, M Y") . ' ' . date("h:i:sa");
                        ?>
                    </span>
                </div>
                <center>
                    <h1 style="color:red;"><strong>MISANA HOME BAKERY</strong></h1>
                </center>
                <h5 style="margin-left:30px;margin-top:10px;"><strong style="color:green">Material Report <br> Status-<span id="batch_number_report"> <?php echo $status; ?></span>
                        <br> Date-<span id="date_report"><?php echo $start; ?> </span>to<span id="date_report"> <?php echo $end; ?></span>

                    </strong></h5>

                <table class="table table table-striped">
                    <thead>
                        <?php
                        $category = array('#', 'Batch', 'Product');
                        foreach ($categories as $i) {
                            if ($i->category_name == 'Umeme' || $i->category_name == 'Taka' || $i->category_name == 'Maji' || $i->category_name == 'Gas') {
                            } else {
                                array_push($category, $i->category_name);
                            }

                            // dd($i);
                            // dd($category);
                        }
                        // dd($category)
                        ?>
                        <tr>
                            <?php
                            foreach ($category as $i) {
                            ?>
                                <th><?php echo $i ?></th>
                            <?php }
                            ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // create the table rows count
                        $index = 1;
                        $general = array();
                        // dd($batches);
                        foreach ($batches as $batch) { // each batch
                            $loop = array_fill(0, sizeof($category), 0);
                        ?>
                            <tr>
                                <?php
                                //in each batch
                                //foreach ($category as $i) { // each category

                                // }

                                foreach ($category as $key => $i) { // each category
                                    // dump(sizeof($loop), sizeof($category));  
                                    if ($i == '#') {
                                        $loop[$key] = $index;
                                    } elseif ($i == 'Batch') {
                                        $loop[$key] = $batch->batch_number;
                                    } elseif ($i == 'Product') {
                                        if ($into_store->contains('batch_number', $batch->batch_number)) {
                                            foreach ($into_store as $into) { // each into store
                                                if (!in_array($into->product_name, $loop, true)) {
                                                    if ($into->batch_number == $batch->batch_number) {
                                                        $loop[$key] = $into->product_name;
                                                    }
                                                    //push product
                                                }
                                            }
                                        } else {
                                            $loop[$key] = 'unknown';
                                        }
                                        // array_push($loop, $batch->batch_number);
                                    } else {
                                        // dump($i);
                                        if ($into_store->contains('category_name', $i)) {
                                            foreach ($into_store as $key1 => $into) {
                                                # each into store
                                                if ($into->batch_number == $batch->batch_number && $into->category_name == $i) {
                                                    $loop[$key] = $into->qty . ' ' . $into->symbol;
                                                }
                                            }
                                        } else {
                                            $loop[$key] =  0.0;
                                        }
                                    }
                                }
                                // dump(sizeof($loop), sizeof($category));
                                // if(sizeof($loop) < sizeof($category)){
                                //     for($i=sizeof($loop); $i< sizeof($category); $i++){
                                //         array_push($loop, 0);
                                //     }  
                                // }


                                // dump($loop);
                                foreach ($loop as $i) {
                                ?>
                                    <td>
                                    <?php
                                    echo $i;
                                } ?>
                                    </td>
                                <?php
                                $index += 1;
                                array_push($general, $loop);
                            }
                            // dump($general);
                            $val = 0;
                            // for ($i=0; $i < sizeof($general) ; $i++) { 

                            // }
                            $last1 = array_fill(0, 3, '-');
                            $last = array_pad($last1, sizeof($category), intVal(0));
                            // foreach ($last as $key => $value) {
                            //     # code...
                            // }
                            // dump($last);
                            echo "am here";
                            foreach ($general as $key => $value) {

                                foreach ($value as $key1 => $value1) {
                                    // array_push($last,$value1);
                                    if ($key1) {
                                        $val = floatVal(explode(' ', $value1)[0]);
                                        //dump(gettype($last[$key1]));
                                        if (gettype($last[$key1]) == 'integer' || gettype($last[$key1]) == 'double') {
                                            $last[$key1] += floatVal($val);
                                        }
                                    }
                                }
                                //    foreach ($value as $key1 => $value1) {
                                //     $val = intVal(explode(' ', $value1)[0]);
                                // //    $last[$key1] += $val;
                                //    }
                            }
                            // dump($general);
                                ?>
                            </tr>
                            <tr>
                                <?php
                                // dump($last); 
                                foreach ($last as $i) {
                                ?>
                                    <td>
                                        <?php
                                        echo $i;
                                        ?>
                                    </td>
                                <?php
                                }
                                ?>
                            </tr>
                    </tbody>
                </table>
                

            </div>

            <!-- jQuery 3 -->
            {{--<script src="{{  asset('assets/bower_components/jquery/dist/jquery.min.js') }} "></script>--}}
            <!-- Bootstrap 3.3.7 -->
            {{--<script src="{{  asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }} "></script>--}}
            <!-- AdminLTE App -->
            {{--<script src="{{  asset('assets/dist/js/adminlte.min.js') }}"></script>--}}
    </body>

</html>