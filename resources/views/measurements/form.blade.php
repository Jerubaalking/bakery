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
                 
                      <div class='col-md-6'>
                      <label>Measurement</label>
                      <input type="text" class="form-control" id="measurement" name="measurement"  placeholder="eg, Grams"  required >
                      </div>
                      <div class='col-md-6'>
                      <label>symbol</label>
                      <input type="text" class="form-control" id="symbol" name="symbol"  placeholder="eg, g for grams"  required >
                      </div>
                      <div class='col-md-6'>
                      <label>type</label>
                      <input type="text" class="form-control" id="type" name="type"  placeholder="imperial..."  required >
                      </div>
                      <div class='col-md-6'>
                      <label>description</label>
                      <textarea type="text" class="form-control" id="description" name="description"  required ></textarea>
                     <span class="help-block with-errors"></span>
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


    


    


