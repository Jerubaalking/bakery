<div class="modal fade" id="modal-account-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  id="form-account" data-toggle="validator" enctype="multipart/form-data" >
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Add Account</h3>
                </div>
                <div class="modal-body"> 
                    <div class="box-body">
                   <input type="hidden" id="id" name="id">
                    <div class="row">
                      
                      
                        
                                <div class="col-md-6">
                                <div class="form-group">
                            <label >Group</label>
                            <select id="account_group" name="account_group"  class="form-control" >
                                <option disabled>--select group--</option>
                                <option  value="Income">Income</option>
                                <option  value="Expenses">Expense</option>
                            </select>
                        
                            <span class="help-block with-errors"></span>
                        </div>
                        </div>
                        
                        <div class="col-md-6">
                         <div class="form-group">
                            <label>Account Name:</label>
                            <input type="text" class="form-control" id="account_name" name="account_name"  autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                    <label>Open Balance</label>
                    <input type="text" class="form-control" name="open_balance" id="open_balance" >
                    </div>
                    <div class="col-md-6">
                    <label>Open Date</label>
                        <input type="date" class="form-control" name="open_date" id="open_date" >
                    </div>
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
