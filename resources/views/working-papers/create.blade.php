<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Working Paper') }}
            </h2>

            <a href="{{ route('working-papers.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('working-papers.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Client Name') }} <span class="text-red-500">*</span>
                        </label>

                        <input list="clients-list" name="client_name" id="client_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Type to search or add new..." value="{{ old('client_name') }}">

                        <datalist id="clients-list">
                            @foreach ($clients as $client)
                                <option value="{{ $client->name }}" data-email="{{ $client->email}}" data-id="{{ $client->id }}">
                            @endforeach
                        </datalist>

                        <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id') }}">

                        @error('client_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Email Address') }}
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="e.g. client@example.com"
                            value="{{ old('email') }}"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="service" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Service Type') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="service" id="service"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="e.g. Tax Audit" value="{{ old('service') }}" required>
                        @error('service')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Job Reference and Period side by side --}}
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="job_reference" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Job Reference') }}
                            </label>
                            <input type="text" name="job_reference" id="job_reference"
                                class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-500 cursor-not-allowed"
                                value="Auto-generated" disabled>
                            <p class="mt-1 text-xs text-gray-500">This reference is auto-generated.</p>
                        </div>

                        <div>
                            <label for="period" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Accounting Period') }} ({{ __('Year') }})
                            </label>
                            <select name="period" id="period"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Choose Year --</option>
                                @foreach(range(date('Y'), 1990) as $year)
                                    <option value="{{ $year }}" {{ old('period') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('period')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-6 border-t gap-4">
                        <a href="{{ route('working-papers.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </a>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Working Paper
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.getElementById('client_name').addEventListener('input', function (e) {
        const input = e.target;
        const list = document.getElementById('clients-list');
        const selectedOption = Array.from(list.options).find(option => option.value === input.value);

        const emailField = document.getElementById('email');
        const idField = document.getElementById('client_id');

        if (selectedOption) {
            // Auto-fill existing client data
            emailField.value = selectedOption.getAttribute('data-email') || '';
            idField.value = selectedOption.getAttribute('data-id') || '';
        } else {
            // It's a new client name being typed
            idField.value = '';
        }
    });
</script>
