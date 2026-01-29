<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Working Paper Details') }}
            </h2>
            {{-- Hide back button for public link users --}}
            <div class="flex gap-3 p-3 justify-center items-center">
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

                    <a href="{{ route('working-papers.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Share link section: Only show to logged in owners --}}
            @auth
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-blue-700 font-bold">Public Access Link</p>
                            <p class="text-xs text-blue-600">Share this URL to clients to allow them to add expenses without logging in.</p>

                            <div class="mt-2">
                                @if($workingPaper->shareTokenIsExpired())
                                    <span class="inline-flex items-center text-xs font-medium text-red-600">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        Link expired {{ $workingPaper->share_token_expires_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs font-medium text-blue-600">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        Expires {{ $workingPaper->share_token_expires_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($workingPaper->shareTokenIsExpired())
                                <input type="text" name="shareUrl" id="shareUrl" disabled
                                    placeholder="This URL has expired"
                                    class="text-xs border-gray-300 rounded bg-gray-100 text-gray-500 w-64 cursor-not-allowed opacity-75">

                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Expired
                                </span>
                            @else
                                <input type="text" name="shareUrl" id="shareUrl" readonly
                                    value="{{ route('client.working-paper.show', $workingPaper->share_token) }}"
                                    class="text-xs border-gray-300 rounded bg-gray-50 text-gray-700 w-64 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-md">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Active
                                </span>

                                <button type="button" onclick="copyLink()"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Copy
                                </button>
                            @endif

                            <form method="POST"
                                action="{{ route('working-papers.share-token.regenerate', $workingPaper) }}"
                                onsubmit="return confirm('Regenerate the share link? The old one will stop working.')">
                                @csrf

                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Regenerate
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">

                    <div class="flex justify-between items-start border-b p-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $workingPaper->job_reference }}</h3>
                            <p class="text-gray-500">{{ $workingPaper->client->name }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $workingPaper->status === 'finalised' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($workingPaper->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 px-6 mb-8">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $workingPaper->service }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Code</label>
                            <p class="mt-1 text-lg text-gray-900 font-mono">{{ $workingPaper->job_reference }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Accounting Period</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $workingPaper->period ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <!-- Expenses  -->
                    <div class="px-6 pb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ __('Expenses') }}
                        </h2>

                        @if($workingPaper->expenses->count())
                            <div class="overflow-x-auto">
                                <div class="overflow-hidden rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Description') }}
                                                </th>
                                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Amount') }}
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Client Comment') }}
                                                </th>
                                                @auth
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        {{ __('Internal Comment') }}
                                                    </th>
                                                @endauth
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Receipt') }}
                                                </th>
                                                @auth
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        {{ __('Action') }}
                                                    </th>
                                                @endauth
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($workingPaper->expenses as $expense)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-4 py-3 text-sm text-gray-900">
                                                        {{ $expense->description }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 text-right font-medium">
                                                        {{ number_format($expense->amount, 2) }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        {{ $expense->client_comment }}
                                                    </td>
                                                    @auth
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            {{ $expense->internal_comment }}
                                                        </td>
                                                    @endauth
                                                    <td class="px-4 py-3 text-sm">
                                                        <div class="flex items-center justify-center">
                                                            @if($expense->receipt_path)
                                                                <a href="{{ route('expenses.receipt', ['expense' => $expense, 'token' => $workingPaper->share_token]) }}"
                                                                    target="_blank"
                                                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                    </svg>
                                                                    View
                                                                </a>
                                                            @else
                                                                <span class="text-gray-400">—</span>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    @auth
                                                        <td class="px-4 py-3 text-sm">
                                                            <div class="flex items-center gap-3">
                                                                @can('update', $expense)
                                                                    <a href="{{ route('working-papers.show', [$workingPaper, 'expense' => $expense->id]) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                        Edit
                                                                    </a>
                                                                @endcan

                                                                @can('delete', $expense)
                                                                    <form action="{{ route('expenses.destroy', $expense) }}" method="post" class="inline-flex" onsubmit="return confirm('Delete this expense?');">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-800 font-medium transition-colors duration-150">
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                </svg>
                                                                            Delete
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    @endauth
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">
                                {{ __('No expenses added yet.') }}
                            </p>
                        @endif
                    </div>

                    {{-- Add expense form: restricted by status --}}
                    @if($workingPaper->status !== 'finalised')
                        <div class="px-6 pb-8 border-t">
                            <h3 class="text-md font-semibold text-gray-900 mt-6 mb-4">
                                {{ $editingExpense ? __('Edit Expense') : __('Add Expense') }}
                            </h3>

                            <form
                                method="POST"
                                action="{{ $editingExpense
                                    ? route('expenses.update', $editingExpense)
                                    : route('expenses.store', $workingPaper)
                                }}"
                                enctype="multipart/form-data"
                                class="space-y-4"
                            >
                                @csrf

                                @if($editingExpense)
                                    @method('PUT')
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text"
                                            name="description"
                                            class="rounded-md border-gray-300"
                                            placeholder="Description"
                                            required
                                            value="{{ old('description', $editingExpense->description ?? '') }}"
                                    >

                                    <input type="number"
                                            name="amount"
                                            step="0.01"
                                            class="rounded-md border-gray-300"
                                            placeholder="Amount"
                                            required
                                            value="{{ old('amount', $editingExpense->amount ?? '') }}"
                                    >
                                </div>

                                <textarea name="client_comment" id="client_comment"
                                    class="w-full rounded-md border-gray-300"
                                    placeholder="Client comment">{{ old('client_comment', $editingExpense->client_comment ?? '') }}</textarea>

                                @auth
                                    @can('addInternalComment', App\Models\Expense::class)
                                        <textarea name="internal_comment" id="internal_comment"
                                        class="w-full rounded-md border-gray-300"
                                        placeholder="Internal comment">{{ old('internal_comment', $editingExpense->internal_comment ?? '') }}</textarea>
                                    @endcan
                                @endauth

                                <input type="file" name="receipt" id="receipt">

                                <div class="flex gap-4">
                                    <button class="inline-flex items-center px-4 py-2
                                    {{ $editingExpense ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-800 hover:bg-gray-700' }}
                                    text-white rounded-md text-xs font-semibold uppercase tracking-widest">
                                        {{ $editingExpense ? 'Update Expense' : 'Add Expense' }}
                                    </button>

                                    @if($editingExpense)
                                        <a href="{{ route('working-papers.show', $workingPaper) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold uppercase tracking-widest">
                                            Cancel
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- Footer Actions --}}
                    <div class="bg-gray-50 -m-8 mt-8 p-6 flex gap-4">
                        <a href="{{ url('/working-papers/'.$workingPaper->id.'/pdf') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download PDF Snapshot
                        </a>

                        @auth
                            @if($workingPaper->status !== 'finalised')
                                <form action="{{ route('working-papers.finalise', $workingPaper) }}" method="POST">
                                    @csrf
                                    <button class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Finalise Paper
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyLink() {
            var copyText = document.getElementById("shareUrl");
            copyText.select();
            document.execCommand("copy");

            // Create toast element
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 z-50 transition-opacity duration-300';
            toast.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Link copied to clipboard!</span>
            `;

            document.body.appendChild(toast);

            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }
    </script>
</x-app-layout>
