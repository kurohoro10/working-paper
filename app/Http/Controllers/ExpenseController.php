<?php

namespace App\Http\Controllers;

use App\Models\Expense;
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

        // Handle the file upload
        if ($request->hasFile('receipt')) {
            // Store the file and add the resulting path to the $validated array
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        // Strip internal_comment if user is NOT an admin
        if (!auth()->check() || auth()->user()->cannot('addInternalComment', Expense::class)) {
            unset($validated['internal_comment']);
        }

        $workingPaper->expenses()->create($validated);

        return back()->with('success', 'Expense added.');
    }

    public function viewReceipt(Request $request, Expense $expense)
    {
        // If user is logged in
        if (auth()->check()) {
            $this->authorize('view', $expense);
        } else {
            $token = $request->query('token');

            if (!$token || $token !== $expense->workingPaper->share_token) {
                abort(403);
            }
        }

        return response()->file(
            storage_path('app/public/' . $expense->receipt_path)
        );
    }
}
