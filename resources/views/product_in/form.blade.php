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
                    <button type="button" name="more_charge" id="more_charge"  class="btn btn-success btn-xs">+ add slot</button><span><small id="batch_notify"></small></span>
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="indexer" name="indexer" value="0">
                    <div class="row">
                            <div class='col-md-5'>
                                <label>Batch Number <small>available</small></label>
                                <select id="batch_number" onchange="populateModal()" name="batch_number" class="form-control">
                                <option disabled id="ophide" selected>--select Batch Number--</option>
                                @foreach($BatchOut as $batch_number)
                                <option value="{{$batch_number->batch_number}}">{{$batch_number->batch_number}}</option>
                                @endforeach
                                <span class="help-block with-errors"></span>
                                </select>
                            </div>
                            <div class='col-md-3'>
                                <label>Batch Value</label>
                                <input id="batch_value" type="text" name="batch_value" value="0" class="form-control" readonly required>
                            </div>
                            
                            <div class='col-md-3'>
                                <label>Manufacture Date</label>
                                <input id="manufacture_date" name="manufacture_date" class="form-control" readonly required>
                            </div>
                            <hr></hr>
                    </div>
                            <div class="row" id="materials" style="margin-top:-10px;">

                            </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary subBtn">Submit</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    var id=$('#product_id').val();
    $.ajax({
            url :'get_stock/'+id,
            success : function(html) {
                if(html.length == 0){
                $('#batch_value').attr('value',0);
                }else{
                $('#batch_value').val(html.data[0])
                }
            },

    });

    var id=$('#category_id').val();
              $.ajax({
                       url :'get_item/'+id,
                       success : function(html) {
                
                        $.each(html.data, function(i, item){
                        $('#product_id').append(  
                            '<option value="'+html.data[i].id+'">'+html.data[i].product_name+'</option>' 
                            );
                       });
                       
            
                        },
                  
                   });
</script>

