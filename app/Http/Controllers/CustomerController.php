<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class CustomerController  extends Controller
{
    use LogsActivity;
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display the customer index view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('customer.index');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $customer = DB::table('customers')->find($id);
        return response()->json(['data' => $customer]);
    }
    /**
     * Store a newly created customer in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:customers,name',
                'phone' => 'required|string|max:20|unique:customers,phone',
                'group' => 'required|string|max:100',
                'location' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            try {
                // Insert customer and retrieve the ID
                $customerId = DB::table('customers')->insertGetId($validatedData);

                // Fetch only the name for logging
                $customerName = DB::table('customers')->where('id', $customerId)->value('name');

                // Log the activity
                $this->logActivity('Customer Created', "ID: $customerId, Name: $customerName");

                DB::commit();

                return response()->json([
                    'message' => 'Customer created successfully.',
                    'success' => true
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Failed to create customer.',
                    'error' => $e->getMessage(),
                    'success' => false
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Invalid request type.',
            'success' => false
        ], 400);
    }

    /**
     * Fetch customers for DataTables API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiCustomer()
    {
        if (request()->ajax()) {
            $customers = DB::table('customers')->select(['id', 'name', 'phone', 'group', 'location', 'created_at', 'updated_at'])->get();

            return DataTables::of($customers)
                ->addColumn('action', function ($customer) {
                    return '<div class="dropdown" style="width:100%;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <button class="m-5 btn btn-outline btn-default btn-sm"><i class="fa fa-ellipsis-v"></i></button>
                                </a>
                                <ul class="dropdown-menu">
                                <li>
                                <li><a onclick="editForm(' . $customer->id . ')" class="btn btn-info btn-xs" style="color:white"><i class="glyphicon glyphicon-edit" style="color:white"></i> edit</a></li>
                                    <li><a onclick="deleteData(' . $customer->id . ')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
                                </ul>
                            </div> 
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json([
            'message' => 'Invalid request type.',
            'success' => false
        ], 400);
    }

    public function update()
    {
        $id = request()->input('id');
    }
    /**
     * Delete a customer by ID.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {


        DB::beginTransaction();

        try {
            $customer = DB::table('customers')->find($id);

            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found.',
                    'success' => false
                ], 404);
            }

            DB::table('customers')->delete($id);

            $this->logActivity('Customer Deleted', "ID: {$customer->id}, Name: {$customer->name}");

            DB::commit();

            return response()->json([
                'message' => 'Customer deleted successfully.',
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete customer.',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
