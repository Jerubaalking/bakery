<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BeeMService;
use Auth;

class MessageController  extends Controller
{

    use LogsActivity;
    protected $beeMService;
    public function __construct(BeeMService $beeMService)
    {
        $this->beeMService = $beeMService;
        $this->middleware('auth');
    }
    /**
     * Display the message index view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('messages.index');
    }


    public function edit($id)
    {
        $message = DB::table('messages')->find($id);
        return response()->json(['data' => $message]);
    }

    public function preSend()
    {

        if (request()->ajax()) {
            try {
                // Fetch distinct groups from the customers table
                $groups = DB::table('customers')->distinct()->pluck('group')->map(function ($group) {
                    return ['value' => $group, 'name' => ucfirst($group)];  // Assuming group is a string, capitalize it
                });

                // Fetch all customers from the database
                $customers = DB::table('customers')->select('id', 'name')->get()->map(function ($customer) {
                    return ['id' => $customer->id, 'name' => $customer->name];
                });


                // Return the data as JSON
                return response()->json([
                    'status' => 'success',
                    'groups' => $groups,
                    'customers' => $customers,
                ]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }
    /**
     * Store a newly created message in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255|unique:messages,title',
                'message' => 'required|string|max:160|unique:messages,message',
            ]);

            DB::beginTransaction();

            try {
                // Insert message and retrieve the ID
                $messageId = DB::table('messages')->insertGetId($validatedData);

                // Fetch only the name for logging
                $messageName = DB::table('messages')->where('id', $messageId)->value('title');

                // Log the activity
                $this->logActivity('Message Created', "ID: $messageId, Name: $messageName");

                DB::commit();

                return response()->json([
                    'message' => 'Message created successfully.',
                    'success' => true
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Failed to create message.',
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
     * Fetch messages for DataTables API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiMessages()
    {
        if (request()->ajax()) {
            $messages = DB::table('messages')->select(['*'])->get();

            return DataTables::of($messages)
                ->addColumn('action', function ($message) {
                    return '<div class="dropdown" style="width:100%;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <button class="m-5 btn btn-outline btn-default btn-sm"><i class="fa fa-ellipsis-v"></i></button>
                                </a>
                                <ul class="dropdown-menu">
                                <li>
                                <li><a onclick="sendForm(' . $message->id . ')" class="btn btn-info btn-xs" style="color:white"><i class="glyphicon glyphicon-envelope" style="color:white"></i> send</a></li>
                                <li><a onclick="editForm(' . $message->id . ')" class="btn btn-info btn-xs" style="color:white"><i class="glyphicon glyphicon-edit" style="color:white"></i> edit</a></li>
                                    <li><a onclick="deleteData(' . $message->id . ')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
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
     * Delete a message by ID.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {


        DB::beginTransaction();

        try {
            $message = DB::table('messages')->find($id);

            if (!$message) {
                return response()->json([
                    'message' => 'Message not found.',
                    'success' => false
                ], 404);
            }

            DB::table('messages')->delete($id);

            $this->logActivity('Message Deleted', "ID: {$message->id}, Name: {$message->name}");

            DB::commit();

            return response()->json([
                'message' => 'Message deleted successfully.',
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete message.',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }


    public function sendMessage(Request $request, $id)
    {
        info(json_encode($request->all()));

        // Validate the incoming request
        $request->validate([
            'sendOption' => 'required|string|in:group,customers',
            'group' => 'nullable|required_if:sendOption,group|string',
            'customers' => 'nullable|required_if:sendOption,customers|array',
            'customers.*' => 'required|integer|exists:customers,id',  // Validate customer IDs as integers and ensure they exist
        ]);

        // Fetch the message content by ID
        $message = DB::table('messages')->find($id);

        if (!$message) {
            return response()->json(['error' => 'Message not found.'], 404);
        }

        // Initialize recipients array
        $recipients = [];

        // Handle group or customers based on `sendOption`
        if ($request->sendOption === 'group') {
            // Fetch customers in the selected group
            $group = $request->group;

            $customerPhones = DB::table('customers')
                ->where('group', $group)
                ->pluck('phone')
                ->toArray();

            if (empty($customerPhones)) {
                return response()->json(['error' => 'No customers found in the selected group.'], 404);
            }

            // Build recipients array
            foreach ($customerPhones as $phone) {
                // Fetch customer by phone number and get the id and phone
                $customer = DB::table('customers')->where('phone', $phone)->first(['id', 'phone']);

                // Ensure the customer is found
                if ($customer) {
                    $recipients[] = [
                        'recipient_id' => $customer->id,  // Use the customer id as an integer
                        'dest_addr' => ltrim($phone, '+'), // Remove the leading '+' sign from phone number
                    ];
                }
            }
        } elseif ($request->sendOption === 'customers') {
            // Use the provided list of customer IDs
            foreach ($request->customers as $customerId) {
                // Fetch customer by ID and get the id and phone
                $customer = DB::table('customers')->where('id', $customerId)->first(['id', 'phone']);

                // Ensure the customer is found
                if ($customer) {
                    $recipients[] = [
                        'recipient_id' => $customer->id,  // Use the customer id as an integer
                        'dest_addr' => ltrim($customer->phone, '+'), // Remove the leading '+' sign from phone number
                    ];
                }
            }
        }

        // Ensure recipients array is not empty
        if (empty($recipients)) {
            return response()->json(['error' => 'No recipients provided.'], 400);
        }

        // Prepare message content
        $messageContent = $message->message;

        // Send the message via BeeM API
        $response = $this->beeMService->sendBulkMessage(
            $messageContent, // Message content
            $recipients,      // Recipients array
        );

        // Return the response (success or failure)
        return response()->json($response);
    }
}
