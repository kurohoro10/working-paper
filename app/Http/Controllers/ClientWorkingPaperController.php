<?php
/**
 * ClientWorkingPaperController
 *
 * This controller handles the guest user's input and output using
 * unique UUID share tokens.
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
 * Handles public-facing interactions for clients. This includes viewing
 * data authorized via token and submitting expense documentation without
 * requiring a full user account.
 */
class ClientWorkingPaperController extends Controller
{
    /**
     * Display the working paper for a client using a unique share token.
     *
     * Validates the token exists and checks the share_token_expires_at
     * timestamp before granting access.
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

        if ($workingPaper->shareTokenIsExpired()) {
            abort(410, 'This shared link has expired.');
        }

        return view('working-papers.show', compact('workingPaper'));
    }

    /**
     * Bulk store or update client-submitted expense information.
     *
     * Updates description and amounts, and handles receipt file uploads
     * to the media collection. Returns to the previous page with a success flash.
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
