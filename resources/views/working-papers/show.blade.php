<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="__('Working Paper Details')" :backRoute="auth()->check() ? route('working-papers.index') : null">
            {{-- Hide back button for public link users --}}
            @auth
                @can('update', $workingPaper)
                    <a href="{{ route('working-papers.edit', $workingPaper) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endcan

                @can('delete', $workingPaper)
                    @if($workingPaper->status !== 'finalised')
                        <form method="POST"
                            action="{{ route('working-papers.destroy', $workingPaper) }}"
                            onsubmit="return confirm('This action cannot be undone. Delete this working paper?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    @endif
                @endcan
            @endauth
        </x-page-header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Share link section: Only show to logged in owners --}}
            @if(auth()->check() && auth()->user()->getRoleRank() >= 2)
                @include('working-papers.partials.share-link-card')
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">

                    @include('working-papers.partials.details-header')

                    {{-- Work Type Selector --}}
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg border-2 border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Work Types</h3>
                        <div class="flex flex-wrap gap-3">
                            @php
                                $workTypes = ['wage', 'rental_property', 'sole_trader', 'bas', 'ctax', 'ttax', 'smsf'];
                                $workTypeLabels = [
                                    'wage' => 'Wage',
                                    'rental_property' => 'Rental Property',
                                    'sole_trader' => 'Sole Trader',
                                    'bas' => 'BAS',
                                    'ctax' => 'Company Tax',
                                    'ttax' => 'Trust Tax',
                                    'smsf' => 'SMSF'
                                ];
                                $selectedTypes = $workingPaper->work_types ?? [];
                            @endphp

                            @foreach($workTypes as $type)
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           name="work_types[]"
                                           value="{{ $type }}"
                                           {{ in_array($type, $selectedTypes) ? 'checked' : '' }}
                                           onchange="toggleWorkTypeSection('{{ $type }}')"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ $workTypeLabels[$type] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Wage Section --}}
                    <div id="section-wage" class="work-type-section mb-8" style="display: {{ in_array('wage', $selectedTypes) ? 'block' : 'none' }};">
                        <div class="border-2 border-blue-200 rounded-lg p-6 bg-blue-50">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Wage Income
                            </h3>

                            @include('working-papers.partials.wage-section')
                        </div>
                    </div>

                    {{-- Rental Property Section --}}
                    <div id="section-rental_property" class="work-type-section mb-8" style="display: {{ in_array('rental_property', $selectedTypes) ? 'block' : 'none' }};">
                        <div class="border-2 border-green-200 rounded-lg p-6 bg-green-50">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Rental Properties
                            </h3>

                            @include('working-papers.partials.rental-property-section')
                        </div>
                    </div>

                    {{-- Sole Trader Section --}}
                    <div id="section-sole_trader" class="work-type-section mb-8" style="display: {{ in_array('sole_trader', $selectedTypes) ? 'block' : 'none' }};">
                        <div class="border-2 border-purple-200 rounded-lg p-6 bg-purple-50">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Sole Trader
                            </h3>

                            @include('working-papers.partials.sole-trader-section')
                        </div>
                    </div>

                    {{-- BAS Section --}}
                    <div id="section-bas" class="work-type-section mb-8" style="display: {{ in_array('bas', $selectedTypes) ? 'block' : 'none' }};">
                        <div class="border-2 border-orange-200 rounded-lg p-6 bg-orange-50">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                BAS (Business Activity Statement)
                            </h3>

                            @include('working-papers.partials.bas-section')
                        </div>
                    </div>

                    {{-- Company Tax Section --}}
                    <div id="section-ctax" class="work-type-section mb-8" style="display: {{ in_array('ctax', $selectedTypes) ? 'block' : 'none' }};">
                        <div class="border-2 border-indigo-200 rounded-lg p-6 bg-indigo-50">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Company Tax
                            </h3>

                            @include('working-papers.partials.ctax-section')
                        </div>
                    </div>

                    {{-- Trust Tax Section --}}
                    <div id="section-ttax" class="work-type-section mb-8" style="display: {{ in_array('ttax', $selectedTypes) ? 'block' : 'none' }};">
                        <div class="border-2 border-pink-200 rounded-lg p-6 bg-pink-50">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Trust Tax
                            </h3>

                            @include('working-papers.partials.ttax-section')
                        </div>
                    </div>

                    {{-- SMSF Section --}}
                    <div id="section-smsf" class="work-type-section mb-8" style="display: {{ in_array('smsf', $selectedTypes) ? 'block' : 'none' }};">
                        <div class="border-2 border-teal-200 rounded-lg p-6 bg-teal-50">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                SMSF (Self-Managed Super Fund)
                            </h3>

                            @include('working-papers.partials.smsf-section')
                        </div>
                    </div>

                    {{-- Legacy Expenses Table (fallback if no work types selected) --}}
                    {{-- @if(empty($selectedTypes))
                        @include('working-papers.partials.expenses-table')
                        @include('working-papers.partials.expense-form')
                    @endif --}}

                    {{-- Footer Actions --}}
                    <div class="bg-gray-50 -m-8 mt-8 p-6 flex gap-4">
                        <a href="{{ url('/working-papers/'.$workingPaper->id.'/pdf') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download PDF Snapshot
                        </a>

                        @if((auth()->check() && auth()->user()->getRoleRank() >= 2) && $workingPaper->status !== 'finalised')
                            <form action="{{ route('working-papers.finalise', $workingPaper) }}" method="POST">
                                @csrf
                                <button class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Finalise Paper
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ==========================================
        // WORK TYPE TOGGLE
        // ==========================================

        function toggleWorkTypeSection(type) {
            const section = document.getElementById('section-' + type);
            const checkbox = document.querySelector(`input[value="${type}"]`);

            if (section) {
                section.style.display = checkbox.checked ? 'block' : 'none';
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                return;
            }

            // Save selection via AJAX
            fetch('{{ route("working-papers.update-work-types", $workingPaper) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content
                },
                body: JSON.stringify({
                    work_type: type,
                    enabled: checkbox.checked
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Work type updated successfully');
                }
            })
            .catch(error => {
                console.error('Error updating work type:', error);
                // Optionally revert checkbox on error
                checkbox.checked = !checkbox.checked;
                section.style.display = checkbox.checked ? 'block' : 'none';
            });
        }

        // ==========================================
        // GST CALCULATIONS
        // ==========================================

        /**
         * Auto-calculate GST when amount is entered
         */
        function calculateGST(amountIncGST, gstRate = 0.10) {
            const net = amountIncGST / (1 + gstRate);
            const gst = amountIncGST - net;
            return {
                net: net.toFixed(2),
                gst: gst.toFixed(2) // FIXED: was gst:gst.toFixed(2)
            };
        }

        /**
         * Validate all three fields match
         */
        function validateGSTFields(incGST, gst, net) {
            const calculated = parseFloat(net) + parseFloat(gst);
            const diff = Math.abs(calculated - parseFloat(incGST));
            return diff < 0.01; // Allow 1 cent rounding tolerance
        }

        /**
         * GST-free toggle
         */
        function toggleGSTFree(checkbox, gstField, netField, amountField) {
            if (checkbox.checked) {
                gstField.value = '0.00'; // FIXED: was amountField.value
                netField.value = amountField.value;
                gstField.disabled = true;
                netField.disabled = true; // Also disable net field when GST-free
            } else {
                gstField.disabled = false;
                netField.disabled = false; // FIXED: was true
                // Recalculate
                const result = calculateGST(amountField.value);
                gstField.value = result.gst;
                netField.value = result.net;
            }
        }

        /**
         * Setup GST auto-calculation on amount input
         * Call this when adding new expense rows
         */
        function setupGSTCalculation(amountInput, gstInput, netInput) {
            amountInput.addEventListener('input', function() {
                const amount = parseFloat(this.value) || 0;
                const result = calculateGST(amount);

                gstInput.value = result.gst;
                netInput.value = result.net;
            });

            // Also validate when GST or Net are manually changed
            gstInput.addEventListener('input', validateGSTInputs);
            netInput.addEventListener('input', validateGSTInputs);

            function validateGSTInputs() {
                const amount = parseFloat(amountInput.value) || 0;
                const gst = parseFloat(gstInput.value) || 0;
                const net = parseFloat(netInput.value) || 0;

                if (!validateGSTFields(amount, gst, net)) {
                    // Add visual warning
                    amountInput.classList.add('border-red-500');
                    gstInput.classList.add('border-red-500');
                    netInput.classList.add('border-red-500');
                } else {
                    // Remove warning
                    amountInput.classList.remove('border-red-500');
                    gstInput.classList.remove('border-red-500');
                    netInput.classList.remove('border-red-500');
                }
            }
        }

        // ==========================================
        // INITIALIZATION
        // ==========================================

        document.addEventListener('DOMContentLoaded', function() {
            // Setup existing GST fields on page load
            document.querySelectorAll('[data-gst-group]').forEach(group => {
                const amountInput = group.querySelector('[data-gst-amount]');
                const gstInput = group.querySelector('[data-gst-gst]');
                const netInput = group.querySelector('[data-gst-net]');

                if (amountInput && gstInput && netInput) {
                    setupGSTCalculation(amountInput, gstInput, netInput);
                }
            });
        });
    </script>
</x-app-layout>
