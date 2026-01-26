<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Working Paper') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('working-papers.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="client_name" class="block text-sm font-medium text-gray-700">
                            {{ __('Client Name') }}
                        </label>
                        <input type="text" name="client_name" id="client_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="e.g. Acme Corp" value="{{ old('client_name') }}" required>
                        @error('client_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="service" class="block text-sm font-medium text-gray-700">
                            {{ __('Service Type') }}
                        </label>
                        <input type="text" name="service" id="service"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="e.g. Tax Audit" value="{{ old('service') }}" required>
                    </div>

                    <div>
                        <label for="job_reference" class="block text-sm font-medium text-gray-700">
                            {{ __('Job Reference') }}
                        </label>
                        <input type="text" name="job_reference" id="job_reference"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100"
                            value="Auto-generated" disabled>
                            <small class="text-muted">This reference is auto-generated.</small>
                    </div>

                    <div>
                        <label for="period" class="block text-sm font-medium text-gray-700">
                            {{ __('Accounting Period') }}({{ __('Year') }})
                        </label>
                        <input type="number" name="period" id="period" min="2000" max="2099"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="2026" value="{{ old('period', date('Y')) }}" required>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-6 gap-4">
                        <a href="{{ route('working-papers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Working Paper
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
