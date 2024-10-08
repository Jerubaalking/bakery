
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
                white-space: nowrap;
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
                white-space: nowrap;
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
                margin:5px;
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
       
        <div>
            <small style="font-family:sans-serif"><?php 
            date_default_timezone_set("Africa/Nairobi");
            echo '>>> '.date("D d, M Y").' '.date("h:i:sa").' <<<'
            ?></small>
        </div>
       
    <div id="watermark">
    <img src="assets/img/misana.png" alt="logo" height="100" width="150" style="float:right;padding-right:22px;margin-top:-25px;">
    <img src="assets/img/misana.png" height="100%" width="100%" style="opacity: 0.05;"/>
        </div>
      <section>
      <div class="box" style="margin-top:80px;">
    
      <center><h1 style="color:red;"><strong>Misana Home Bakery</strong></h1></center>
      <center><h3 style="margin-left:30px;margin-top:-10px;"><strong style="color:green">Material Report</strong></h3></center>

        <table class="table table table-striped">
        <thead>
            <tr>
                    <th scope="col" class="border-0 pl-0">Material</th>
                    <!-- <th scope="col" class="border-0 pl-0">Cost</th> -->
                    <th scope="col" class="border-0 pl-0">Available</th>
                   
                </tr>
            </thead>
            <tbody>
                {{-- Items --}}
                <?php
                  foreach($materials as $material){ ?>
                <tr>
                    <td class="pl-0" style="margin:20px;">
                        <?php echo $material->name; ?>
                     </td>
                     <!-- <td class="pl-0">
                        <?php //echo $material->cost; ?>
                    </td> -->
                     <td class="pl-0" style="margin:20px;">
                        <?php echo number_format($material->available, 0).' '.$material->symbol;  ?>
                    </td>
                </tr>
                <?php } ?>
         
                @endforeach
                </tbody>
               </table>

        </div>
   


