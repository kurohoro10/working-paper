<?php
/**
 * ClientWorkingPaperController
 *
 * This controller handles the guest user's input and output using share token
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

use App\Models\WorkingPaper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ClientWorkingPaperController
 *
 * Handles client-facing access to internal working papers via unique tokens.
 * This controller manages the public/guest interaction layer where clients
 * can review their specific data and upload expense details.
 */
class ClientWorkingPaperController extends Controller
{
    /**
     * Display the working paper for a client using a unique share token.
     *
     * @param string $token The unique hash/token used to identify the working paper.
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(string $token): View
    {
        $workingPaper = WorkingPaper::where('share_token', $token)
            ->with('expenses')
            ->firstOrfail();

        return view('working-papers.show', compact('workingPaper'));
    }

    /**
     * Bulk store or update client-submitted expense information.
     *
     * Processes a collection of expense data, handles file uploads for receipts,
     * and ensure that internal-only fields remain untouched.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\WorkingPaper $workingPaper
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, WorkingPaper $workingPaper): RedirectResponse
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
