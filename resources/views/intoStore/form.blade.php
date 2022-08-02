
  <script src="{{  asset('assets/bower_components/jquery/dist/jquery.min.js') }} "></script>
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
                    <input type="hidden" id="indexer" name="indexer" value="0">
                    <input type="hidden" id="id" name="id">
                    <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div class='col-md-6 ' >
                                <label>batch number</label>
                                <input type="text" class="form-control" id="batch_number" name="batch_number"  placeholder="" required readonly >
                                <small>This number is <strong>NEW</strong> everytime you open this page</small>
                            </div>
                            <div class="col-md-6">
                                <label>Date</label>
                                <input type="date" class="form-control" id="date" name="date"  placeholder="" required >
                            </div>
                        </div>
                        <div class="col-md-12 form-group receipt">
                            <div class="col-md-6"  >
                                <label>Receipt</label>
                                <input type="text" class="form-control receiptInput" id="receipt[]" name="receipt[]"  placeholder=""  >
                            </div>
                            <div class="col-md-6">
                                <div class='row'>
                                    <div class="col-12 col-md-4">
                                        <small>
                                            <p style="font-weight:600;">
                                                Value: <span class="text-success"><br> <span class="itemValtp">0</span> tzs</span>
                                            </p>
                                        </small>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <small>
                                            <p style="font-weight:600;">
                                                Slots: <span class="text-success"> <br><span class="slots">0</span> slots</span>
                                            </p>
                                        </small>
                                    </div>
                                    <div class="col-12 col-md-4">
                                            <small>
                                                <p style="font-weight:600;">
                                                    Count: <span class="text-success"><br><span class="counttp">0</span> items</span>
                                                </p>
                                            </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top:10px;margin-left:5px; color:black; width:99%;"></hr>  
                        <div class="col-md-12" id="materials" style="max-height:55vh; overflow-y:scroll; background-color:milky; opacity:0.9;">
                            
                        </div> 
                    </div>          
                </div>
                <div class="modal-footer">
                    <div>
                    <div style="float:left;">
                                       
                                        <small>
                                            <p style="font-weight:600;">
                                                Slots: <span class="text-success"> <br><span class="slots">0</span> slots </span>
                                            </p>
                                        </small>
                    </div>
                    <div>
                                            <small>
                                                <p style="font-weight:600;">
                                                    Count: <span class="text-success"><br><span class="countbtm">0</span> items</span>
                                                </p>
                                            </small>
                                            
                                            <small>
                                            <p style="font-weight:600;">
                                                Value: <span class="text-success"><br> <span class="itemValbtm">0</span> TZS</span>
                                            </p>
                                        </small>
                    </div>
                    </div>
                    
                    <button type="button" name="more_charge" id="more_charge"  class="btn btn-success btn-default">+ add</button>
                    <button type="button" onclick="add_expensive_row()"  name="more_charge1" id="more_charge1"  class="hidden btn btn-success btn-default">+ add</button>
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
<script>
        
        
        // onChangeElement('#materials', function(newElement, count){
        //     console.log('prev changed',$(`.selectMaterial_id${count}`).val(), count);
        //         for(var i=0; i<count; i++ ){
        //             $(`.selectMaterial_id${i+1}`).load(function(){
        //                     console.log("E::",$this);
        //                     // $(e).select2({
        //                     //     dropdownParent: $('#modal-form')
        //                     // });
        //                 })
        //         }
        //     // con
        // })
        // $(t).select2({
        //     dropdownParent: $('#modal-form')
        // });
        $(document).ready(function(){
                // var indexes = $('#indexer').val();
                // // for(var i = 1; i<=indexer; i++){
                //     $(`.selectMaterial_id${i}`).select2({
                //         dropdownParent: $('#modal-form')
                //     });
                // }
            // $('#materials').on('DOMSubtreeModified', function(){
            //     $('#materials span div div select').select2({
            //         dropdownParent: $('#modal-form')
            //     });
            // })
        })
</script>


    


    


