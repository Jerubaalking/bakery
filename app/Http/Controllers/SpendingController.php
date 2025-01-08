<?php

namespace App\Http\Controllers;

use App\Models\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SpendingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Check if the request is an AJAX call (for DataTables or API)
        if ($request->ajax()) {
            $spending = Spending::query();

            // Apply filters, e.g., by date or user
            if ($request->has('from') && $request->has('to')) {
                $spending->whereBetween('created_at', [
                    Carbon::parse($request->date_from)->startOfDay(),
                    Carbon::parse($request->date_to)->endOfDay()
                ]);
            }

            // Return data formatted for DataTables
            return DataTables::of($spending)
                ->addIndexColumn()
                // ->addColumn('formatted_amount', function ($expense) {
                //     return number_format($expense->amount, 2);
                // })
                ->addColumn('actions', function ($spending) {
                    return '<div class="dropdown" style="width:100%;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <button class="m-5 btn btn-outline btn-default btn-sm"><i class="fa fa-ellipsis-v"></i></button>
                                </a>
                                <ul class="dropdown-menu">
                                <li>
                                <li><a onclick="editForm(' . $spending->id . ')" class="btn btn-info btn-xs" style="color:white"><i class="glyphicon glyphicon-edit" style="color:white"></i> edit</a></li>
                                    <li><a onclick="deleteData(' . $spending->id . ')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
                                </ul>
                            </div> 
                   ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $expenses = [];
        // Default view for non-AJAX requests
        return view('spending.index', compact('expenses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'receipt' => 'string',
            'date' => 'date',
            'category' => 'required|string|max:255',
        ]);

        $spending = new Spending();
        $spending->description = $request->description;
        $spending->category = $request->category;
        $spending->receipt = $request->receipt;
        $spending->date = $request->date;
        $spending->amount = $request->amount;
        $spending->save();

        return redirect()->back()->with('success', 'Spending recorded successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Spending $spending
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $spending = Spending::find($id);
        return response()->json(['data' => $spending]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Spending $spending
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'receipt' => 'string',
            'date' => 'date',
            'category' => 'required|string|max:255',
        ]);
        $spending = Spending::find($id);
        $spending->description = $request->description;
        $spending->category = $request->category;
        $spending->receipt = $request->receipt;
        $spending->date = $request->date;
        $spending->amount = $request->amount;
        $spending->save();
        DB::commit();
        return redirect()->route('spending.index')->with('success', 'Spending updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Spending $spending
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Spending $spending)
    {
        $spending->delete();

        return redirect()->route('spending.index')->with('success', 'Expense deleted successfully!');
    }
}
