<?php
namespace App\Http\Controllers;

use App\User;
use Excel;
use Illuminate\Http\Request;
use PDF;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display the authenticated user's profile.
     */
    public function show()
    {
        return view('profile.index');
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        return view('profile_edit');
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request, $id)
	{
        info('am here --->>');
		// Start a database transaction
		DB::beginTransaction();

		try {
			// Log incoming request data
			info('user request data ===>', $request->all());

			$updated_at = Carbon::now();

			// Prepare the data for updating
			$form_data = [
				'name' => $request->name,
				'email' => $request->email,
				'phone' => $request->phone,
				'role' => $request->role,
				'updated_at' => $updated_at,
			];
			$passwordChanged = false;
			// Only include the password in the update if it is provided
			if ($request->filled('password')) {
				// Update the password
				$form_data['password'] = Hash::make($request->password);
				$passwordChanged = true;
			} 

			// Update the user in the database
			DB::table('users')->where('id', '=', $id)->update($form_data);

			// Commit the transaction
			DB::commit();

			return response()->json([
				'success' => true,
				'password_changed' => $passwordChanged,
				'message' => 'User Updated',
			]);
		} catch (\Exception $e) {
			// Rollback the transaction if anything goes wrong
			DB::rollBack();

			// Log the error
			\Log::error('Error updating user: ' . $e->getMessage());

			return response()->json([
				'success' => false,
				'message' => 'Failed to update user. Please try again.',
			], 500);
		}
	}
}
