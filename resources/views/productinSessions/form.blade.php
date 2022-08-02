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
                        <div class='col-md-6' style="margin-bottom:10px;">
                        <label>batch number</label>
                        <input type="text" class="form-control" id="batch_number" name="batch_number"  placeholder="" required readonly >
                        </div>
                        <div class="col-md-6" style="margin-bottom:10px;" >
                        <label>Purchase date</label>
                        <input type="date" class="form-control" id="date" name="date"  placeholder="" required >
                        </div>
                        <hr style="margin-top:10px;margin-left:5px; color:black; width:99%;"></hr>  
                        <div class="col-md-12" id="materials">
                            
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



    


    


