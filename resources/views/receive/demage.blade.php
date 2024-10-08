<div class="modal fade" id="modal_demage" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <form id="form-demage" method="post" class="form-horizontal bg-light" data-toggle="validator"
                enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header ">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>
                <div class="modal-body box-body">
                    <input type="hidden" id="id" name="id">
                    
                    <div class="row" style="overflow:hidden;">
                            <div class='col-md-12'>
                                <div class='col-md-6'>
                                    <label>Account</label>
                                    <select id="supplier_id1" name="supplier_id1" class="form-control">
                                        <option disabled id="ophide">--select Account--</option>
                                        @foreach($empo as $x)
                                        <option value="{{$x->id}}">{{$x->employee_number}}</option>
                                        @endforeach
                                        <span class="help-block with-errors"></span>
                                    </select>
                                </div>
                                <div class='col-md-6 '>
                                    <label>Date</label>
                                    <input type="text" class="form-control" id="date_in" name="date_in"
                                        value="<?php $kevi=date('Y-m-d');echo $kevi?>" required readonly>
                                    <span class="help-block with-errors"></span>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <h4 class="" style="margin:15px;">Demaged Information</h4>
                            </div>
                            <div class='col-md-12' id="demage_stock" style="max-height:45vh; overflow-y:scroll;">
                            </div>
                            <hr><hr>
                            <div class="col-md-6">
                                <label for="sub_total" class="col-sm-3 col-form-label" align="right">Net Total</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly name="sub_total" class="form-control form-control-sm"
                                        id="sub_total" required />
                                </div>
                            </div>

                            <!-- /.box-body -->

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" name="more_charge" id="more_charge"  class="btn btn-success btn-default">+ add</button>
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



    


    


