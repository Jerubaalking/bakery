<div class="modal fade" id="modal-account" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog  modal-sm">
        <div class="modal-content">
            <form id="form-account" data-toggle="validator" enctype="multipart/form-data">
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Account Name</label>
                                    <input type="text" class="form-control" id="account_name" name="account_name" autofocus required>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Account group</label>
                                    <input type="text" class="form-control" id="account_group" name="account_group" autofocus required>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="" selected disabled>--select status--</option>
                                <option value="active">active</option>
                                <option value="not-active">not-active</option>
                                <span class="help-block with-errors"></span>
                            </select>

                        </div>

                        <div class="form-group">
                            <label>Department</label>
                            <select class="form-control" id="department_id" name="department_id">
                                <option disabled>--select department--</position>
                                    @foreach($departments as $p)
                                <option value="{{$p->id}}">{{$p->department_name}}</position>
                                    @endforeach
                            </select>
                        </div> 
                        <div class="form-group">
                            <label>Employee</label>
                            <select class="form-control" id="employee_id" name="employee_id">
                                <option disabled>--select employee--</position>
                                    @foreach($employees as $p)
                                <option value="{{$p->id}}">{{$p->first_name}} {{$p->last_name}}</position>
                                    @endforeach
                            </select>
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