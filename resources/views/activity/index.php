@extends('layouts.master')

@section('top')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <style>
        /* Custom styles for the activity log page */
        .table-responsive {
            margin-top: 20px;
        }
        .activity-header {
            margin-bottom: 20px;
        }
        .activity-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .activity-header p {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="activity-header text-center">
        <h1>Activity Log</h1>
        <p>View the activities performed by users along with the details.</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="activity-log-table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Action</th>
                    <th scope="col">User</th>
                    <th scope="col">Time</th>
                    <th scope="col">Old Values</th>
                    <th scope="col">New Values</th>
                </tr>
            </thead>
            <tbody id="audits">
                @foreach($audits as $audit)
                    <tr>
                        <td>{{ $audit->event }}</td>
                        <td>{{ $audit->user->name }}</td>
                        <td>{{ $audit->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <table class="table table-sm">
                                @foreach($audit->old_values as $attribute => $value)
                                    <tr>
                                        <td><b>{{ $attribute }}</b></td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                        <td>
                            <table class="table table-sm">
                                @foreach($audit->new_values as $attribute => $value)
                                    <tr>
                                        <td><b>{{ $attribute }}</b></td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
    <!-- DataTables JS -->
    <script src="{{ asset('assets/bower_components/datatables.net/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#activity-log-table').DataTable({
                order: [[2, 'desc']], // Order by time descending
                pageLength: 10,
                responsive: true,
                language: {
                    emptyTable: "No activities found"
                }
            });
        });
    </script>
@endsection
