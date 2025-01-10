@extends('layouts.master')

@section('top')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
<div class="box">
    <!-- Section Title -->
    <h3 class="title-5 m-b-35">Spending Details</h3>

    <!-- Export Report Form -->
    <div>
        <div class="row">
            <!-- Date Range Inputs -->
            <div class="col-md-3">
                <label for="from" class="col-form-label">From</label>
                <input type="date" class="form-control input-md" id="from" name="from" required>
            </div>
            <div class="col-md-3">
                <label for="to" class="col-form-label">To</label>
                <input type="date" class="form-control input-md" id="to" name="to" required>
            </div>

            <!-- Category Selection -->
            <div class="col-md-2">
                <div class="form-group">
                    <label>Category</label>
                    <select id="category" name="category" class="form-control">
                        <option value="all">All</option>
                        @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-md-1" style="margin-top:30px;">
                <button onclick="generateTable()" class="btn btn-success btn-md">Find</button>
            </div>
            <!-- Submit Button -->
            <div class="col-md-1" style="margin-top:30px;">
                <a id="add_btn" class="btn btn-success btn-md">Add</a>
            </div>
            <!-- Submit Button -->
            <div class="col-md-2" style="margin-top:30px;">
                <button type="submit" class="btn btn-primary btn-md">PDF</button>
            </div>
        </div>
    </div>
    <br> <br>

    <!-- DataTable -->
    <div class="table-responsive">
        <table id="spending-table" class="table table-striped table-data2">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Receipt</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Include Modal Form -->
@include('spending.form')
@endsection

@section('bot')
<!-- DataTables Scripts -->
<script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/validator/validator.min.js') }}"></script>

<script type="text/javascript">
    // Initialize DataTable
    // Calculate the date range (one month back from today)
    // Initialize DataTables
    const today = new Date();
    const oneMonthBack = new Date();
    oneMonthBack.setMonth(today.getMonth() - 1);

    // Format the dates as `YYYY-MM-DD`
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const fromDate = formatDate(oneMonthBack);
    const toDate = formatDate(today);
    $('#from').val(fromDate);
    $('#to').val(toDate);
    console.log({
        fromDate,
        toDate
    });
    // Initialize DataTables with the date range as additional AJAX parameters
    const generateTable = function() {
        const tableElement = $('#spending-table');

        // Destroy existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable(tableElement)) {
            tableElement.DataTable().destroy();
        }

        // Initialize DataTable
        return tableElement.DataTable({
            processing: true,
            serverSide: true,
            rowReorder: {
                selector: 'td:nth-child(3)'
            },
            "autoWidth": false,
            // serverSide: true,
            dom: 'lBfrtip',
            "scrollCollapse": true,
            buttons: [],
            "lengthMenu": [5, 10, 25, 50, 100],
            responsive: true,
            ajax: {
                url: "{{ url('/spendings') }}",
                data: function(d) {
                    d.from = $('#from').val(); // Pass string date
                    d.to = $('#to').val();
                    d.category = $('#category').val();

                    console.log(d); // Log data being sent
                }
            },
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'receipt',
                    name: 'receipt'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    };

    // Call the function to initialize the table
    generateTable();


    // Add New Spending
    $('#add_btn').on('click', function(e) {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-spending-form').modal('show');
        $('#form-spending')[0].reset();
        $('.modal-title').text('Add Spending');
    });

    // Handle Form Submit
    $('#form-spending').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this); // Use FormData to handle file upload if required

        $.ajax({
            url: "{{ url('spendings') }}",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false, // Ensure files are sent correctly if present
            contentType: false, // Prevent default content-type header
            success: function(response) {
                $('#form-spending').trigger('reset');
                $('#modal-spending-form').modal('hide');
                generateTable().ajax.reload(); // Reload the table data 
                swal({
                    title: 'Success!',
                    text: data.message,
                    type: 'success',
                    timer: '1500'
                });
            },
            error: function(xhr, status, error) {
                swal({
                    title: 'Oops...!',
                    text: data.message,
                    type: 'error',
                    timer: '5500'
                });
            }
        });
    });

    // Edit Spending
    function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('POST');
        $.ajax({
            url: "{{ url('spendings') }}" + '/' + id,
            type: "GET",
            dataType: "JSON",
            success: function(html) {
                console.log({
                    html
                });
                $('#modal-spending-form').modal('show');
                $('.modal-title').text('Edit Spending');
                $('#id').val(html.data.id);
                $('#category').val(html.data.category);
                $('#description').val(html.data.description);
                $('#date').val(html.data.date);
                $('#receipt').val(html.data.receipt);
                $('#amount').val(html.data.amount);
            },
            error: function() {
                alert("Unable to fetch data");
            }
        });
    }

    // Delete Spending
    function deleteData(id) {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: "{{ url('Expensive') }}" + '/' + id,
                type: "POST",
                data: {
                    '_method': 'DELETE',
                    '_token': csrf_token
                },
                success: function(data) {
                    generateTable().ajax.reload();
                    alert('Data deleted successfully');
                },
                error: function() {
                    alert('Failed to delete data');
                }
            });
        }
    }
</script>
@endsection