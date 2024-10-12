<div class="modal fade" id="modal_payment" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-payment" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="reset" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="task_id_receive" name="task_id_receive">
                    <input type="hidden" id="task_number" name="task_number">
                    <input type="hidden" id="employee_id" name="employee_id">
                    <div class="box-body row">
                        <div class="col-md-12 row">
                            <div class='col-md-6 offset-md-1 flex' style="display: inline-block;">
                                <small class="receive_account" style="float:inline-start">Account</small><br>
                                <small class="receive_employee_number" style="float:inline-start">Date:</small><br>
                                <small class="receive_date" style="float:inline-start">Latest payment:</small>
                            </div>
                            <div class='col-md-6 offset-md-3' style="display: inline-block;">
                                <div class="" style="float:inline-end">
                                    <small class="text-danger">* Select Payment Method </small>
                                    <select name="payment_methode" id="payment_methode" class="form-control">
                                        <option value="" selected disabled>Select payment method</option>
                                        <option value="cash">Cash</option>
                                        <option value="mobile">Mobile</option>
                                        <option value="bank">Bank</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <div class="mr-3" style="float:inline-end; margin-right:24px;">
                                    <small class="text-danger">* Pay date </small>
                                    <input type="date" name="pay_date" id="pay_date" value="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <hr class="col-md-12">
                        </hr>
                        <div class="col-md-12 row">
                            <div class="col-md-12">
                                <h5 class="" style="margin:10px;">Receive Task</h5>
                                <div class='col-md-12 receive_task' id="receive_task" style="max-height:45vh; overflow-y:scroll;">
                                </div>
                            </div>
                        </div>
                        <hr class="col-md-12">
                        </hr>
                        <div class="col-md-12 row">
                            <div class="col-md-4">
                                <strong for="paid_total" class="flex">Paid Total</strong>
                                <div class="">
                                    <input min="0" style="font-family: monospace;" value="0" style="color:brown;" onchange="" name="paid_total" class="form-control"
                                        id="paid_total" readonly />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <strong for="expected_total" class="flex" align="right">Expected Total</strong>
                                <div class="">
                                    <input min="0" type="text" value="0" style="color:brown;" onchange="" name="expected_total" class="form-control"
                                        id="expected_total" required readonly />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="received_total" class="flex" align="right">Received Total</label>
                                <div class="">
                                    <input min="0" type="number" value="0" onchange="" name="received_total" class="form-control"
                                        id="received_total" required />
                                </div>
                            </div>
                        </div>

                        <!-- /.box-body -->

                    </div>
                </div>


                <div class="modal-footer" style="margin-top: 3vh;">
                    <button type="button" onclick="reset" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->