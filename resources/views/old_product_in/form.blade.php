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
                     <div class='col-md-5'>
                      <label>Category Name</label>
                        <select id="category_id" name="category_id" class="form-control">
                        <option disabled id="ophide">--select Category Name--</option>
                         @foreach($cat as $cat)
                         <option value="{{$cat->id}}">{{$cat->cat_name}}</option>
                        @endforeach
                        <span class="help-block with-errors"></span>
                        </select>
                     </div>
                     <div class='col-md-5'>
                      <label>Item Name</label>
                        <select id="product_id" name="product_id" class="form-control" required>
                        <span class="help-block with-errors"></span>
                        </select>
                     </div>
                     <div class="col-md-2">
                     <label>Current Stock</label>
                     <input type="text" class="form-control" id="current_stock" readonly>
                    </div>     
                    </div>
                    <hr></hr>
                   </div>
                     <div class="row">
                     <div class="col-md-6">
                     <label >Quantity</label>
                     <input type="text" class="form-control" id="qty" name="qty" value="1" required>
                     <span class="help-block with-errors"></span>
                    </div>
                    <div class="col-md-6">
                    <label>Date </label>
                    <input type="date" 
                    class="form-control" id="date_in" name="date_in" >
                    <span class="help-block with-errors"></span>
                    <div class="row">
                  
                     <!-- <div class="col-md-6">
                            <label >Cost</label>
                            <input type="text" class="form-control" id="price" name="price" required >
                            <span class="help-block with-errors"></span>
                        
                     </div>
                     <div class="col-md-6">
                            <label >Total Price</label>
                            <input type="text" class="form-control" id="tprice" name="tprice"  readonly>
                            <span class="help-block with-errors"></span>
                        
                    </div> -->
                    </div>
                
                   
                 
                    </div>
               
                    <!-- /.box-body -->

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

    

