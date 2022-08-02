@extends('layouts.master')


@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">

@endsection

@section('content')
    <div class="box">

                        <h3 class="title-5 m-b-35">ProductIn Details</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <div class="rs-select2--light rs-select2--md">
                                            <select class="js-select2" name="property">
                                                <option selected="selected">All Properties</option>
                                                <option value="">Option 1</option>
                                                <option value="">Option 2</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <div class="rs-select2--light rs-select2--sm">
                                            <select class="js-select2" name="time">
                                                <option selected="selected">Today</option>
                                                <option value="">3 Days</option>
                                                <option value="">1 Week</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <button class="au-btn-filter">
                                            <i class="zmdi zmdi-filter-list"></i>filters</button>
                                    </div>
                                    <div class="table-data__tool-right">
                                 
                                        <a  onclick="addForm()" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                            <i class="zmdi zmdi-plus"></i>Add</a>
                                    </div>
                                </div>
                                <br>


        <!-- /.box-header -->
        <div class="box-body">
        <form action="exportProduct_inAll" method="POST" enctype="multipart/form-data">
                           {{ csrf_field() }} {{ method_field('POST') }}
                            <div class="row">
                            <div class="col-md-2">
                            <label for="from" class="col-form-label">From</label>
                             <input type="date" class="form-control input-sm" id="from" name="from" required>
                             </div>
                              <div class="col-md-2">
                              <label for="from" class="col-form-label">To</label>
                              <input type="date" class="form-control input-sm" id="to" name="to"  required>
                              </div>
                               <div class="col-md-2">
                               <div class="form-group">
                               <label>Product</label>
                                 <select id="product_id1" name="product_id1"  class="form-control" >
                              <option disabled>--select group--</option>
                              <option value="all">All</option>
                              @foreach($products as $i)
                                  <option value="{{$i->id}}">{{$i->product_name}}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                            </div>
                                </div>
                              <div class="col-md-4" style="margin-top:28px;">
                            <button type="submit" class="btn btn-primary btn-sm" name="search" >Export Report</button>
                             </div>
                
                              </div>
                                </form>
                             <br>
                             <br>
                             <div class="table-responsive table-striped  table-responsive">
            <table id="products-in-table" class="table  table-striped table-data2">
                <thead>
                <tr>
                  
                    <th>Batch Number</th>
                    <th>Item Name</th>
                    <th>QTY</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="box col-md-6">

       

    {{--<div class="box-header">--}}
    {{--<a onclick="addForm()" class="btn btn-primary" >Add Products Out</a>--}}
    {{--<a href="" class="btn btn-danger">Export PDF</a>--}}
    {{--<a href="" class="btn btn-success">Export Excel</a>--}}
    {{--</div>--}}

  </div>

    @include('product_in.form')

@endsection

@section('bot')


<script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>


    <!-- InputMask -->
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('assets/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('assets/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>
    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    {{--<script>--}}
    {{--$(function () {--}}
    {{--$('#items-table').DataTable()--}}
    {{--$('#example2').DataTable({--}}
    {{--'paging'      : true,--}}
    {{--'lengthChange': false,--}}
    {{--'searching'   : false,--}}
    {{--'ordering'    : true,--}}
    {{--'info'        : true,--}}
    {{--'autoWidth'   : false--}}
    {{--})--}}
    {{--})--}}
    {{--</script>--}}

    <script>
        $(function () {

            //Date picker
            $('#tanggal').datepicker({
                autoclose: true,
                // dateFormat: 'yyyy-mm-dd'
            })

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false
            })
        })
    </script>

    <script type="text/javascript">
        var table = $('#products-in-table').DataTable({
            processing: true,
            serverSide: true,
            dom:'lBfrtip',
           "ScrollX": "100%",
           "scrollCollapse": true,
            buttons: [
            'excel', 'pdf', 'print'
           ],
           "lengthMenu": [10,20,30,50,100,500,1000,2000,5000,10000,50000,100000],
            ajax:  "{{ url('apiProducts_in') }}",
            columns: [
             
                {data: 'batch_number', name: 'batch_number'},
                {data: 'products_name', name: 'products_name'},
                {data: 'qty', name: 'qty'},
                {data: 'date_in', name: 'date_in'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            $('#form-item')[0].reset();
            save_method = "add";
            $(".subBtn").attr("disabled",false);
            $('#product_id').html("");
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
         
            $('.modal-title').text('Add Finished Products');
           
            $('#materials').html('');
            get_process_batches();
            populateModal();
           
        }
        function populateModal(){
            $('#batch_notify').removeClass('text-danger');
            $('#batch_notify').removeClass('text-success');
            $('#batch_notify').text('');
            $("#indexer").val(0)

            var id=$('#batch_number').val();
            
            var allBatches = JSON.parse('<?php echo $batches ?>');
            var mat_val = 0;
            console.log(allBatches);
            for(let batch of allBatches){
                if(batch.batch_number == id){
                    console.log(batch.material_value, id, batch.manufacture_date);
                    mat_val += parseInt(batch.material_value);
                    $('#manufacture_date').val(batch.manufacture_date);
                }
            }  
            
            $('#batch_value').val(mat_val);          
        }
        function get_process_batches(){
            $('#batch_number').html('');
            $.ajax({
                    url:"{{ url('get_process_batches') }}",
                    success: function(html) {
                        var allBatches = JSON.parse( JSON.stringify(html));
                        console.log(allBatches);
                                $('#batch_number').append('<option value="" disabled selected>--select batch number--</option>');
                        for(let batch of allBatches){
                                $('#batch_number').append('<option value="'+batch.batch_number+'">'+batch.batch_number+'</option>'); 
                                
                        }  
                    }
            })
        }
        function populateCurrentstock(prev){
            var id= $(`.selectProduct_id${prev}`).val();
            $.ajax({
                      url :'check_stock/'+id,
                      success : function(html) {
                         $(`.inputStock${prev}`).val(html.data[0].available)
                       },
            });
            // $(`.inputStock${prev}`).val(stockAvailable);
                  
        }
        
        function populateProducts(prev){
            // var tr = document.querySelector(`.selectCategory_id${prev}`);
           
            var id= $(`.selectCategory_id${prev}`).val();
            
            var products = JSON.parse('<?php echo $products ?>')
            var products_in = JSON.parse('<?php echo $products_in ?>')
            var categories = JSON.parse('<?php echo $cat ?>')
            console.log("product_in::",products_in, "categories::", categories, 'products::', products);
            $(`.selectProduct_id${prev}`).html('');
            console.log(id)
                for(let product of products){
                    if(product.category_id == id){
                        console.log('products::', product);
                        
                        $(`.selectProduct_id${prev}`).append(`<option value='${product.id}'>${product.product_name}</option>`);
            //             // console.log(product_in.material_value, id, product_in.manufacture_date);
            //             // $('#batch_value').val(product_in.material_value); 
            //             // $('#manufacture_date').text(product_in.manufacture_date); 
                    }
                } 
                populateCurrentstock(prev);          
        }
        $('#category_id').on("change",function(){
        
            var id=$('#category_id').val();
              $.ajax({
                       url :'get_item/'+id,
                       success : function(html) {
                        $('#product_id').html("");
                      if(html.data!=0){
                        $.each(html.data, function(i, item){
                        $('#product_id').append(  
                            '<option value="'+html.data[i].id+'">'+html.data[i].product_name+'</option>' 
                            );
                       });
                       var id=$('#product_id').val();
             
                    $('#qty').val("");
           
                     $.ajax({
                      url :'check_stock/'+id,
                      success : function(html) {
                         $('#current_stock').val(html.data[0].available)
                       },
                 
                  });
                      }
                      else if(html.data==0){
                          alert("please Add in stock First")
                      }

                        },
                  
                   });
            
        })

        $('#product_id').on("keyup change",function(){

            if(Number($('#current_stock').val()==0)){       
              $('#qty').val("");
              }
          
            var id=$('#product_id').val();
             
              $('#qty').val("");
            
              $.ajax({
                       url :'check_stock/'+id,
                       success : function(html) {
                          $('#current_stock').val(html.data[0].available)
                        },
                  
                   });
        })
  
// $('#materials').delegate('#material_id','click',

    const onChangeElement = (qSelector, cb)=>{
    var target = document.querySelector(qSelector);
    if(target){
        const config = {
            attributes:true,
            childList:true,
            characterData: true
        }
        const callback = function(mutationsList, observer){
            var allObjects = [];
            var count = target.children.length;
                // console.log(target.children.length);
                
                    for(var i = 0; i<target.children.length;i++){
                        allObjects.push((($(target.children[i]).children()).children()).find('select'))
                        console.log((($(target.children[i]).children()).children()).find('select'));
                    }
            
            // target.children.forEach(mutation => {
            //     console.log("mutation:: ",mutation);
            //     // count = $(mutation.previousSibling.childElementCount);
            //     // allObjects.push(($(target.children[]).children()).children())
            // });
            
            cb(allObjects, count, observer);
            observer.disconnect();
        };
        var observer = new MutationObserver(callback)
        observer.observe(target, config);
        

    }else{
        console.log('onChangeElement:Invalid Selector');
    }
    }

$('#more_charge').on('click', function(){
    var prev = parseInt($("#indexer").val())+1;
    
    var id=$('#batch_number').val();

    console.log(prev);
    var tr = $(this).parent().parent();
        var html = '';
        html += '<span class="col-md-12" id="row'+prev +'"><div class="row" style="margin-top:5px;">';
        html += '<div class="col-md-2">';
        html +=  '<label>Categories</label><br>';
        html += '<select onchange="populateProducts('+prev+')" name="category_id[]" id="category_id[]" class="selectCategory_id'+prev+' form-control" style="width:100%;"> @foreach($cat as $x)<option value="{{$x->id}}">{{$x->cat_name}}</option>@endforeach</select>';
        html += '<span class="help-block with-errors"></span>';
        html += '</div>'; 
        html += '<div class="col-md-4">';
        html +=  '<label>Product</label><br>';
        html += '<select onchange="populateCurrentstock('+prev+')" name="product_id[]" id="product_id[]" class="selectProduct_id'+prev+' form-control" style="width:100%;"> </select>';
        html += '<span class="help-block with-errors"></span>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html +=  '<label>Quantity</label>';
        html += '<input type="text" name="qty[]"  id="qty[]" class="form-control inputQty'+prev+'" required placeholder="qty" value="1"/>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<label>Current Stock</label>';
        html += '<input type="text" class="form-control inputStock'+prev+'" name="current_stock[]" id="current_stock[]"  readonly>';
        html += '</div>';

        html += '<div class="col-md-2">';
        html +=  '<label>action</label><br>';
        
            html += '<button type="button" name="remove" id="'+prev +'" class="btn btn-danger btn-xs remove">-</button>';
        
        html += '</div>';
        html += '</div></div><br /></span>';
        if(id){ 
            $('#batch_notify').removeClass('text-danger');
            $('#batch_notify').addClass('text-success');
            $('#batch_notify').text( ' '+(prev)+' slot populated');
            $('#indexer').attr('value', prev);
            $('#materials').append(html);
            populateProducts(prev);
            populateCurrentstock(prev);
        }else{
            $('#batch_notify').text(' No product batch in process to finished goods!');
        }
    
    // tr.find(`.selectMaterial_id${prev}`).select2();
});
$('#materials').delegate('.remove', 'click', function(){
      
      var tr = $(this).parent().parent();
      
    var prev = parseInt($("#indexer").val())-1;
    $('#indexer').attr('value', prev);
    $('#batch_notify').removeClass('text-success');
    $('#batch_notify').addClass('text-danger');
    $('#batch_notify').text( ' '+(prev)+' slot remaining!');
      // tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
      // calculate(0,0);
      var row_no = $(this).attr("id");
      $('#row'+row_no).html('');
      $('#row'+row_no).remove();
      // tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
      //     calculate(0,0);
  });

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#form-item')[0].reset();
            $.ajax({
                url: "{{ url('productsIn') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(html) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Products In');
                    $('#id').val(html.data.id);
                    $('#product_id').val(html.data.product_id);
                    $('#qty').val(html.data.qty);
                 
                    $('#price').val(html.data.price);
                    $('#tprice').val(html.data.tprice);
                    $('#date_in').val(html.data.date_in);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('productsIn') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('productsIn') }}";
                    else url = "{{ url('productsIn') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        beforeSend:function(){
                        $(".subBtn").attr("disabled",true);
                        },
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            $(".subBtn").attr("disabled",false);
                            $('.subBtn').html("Please wait...");
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            $(".subBtn").attr("disabled",false);
                            $('.subBtn').html("submit");
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    </script>

@endsection
