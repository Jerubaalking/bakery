<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form  id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data" >
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="box-body">
                        <div class="row">
                            <div class='col-md-12 form-group'>
                                <div class='col-md-6'>
                                    <label>name</label>
                                    <input type="text" class="form-control" id="name" name="name"  placeholder="eg Bakresa chapa maandazi"  required >
                                </div>
                                <div class="col-md-6">
                                    <label>Measurement</label>
                                    <select id="measurement_id" name="measurement_id"  class="form-control" >
                                    <option disabled>--select measurement--</option>
                                    @foreach($measurements as $i1)
                                    <option value="{{$i1->id}}">{{$i1->measurement}}</option>
                                    @endforeach
                                    </select>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="col-md-6">
                                    <label>Category</label>
                                    <select id="category_id" name="category_id"  class="form-control" >
                                    <option disabled>--select category--</option>
                                    @foreach($material_categories as $i4)
                                    <option value="{{$i4->id}}">{{$i4->category_name}}</option>
                                    @endforeach
                                    </select>
                                    <span class="help-block with-errors"></span>
                                </div>
                                <div class='col-md-6'>
                                    <label>Cost per measurement</label>
                                    <input type="text" class="form-control" id="unit_cost" name="unit_cost"  placeholder="0" required >
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="reset" class="btn btn-warning reset">Reset</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


    


    


