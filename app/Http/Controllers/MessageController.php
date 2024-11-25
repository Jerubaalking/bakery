<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class MessageController  extends Controller
{
    use LogsActivity;
    public function __construct()
    {
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
                ->addColumn('action', function ($data) {
                    return '
                        <a href="#" class="btn btn-outline-secondary btn-xs" style="color:black;"><i class="glyphicon glyphicon-eye-open text-success"></i> View</a>
                        <a onclick="sendForm(' . $data->id . ')" style="color:black;"  class="btn btn-outline-secondary btn-xs"><i class="glyphicon glyphicon-send text-info"></i> Send</a>
                        <a onclick="editForm(' . $data->id . ')" style="color:black;" class="btn btn-outline-secondary btn-xs"><i class="glyphicon glyphicon-edit text-primary"></i> Edit</a>
                        <a onclick="deleteData(' . $data->id . ')" style="color:black;" class="btn btn-outline-secondary btn-xs"><i class="glyphicon glyphicon-trash text-danger"></i> Delete</a>
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

    public function update(){
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
}
