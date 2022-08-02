<div class="modal fade" id="modal-demage" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form  id="form-demage" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data" >
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                    </div>
                   <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="normal" name="normal" value="normal">
                    <div class="box-body">
                    <div class="row">
                      <div class='col-md-6'>
                      <label>Sales By</label>
                      <input type="text" class="form-control" id="sale_by" name="sale_by" value="wait it will happen soon"   required readonly>
                      </div>
                      <div class='col-md-6'>
                      <label>Date</label>
                      <input type="text" class="form-control" id="date_in" name="date_in" value="<?php $kevi=date('Y-m-d');echo $kevi?>"   required readonly>
                     <span class="help-block with-errors"></span>
                      </div>
             
                      <hr>
                      </hr>
                      <div class="row">
                      <div class="col-md-12">
                   
                       <span id="personal"></span>
                      </div>
                      </div>
                      <hr></hr>
                      <div class="row">
                      <div class='col-md-12'>
                      <span id="expensive"></span>
                       </span>
                      </div>
                       </div>
                       <hr></hr>
                       <div class="row">
                       <label for="sub_total" class="col-sm-3 col-form-label" align="right">Net Total</label>
                      <div class="col-sm-6">
                      <input type="hidden" name="sales_by" class="form-control form-control-sm" id="sales_by"  value="saler"/>
                        <input type="text" readonly name="sub_total" class="form-control form-control-sm" id="sub_total2" required/>
                        
                      </div>
                    
                    </div>
                    <hr></hr>
                    </div>
                    <!--                   
                    <div class="form-group row">
                      <label for="discount" class="col-sm-3 col-form-label" align="right">DISCOUNT</label>
                      <div class="col-sm-6">
                        <input type="text" name="discount" class="form-control form-control-sm" id="discount"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="net_total" class="col-sm-3 col-form-label" align="right">TOTAL EXCLUSIVE OF VAT</label>
                      <div class="col-sm-6">
                        <input type="text" readonly name="net_total" class="form-control form-control-sm" id="net_total" required/>
                      </div>
                    </div> -->
                    <!-- <div class="form-group row">
                      <label for="due" class="col-sm-3 col-form-label" align="right">Due</label>
                      <div class="col-sm-6">
                    </div> -->
                   
                    <!-- /.box-body -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning reset">Reset</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


    


    


