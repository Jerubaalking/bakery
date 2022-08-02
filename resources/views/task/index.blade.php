@extends('layouts.master')
@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
<div class="box" style="margin-top:30px;">
        <div class="box-body" >
        <h3 class="title-5 m-b-35" >Task Detatails</h3>
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
                                 
                                        <a id="add_btn" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                            <i class="zmdi zmdi-plus"></i>Add</a>
                                    </div>
                                </div>
                                <br>
                     <div class="box-body" style="margin-top:12px;">
                       <form action="exportTask" method="POST" enctype="multipart/form-data">
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
                              <div class="col-md-4" style="margin-top:28px;">
                            <button type="submit" class="btn btn-primary btn-sm" name="search" >Export Report</button>
                             </div>
                
                              </div>
                                </form>
                             <br>
                             <br>
        <div class="table-responsive table-striped  table-responsive">
          <table id="task-table" class="table  table-striped table-data2">
          <thead>
        
          <tr>
                <th>Supplier Name</th>
                <!-- <th>Supplier Number</th> -->
                <!--<th>Task Number</th>-->
                <th>Total Amount</th>
                <th>Amount Paid</th>
                <th>Amount due</th>
                <th>Product  Returned Cost</th>
                <th>Product demage Cost</th>
                <th>Date</th>
                <th></th>
          </tr>
          </thead>
       
              <tbody>
         
              </tbody>
      
     
      </table>
      </div>
        </div>
        <!-- /.box-body -->
    </div>
        <div class="box" style="margin-top:30px;">
        <div class="box-body" >
        <h3 class="title-5 m-b-35" style="color:red">Closed Tasks</h3>
        <div class="table-responsive table-striped  table-responsive">
          <table id="task-table1" class="table  table-striped table-data2">
          <thead>
        
          <tr>
                <th>Supplier Name</th>
                <!-- <th>Supplier Number</th> -->
                <!--<th>Task Number</th>-->
                <th>Total Amount</th>
                <th>Amount Paid</th>
                <th>Amount due</th>
                <th>Product Return Cost</th>
                <th>Product demage Cost</th>
                <th>Date</th>
                <th></th>
          </tr>
          </thead>
       
              <tbody>
         
              </tbody>
      
     
      </table>
      </div>
        </div>
        @include('demage_products.form');
        <!-- /.box-body -->
    </div>

   <div class="box col-md-6">
  
     @include('receive.pay');
    {{--<div class="box-header">--}}
    {{--<a onclick="addForm()" class="btn btn-primary">Create</a>--}}
    <!-- {{--<a href="" class="btn btn-danger">Export PDF</a>--}}
    {{--<a href="" class="btn btn-success">Export Excel</a>--}} -->
    {{--</div>--}}   
    </div>
 
@include('task.form');



@endsection

@section('bot')

@include('stock_return.form');
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
         var table = $('#task-table').DataTable({
            processing: true,
            serverSide: true,
            dom:'lBfrtip',
           "ScrollX": "100%",
           "scrollCollapse": true,
            buttons: [
            'excel', 'pdf', 'print'
           ],
           "lengthMenu": [50,100,500,1000,2000,5000,10000,50000,100000],
            ajax:  "{{ url('apiTask') }}",
            columns: [
                {data: 'first_name', name: 'first_name'},
               // {data: 'employee_number', name: 'employee_number'},
                // {data: 'task_number', name: 'task_number'},
                {data: 'sub_total', name: 'sub_total'},
                {data: 'amount_paid', name: 'amount_paid'},
                {data: 'amount_due', name: 'amount_due'}, 
                {data: 'returned', name: 'returned'}, 
                {data: 'demage_cost', name: 'demage_cost'}, 
                {data: 'created_at', name: 'created_at'}, 
                // {data: 'adds', name: 'adds'}, 
          
                {data: 'action', name: 'action', orderable: false, searchable: false}
               
            ]
        });
        var table1 = $('#task-table1').DataTable({
            processing: true,
            serverSide: true,
            ajax:  "{{ url('apiTask1') }}",
            columns: [
                {data: 'first_name', name: 'first_name'},
               // {data: 'employee_number', name: 'employee_number'},
                // {data: 'task_number', name: 'task_number'},
                {data: 'sub_total', name: 'sub_total'},
                {data: 'amount_paid', name: 'amount_paid'},
                {data: 'amount_due', name: 'amount_due'}, 
                {data: 'returned', name: 'returned'}, 
                {data: 'demage_cost', name: 'demage_cost'}, 
                {data: 'created_at', name: 'created_at'}, 
                // {data: 'adds', name: 'adds'}, 
          
                {data: 'action', name: 'action', orderable: false, searchable: false}
               
            ]
        });
     $(document).on('click','#add_btn',async function(){
  
          save_method = "add";
          $('#form-item')[0].reset();
          $('input[name=_method]').val('POST');
          $('#modal-form').modal('show');
        
          $('.modal-title').text('Task Form');
          $('#personal').html('');
          $('#expensive').html('');
          $('#return_stock').html('');
          add_expensive_row();
          calculate(0,0);

       var tr = $(this).parent().parent(); 
            var product_id=tr.find("#product_id").val() ;
          
            await $.ajax({
                url:"check_stock/"+product_id,
                success: function(html) {  
              var stock= tr.find(".stock").val(html.data[0].available);
              var qty= tr.find("#qty").val();
                
              }
     
        
         });
         var id=$("#supplier_id").val();
            $.ajax({
                url:"empo_info/"+id,
                success: function(html) {  
                    $('#first_name1').val(html.data[0].first_name); 
                    $('#last_name1').val(html.data[0].last_name); 
                    $('#phone_number1').val(html.data[0].phone);
              }
     
           })
    var html = '';
    html += '<div class="col-md-4">';
    html += '<input type="text" name="first_name" id="first_name1" class="form-control " readonly>';
    html += '</div>';
    html += '<div class="col-md-4">';
    html += '<input type="text" name="last_name"  id="last_name1" class="form-control" readonly/>';
    html += '</div>';
    html += '<div class="col-md-4">';
    html += '<input type="text" name="phone_number"  id="phone_number1" class="form-control" readonly/>';
    html += '</div>';

    $('#personal').append(html);
  
  
        });

 $("#expensive").delegate("#qty","keyup",function(){
     var qty=$(this);
    var tr = $(this).parent().parent();
    if(qty.val()==""){
        alert("please eneter a valid quantity");
        tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
      
    }
    else if(isNaN(qty.val())){
        alert("please eneter a valid quantity");
        qty.val(1);
        tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
    }
    
    else{
        if((qty.val()-0)>(tr.find(".stock").val()-0)){
            alert("soory ! this much  of Quantity is not available");
            qty.val(1);
            tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
        }
        else{
          
   // tr.find("#amt").html(qty.val() * tr.find(".price").val());
      tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
     
        }
    }


})
$("#expensive").delegate("#price","keyup",function(){
    var tr = $(this).parent().parent();
    var price=$(this);
    if(isNaN(price.val())){
        alert("please eneter a valid amount");
        price.val("");    

    tr.find(".amt").html("0");
     
    }
    else if(price.val()==0){
        alert("please eneter a valid amount");
        price.val("");
       
    tr.find(".amt").html("0");
			
    }
  else{
       // tr.find("#amt").html(qty.val() * tr.find(".price").val());
    tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
     
  }
})

$("#expensive").delegate('#product_id','change',function(){
       var tr = $(this).parent().parent(); 
            var product_id=tr.find("#product_id").val() ;
          
            $.ajax({
                url:"check_stock/"+product_id,
                success: function(html) {  
              var stock= tr.find(".stock").val(html.data[0].available);
              var qty= tr.find("#qty").val();
                
              }
     
           })
         });

     $("#supplier_id").on('change',function(){

            var id=$("#supplier_id").val();
            $.ajax({
                url:"empo_info/"+id,
                success: function(html) {  
                    $('#first_name1').val(html.data[0].first_name); 
                    $('#last_name1').val(html.data[0].last_name); 
                    $('#phone_number1').val(html.data[0].phone);
              }
     
           })
         });


 $("#return_stock").delegate("#qty","keyup",function(){
   
    var qty=$(this);
    var tr = $(this).parent().parent();
  if(qty.val()==""){
        alert("please eneter a valid quantity");
        tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
      
    }
 else if(isNaN(qty.val())){
        alert("please eneter a valid quantity");
        qty.val(1);
        tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
    }else{
        if((qty.val()-0)>(tr.find("#qty1").val()-0)){
            alert("soory ! this much  of Quantity is not available");
            qty.val(1);
            tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
        }
        else{
          
   // tr.find("#amt").html(qty.val() * tr.find(".price").val());
      tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
     
        }
    }
  
     
})
$("#return_stock").delegate("#price","keyup",function(){
    var tr = $(this).parent().parent();

   // tr.find("#amt").html(qty.val() * tr.find(".price").val());
    tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
     
})



$("#form-demage").delegate("#qty","keyup",function(){
   
   var qty=$(this);
   var tr = $(this).parent().parent();
 if(qty.val()==""){
       alert("please eneter a valid quantity");
       tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
               calculate(0,0);
     
   }
else if(isNaN(qty.val())){
       alert("please eneter a valid quantity");
       qty.val(1);
       tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
               calculate(0,0);
   }else{
       if((qty.val()-0)>(tr.find("#qty1").val()-0)){
           alert("soory ! this much  of Quantity is not available");
           qty.val(1);
           tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
               calculate(0,0);
       }
       else{
         
  // tr.find("#amt").html(qty.val() * tr.find(".price").val());
     tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
               calculate(0,0);
    
       }
   }
 
    
})
$("#form-demage").delegate("#price","keyup",function(){
   var tr = $(this).parent().parent();

  // tr.find("#amt").html(qty.val() * tr.find(".price").val());
   tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
               calculate(0,0);
    
})

$("#amount").on("keyup",function(){
if( Number($('#amount').val())>Number($('#amount_due').val())){

    alert("hey!! check amount due");
    $('#amount').val("");

}

if(Number($('#amount').val())<0){
    alert('Please check amount you enter')
    $('#amount').val("");
}
     
})






function calculate(dis,paid){
    var sub_total = 0;
    var gst = 0;
    var net_total = 0;
    var discount = dis;
    var paid_amt = paid;
    var due = 0;
    $(".amt").each(function(){
        sub_total = sub_total + ($(this).html() * 1);
      
    })
    $("#sub_total1").val(sub_total);
    $("#sub_total").val(sub_total);
    $("#sub_total2").val(sub_total);
    $("#discount").val(discount);
    net_total = net_total - discount;



}


$(document).on('click','.reset',function(){
    $('#modal-form form')[0].reset();
   
})





/*Order Accepting*/
 function add_expensive_row(count_charge = ''){
   var tr = $(this).parent().parent();
     var html = '';
      html +='<div class="col-md-12 form-group" style="margin:10px;">'
      html += '<span id="row'+count_charge +'" >';
    //   html += '<div class="col-md-3">';
    //   html += '<input type="text" name="title[]" id="title'+count_charge+'" class="form-control selectpicker" data-live-search="true" required  placeholder="title"></textarea>';
    //   html += '</div>';
      html += '<div class="col-md-3">';
      html +=  '<label>Items Name</label>';
      html += '<select  name="product_id[]" id="product_id" class="form-control"><option selected disabled>--select product--</option> @foreach($product as $x)<option value="{{$x->id}}">{{$x->product_name}}</option>@endforeach</select>';
      html += '<span class="help-block with-errors"></span>';
      html += '</div>';
      html += '<div class="col-md-2">';
      html +=  '<label>Available</label>';
      html += '<input type="text" class="form-control stock"  readonly/>';

      html += '</div>';
      html += '<div class="col-md-2">';
      html +=  '<label>Qty</label>';
      html += '<input type="text" name="qty[]"  id="qty" class="form-control" required placeholder="qty" value="1"/>';

      html += '</div>';
      html += '<div class="col-md-2">';
      html +=  '<label>Price</label>';
      html += '<input type="text" name="price[]"  id="price" class="form-control" required placeholder="cost" >';
      html += '</div>';
    
      html += '<div class="col-md-1">';
      html +=  '<label>amt</label><br>';
      html += '<span class="amt">0</span>';
      html += '</div>';
      html += '<div class="col-md-2">';
      html +=  '<label>action</label><br>';
      if(count_charge == '')
      {
        html += '<button type="button" name="more_charge" id="more_charge" class="btn btn-success btn-xs">+</button>';
      }
      else
      {
        html += '<button type="button" name="remove" id="'+count_charge +'" class="btn btn-danger btn-xs remove">-</button>';
      }
      html += '</div>';
      html += '</div></div><br /></span></div>';
  
      $('#expensive').append(html);

      tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
        }
        var count_charge = 0;
    $(document).on('click', '#more_charge', function(){
        var tr = $(this).parent().parent(); 
            var product_id=tr.find("#product_id").val() ;
            $.ajax({
                url:"check_stock/"+product_id,
                success: function(html) {  
              var stock= tr.find(".stock").val(html.data[0].stock);
              var qty= tr.find("#qty").val();
                
              }
        
         });
        
     count_charge = count_charge+ 1;
    add_expensive_row(count_charge);
    tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
      
     });
     $(document).on('click', '.remove', function(){
      
        var tr = $(this).parent().parent();
        
        // tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
        // calculate(0,0);
    var row_no = $(this).attr("id");
     $('#row'+row_no).remove();
     tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
        calculate(0,0);
     });

     $(document).on('click', '.remove1', function(){
      
      var tr = $(this).parent().parent();;
   
     $(this).parents("#row").remove();
  
    //  $('#row').remove();
      tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
      calculate(0,0);
   });
   
     $(document).on('click','.prove',function(){
            var id=$(this).attr("id");
            alert(id);
            swal({
                title: 'Are you sure?',
                text: "Approve Invoice",
                type: 'success',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes,Approve It!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('approve1') }}" + '/' + id,
                 
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
         
          });


 function returnForm(id) {
      
    $('#form-return')[0].reset();
       $('#task_id').val(id);
       $('#demage_stock').html("");
       $('#return_stock').html("");
       $('#expensive').html("");
          save_method = "add";
          $('input[name=_method]').val('POST');
         var tr = $(this).parent().parent();
        
         count_charge=''; 
         $.ajax({
             url: "{{ url('/return_stock') }}" + '/' + id ,
             type: "GET",
             dataType: "JSON",
             success: function(html) {
                 $('#modal-return').modal('show');
                 $('.modal-title').text('Remain Form');
                $('#id').val(html.data[0].id);
                $('#first_name').val(html.data[0].first_name); 
            
                $('#last_name').val(html.data[0].last_name); 
                $('#phone_number').val(html.data[0].phone);
                $('#date_in').val(html.data[0].created_at);
                $('#sub_total').val(html.data[0].sub_total);
                $('#supplier_id1').val(html.data[0].empoyee_id);
                       
            
              
                 $.each(html.data, function(i, item){
                
                html_form=''; 
                html_form+= '<span id="row'+count_charge +'"><div class="row">';
                html_form += '<div class="col-md-3">';
                html_form +=  '<label>Items Name</label>';
                html_form += '<select  name="product_id[]" id="product_id1" class="form-control"  ><option value="'+html.data[i].product_id+'">'+html.data[i].product_name+'</option></select>';
                html_form += '<span class="help-block with-errors"></span>';
                html_form += '</div>';
                html_form += '<div class="col-md-2">';
                html_form +=  '<label>previousy Qty</label>';
                html_form += '<input type="text" class="form-control" name="qty1[]" id="qty1" value="'+html.data[i].qty+'"  readonly/>';
                html_form += '</div>';
                html_form += '<div class="col-md-2">';
                html_form +=  '<label>return</label><br>';
                html_form+= '<input type="text" name="qty[]" id="qty"     class="form-control" placehplder="qty" value="'+html.data[i].qty+'" />';
                html_form += '</div>';
                html_form += '<div class="col-md-2">';
                html_form +=  '<label>Price</label><br>';
                html_form+= '<input type="text" name="price[]" id="price"  value="'+html.data[i].price+'" class="form-control" placehplder="price" readonly/>';
                html_form += '</div>';
                html_form+= '<input type="hidden" name="sales_id[]" id="id"  value="'+html.data[i].id+'" class="form-control" placehplder="price" readonly/>';
          
                html_form += '<div class="col-md-1">';
                html_form +=  '<label>amt</label><br>';
                html_form += '<span class="amt" >'+html.data[i].amt+'</span>';
                html_form += '</div>';
                html_form += '<div class="col-md-2">';
                 html_form +=  '<label>Action</label><br>';
           
     
                 html_form += '<button type="button" name="remove"  class="btn btn-danger btn-xs remove1">-</button>';
                  
         
              html_form += '</div></div><br /></span>';    
          
               $('#return_stock').append(html_form);
               tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
				calculate(0,0);
                   });
          
             },
             error : function() {
                 alert("Nothing Data");
             }
         });
         
 
     }
     function demageForm(id) {
       $('#form-demage')[0].reset();
         $('#task_id1').val(id);
         $('#demage_stock').html("");
         $('#return_stock').html("");
         $('#expensive').html("");
            save_method = "add";
            $('input[name=_method]').val('POST');
           var tr = $(this).parent().parent();
          
           count_charge=''; 
           $.ajax({
               url: "{{ url('/return_stock') }}" + '/' + id ,
               type: "GET",
               dataType: "JSON",
               success: function(html) {
                   $('#modal-demage').modal('show');
                   $('.modal-title').text('Demage Product Form');
                  $('#id').val(html.data[0].id);
                  $('#first_name2').val(html.data[0].first_name); 
              
                  $('#last_name2').val(html.data[0].last_name); 
                  $('#phone_number2').val(html.data[0].phone);
                  $('#date_in').val(html.data[0].created_at);
                  $('#sub_total2').val(html.data[0].sub_total);
                  $('#supplier_id1').val(html.data[0].empoyee_id);
                         
              
                
                   $.each(html.data, function(i, item){
                  
                  html_form=''; 
                  html_form+= '<span id="row'+count_charge +'"><div class="row">';
                  html_form += '<div class="col-md-3">';
                  html_form +=  '<label>Items Name</label>';
                  html_form += '<select  name="product_id[]" id="product_id1" class="form-control"  ><option value="'+html.data[i].product_id+'">'+html.data[i].product_name+'</option></select>';
                  html_form += '<span class="help-block with-errors"></span>';
                  html_form += '</div>';
                  html_form += '<div class="col-md-2">';
                  html_form +=  '<label>previousy Qty</label>';
                  html_form += '<input type="text" class="form-control" name="qty1[]" id="qty1" value="'+html.data[i].qty+'"  readonly/>';
                  html_form += '</div>';
                  html_form += '<div class="col-md-2">';
                  html_form +=  '<label>return</label><br>';
                  html_form+= '<input type="text" name="qty[]" id="qty"     class="form-control" placehplder="qty" value="'+html.data[i].qty+'" />';
                  html_form += '</div>';
                  html_form += '<div class="col-md-2">';
                  html_form +=  '<label>Price</label><br>';
                  html_form+= '<input type="text" name="price[]" id="price"  value="'+html.data[i].price+'" class="form-control" placehplder="price" readonly/>';
                  html_form += '</div>';
                  html_form+= '<input type="hidden" name="sales_id[]" id="id"  value="'+html.data[i].id+'" class="form-control" placehplder="price" readonly/>';
            
                  html_form += '<div class="col-md-1">';
                  html_form +=  '<label>amt</label><br>';
                  html_form += '<span class="amt" >'+html.data[i].amt+'</span>';
                  html_form += '</div>';
                  html_form += '<div class="col-md-2">';
                   html_form +=  '<label>Action</label><br>';
             
       
                   html_form += '<button type="button" name="remove"  class="btn btn-danger btn-xs remove1">-</button>';
                    
           
                html_form += '</div></div><br /></span>';    
            
                 $('#demage_stock').append(html_form);
                 tr.find(".amt").html( tr.find("#qty").val() * tr.find("#price").val() );
                  calculate(0,0);
                     });
            
               },
               error : function() {
                   alert("Nothing Data");
               }
           });
           
   
       }

  $('#return_qty').on('change',function(){
    
  })


    function editForm(id) {
        
        $('#expensive').html('');
      
        $('#form-item')[0].reset();
         save_method = 'edit';
         $('input[name=_method]').val('PATCH');
       
         var tr = $(this).parent().parent();
        
         count_charge=''; 
         $.ajax({
             url: "{{ url('/task') }}" + '/' + id + "/edit",
             type: "GET",
             dataType: "JSON",
             success: function(html) {
                 $('#modal-form').modal('show');
                 $('.modal-title').text(' Edit Details');
                $('#id').val(html.data[0].id);
                 $('#supplier_id').val(html.data[0].empoyee_id);
              
                $('#date_in').val(html.data[0].created_at);
                $('#sub_total').val(html.data[0].amount_due);
                
                 $.each(html.data, function(i, item){
                
                html_form=''; 
                html_form+= '<span id="row'+count_charge +'"><div class="row">';
                html_form += '<div class="col-md-3">';
                html_form +=  '<label>Items Name</label>';
                html_form += '<select  name="product_id[]" id="product_id" class="form-control"><option value="'+html.data[i].product_id+'">'+html.data[i].product_name+'</option> @foreach($product as $x)<option value="{{$x->id}}">{{$x->product_name}}</option>@endforeach</select>';
                html_form += '<span class="help-block with-errors"></span>';
                html_form += '</div>';
                html_form += '<div class="col-md-2">';
                html_form +=  '<label>Current Stock</label>';
                html_form += '<input type="text" class="form-control stock" value="'+html.data[i].stock+'"  readonly/>';
                html_form += '</div>';
                html_form += '<div class="col-md-2">';
                html_form +=  '<label>Qty</label><br>';
                html_form+= '<input type="text" name="qty[]" id="qty"  value="'+html.data[i].qty+'" class="form-control" placehplder="qty"/>';
                html_form += '</div>';
                html_form += '<div class="col-md-2">';
                html_form +=  '<label>Price</label><br>';
                html_form+= '<input type="text" name="price[]" id="price"  value="'+html.data[i].price+'" class="form-control" placehplder="price"/>';
                html_form += '</div>';
                html_form += '<div class="col-md-1">';
                html_form +=  '<label>amt</label><br>';
               html_form += '<span class="amt" >'+html.data[i].amt+'</span>';
               html_form += '</div>';
               html_form += '<div class="col-md-2">';
               html_form +=  '<label>Action</label><br>';
              if(count_charge == '')
                {
               html_form += '<button type="button" name="more_charge" id="more_charge" class="btn btn-success btn-xs">+</button>';
               }
            //   if (count_charge >=1 )
            //     {
            //      html_form += '<button type="button" name="remove" id="'+count_charge +'" class="btn btn-danger btn-xs remove">-</button>';
            //    }
               else
                {
            html_form += '<button type="button" name="remove" id="'+count_charge +'" class="btn btn-danger btn-xs remove">-</button>';
            }
             html_form += '</div>';
             html_form += '</div></div><br /></span>';    
             $.isNumeric(count_charge)
               count_charge=0;
               count_charge=count_charge+1;
               $('#expensive').append(html_form);
             //   tr.find(".amt").html( tr.find(("#qty").val() * tr.find("#price").val());
             //   calculate(0,0);
                   });
          
             },
             error : function() {
                 alert("Nothing Data");
             }
         });
 
     }
         $(document).on('click','.pays',function(){
           $(".subBtn").attr("disabled",false);
          $('#form-payment')[0].reset();
          var id=$(this).attr("id");
           $('#tasks_id').val($(this).attr("id"));
          $('#modal_payment').modal('show');
       
          save_method = "add";
          $('input[name=_method]').val('POST');
          $('.modal-title').text('Receiving Payment');
      
            $.ajax({
                url:"check_amount/"+id,
                cache:false,
                success: function(html) {            
                $('#amount-due').val('');
                $('#amount_due').val(html.data[0].amount_due);
                // $(".subBtn").attr("enabled",true);
              }
     
           })
       

         
          });

         $(document).on('click','.view',function(){
            $('#expensive').html('');
             var id=$(this).attr("id");
            $.ajax({
                url:"view_details/"+id,
                cache:false,
                success: function(html) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Details');
                    $('#id').val(html.data[0].id);
                    $('#supplier_id').val(html.data[0].empoyee_id);
                    $('#date_in').val(html.data[0].created_at);
                    $('#sub_total').val(html.data[0].amount_due);
                    $.each(html.data, function(i, item){
                   html_form=''; 
                   html_form+= '<span id="row'+count_charge +'"><div class="row">';
                   html_form+= '<div class="col-md-3">';
                   html_form += '<label>product_name</label>';
                   html_form += '<input type="text"   value="'+html.data[i].product_name+'" class="form-control" readonly/>';
                   html_form += '</div>';
                   html_form += '<div class="col-md-2">';
                   html_form +=  '<label>Current Stock</label>';
                   html_form += '<input type="text" class="form-control stock" value="'+html.data[i].stock+'"  readonly/>';
                   html_form += '</div>';
                   html_form += '<div class="col-md-1">';
                   html_form += '<label>Qty</label>';
                   html_form+= '<input type="text"   value="'+html.data[i].qty+'" class="form-control" readonly/>';
                   html_form += '</div>';
                   html_form += '<div class="col-md-3">';
                   html_form += '<label>Cost</label>';
                   html_form+= '<input type="text"   value="'+html.data[i].price+'" class="form-control" readonly/>';
                   html_form += '</div>';
                   html_form += '<div class="col-md-3">';
                   html_form += '<label>Unity Cost</label>'
                   html_form+= '<input type="text"   value="'+html.data[i].amt+'" class="form-control" readonly/>';
                   html_form += '</div>';
                   html_form+= '</div></div><br /></span>';
                  $('#expensive').append(html_form);
                  
                      });
             
                },
                error : function() {
                    alert("Nothing Data");
                }
            });

         })
        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "To delete Task and Return Items Quantities To Stock!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, return it!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('task') }}" + '/' + id,
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
            $('#form-item').validator().on('submit', function (e) {
      
            
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('task') }}";
                    else url = "{{ url('task') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#form-item")[0]),
                        contentType: false,
                        processData: false,
                        beforeSend:function(){
                        $(".subBtn").attr("disabled",true);
                        $(".subBtn").html("Please Wait..");

                        },
                      
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            $(".subBtn").attr("disabled",false);
                             $(".subBtn").html("Save");
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            $(".subBtn").attr("disabled",false);
                             $(".subBtn").html("Save");
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

        $(function(){
            $('#form-return').validator().on('submit', function (e) {
      
            
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('remains') }}";
                    else url = "{{ url('remains') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#form-return")[0]),
                        contentType: false,
                        processData: false,
                        beforeSend:function(){
                        $(".subBtn").attr("disabled",true);
                        $(".subBtn").html("Please Wait..");

                        },
                        success : function(data) {
                            $(".subBtn").attr("disabled",false);
                            $(".subBtn").html("Save");
                            $('#modal-return').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            $(".subBtn").attr("disabled",false);
                            $(".subBtn").html("Save");
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

        
        $(function(){
            $('#form-demage').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('demage_products') }}";
                    else url = "{{ url('demage_products') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#form-demage")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-demage').modal('hide');
                            table.ajax.reload();
                       
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
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


        //demage_products
        $(function(){
            $('#form-payment').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                 
                    if (save_method == 'add') url = "{{ url('receive_pay') }}";
           

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#form-payment")[0]),
                        contentType: false,
                        processData: false,  
                        beforeSend:function(){
                        $(".subBtn").attr("disabled",true);
                        $(".subBtn").val("Please Wait..");

                        },
                        success : function(data) {
                            $('#form-payment')[0].reset();
                            $('#modal_payment').modal('hide');
                            $(".subBtn").attr("disabled",false);
                            $(".subBtn").val("submit");
                            table1.ajax.reload();
                            table.ajax.reload();    
                         

                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            $(".subBtn").attr("disabled",false);
                            $(".subBtn").val("submit");
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
