<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = DB::table('activities')->get();

        return view('activity.index', compact('activities'));
    }
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function apiActivity(Request $request)
    {
        info('am here at api activity');
        if ($request->ajax()) {


            $activity = DB::table('activities')->get();
            // You have to create a link option to view account
                return DataTables::of($activity)

                    ->editColumn('time', function ($activity) {
                        return '<div class="text-warning">' . $activity->created_at . '</div>';
                    })
                
                    ->editColumn('action', function ($activity) {
                        return '<small class="text-default text-wrap" style="max-width:30%;  display:flex;">' . $activity->action . '</small>';
                    })
                  
                    ->editColumn('info', function ($activity) {
                        return '<small class="bg-dark">' . $activity->output . '</small>';
                    })
                    ->rawColumns([])
                    ->make(true);
        }

    }
}
