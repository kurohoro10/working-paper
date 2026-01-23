<?php

namespace App\Http\Controllers;

use App\Models\WorkingPaper;
use Illuminate\Http\Request;

/**
     * Class ClientWorkingPaperController
     *
     * Handles client-facing access to EndureGo Internal Working Papers
     * via signed URLs. Clients can view and submit expense information
     * but cannot access internal-only data.
     *
     * Security:
     * - Access is restricted to signed URLs
     * - No authentication required
     * - Internal comments are hidden
     *
     * @package App\Http\Controllers
     */
class ClientWorkingPaperController extends Controller
{
    /**
     * Display the working paper for a client via signed URL.
     *
     * This method allows a client to:
     * - View client & job details
     * - Enter expenses
     * - Upload supporting documents
     * - Add client comments
     *
     * Internal-only fields are excluded.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Illuminate\View\View
     */
    public function show(string $token)
    {
        $workingPaper = WorkingPaper::where('share_token', $token)
            ->with('expenses')
            ->firstOrfail();

        return view('working-papers.show', compact('workingPaper'));
    }

    /**
     * Store or update client-submitted expense information.
     *
     * Clients can only modify:
     * - Expense description
     * - Amount
     *  -Client comments
     * - Upload supporting documents
     *
     * Internal comments are protected.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, WorkingPaper $workingPaper)
    {
        $validated = $request->validate([
            'expenses.*.id'             => ['nullable', 'integer', 'exists:expenses,id'],
            'expenses.*.description'    => ['required', 'string', 'max:255'],
            'expenses.*.amount'         => ['required', 'numeric', 'min:0'],
            'expenses.*.client_comment' => ['nullable', 'string'],
            'expenses.*.receipt'        => ['nullable', 'file', 'max:5120'],
        ]);

        foreach ($validated['expenses'] as $expenseData) {
            $expense = $workingPaper->expenses()->updateOrCreate(
                ['id' => $expenseData['id'] ?? null],
                [
                    'description'    => $expenseData['description'],
                    'amount'         => $expenseData['amount'],
                    'client_comment' => $expenseData['client_comment'] ?? null,
                ]
            );

            // Handle receipt upload if present
            if (isset($expenseData['receipt'])) {
                $expense
                    ->addmedia($expenseData['receipt'])
                    ->toMediaCollection('receipts');
            }
        }

        return back()->with('success', 'Your information has been saved.');
    }

}
