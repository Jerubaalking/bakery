<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-item" class="form-horizontal form-item bg-light " method="post" data-toggle="validator"
                enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header ">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="box-body">
                        <div class="row" style="overflow:hidden;">
                            <div class='col-md-12'>
                                <div class='col-md-6'>
                                    <label>Account</label>
                                    <select id="supplier_id" name="supplier_id" onchange="setEmployee()" class="form-control">
                                        <option disabled id="ophide">--select Account--</option>
                                        @foreach($account as $x)
                                        <option value="{{$x->id}}">{{$x->account_name}}</option>
                                        @endforeach
                                        <span class="help-block with-errors"></span>
                                    </select>
                                </div>
                                <div class='col-md-6 '>
                                    <label>Date</label>
                                    <input type="text" class="form-control" id="date_in" name="date_in"
                                        value="<?php $kevi = date('Y-m-d');
                                                echo $kevi ?>" required readonly>
                                    <span class="help-block with-errors"></span>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <h4 class="" style="margin:15px;">Dispatch Information</h4>
                                <div class='col-md-12' id="personal" style="max-height:45vh; overflow-y:scroll;">
                                </div>
                            </div>

                            <hr class="col-md-12">
                            </hr>
                            <div class="col-md-12">
                                <div class="col-md-12 form-group" id="expensive"
                                    style="max-height:45vh; overflow-y:scroll;">
                                </div>
                            </div>
                            <hr class="col-md-12">
                            </hr>
                            <div class="col-md-6">
                                <label for="sub_total" class="col-sm-3 col-form-label" align="right">Net Total</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly name="sub_total" class="form-control form-control-sm"
                                        id="sub_total1" value="0" required />
                                </div>
                            </div>

                            <!-- /.box-body -->

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" name="more_charge" id="more_charge" class="btn btn-success btn-default">+ add</button>
                    <button type="submit" class="btn btn-warning reset">Reset</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<div class="modal fade" id="modal-form-demage" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-demage" class="form-horizontal form-demage bg-light " method="post" data-toggle="validator"
                enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header ">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" value="">
                    <div class="box-body">
                        <div class="row" style="overflow:hidden;">
                            <div class='col-md-12'>
                                <div class='col-md-6'>
                                    <label>Account</label>
                                    <select id="supplier_id" name="supplier_id" class="form-control supplier_id_demage">
                                        <option select value="" disabled id="ophide">--select Account--</option>
                                        @foreach($account as $x)
                                        <option value="{{$x->id}}">{{$x->account_name}}</option>
                                        @endforeach
                                        <span class="help-block with-errors"></span>
                                    </select>
                                </div>
                                <div class='col-md-6 '>
                                    <label>Date</label>
                                    <input type="text" class="form-control date_in" id="date_in_demage" name="date_in"
                                        value="" required readonly>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4 class="" style="margin:15px;">Dispatch Information</h4>
                                <div class='col-md-12' id="personal" style="max-height:45vh; overflow-y:scroll;">
                                </div>
                            </div>

                            <hr class="col-md-12">
                            </hr>
                            <div class="col-md-12">
                                <div class="col-md-12 form-group" id="expensive-demage"
                                    style="max-height:45vh; overflow-y:scroll;">
                                </div>
                            </div>
                            <hr class="col-md-12">
                            </hr>
                            <div class="col-md-6">
                                <label for="sub_total_damages" class="col-sm-3 col-form-label" align="right">Net Total</label>
                                <div class="col-sm-6">
                                    <input type="text" value="0" onchange="" name="sub_total_damages" class="form-control form-control-sm"
                                        id="sub_total_damages" required readonly />
                                </div>
                            </div>

                            <!-- /.box-body -->

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <!--<button type="button" name="more_charge" id="more_charge"  class="btn btn-success btn-default">+ add</button>-->
                    <button type="reset" class="btn btn-warning reset">Reset</button>
                    <button type="submit" class="btn btn-primary save">Save</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->