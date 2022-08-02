<div class="modal fade" id="modal-deposite" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  id="form-deposite" data-toggle="validator" enctype="multipart/form-data" >
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Deposite Amount</h3>
                </div>

                <div class="modal-body"> 
                    <div class="box-body">
                   <input type="hidden" id="account_id" name="account_id">
                   <input type="hidden" id="staff_type" name="staff_type" value="asali">
                    <div class="row">
                        <div class="col-md-6">
                         <div class="form-group">
                            <label>Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount"  autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                        </div>
                        <div class="col-md-6">
                         <div class="form-group">
                            <label>Date</label>
                            <input type="text" class="form-control" id="deposite_date" name="deposite_date" value="<?php $date=date('Y-m_d');echo $date?>" readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                        </div>
                        <!-- <div class="col-md-4">
                         <div class="form-group">
                            <label>From</label>
                          <select id="from_where" name="from_where" class="form-control">
                          <option disabled>--select here</option>
                          <option value="mpesa">mpesa</option>
                          <option value="bank">bank</option>
                          </select>
                            <span class="help-block with-errors"></span>
                        </div>
                        </div> -->
                    </div> 
                    </div>
                    <!-- /.box-body -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
