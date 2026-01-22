<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Working Paper Details') }}
            </h2>
            <a href="{{ route('working-papers.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                &larr; Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">

                    <div class="flex justify-between items-start border-b p-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $workingPaper->job_reference }}</h3>
                            <p class="text-gray-500">{{ $workingPaper->client_name }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $workingPaper->status === 'finalised' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($workingPaper->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 px-6 mb-8">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $workingPaper->service }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Code</label>
                            <p class="mt-1 text-lg text-gray-900 font-mono">{{ $workingPaper->job_reference }}</p>
                        </div>
                    </div>

                    <!-- Expenses  -->
                    <div class="px-6 pb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ __('Expenses') }}
                        </h2>

                        @if($workingPaper->expenses->count())
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                                        <tr>
                                            <th class="px-4 py-2 text-left">{{ __('Description') }}</th>
                                            <th class="px-4 py-2 text-right">{{ __('Amount') }}</th>
                                            <th class="px-4 py-2">{{ __('Client Comment') }}</th>
                                            <th class="px-4 py-2">{{ __('Receipt') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($workingPaper->expenses as $expense)
                                            <tr class="border-t">
                                                <td class="px-4 py-2">{{ $expense->description }}</td>
                                                <td class="px-4 py-2 text-right">
                                                    {{ number_format($expense->amount, 2) }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    {{ $expense->client_comment}}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    @if($expense->receipt_path)
                                                        <a href="{{ asset('storage/' .  $expense->receipt_path) }}"
                                                            target="_blank"
                                                            class="text-blue-600 hover:underline"
                                                        >
                                                            View
                                                        </a>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">
                                {{ __('No expenses added yet.') }}
                            </p>
                        @endif
                    </div>

                    @if($workingPaper->status !== 'finalised')
                        <div class="px-6 pb-8 border-t">
                            <h3 class="text-md font-semibold text-gray-900 mt-6 mb-4">
                                {{ __('Add Expense') }}
                            </h3>

                            <form
                                method="POST"
                                action="{{ ROUTE('expenses.store', $workingPaper) }}"
                                enctype="multipart/form-data"
                                class="space-y-4"
                            >
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input
                                        type="text"
                                        name="description"
                                        class="rounded-md border-gray-300"
                                        placeholder="Description"
                                        required
                                    >

                                    <input
                                        type="number"
                                        name="amount"
                                        step="0.01"
                                        class="rounded-md border-gray-300"
                                        placeholder="Amount"
                                        required
                                    >
                                </div>

                                <textarea name="client_comment" id="client_comment"
                                    class="w-full rounded-md border-gray-300"
                                    placeholder="Client comment"></textarea>

                                <textarea name="internal_comment" id="internal_comment"
                                    class="w-full rounded-md border-gray-300"
                                    placeholder="Internal comment"></textarea>

                                <input type="file" name="receipt" id="receipt">

                                <button class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">
                                    Add Expense
                                </button>
                            </form>
                        </div>
                    @endif



                    <div class="bg-gray-50 -m-8 mt-8 p-6 flex gap-4">
                        <a href="{{ url('/working-papers/'.$workingPaper->id.'/pdf') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download PDF Snapshot
                        </a>

                        {{-- @if($workingPaper->status !== 'finalised')
                            <form action="{{ route('working-papers.finalise', $workingPaper) }}" method="POST">
                                @csrf
                                <button class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                    Finalise Paper
                                </button>
                            </form>
                        @endif --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
