{{-- Company Tax Section Partial - working-papers/partials/ctax-section.blade.php --}}

<div class="space-y-6">
    {{-- Quarter Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button type="button" class="quarter-tab-ctax active border-b-2 border-indigo-600 py-4 px-1 text-sm font-medium text-indigo-600" data-quarter="all">
                All Periods
            </button>
            <button type="button" class="quarter-tab-ctax border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-quarter="q1">
                Q1 (Jul-Sep)
            </button>
            <button type="button" class="quarter-tab-ctax border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-quarter="q2">
                Q2 (Oct-Dec)
            </button>
            <button type="button" class="quarter-tab-ctax border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-quarter="q3">
                Q3 (Jan-Mar)
            </button>
            <button type="button" class="quarter-tab-ctax border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-quarter="q4">
                Q4 (Apr-Jun)
            </button>
        </nav>
    </div>

    {{-- Income Section --}}
    <div>
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-md font-semibold text-gray-800">Income</h4>
            <button type="button" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
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
            <button type="button" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
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
    <div class="bg-indigo-100 rounded-lg p-6 border-2 border-indigo-300">
        <h4 class="text-lg font-bold text-gray-900 mb-4">Company Tax Summary</h4>
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
                <div class="text-2xl font-bold text-indigo-600">$0.00</div>
            </div>
        </div>
    </div>

    {{-- Comments Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Client Comments</label>
            <textarea rows="3"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Add any notes or comments..."></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Internal Comments</label>
            <textarea rows="3"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Add internal notes..."></textarea>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Quarter tab switching for CTax
    document.querySelectorAll('.quarter-tab-ctax').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.quarter-tab-ctax').forEach(t => {
                t.classList.remove('active', 'border-indigo-600', 'text-indigo-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });

            this.classList.add('active', 'border-indigo-600', 'text-indigo-600');
            this.classList.remove('border-transparent', 'text-gray-500');

            const quarter = this.dataset.quarter;
            console.log('CTax - Filtering by quarter:', quarter);
        });
    });
</script>
@endpush
