<div class="modal fade" id="modal-report" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form  id="form-report" method="POST" action="supplier_report" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data" >
            {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="suppier_id" name="id">
                    <div class="box-body">
                    <div class="row">
                    <div class="col-md-6">
                            <label for="from" class="col-form-label">From</label>
                             <input type="date" class="form-control input-sm" id="from" name="from" required>
                   </div>
                  <div class="col-md-6">
                              <label for="from" class="col-form-label">To</label>
                              <input type="date" class="form-control input-sm" id="to" name="to"  required>
                  </div>
             

                      </div>
                <br></br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
              
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


    


    


