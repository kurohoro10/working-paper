<?php
/**
 * ExpenseController
 *
 * This controller handles the storing and viewing of expense
 * for both admin and guest users.
 *
 * @category  Controllers
 * @package   App\Http\Controllers
 * @author    Name <email@email.com>
 * @copyright 2026 Name
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   GIT: 1.2.0
 * @link      http://url.com
 */

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\WorkingPaper;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ExpenseController
 *
 * Manages the lifecycle of expense records associated with working papers,
 * including file management for receipts and multi-layer authorization.
 */
class ExpenseController extends Controller
{
    /**
     * Show the expense edit form.
     *
     * @param \App\Models\Expense $expense
     * @return \Illuminate\View\View
     */
    public function edit(Expense $expense): View
    {
        $this->authorize('update', $expense);

        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update an expense
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Expense $expense
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorize('update', $expense);

        $validated = $request->validate([
            'description'      => ['required', 'string'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'client_comment'   => ['nullable', 'string'],
            'internal_comment' => ['nullable', 'string'],
            'receipt'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        // Receipt replacement
        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        // Enforce internal comment permission
        if (auth()->user()->cannot('addInternalComment', Expense::class)) {
            unset($validated['internal_comment']);
        }

        $expense->update($validated);

        return back()->with('success', 'Expense updated successfully.');
    }

    /**
     * Delete an expense.
     *
     * @param \App\Models\Expense $expense
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        return back()->with('success', 'Expense deleted.');
    }

    /**
     * Store a new expense for a specific working paper.
     *
     * Validates input, ensures the working paper is not finalized,
     * handles receipt file uploads, and filters internal comments
     * based on user permissions.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, WorkingPaper $workingPaper): RedirectResponse
    {
        // 1. Immutable State Check
        if ($workingPaper->status === 'finalised') {
            return back()->with('error', 'This paper is finalised and cannot be edited.');
        }

        $validated = $request->validate([
            'description'      => ['required', 'string'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'client_comment'   => ['nullable', 'string'],
            'internal_comment' => ['nullable', 'string'],
            'receipt'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        // 2. Asset Mangement
        if ($request->hasFile('receipt')) {
            // Store the file and add the resulting path to the $validated array
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        // 3. Authorization/Permission Filtering
        if (!auth()->check() || auth()->user()->cannot('addInternalComment', Expense::class)) {
            unset($validated['internal_comment']);
        }

        $workingPaper->expenses()->create($validated);

        return back()->with('success', 'Expense added.');
    }

    /**
     * Serve the receipt file for viewing from local storage.
     *
     * This implementation relies on the local filesystem. It retrieves the
     * file from the local storage path and streams it as a binary response.
     * * Note: This method is currently coupled to local disk drivers.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Expense $expense
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function viewReceipt(Request $request, Expense $expense): BinaryFileResponse
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
