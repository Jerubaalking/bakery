@extends('layouts.master')

@section('top')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endsection

@section('content')
<div class="box">
    <h3 class="title-5 m-b-35">User's Details</h3>
    <div class="table-data__tool">
        <div class="table-data__tool-right">
            <a onclick="editForm()" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                <i class="zmdi zmdi-edit"></i>Edit Profile
            </a>
        </div>
    </div>
    <br>

    <div class="box-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Name</td>
                    <td>{{ Auth::user()->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ Auth::user()->email }}</td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td>{{ Auth::user()->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Role</td>
                    <td>{{ Auth::user()->role }}</td>
                </tr>
                <tr>
                    <td>Created At</td>
                    <td>{{ Auth::user()->created_at ? \Carbon\Carbon::parse(Auth::user()->created_at)->format('Y-m-d H:i:s') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Updated At</td>
                    <td>{{ Auth::user()->updated_at ? \Carbon\Carbon::parse(Auth::user()->updated_at)->format('Y-m-d H:i:s') : 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editProfileForm" method="POST" action="/profile/{{Auth::user()->id}}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Leave blank if you do not want to change the password.</small>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" class="form-control" id="role" name="role" value="{{ Auth::user()->role }}" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('bot')
<!-- Include Bootstrap and jQuery scripts -->
<script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Include SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<script>
    function editForm() {
        $('#editProfileModal').modal('show');
    }

    $(document).ready(function () {
        $('#editProfileForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission
            
            var formData = $(this).serialize(); // Serialize form data
            
            $.ajax({
                url: $(this).attr('action'), // Get action URL from form
                type: 'POST',
                data: formData,
                success: function (response) {
                    // Show success alert
                    swal("Success!", "Profile updated successfully.", "success");
                    
                    // Close the modal
                    $('#editProfileModal').modal('hide');

                    // Check if the password has changed
                    if (response.password_changed) {
                        // Log the user out using AJAX
                        logoutUser();
                    }
                },
                error: function (xhr) {
                    // Handle error
                    swal("Error!", "There was a problem updating the profile.", "error");
                }
            });
        });
    });

    function logoutUser() {
        $.ajax({
            url: "{{ route('logout') }}", // Use the logout route
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}' // Include CSRF token
            },
            success: function () {
                // Redirect or perform actions after logout
                window.location.href = "{{ url('/home') }}"; // Redirect to home or login page
            },
            error: function () {
                // Handle logout error
                swal("Error!", "There was a problem logging out.", "error");
            }
        });
    }
</script>

@endsection
