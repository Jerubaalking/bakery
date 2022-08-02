@extends('layouts.master')
@section('top')
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
    <div class="box">
    <h3 class="title-5 m-b-35">Product demage Details</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">  
                                     
                                    </div>
                                    <div class="table-data__tool-right">
                                        <a  id="add_btn"  class="au-btn au-btn-icon au-btn--green au-btn--small">
                                        <i class="zmdi zmdi-plus"></i>Add</a>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="box-body" style="margin-top:12px;">
                              <form action="exportDemage" method="POST" enctype="multipart/form-data">
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
        <!-- /.box-header -->
        <div class="box-body">
          
              <div class="table-responsive table-striped  table-responsive"> 
             <table  id="task-table"   class="table  table-striped table-data2">
                <thead>
                <tr>
                <th>Items Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Unity Cost</th>
                <th>Date</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        </div>
        <!-- /.box-body -->
    <div class="box col-md-6">
    {{--<div class="box-header">--}}
    {{--<a onclick="addForm()" class="btn btn-primary">Create</a>--}}
    <!-- {{--<a href="" class="btn btn-danger">Export PDF</a>--}}
    {{--<a href="" class="btn btn-success">Export Excel</a>--}} -->
    {{--</div>--}}   
    </div>




@endsection

@section('bot')


@include('demage_products.forms');

 
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
           "lengthMenu": [10,20,30,50,100,500,1000,2000,5000,10000,50000,100000],
            ajax:  "{{ url('/apiDemage') }}",
            columns: [
                {data: 'product_name', name: 'product_name'},
                {data: 'qty', name: 'qty'},
                {data: 'price', name: 'price'},
                {data: 'amt', name: 'amt'},
                {data: 'created_at', name: 'created_at'},  
               
            ]
        });
  $(document).on('click','#add_btn',function(){
  save_method = "add";
  $('#form-demage')[0].reset();
  $('input[name=_method]').val('POST');
  $('#modal-demage').modal('show');
  $('.modal-title').text('Demage Products');
  $('#expensive').html('');
  add_expensive_row();
  calculate(0,0);

var tr = $(this).parent().parent(); 
    var product_id=tr.find("#product_id").val() ;
  
    $.ajax({
        url:"check_stock/"+product_id,
        success: function(html) {  
      var stock= tr.find(".stock").val(html.data[0].stock);
      var qty= tr.find("#qty").val();
        
      }


 });

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
      var stock= tr.find(".stock").val(html.data[0].stock);
      var qty= tr.find("#qty").val();
        
      }

   })
 });



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
html += '<span id="row'+count_charge +'"><div class="row">';
//   html += '<div class="col-md-3">';
//   html += '<input type="text" name="title[]" id="title'+count_charge+'" class="form-control selectpicker" data-live-search="true" required  placeholder="title"></textarea>';
//   html += '</div>';
html += '<div class="col-md-3">';
html +=  '<label>Items Name</label>';
html += '<select  name="product_id[]" id="product_id" class="form-control"><option disabled>--select product--</option> @foreach($product as $x)<option value="{{$x->id}}">{{$x->product_name}}</option>@endforeach</select>';
html += '<span class="help-block with-errors"></span>';
html += '</div>';
html += '<div class="col-md-2">';
html +=  '<label>Current Stock</label>';
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
html += '</div></div><br /></span>';

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

$(function(){
    $('#form-demage').validator().on('submit', function (e) {
        if (!e.isDefaultPrevented()){
            if (save_method == 'add') url = "{{ url('/demage_products') }}";
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
    </script>
   
@endsection
