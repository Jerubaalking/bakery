<div class="modal fade" id="modal-employee" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  id="form-employee" data-toggle="validator" enctype="multipart/form-data" >
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Add Expense Type</h3>
                </div>
                <div class="modal-body"> 
                    <div class="box-body">
                   <input type="hidden" id="id" name="id">
                   <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"  autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"  autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                              </div>
                         </div>
                       
                       
                        <div class="form-group">
                            <label >Gender</label>
                            <select class="form-control" id="gender" name="gender">
                            <option disabled>--select gender--</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <span class="help-block with-errors"></span></select>
                          
                        </div>
                        <div class="form-group">
                            <label >Birthday</label>
                            <input type="date" class="form-control" id="birthday" name="birthday"   required>
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="form-group">
                            <label >Address</label>
                            <input type="text" class="form-control" id="address" name="address"   required>
                            <span class="help-block with-errors"></span>
                        </div>

                        <!-- <div class="form-group">
                            <label >Email</label>
                            <input type="email" class="form-control" id="email" name="email"   required>
                            <span class="help-block with-errors"></span>
                        </div> -->

                        <div class="form-group">
                            <label >Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"   required>
                            <span class="help-block with-errors"></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Position</label>
                            <select class="form-control" id="position_id" name="position_id">
                            <option disabled>--select postion--</position>
                            @foreach($position as $p)
                            <option value="{{$p->id}}">{{$p->position_name}}</position>
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
