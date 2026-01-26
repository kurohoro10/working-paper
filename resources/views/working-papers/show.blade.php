<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Working Paper Details') }}
            </h2>
            {{-- Hide back button for public link users --}}
            @auth
                <a href="{{ route('working-papers.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    &larr; Back to List
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Session Messages --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 p-4 shadow-sm rounded" role="alert">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm rounded">
                    <ul>
                        @foreach ($errors->all as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Share link section: Only show to logged in owners --}}
            @auth
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-blue-700 font-bold">Public Access Link</p>
                            <p class="text-xs text-blue-600">Share this URL to clients to allow them to add expenses without logging in.</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="text" name="shareUrl" id="shareUrl" readonly
                                value="{{ route('client.working-paper.show', $workingPaper->share_token) }}"
                                class="text-xs border-gray-300 rounded bg-white w-64">

                            <button onclick="copyLink()"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            @endauth

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
                                            <th class="px-4 py-2  text-left">{{ __('Client Comment') }}</th>
                                            @auth
                                                <th class="px-4 py-2  text-left">{{ __('Internal Comment') }}</th>
                                            @endauth
                                            <th class="px-4 py-2  text-left">{{ __('Receipt') }}</th>
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
                                                @auth
                                                    <td class="px-4 py-2">
                                                        {{ $expense->internal_comment}}
                                                    </td>
                                                @endauth
                                                <td class="px-4 py-2 text-center">
                                                    @if($expense->receipt_path)
                                                        <a href="{{ route('expenses.receipt', ['expense' => $expense, 'token' => $workingPaper->share_token]) }}"
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

                    {{-- Add expense form: restricted by status --}}
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
                                    <input type="text" name="description" class="rounded-md border-gray-300" placeholder="Description" required >

                                    <input type="number" name="amount" step="0.01" class="rounded-md border-gray-300" placeholder="Amount" required >
                                </div>

                                <textarea name="client_comment" id="client_comment"
                                    class="w-full rounded-md border-gray-300"
                                    placeholder="Client comment"></textarea>

                                @auth
                                    @can('addInternalComment', App\Models\Expense::class)
                                        <textarea name="internal_comment" id="internal_comment"
                                        class="w-full rounded-md border-gray-300"
                                        placeholder="Internal comment"></textarea>
                                    @endcan
                                @endauth

                                <input type="file" name="receipt" id="receipt">

                                <div class="flex gap-4">
                                    <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Add Expense
                                    </button>
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
