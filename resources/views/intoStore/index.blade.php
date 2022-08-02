@extends('layouts.master')
@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net/css/dataTables.dateTime.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net/css/editor.dataTables.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net/css/font-awesome.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net/css/select.dataTables.min.css') }} ">
@endsection
@section('content')
    <div class="box">     
    <h3 class="title-5 m-b-35">Material store</h3>  
         <div class="box-body" style="margin-top:-25px;"> 
         <div> 

         </div> 
            <div class="table-data__tool " >
                    <div class="table-data__tool-right col-md-12">
                                <div class="col-md-2 form group" style="margin-left:-30px;">
                                    <label>Status</label> <br>
                                    <select onchange="populateTable('apiIntoStore')" class="form-control" name="status" id="status">
                                        <!--<option value="all">all</option>-->
                                        <option value="in" selected>Stock-In</option>
                                        <option value="process">Stock-In Process</option>
                                        <option value="finished">Finished-Process</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form group">
                                    <label>Date Range</label> <br>
                                    <input type="text" class="form-control" name="date_range" id="date_range">
                                </div>
                                <div class="col-md-1 form group">
                                <a  id="print_btn" target="_blank" onclick="reportPDF('exportIntoStorePDF')" style="margin-top:22px;" class="btn btn-default  au-btn--small">
                                     <i class="zmdi zmdi-download"></i> <i class="fa fa-pdf"></i> PDF </a>
                                </div>
                                <div class="col-md-1 form group" style="margin-top:2px;">
                                <label> Value</label> <br>
                                <label id="in_total" class="text-success"></label>
                                </div>
                                <!-- <div class="col-md-2 form group">
                                <label>Used Materials</label> <br>
                                <label id="out_total" class="text-danger"></label>
                                </div> -->
                                <a  id="use_btn" style="float:right; margin:5px;" class="btn btn-warning  au-btn--small">
                                     <i class="zmdi zmdi-edit"></i> Use</a>
                                 <a  id="add_btn" style="float:right;margin:5px;"; class="btn btn-success au-btn--small">
                                     <i class="zmdi zmdi-plus"></i> Add</a>
                    </div>
                                  
            </div>
           <div class="table-responsive table-striped  table-responsive">
           <table id="task-table" class="table  table-striped table-data2">
                <thead>
                <tr>
                <th>Batch</th>
                <th>Material</th>
                <th>Category</th>
                <th>Type</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Cost/Measurement</th>
                <th>Gen. Cost</th>
                <th>comment</th>
                <th>date</th>
                <th></th>
                </tr>
                </thead>
                <tbody></tbody>
             </table>
           </div>
         
        </div>
        <!-- /.box-body -->
    </div>
@include('intoStore.form');



@endsection

@section('bot')



    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>
    <script src=" {{ asset('assets/bower_components/datatables.net/js/dataTables.rowReorder.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.responsive.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.select.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.dateTime.min.js') }} "></script>


    <!-- InputMask -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/submition.js') }}"></script>
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
    
    <!-- <script src="{{ asset('assets/bower_components/datatables.net/js/dataTables.editor.min.js') }} "></script> -->

    {{--<script>--}}
    {{--$(function () {--}}
    {{--$('#items-table').DataTable()--}}
    {{--$('#example2').DataTable({--}}
    {{--'paging'      : true,--}}
    {{--'lengthChange': false,--}}
    {{--'searching'   : true,--}}
    {{--'ordering'    : true,--}}
    {{--'info'        : true,--}}
    {{--'autoWidth'   : false--}}
    {{--})--}}
    {{--})--}}
    {{--</script>--}}

 
    <script type="text/javascript">
    $('#status').select2({
        width:'100%'
    })
        $('#date_range').daterangepicker();
         function populateTable(route){
            var dts = $('#date_range').val();
            var status = $('#status').val();
            
            let start = moment(`${dts.split('-')[0]}`).utc().format('YYYY-MM-DD 23:59:59');
            //i add 1 day to the end date due to some bug
            let end = moment(`${dts.split('-')[1]}`).add(1, 'days').utc().format('YYYY-MM-DD 23:59:59'); 
            let st = start;
            let ed = end;
            console.log("Added day::",st, 'end date:',end)
            console.log(st, end);
                var t = new Date();
                console.log("time to db",start)
                // var router = route;
                let obj = {};
                if(status=='all'){
                        obj.start=st.toString()+' 00:00:00';
                        obj.end=ed.toString()+' 23:59:59';
                        obj.status=null;
                }else{
                        
                        obj.start=st.toString()+' 00:00:00';
                        obj.end=ed.toString()+' 23:59:59';
                        obj.status=status;
                }
                
                var router = {
                type: "GET",
                dataType: "json",
                data :obj,
                contentType: "application/json",
                url:route,
                };
                
                var editor; // use a global for the submit and return data rendering in the examples
    let colums = [
                    
                    {data: 'batch_number', name: 'batch_name'},
                    {data: 'name', name: 'name'},
                    {data: 'category_name', name: 'category_name'},
                    {data: 'type', name: 'type'},
                    {data: 'status', name: 'status'},
                    {data: 'qty', render: $.fn.dataTable.render.number( ',', '.', 0, 'TZS' ), name: 'qty'},
                    {data: 'symbol', name: 'symbol'},
                    {data: 'unit_cost', name: 'unit_cost'},
                    {data: 'sam', name: 'sam'},
                    {data: 'comments', name: 'comments'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                
    ]
                $('#task-table').DataTable().clear();
                $('#task-table').DataTable().destroy();
                let table = $('#task-table').DataTable({
                    processing: true,
                    rowReorder: {
                        selector: 'td:nth-child(3)'
                    },
                    "autoWidth": false,
                    // serverSide: true,
                    dom:'lBfrtip',
                    "scrollCollapse": true,
                        buttons: [],
                    "lengthMenu": [5,10,25,50,100],
                    
                    responsive: true,
                    ajax: router,
                    columns: [
                    
                        {data: 'batch_number', name: 'batch'},
                        {data: 'name', name: 'material'},
                        {data: 'category_name', name: 'category'},
                        {data: 'type', name: 'type'},
                        {data: 'status', name: 'status'},
                        {data: 'qty', name: 'quantity'},
                        {data: 'symbol', name: 'Unit'},
                        {data: 'unit_cost', name: 'cost/unit'},
                        {data: 'sam', name: 'gen. cost'},
                        {data: 'comments', name: 'comments'},
                        {data: 'created_at', name: 'date'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    
                    ]
                });
                setTotals(router);

            return table;
        }
       
        function setTotals(router){
            
            var dts = $('#date_range').val();
            var status = $('#status').val();
            let start = moment(`${dts.split('-')[0]}`).utc().format('YYYY-MM-DD 23:59:59');
            //i add 1 day to the end date due to some bug
            let end = moment(`${dts.split('-')[1]}`).add(1, 'days').utc().format('YYYY-MM-DD 23:59:59'); 
            let st = start;
            let ed = end;
            let obj = {};
                if(status=='all'){
                        obj.start=st.toString()+' 00:00:00';
                        obj.end=ed.toString()+' 23:59:59';
                        obj.status=null;
                }else{
                        
                        obj.start=st.toString()+' 00:00:00';
                        obj.end=ed.toString()+' 23:59:59';
                        obj.status=status;
                }
            $.ajax({
                    // type: router.type,
                    // dataType: router.dataType,
                    data :obj,
                    url:'/intoStoreShowByDates',
                    success:function(html){
                        let datas = JSON.parse(html);
                        let tcost_in = 0;
                        // let tcost_out = 0;
                        datas[0].map((item)=>{
                            tcost_in += parseInt(item.cost)
                        });
                        // datas[1].map((item)=>{
                        //     tcost_out += item.cost
                        // });
                        $('#in_total').html('');
                        $('#in_total').text(tcost_in.toLocaleString("en-US"))
                        // $('#out_total').text(tcost_out.toLocaleString("en-US"))
                        console.log(datas, tcost_in);
                    }
                })
        }
        function reportPDF(router){
            
            var dts = $('#date_range').val();
            var status = $('#status').val();
            let start = moment(`${dts.split('-')[0]}`).utc().format('YYYY-MM-DD 23:59:59');
            //i add 1 day to the end date due to some bug
            let end = moment(`${dts.split('-')[1]}`).add(1, 'days').utc().format('YYYY-MM-DD 23:59:59'); 
            let st = start;
            let ed = end;
            let obj = {};
                if(status=='all'){
                        obj.start=st.toString()+' 00:00:00';
                        obj.end=ed.toString()+' 23:59:59';
                        obj.status=null;
                }else{
                        
                        obj.start=st.toString()+' 00:00:00';
                        obj.end=ed.toString()+' 23:59:59';
                        obj.status=status;
                }
            $.ajax({
                    // type: router.type,
                    // dataType: router.dataType,
                    data :obj,
                    url:router,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success:function(html){
                        var blob = new Blob([html] , {type:'application/pdf'});
                        // var link = document.createElement('a');
                        // link.href = window.URL.createObjectURL(blob);
                        // link.download = moment().utc()+"-MaterialReport.pdf";
                        // link.click();
                        if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                            window.navigator.msSaveOrOpenBlob(blob); // for IE
                        }
                        else {
                            var fileURL = URL.createObjectURL(blob);
                            var newWin = window.open(fileURL);
                            newWin.focus();
                            newWin.location.reload();
                        }
                        // console.log(html)
                    }
                })
        }

       let table = populateTable('apiIntoStore');

        $(function() {

            var start =moment().startOf('month');
            var end = moment().endOf('month');

        

            $('#date_range').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment().add(1,'days')],
                'Last 30 Days': [moment().subtract(29, 'days'), moment().add(1,'days')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
            
            populateTable('apiIntoStore');

        });

       $('#date_range').on('change', ()=>{
                 populateTable('apiIntoStore');
       })

        $('#add_btn').on('click',function(){
    
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#form-item')[0].reset();
            $('.modal-title').text('Stock Material');
            getBatch();
            $('#more_charge').addClass('hidden');
            $('#more_charge1').removeClass('hidden');
            $('.receipt').removeClass('hidden');
            $('#materials').html('');
        });

        $('#use_btn').on('click',function(){
    
            save_method = "use";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#form-item')[0].reset();
            $('.modal-title').text('Use Material');
            getBatch();
            $('#more_charge').removeClass('hidden');
            $('#more_charge1').addClass('hidden');
            $('.receipt').addClass('hidden');
            $('#materials').html('');
        });
        
        function add_expensive_row(){
            var prev = parseInt($("#indexer").val())+1;
            var tr = $(this).parent().parent();
                var html = '';
                html += '<span class="col-md-12" id="row'+prev +'"><div class="row">';
                html += '<div class="col-md-4">';
                html +=  '<label>Materials</label>';
                html += '<select onchange="getAvailableQuantity('+prev+')" name="material_id[]" id="material_id[]" class="form-control selectMaterial_id'+prev+'"><option value="first" selected>--select material--</option>@foreach($materials as $x)<option value="{{$x->id}}">{{$x->name}}</option>@endforeach</select>';
                html += '<span class="help-block with-errors"></span>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html +=  '<label>Available</label>';
                html += '<input type="text" name="cqty[]"  id="cqty[]" class="cqty'+prev+' form-control" required placeholder="qty" value="" readonly/>';
                html +=  '<br>';
                html +=  '<label>Quantity</label>';
                html +=  '<br>';
                html += '<input type="number" onchange="getCount('+prev+')" oninput="adding('+prev+')" name="qty[]"  id="qty[]" class="form-control selectQty'+prev+'" required placeholder="qty" value=""/>';
                html +=  '<input type="hidden" value="0" class="total'+prev+'">';
                html += '</div>';
                html += '<div class="col-md-3">';
                html +=  '<label>Comment</label>';
                html += '<textarea type="text" name="comments[]"  id="comments[]" class="form-control" required placeholder="comment" ></textarea>';
                html +=  '<label>Receipt</label>';
                html +=  '<br>';
                html += '<input type="text" oninput="" name="receipt[]"  id="receipt[]" class="form-control selectReceipt'+prev+'" required placeholder="receipt" value=""/>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html +=  '<label>action</label><br>';
                html += '<button type="button" name="remove" id="'+prev +'" class="btn btn-danger btn-xs remove">-</button>';
                html += '</div>';
                html += '</div></div><br /></span>';
                $('#indexer').attr('value', prev);
                $('#materials').append(html);
                receiptManager(prev);
                getSlotCount(prev);
              
                
        }
        function receiptManager(prev){
            console.log(prev);
            var available= $(`.receiptInput`).val();
            let assigned = $(`.selectReceipt${prev}`).val();
            if(typeof assign == 'string'||assigned == 0 ||available==NaN){
                $(`.selectReceipt${prev}`).val(available);
            }
        }
        function setValueOfQty(prev){
            
            var qtys= $('input[name="qty[]"]').map(function(){return this.value}).get();
            $.ajax({
                    url:"{{ url('intoStoreShow') }}",
                    success:function(html){
                        
                        var datas = JSON.parse(html);
                        
                        var totalValue =0;
                        var unit_cost = 0;
                        var ids= $('select[name="material_id[]"]').map(function(){return this.value}).get();
                        
                            var totalValue = 0;
                            console.log("unit cost:: here....::", ids);
                            for (let i = 0; i < ids.length; i++) {
                                let unit_cost = 0;
                                datas.map(function(item){
                                    if(item.material_id == ids[i]){
                                           unit_cost = parseInt(item.unit_cost);
                                    }
                                });
                                totalValue += parseInt(unit_cost) * parseInt(qtys[i]);
                            }
                                
                                // totalValue += (qty*unit_cost);
                            $('.itemValbtm').html();
                            $('.itemValbtm').text(`${totalValue.toLocaleString("en-US")}`);
                            
                            $('.itemValtp').html();
                            $('.itemValtp').text(`${totalValue.toLocaleString("en-US")}`);
                            // }

                    }
            });
        }
        function getSlotCount(prev){
            $('.slots').text(prev);
        }
        async function getCount(prev, offset=0){
            let countsArray = $('select[name="material_id[]"]').map(function(){return this.value}).get();
            let uniq_set = new Set();
            uniq_set.add(countsArray);
            let sorted = [...uniq_set].sort();
            console.log("set length:",sorted[0].length-offset, prev, sorted[0]);
            $('.countbtm').html('');
            $('.countbtm').text(`${(sorted[0].length-offset).toLocaleString("en-US")}`);
            
            $('.counttp').html('');
            $('.counttp').text(`${(sorted[0].length-offset).toLocaleString("en-US")}`);
            setValueOfQty(prev);
        }
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

        function getAvailableQuantity(prev){
            var id= $(`.selectMaterial_id${prev}`).val();
            
            $.ajax({
                    url:"{{ url('intoStoreShow') }}",
                    success: function(html) {
                        var datas = JSON.parse(html);
                        var totalInQty = 0;
                        var totalOutQty = 0;
                        var totalQty = 0;
                        datas.map(function(item){
                            if(item.status == 'in' && item.material_id == id){
                               totalInQty +=parseInt(item.qty);
                            }
                            if(item.status == 'process' && item.material_id == id||item.status == 'finished' && item.material_id == id ){
                               totalOutQty += parseInt(item.qty);
                            }
                        });
                        var availableQty = totalInQty-totalOutQty;
                        $(`.cqty${prev}`).val(availableQty);
                        $(`.total${prev}`).val(availableQty);
                    }
            });
        }
        function deduct(prev){
            // var available= parseInt($(`.cqty${prev}`).val());
            var available= parseInt($(`.total${prev}`).val());
            let assigned = $(`.selectQty${prev}`).val();
            
            console.log(assigned);
            if(assigned == NaN){
                assigned = 0;
                $(`.selectQty${prev}`).val(0);
                $(`.cqty${prev}`).val(`${available}`);
               
            }else{ 
                if(typeof assigned == 'object'){
                    assigned = 0;
                    $(`.selectQty${prev}`).val(0);
                    $(`.cqty${prev}`).val(`${available}`);
                
                }else{
                    if(assigned >= available){
                        $(`.selectQty${prev}`).val(`${available}`);
                        $(`.cqty${prev}`).val(0);
                    }else{
                        $(`.cqty${prev}`).val(`${available-assigned}`);
                    }
                }
                
            }
            
                               
        }
        function adding(prev){
            // var available= parseInt($(`.cqty${prev}`).val());
            
            // for (let i = 0; i < countsArray.length; i++) {
            //         totalItems += parseInt(countsArray[i]);
            // }
            
            // var available= parseInt($(`.total${prev}`).val());
            // let assigned = $(`.selectQty${prev}`).val();
            // console.log(assigned);
            // if(assigned == NaN){
            //     assigned = 0;
            //     $(`.selectQty${prev}`).val(0);
            //     $(`.cqty${prev}`).val(`${available}`);
               
            // }else{ 
            //     if(typeof assigned == 'object'){
            //         assigned = 0;
            //         $(`.selectQty${prev}`).val(0);
            //         $(`.cqty${prev}`).val(`${available}`);
                
            //     }else{
            //         if(assigned <= 0){
            //             $(`.cqty${prev}`).val(`${available}`);
            //             $(`.selectQty${prev}`).val(0);
            //         }else{
            //             var t = parseInt(available)+parseInt(assigned)
            //             $(`.cqty${prev}`).val(`${t}`);
            //         }
            //     }
                
            // }
            
                               
        }

        $('#more_charge').on('click', function(){
            var prev = parseInt($("#indexer").val())+1;
            console.log(prev);
            var tr = $(this).parent().parent();
                var html = '';
                html += '<span class="col-md-12" id="row'+prev +'"><div class="row">';
                html += '<div class="col-md-4">';
                html +=  '<label>Materials</label>';
                html += '<select onchange="getAvailableQuantity('+prev+')" name="material_id[]" id="material_id[]" class="form-control selectMaterial_id'+prev+'"><option value="first" selected>--select material--</option>@foreach($materials as $x)<option value="{{$x->id}}">{{$x->name}}</option>@endforeach</select>';
                html += '<span class="help-block with-errors"></span>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html +=  '<label>Available</label>';
                html += '<input type="text" name="cqty[]"  id="cqty[]" class="cqty'+prev+' form-control" required placeholder="qty" value="" readonly/>';
                html +=  '<br>';
                html +=  '<label>Quantity</label>';
                html +=  '<br>';
                html += '<input type="text" onchange="getCount('+prev+')" oninput="deduct('+prev+')" name="qty[]"  id="qty[]" class="form-control selectQty'+prev+'" required placeholder="qty" value="0"/>';
                html +=  '<input type="hidden" value="0" class="total'+prev+'">';
                html += '</div>';
                html += '<div class="col-md-3">';
                html +=  '<label>Comment</label>';
                html += '<textarea type="text" name="comments[]"  id="comments[]" class="form-control" required placeholder="comment" ></textarea>';
            
                html += '</div>';
                html += '<div class="col-md-2">';
                html +=  '<label>action</label><br>';
                html += '<button type="button" name="remove" id="'+prev +'" class="btn btn-danger btn-xs remove">-</button>';
                html += '</div>';
                html += '</div></div><br /></span>';
                $('#indexer').attr('value', prev);
                $('#materials').append(html);
                getSlotCount(prev);
                // tr.find(`.selectMaterial_id${prev}`).select2();
               
        });

        function edit_expensive_row(count_charge){
            var tr = $(this).parent().parent();
                var html = '';
                html += '<span class="col-md-12" id="row'+count_charge +'"><div class="row">';
                html += '<div class="col-md-3">';
                html +=  '<label>Materials</label>';
                html += '<select  name="material_id[]" id="material_id[]" class="form-control"><option disabled>--select material--</option> @foreach($materials as $x)<option value="{{$x->id}}">{{$x->type}} {{$x->name}} in {{$x->symbol}}</option>@endforeach</select>';
                html += '<span class="help-block with-errors"></span>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html +=  '<label>Date</label>';
                html += '<input type="date" class="form-control stock" name="date[]"  id="date[]" required/>';

                html += '</div>';
                html += '<div class="col-md-2">';
                html +=  '<label>Quantity</label>';
                html += '<input type="text" name="qty[]"  id="qty[]" class="form-control" required placeholder="qty" value="1"/>';

                html += '</div>';
                html += '<div class="col-md-3">';
                html +=  '<label>Comment</label>';
                html += '<textarea type="text" name="comments[]"  id="comments[]" class="form-control" required placeholder="comment" ></textarea>';
                html += '</div>';
                html += '</div></div><br /></span>';
            
                $('#materials').append(html);
        }
        function getBatchNumber(prev){
            var tr = (($('#row'+prev).children()).children()).children(); 
            var material_id =parseInt($(tr[1]).val());
            var cqty =$(".cqty"+prev);
            console.log("tr::",tr, "cqt",cqty);
            $.ajax({
                    url:"{{ url('intoStoreShow') }}",
                    success: function(html) {
                        var dbData = JSON.parse(html);
                        let inSum = 0;
                        let outSum = 0;
                        let inSumMaterial = 0;
                        let outSumMaterial = 0;
                        let currentMaterial = 0;
                        dbData.map(function(data){
                        if(data.status=='in'){
                            if(data.material_id==material_id){
                                inSumMaterial+=data.qty;
                                inSum+=data.qty;
                            }else{
                                inSum+=data.qty;
                            }
                        }else{
                            if(data.material_id==material_id){
                                outSumMaterial+=data.qty;
                                outSum+=data.qty;
                            }else{
                                outSum+=data.qty;
                            }
                        }
                        });
                        currentMaterial = inSumMaterial - outSumMaterial;
                        cqty.val(currentMaterial)
                        console.log(inSum, outSum, inSumMaterial, outSumMaterial, currentMaterial);
                        for(var data of dbData){
                            console.log(data);
                        }
                    // $('#modal-form').modal('show');
                        // $('.modal-title').text('Edit Material');
                        // $('#materials').html('');
                        // edit_expensive_row();
                        // $('#id').attr('value',html.data.id);
                        // $('#batch_number').attr('value',html.data.batch_number);
                        // $('#material_id').attr('value', html.data.material_id);
                        // $('#qty').attr('value', html.data.qty);
                        // $('#comments').attr('value',html.data.comments);
                        // $('#date').attr('value',html.data.date);
                    },
                    error: function(err){
                        console.log(err);
                    }
            });
        };
        $('#materials').delegate('.remove', 'click', function(){
      
            var tr = $(this).parent().parent();
            
            var prev = parseInt($("#indexer").val())-1;
            console.log("delet prev::",prev)
            $('#indexer').attr('value', prev);
                getSlotCount(prev);
                getCount(prev, 1);
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
                    url:"{{ url('intoStore') }}" + '/' + id + "/edit",
                    
                    success: function(html) {

                        $('#modal-form').modal('show');
                        $('.modal-title').text('Edit Material');
                        $('#materials').html('');
                        edit_expensive_row();
                        $('#id').attr('value',html.data.id);
                        $('#batch_number').attr('value',html.data.batch_number);
                        $('#material_id').attr('value', html.data.material_id);
                        $('#qty').attr('value', html.data.qty);
                        $('#comments').attr('value',html.data.comments);
                        $('#date').attr('value',html.data.date);
                    },
                    error : function() {
                        alert("Nothing Data");
                    }
                });
    
        }
        function getBatch(){
            var theRandomNumber = `BCH-IN${Date.now()+Math.floor(Math.random() * 99999999999) + 111111000000}`;
            $('#batch_number').attr('value',theRandomNumber);
        }
        function deleteData(id){
            console.log(id)
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "Delete Record?",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('intoStore') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        if(data.success){
                            
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                        populateTable('apiIntoStore');
                        }else{
                            console.log(data)
                            swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '5500'
                        })
                        }
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '5500'
                        })
                    }
                });
            });
        }
   

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {                
                    if (!e.isDefaultPrevented()){
                            var id = $('#id').val();
                            if (save_method == 'add') {
                                url = "{{ url('intoStore') }}";
                                $.ajax({
                                        url : url,
                                        type : "POST",
                                        data: new FormData($("#modal-form form")[0]),
                                        contentType: false,
                                        processData: false,
                                        beforeSend:function(){
                                        $(".subBtn").attr("disabled",true);
                                        },
                                        success : function(data) {
                                            console.log(data)
                                            if(data.success){
                                                
                                            $('#modal-form').modal('hide');
                                            table.ajax.reload();
                                                swal({
                                                    title: 'Success!',
                                                    text: data.message,
                                                    type: 'success',
                                                    timer: '1500'
                                                })
                                            }else{
                                                if(data.message.errorInfo){
                                                    if(data.message.errorInfo[2].includes('Duplicate')){
                                                        swal({
                                                            title: 'Oops...',
                                                            text: "Duplicate data!",
                                                            type: 'error',
                                                            timer: '3000'
                                                        })
                                                    }else{
                                                        swal({
                                                            title: 'Oops...',
                                                            text: data.message.errorInfo,
                                                            type: 'error',
                                                            timer: '3000'
                                                        })
                                                    }
                                                }else{
                                                swal({
                                                title: 'Oops...',
                                                text: data.message,
                                                type: 'error',
                                                timer: '3000'
                                            })

                                                }
                                            }
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
                            }else {
                                if(save_method == 'use' ) {
                                    url = "{{ url('apiUseStore') }}";
                                    $.ajax({
                                        url : url,
                                        type : "POST",
                                        data: new FormData($("#modal-form form")[0]),
                                        contentType: false,
                                        processData: false,
                                        beforeSend:function(){
                                        $(".subBtn").attr("disabled",true);
                                        },
                                        success : function(data) {
                                            console.log(data)
                                            if(data.success){
                                                
                                            $('#modal-form').modal('hide');
                                            table.ajax.reload();
                                            swal({
                                                title: 'Success!',
                                                text: data.message,
                                                type: 'success',
                                                timer: '1500'
                                            })
                                            
                                            populateTable('apiIntoStore');
                                            }else{
                                                if(data.message.errorInfo){
                                                    if(data.message.errorInfo[2].includes('Duplicate')){
                                                        swal({
                                                            title: 'Oops...',
                                                            text: "Duplicate data!",
                                                            type: 'error',
                                                            timer: '3000'
                                                        })
                                                    }else{
                                                        swal({
                                                            title: 'Oops...',
                                                            text: data.message.errorInfo,
                                                            type: 'error',
                                                            timer: '3000'
                                                        })
                                                    }
                                                }else{
                                                swal({
                                                title: 'Oops...',
                                                text: data.message,
                                                type: 'error',
                                                timer: '3000'
                                            })

                                                }
                                            }
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
                                } else {
                                    url = "{{ url('intoStore') . '/' }}" + id;
                                    $.ajax({
                                        url : url,
                                        type : "POST",
                                        data: new FormData($("#modal-form form")[0]),
                                        contentType: false,
                                        processData: false,
                                        beforeSend:function(){
                                        $(".subBtn").attr("disabled",true);
                                        },
                                        success : function(data) {
                                            console.log(data)
                                            if(data.success){
                                                
                                            $('#modal-form').modal('hide');
                                            table.ajax.reload();
                                            swal({
                                                title: 'Success!',
                                                text: data.message,
                                                type: 'success',
                                                timer: '1500'
                                            })
                                            
                                            populateTable('apiIntoStore');
                                            }else{
                                                if(data.message.errorInfo){
                                                    if(data.message.errorInfo[2].includes('Duplicate')){
                                                        swal({
                                                            title: 'Oops...',
                                                            text: "Duplicate data!",
                                                            type: 'error',
                                                            timer: '3000'
                                                        })
                                                    }else{
                                                        swal({
                                                            title: 'Oops...',
                                                            text: data.message.errorInfo,
                                                            type: 'error',
                                                            timer: '3000'
                                                        })
                                                    }
                                                }else{
                                                swal({
                                                title: 'Oops...',
                                                text: data.message,
                                                type: 'error',
                                                timer: '3000'
                                            })

                                                }
                                            }
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
                                }
                                
                            }
                        return false;
                    }   
            });

        });
    </script>
   
@endsection
