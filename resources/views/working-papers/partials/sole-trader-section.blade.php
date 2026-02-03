{{-- Sole Trader Section Partial - working-papers/partials/sole-trader-section.blade.php --}}

<div class="space-y-6">
    {{-- Quarter Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button type="button" class="quarter-tab active border-b-2 border-purple-600 py-4 px-1 text-sm font-medium text-purple-600" data-quarter="all">
                All Periods
            </button>
            <button type="button" class="quarter-tab border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-quarter="q1">
                Q1 (Jul-Sep)
            </button>
            <button type="button" class="quarter-tab border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-quarter="q2">
                Q2 (Oct-Dec)
            </button>
            <button type="button" class="quarter-tab border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-quarter="q3">
                Q3 (Jan-Mar)
            </button>
            <button type="button" class="quarter-tab border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-quarter="q4">
                Q4 (Apr-Jun)
            </button>
        </nav>
    </div>

    {{-- Income Section --}}
    <div>
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-md font-semibold text-gray-800">Income</h4>
            <button type="button" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                + Add Income
            </button>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quarter</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Upload</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                            No income entries yet
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">Total Income</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">$0.00</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Expenses Section --}}
    <div>
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-md font-semibold text-gray-800">Expenses</h4>
            <button type="button" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                + Add Expense
            </button>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount inc GST</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">GST</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net (ex GST)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quarter</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Upload</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">
                            No expenses added yet
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900">Total Expenses</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">$0.00</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">$0.00</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">$0.00</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="bg-purple-100 rounded-lg p-6 border-2 border-purple-300">
        <h4 class="text-lg font-bold text-gray-900 mb-4">Profit/Loss Summary</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <div class="text-sm text-gray-600">Total Income</div>
                <div class="text-2xl font-bold text-green-600">$0.00</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Total Expenses (Net)</div>
                <div class="text-2xl font-bold text-red-600">$0.00</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Net Profit</div>
                <div class="text-2xl font-bold text-purple-600">$0.00</div>
            </div>
        </div>
    </div>

    {{-- Comments Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Client Comments</label>
            <textarea rows="3"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                      placeholder="Add any notes or comments..."></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Internal Comments</label>
            <textarea rows="3"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                      placeholder="Add internal notes..."></textarea>
        </div>
    </div>
</div>

{{-- Add this at the bottom of sole-trader-section.blade.php, before @push('scripts') --}}

{{-- Hidden template for new expense rows --}}
<template id="sole-trader-expense-row-template">
    <tr data-gst-group>
        <td class="px-4 py-3">
            <select class="w-full rounded-md border-gray-300 text-sm" name="type[]">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
            </select>
        </td>
        <td class="px-4 py-3">
            <input type="text"
                   name="description[]"
                   class="w-full rounded-md border-gray-300 text-sm"
                   placeholder="Expense description">
        </td>
        <td class="px-4 py-3">
            <input type="number"
                   step="0.01"
                   name="amount_inc_gst[]"
                   data-gst-amount
                   class="w-full rounded-md border-gray-300 text-sm"
                   placeholder="0.00">
        </td>
        <td class="px-4 py-3">
            <input type="number"
                   step="0.01"
                   name="gst_amount[]"
                   data-gst-gst
                   class="w-full rounded-md border-gray-300 text-sm bg-gray-50"
                   placeholder="0.00"
                   readonly>
        </td>
        <td class="px-4 py-3">
            <input type="number"
                   step="0.01"
                   name="net_ex_gst[]"
                   data-gst-net
                   class="w-full rounded-md border-gray-300 text-sm bg-gray-50"
                   placeholder="0.00"
                   readonly>
        </td>
        <td class="px-4 py-3">
            <select class="w-full rounded-md border-gray-300 text-sm" name="quarter[]">
                <option value="all">All</option>
                <option value="q1">Q1</option>
                <option value="q2">Q2</option>
                <option value="q3">Q3</option>
                <option value="q4">Q4</option>
            </select>
        </td>
        <td class="px-4 py-3">
            <input type="file"
                   name="upload[]"
                   class="text-xs"
                   required>
        </td>
        <td class="px-4 py-3">
            <button type="button"
                    onclick="removeExpenseRow(this)"
                    class="text-red-600 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </td>
    </tr>
</template>

@push('scripts')
<script>
    // Quarter tab switching (existing code)
    document.querySelectorAll('.quarter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // ... existing code ...
        });
    });

    // Add expense row functionality
    document.querySelector('#section-sole_trader .text-purple-600').addEventListener('click', function() {
        const template = document.getElementById('sole-trader-expense-row-template');
        const tbody = document.querySelector('#section-sole_trader tbody');

        // Remove "no expenses" row if it exists
        const emptyRow = tbody.querySelector('td[colspan="8"]');
        if (emptyRow) {
            emptyRow.closest('tr').remove();
        }

        // Clone and add new row
        const newRow = template.content.cloneNode(true);
        tbody.appendChild(newRow);

        // Setup GST calculation for the new row
        const addedRow = tbody.lastElementChild;
        const amountInput = addedRow.querySelector('[data-gst-amount]');
        const gstInput = addedRow.querySelector('[data-gst-gst]');
        const netInput = addedRow.querySelector('[data-gst-net]');

        if (amountInput && gstInput && netInput) {
            setupGSTCalculation(amountInput, gstInput, netInput);
        }
    });

    function removeExpenseRow(button) {
        const row = button.closest('tr');
        const tbody = row.closest('tbody');
        row.remove();

        // If no rows left, show empty message
        if (tbody.children.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">No expenses added yet</td></tr>';
        }
    }
</script>
@endpush
