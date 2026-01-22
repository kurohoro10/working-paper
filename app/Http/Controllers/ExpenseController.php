<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkingPaper;
use Illuminate\Support\Facades\Storage;

/**
 * Handles expense CRUD for working papers.
 */
class ExpenseController extends Controller
{
    /**
     * Store a new expense.
     */
    public function store(Request $request, WorkingPaper $workingPaper)
    {
        $validated = $request->validate([
            'description'      => 'required|string',
            'amount'           => 'required|numeric|min:0',
            'client_comment'   => 'nullable|string',
            'internal_comment' => 'nullable|string',
            'receipt'          => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->fil('receipt')->store('receipts');
        }

        $workingPaper->expenses()->create($validated);

        return back()->with('success', 'Expense added.');
    }
}
