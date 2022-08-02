@extends('layouts.master')
@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')


                   
                    <div class="row">
                    <div class="col-lg-12">
                                <!-- TOP CAMPAIGN-->
                                <div class="top-campaign">
                                <section class="content-header"><h3>Supplier Details</h3>
                                </section>
                                <div class="box box-widget">
                   <div class="box-header with-border">
                    <div class="row">
                    <div class="col-sm-4">
                        <div class="row">
                          
                            <div class="col-sm-12">
                            <div class="user-block">
                            <span class="username">
                            {{$data[0]->first_name}} {{$data[0]->last_name}}
                            </span>
                            <br>
                            <span class="username">
                            {{$data[0]->employee_number}} 
                            </span>
                            <br>
                            </div>
                        
                        </div>
                      
                        </div><!-- /.user-block -->         
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                        <ul class="list-unstyled">
                           <li>Supplier Number:<b> {{$data[0]->task_number}}</b> </li>
                           <li>Amount Paid:<b>{{$data[0]->amount_paid}}</b> </li>
                            <li>Amount Due:<b  style="color:red"> {{$data[0]->amount_due}} </b></li>
                           <li>Cost return:<b style="color:red">{{$data[0]->returned}}</b></li>
                           <li>Cost Demage: <b  style="color:red">{{$data[0]->demage_cost}}</b></li>
                          
                       
                        </ul>
                    </div>
                    <div class="col-sm-4">
                    <div class="pull-left">
                    <div class="input-group-btn">
                <button type="button" class="btn btn-info dropdown-toggle margin" data-toggle="dropdown">View Report
                    <span class="fa fa-caret-down"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                         <li><a href="/single_report/{{$data[0]->id}}" >View Report</a></li>
                
                          
                </ul>
            </div>
                    </div>
                      </div>        
                    </div>

    <div class="box">
        <div class="box-header">
            <h3 class="title-5 m-b-35" > Sales Details</h3>
            <input type="text" name="task_id" id="task_id_info" value="{{$id}}">
        </div>
        <div class="table-responsive table-striped  table-responsive"> 
         <table  class="table  table-striped table-data2" id="sales-table">
                <thead>
                <tr>
                <th>Items Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Unity Price</th>
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
        
    <div class="box-body" style="margin-top:20px;">
    <div class="table-responsive table-striped  table-responsive"> 
      <table  class="table  table-striped table-data2" id="demage-table">
          <thead>
          <h3 class="title-5 m-b-35 " ><span class="text-danger">Demages Products<span></h3>
          <tr>
                <th>Items Name</th>
                <th>Return Qty</th>
                <th>Product Return Cost</th>
                <th>Return Unity Cost</th>
                <th>Date </th>
                
          </tr>
          </thead>
           <tbody>
            </tbody>
      
     
      </table>
      </div>
  </div>
       <!-- /.box-body -->
        
       <div class="box-body" style="margin-top:20px;">
       <div class="table-responsive table-striped  table-responsive">
          <table id="task-table1" class="table  table-striped table-data2">
      <table  class="table  table-striped table-data2" id="return-table">
          <thead>
          <h3 class="title-5 m-b-35" ><span class="text-danger">Return  Products</span></h3>
          <tr>
                <th>Items Name</th>
                <th>Return Qty</th>
                <th>Product Return Cost</th>
                <th>Return Unity Cost</th>
                <th>Date</th>
              
          </tr>
          </thead>
              <tbody>
             </tbody>
      
     
      </table>
      </div>
  </div>
 
   
  <div class="box-body">
       <div class="table-responsive table-striped  table-responsive"> 
      <table  class="table  table-striped table-data2">
          <thead>
          <h3 class="title-5 m-b-35" >Sales Payment History</h3>
          <tr>
              
                <th>Task Number</th>
              
                <th>Qty</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Date</th>
               
          </tr>
          </thead>
          @foreach($pay as $i)
              <tbody>
        
              <td>{{ $i->task_number}}</td>
           
              <td>{{ $i->qty}}</td>
              <td>{{ $i->amount}}</td>
              <td>{{ $i->payment_methode}}</td>
              <td>{{ $i->created_at}}</td>
              </tbody>
          @endforeach
     
      </table>
      </div>
  </div>

  <div class="box-body">
  <div class="table-responsive table-striped  table-responsive"> 
      <table  class="table  table-striped table-data2">
          <thead>
          <h3 class="title-5 m-b-35" >Previously Closed Tasks</h3>
          <tr>
               <th>Supplier Name</th>
                <th>Supplier Number</th>
                <th>Task Number</th>
                <th>Total Amount</th>
                <th>Amount Paid</th>
                <th>Amount due</th>
                <th>Product Return Cost</th>
                <th>Date</th>
                <th></th>
               
          </tr>
          </thead>
          @foreach($close_task as $i)
              <tbody>
              <td>{{ $i->first_name}}</td>
              <td>{{ $i->employee_number}}</td>
              <td>{{ $i->task_number}}</td>
              <td>{{ $i->sub_total}}</td>
              <td>{{ $i->amount_paid}}</td>
              <td>{{ $i->amount_due}}</td>
              <td>{{ $i->returned}}</td>
              <td>{{ $i->created_at}}</td>
              <td>
                  <a href="/task_info/{{$i->id}}"  class="btn btn-sm btn-info"><i class="glyphicon glyphicon-eye-open"></i>View</a>
              </td>
              </tbody>
          @endforeach
     
      </table>
  </div>
  @include('demage_products.form');
</div>


@include('receive.pay');
@endsection

@section('bot')


@include('stock_return.form');
    <!-- DataTables -->
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
    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

   <script>
       var id=$('#task_id_info').val();
   
     var table = $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            dom:'lBfrtip',
           "ScrollX": "100%",
           "scrollCollapse": true,
            buttons: [
            'excel', 'pdf', 'print'
           ],
           "lengthMenu": [50,100,500,1000,2000,5000,10000,50000,100000],
            ajax:  "{{ url('/infoApi'). '/' }} " +id,
           
            columns: [
             
                {data: 'product_name', name: 'product_name'},
                {data: 'qty', name: 'qty'},
                {data: 'price', name: 'price'}, 
                {data: 'amt', name: 'amt'}, 
                {data: 'created_at', name: 'created_at'}, 
                {data: 'action', name: 'action', orderable: false, searchable: false}
               
            ]
        });
        var demage_table = $('#demage-table').DataTable({
            processing: true,
            serverSide: true,
            dom:'lBfrtip',
           "ScrollX": "100%",
           "scrollCollapse": true,
            buttons: [
            'excel', 'pdf', 'print'
           ],
           "lengthMenu": [50,100,500,1000,2000,5000,10000,50000,100000],
            ajax:  "{{ url('/demageApi'). '/' }} " +id,
           
            columns: [
                {data: 'product_name', name: 'product_name'},
                {data: 'qty', name: 'qty'},
                {data: 'price', name: 'price'}, 
                {data: 'amt', name: 'amt'}, 
                {data: 'created_at', name: 'created_at'}, 
                // {data: 'action', name: 'action', orderable: false, searchable: false}
               
            ]
        });

        var return_table = $('#return-table').DataTable({
            processing: true,
            serverSide: true,
            dom:'lBfrtip',
           "ScrollX": "100%",
           "scrollCollapse": true,
            buttons: [
            'excel', 'pdf', 'print'
           ],
           "lengthMenu": [50,100,500,1000,2000,5000,10000,50000,100000],
            ajax:  "{{ url('/returnApi'). '/' }} " +id,
           
            columns: [
                {data: 'product_name', name: 'product_name'},
                {data: 'qty', name: 'qty'},
                {data: 'price', name: 'price'}, 
                {data: 'amt', name: 'amt'}, 
                {data: 'created_at', name: 'created_at'}, 
                // {data: 'action', name: 'action', orderable: false, searchable: false}
               
            ]
        });


        function returnForm(id) {
          $('#form-return')[0].reset();
         
          $('#modal-return').modal('show');
          save_method = "add";
          $('input[name=_method]').val('POST');
           $('#sales_id').val(id);
   
       }

       $(function(){
            $('#form-return').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('remains') }}";
                    else url = "{{ url('remains') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
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
                            location.reload();
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


   function demageForm(id) {
         $('#form-demage')[0].reset();
         $('#demage_stock').html("");
         save_method = "add";
         $('input[name=_method]').val('POST');
         $('#modal-demage').modal('show');
         $('.modal-title').text('Demage Product Form');
         $('#sales_id1').val(id);     
           
   
       }


          
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
                        beforeSend:function(){
                        $(".subBtn").attr("disabled",true);
                        $(".subBtn").html("Please Wait..");
                        },
                        success : function(data) {
                            $('#modal-demage').modal('hide');
                            table.ajax.reload();
                            location.reload();
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
        $(document).on('click','.pays',function(){
           $(".subBtn").attr("disabled",false);
          $('#form-payment')[0].reset();
          var id=$(this).attr("id");
          $('#sales_pay_id').val(id);
          $('#modal_payment').modal('show');
          save_method = "add";
          $('input[name=_method]').val('POST');
          $('.modal-title').text('Receiving Payment');
          });

         
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
                          
                            table.ajax.reload();    
                            location.reload();

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
