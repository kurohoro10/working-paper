<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Client Details') }}
            </h2>
            <div class="flex gap-3 p-3 justify-center items-center">
                @can('update', $client)
                    <a href="{{ route('admin.clients.edit', $client) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endcan

                @can('delete', $client)
                    <form method="POST"
                        action="{{ route('admin.clients.destroy', $client) }}"
                        onsubmit="return confirm('This action cannot be undone. Delete this client?');">
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
                @endcan

                <a href="{{ route('admin.clients.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">

                    {{-- Client Header --}}
                    <div class="flex justify-between items-start border-b p-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h3>
                            @if($client->company)
                                <p class="text-gray-500">{{ $client->company }}</p>
                            @endif
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            Active Client
                        </span>
                    </div>

                    {{-- Contact Information --}}
                    <div class="pt-6 px-6 mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ __('Contact Information') }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Email</label>
                                @if($client->email)
                                    <p class="mt-1 text-lg text-gray-900">
                                        <a href="mailto:{{ $client->email }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $client->email }}
                                        </a>
                                    </p>
                                @else
                                    <p class="mt-1 text-lg text-gray-400">Not provided</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</label>
                                @if($client->phone)
                                    <p class="mt-1 text-lg text-gray-900">
                                        <a href="tel:{{ $client->phone }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $client->phone }}
                                        </a>
                                    </p>
                                @else
                                    <p class="mt-1 text-lg text-gray-400">Not provided</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Company</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $client->company ?? 'Not provided' }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Tax Number</label>
                                <p class="mt-1 text-lg text-gray-900 font-mono">{{ $client->tax_number ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Address Information --}}
                    @if($client->address)
                        <div class="px-6 pb-8 border-t pt-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                                {{ __('Address') }}
                            </h2>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-line">{{ $client->address }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Notes --}}
                    @if($client->notes)
                        <div class="px-6 pb-8 border-t pt-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                                {{ __('Notes') }}
                            </h2>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-line">{{ $client->notes }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Working Papers Section --}}
                    <div class="px-6 pb-8 border-t pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                {{ __('Working Papers') }}
                            </h2>
                            @can('create', App\Models\WorkingPaper::class)
                                <a href="{{ route('working-papers.create', ['client_id' => $client->id]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    New Working Paper
                                </a>
                            @endcan
                        </div>

                        @if($client->workingPapers && $client->workingPapers->count())
                            <div class="overflow-x-auto">
                                <div class="overflow-hidden rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Job Reference') }}
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Service') }}
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Status') }}
                                                </th>
                                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Expenses') }}
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Created') }}
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    {{ __('Action') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($client->workingPapers as $workingPaper)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                        {{ $workingPaper->job_reference }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900">
                                                        {{ $workingPaper->service }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                            {{ $workingPaper->status === 'finalised' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ ucfirst($workingPaper->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">
                                                        {{ $workingPaper->expenses->count() }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        {{ $workingPaper->created_at->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <a href="{{ route('working-papers.show', $workingPaper) }}"
                                                            class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ __('No working papers found for this client.') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Footer Actions --}}
                    <div class="bg-gray-50 -m-8 mt-8 p-6 flex gap-4">
                        <a href="{{ route('admin.clients.edit', $client) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Client
                        </a>

                        @can('create', App\Models\WorkingPaper::class)
                            <a href="{{ route('working-papers.create', ['client_id' => $client->id]) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create Working Paper
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
