{{-- BAS Section Partial - working-papers/partials/bas-section.blade.php --}}

<div class="space-y-6">
    {{-- Quarter Selection & Combine Feature --}}
    <div class="flex justify-between items-center p-4 bg-orange-100 rounded-lg border border-orange-300">
        <div class="flex items-center space-x-4">
            <label class="flex items-center">
                <input type="checkbox" class="rounded border-gray-300 text-orange-600" checked>
                <span class="ml-2 text-sm font-medium">Q1</span>
            </label>
            <label class="flex items-center">
                <input type="checkbox" class="rounded border-gray-300 text-orange-600" checked>
                <span class="ml-2 text-sm font-medium">Q2</span>
            </label>
            <label class="flex items-center">
                <input type="checkbox" class="rounded border-gray-300 text-orange-600" checked>
                <span class="ml-2 text-sm font-medium">Q3</span>
            </label>
            <label class="flex items-center">
                <input type="checkbox" class="rounded border-gray-300 text-orange-600" checked>
                <span class="ml-2 text-sm font-medium">Q4</span>
            </label>
        </div>

        <button type="button"
                class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
            </svg>
            Combine Q1-Q4
        </button>
    </div>

    {{-- Current Quarter View --}}
    <div class="border-b border-gray-200 pb-2">
        <div class="flex items-center justify-between">
            <h4 class="text-lg font-semibold text-gray-900">Quarter 1 (July - September)</h4>
            <div class="flex space-x-2">
                <button type="button" class="px-3 py-1 text-sm bg-gray-100 rounded hover:bg-gray-200">← Prev</button>
                <button type="button" class="px-3 py-1 text-sm bg-gray-100 rounded hover:bg-gray-200">Next →</button>
            </div>
        </div>
    </div>

    {{-- GST Sales (G1) --}}
    <div>
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-md font-semibold text-gray-800">G1: Total Sales (GST Inclusive)</h4>
            <button type="button" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                + Add Sale
            </button>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Inc GST</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">GST</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ex GST</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Upload</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-3 py-6 text-center text-sm text-gray-500">No sales entries</td>
                    </tr>
                </tbody>
                <tfoot class="bg-orange-50 font-semibold">
                    <tr>
                        <td colspan="2" class="px-3 py-2 text-sm">G1 Total</td>
                        <td class="px-3 py-2 text-sm">$0.00</td>
                        <td class="px-3 py-2 text-sm">$0.00</td>
                        <td class="px-3 py-2 text-sm">$0.00</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- GST on Purchases (G11) --}}
    <div>
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-md font-semibold text-gray-800">G11: GST on Purchases</h4>
            <button type="button" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                + Add Purchase
            </button>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Inc GST</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">GST</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ex GST</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Upload</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-3 py-6 text-center text-sm text-gray-500">No purchase entries</td>
                    </tr>
                </tbody>
                <tfoot class="bg-orange-50 font-semibold">
                    <tr>
                        <td colspan="2" class="px-3 py-2 text-sm">G11 Total</td>
                        <td class="px-3 py-2 text-sm">$0.00</td>
                        <td class="px-3 py-2 text-sm">$0.00</td>
                        <td class="px-3 py-2 text-sm">$0.00</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- BAS Summary Card --}}
    <div class="bg-orange-100 rounded-lg p-6 border-2 border-orange-300">
        <h4 class="text-lg font-bold text-gray-900 mb-4">BAS Summary - Q1</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg">
                <div class="text-xs text-gray-600 mb-1">G1: Total Sales</div>
                <div class="text-xl font-bold text-gray-900">$0.00</div>
            </div>
            <div class="bg-white p-4 rounded-lg">
                <div class="text-xs text-gray-600 mb-1">1A: GST on Sales</div>
                <div class="text-xl font-bold text-green-600">$0.00</div>
            </div>
            <div class="bg-white p-4 rounded-lg">
                <div class="text-xs text-gray-600 mb-1">1B: GST on Purchases</div>
                <div class="text-xl font-bold text-blue-600">$0.00</div>
            </div>
            <div class="bg-white p-4 rounded-lg">
                <div class="text-xs text-gray-600 mb-1">Net GST</div>
                <div class="text-xl font-bold text-orange-600">$0.00</div>
            </div>
        </div>

        <div class="mt-4 p-4 bg-white rounded-lg border-2 border-orange-400">
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-700">Amount Payable to ATO (Refund)</span>
                <span class="text-2xl font-bold text-orange-600">$0.00</span>
            </div>
        </div>
    </div>

    {{-- Annual Consolidated View (shown when combining quarters) --}}
    <div id="annual-bas-summary" class="hidden bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg p-6 border-2 border-orange-400">
        <h4 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Annual BAS Summary (Q1-Q4 Combined)
        </h4>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-xs text-gray-600 mb-1">Q1 Net GST</div>
                <div class="text-lg font-bold text-gray-900">$0.00</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-xs text-gray-600 mb-1">Q2 Net GST</div>
                <div class="text-lg font-bold text-gray-900">$0.00</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-xs text-gray-600 mb-1">Q3 Net GST</div>
                <div class="text-lg font-bold text-gray-900">$0.00</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-xs text-gray-600 mb-1">Q4 Net GST</div>
                <div class="text-lg font-bold text-gray-900">$0.00</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg border-2 border-orange-500">
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-gray-900">Annual Total GST</span>
                <span class="text-3xl font-bold text-orange-600">$0.00</span>
            </div>
        </div>
    </div>

    {{-- Comments --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Client Comments</label>
            <textarea rows="3"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                      placeholder="Add any notes or comments..."></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Internal Comments</label>
            <textarea rows="3"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                      placeholder="Add internal notes..."></textarea>
        </div>
    </div>
</div>
