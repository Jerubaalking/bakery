@extends('layouts.master')
@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
      <div class="container">
        <table class="table" >
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
                <td>{{ $audit->created_at }}</td>
                <td>
                  <table class="table">
                    @foreach($audit->old_values as $attribute => $value)
                      <tr>
                        <td><b>{{ $attribute }}</b></td>
                        <td>{{ $value }}</td>
                      </tr>
                    @endforeach
                  </table>
                </td>
                <td>
                  <table class="table">
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
    @endsection