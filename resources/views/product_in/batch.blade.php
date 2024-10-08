<div class="modal fade" id="modal-materialData" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header"><center>
                    <h3 style="color:red; margin-bottom:5px;"><strong>MISANA HOME BAKERY</strong></h3>
                </center>
                <center>
                    <h5 style="margin-left:30px;margin-top:10px;"><strong style="color:green">Material Report <br> Batch-<span id="batch_number_report"></span>
                    <br> Date-<span id="date_report"></span>
                            <?php //echo $batch_number ?>
                        </strong></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="font-size:20px;">&times;</span></button>
            <h3 class="modal-title"></h3>
        </div>
        <div id="watermark"> 
                </center>
            <!-- <img src="assets/img/misana.png" alt="logo" height="50" width="90"
                style="float:right;"> -->
            <img src="assets/img/misana.png" height="100%" width="100%" style="opacity: 0.05;" />
        </div>
        <section class="modal-body">
            <div class="box" style="margin-top:-70%; z-index:0;">
               



                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="border-0 pl-0">Material</th>
                            <th scope="col" class="border-0 pl-0">Category</th>
                            <th scope="col" class="border-0 pl-0">Quantity</th>
                            <th scope="col" class="border-0 pl-0">Cost</th>
                        </tr>
                    </thead>
                    <tbody id="materialData-body">
                    </tbody>
                </table>

            </div>
        </section>

        <div class="modal-footer nonPrintable">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="printModal()" class="btn btn-primary subBtn">Print</button>
                </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
