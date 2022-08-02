<div class="modal fade" id="modal-depo" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form  id="form-depo" data-toggle="validator" enctype="multipart/form-data" >
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Deposite</h3>
                </div>

                <div class="modal-body"> 
                    <div class="box-body">
            
                    <div class="row">
                       
                          <div class="col-md-4">
                            <div class="form-group">
                            <label >Select Account</label>
                            <select id="account_id" name="account_id"  class="form-control" >
                            <option disabled>--select group--</option>
                            @foreach($account as $i)
                            <option value="{{$i->id}}">{{$i->account_name}} ({{number_format($i->account_balance,2)}})</option>
                            @endforeach
                         
                            </select>
                          
                            <span class="help-block with-errors"></span>
                        </div>
                        </div>
                        <div class="col-md-4">
                         <div class="form-group">
                            <label>Check number</label>
                            <input type="text" id="check_number" name="check_number" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        </div>
                        <div class="col-md-4">
                         <div class="form-group">
                            <label>Memo</label>
                             <textarea name="memo" id="memo" class="form-control" required></textarea>
                            <span class="help-block with-errors"></span>
                        </div>
                        </div>
                    </div> 
                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                            <label>Amount</label>
                            <input type="text" name="amount" id="amount" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="deposite_date" id="deposite_date" 
                             class="form-control" require>
                            <span class="help-block with-errors"></span>
                        </div>
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
