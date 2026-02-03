<x-app-layout>
    <x-slot name="header">
        <x-page-header  :title="__('Map CSV Columns')" :backRoute="Route('working-papers.import')"/>
    </x-slot>

    @if($errors->any())
        <div class="max-w-3xl mx-auto mb-6 mt-6">
            <div class="bg-red-50 border-l-4 border-red-400 p-4 shadow-sm rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-red-800">
                            Import Failed
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ str_replace('_', ' ', $errors->first('import_error') ?? 'Please check the file and try again.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="p-6">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">
                            Column Mapping
                        </h2>
                        <p class="text-sm text-gray-600">
                            Map each CSV column to the corresponding database field. Columns can be ignored by selecting "-- Ignore --".
                        </p>
                    </div>

                    <form method="post" action="{{ route('working-paper.import.execute') }}">
                        @csrf

                        <input type="hidden" name="path" value="{{ $path }}">

                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-200 rounded-lg">
                                <thead>
                                    <tr class="bg-gray-100 border-b border-gray-200">
                                        <th class="p-4 text-left text-sm font-semibold text-gray-700">
                                            CSV Column
                                        </th>
                                        <th class="p-4 text-left text-sm font-semibold text-gray-700">
                                            Map to Database Field
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach($headers as $index => $header)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-4">
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center justify-center w-6 h-6 mr-3 text-xs font-medium text-gray-600 bg-gray-200 rounded-full">
                                                        {{ $index + 1 }}
                                                    </span>
                                                    <span class="font-medium text-gray-800">
                                                        {{ $header }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                <select name="mapping[{{ $index }}]"
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="">-- Ignore --</option>

                                                    <optgroup label="Working Paper">
                                                        @foreach($workingPaperFields as $field)
                                                            <option value="working_paper.{{ $field }}"
                                                                {{ strtolower(str_replace([' ', '_', '-'], '', $header)) === strtolower(str_replace([' ', '_', '-'], '', $field)) ? 'selected' : '' }}>
                                                                {{ ucwords(str_replace('_', ' ', $field)) }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>

                                                    <optgroup label="Client">
                                                        @foreach($clientFields as $field)
                                                            <option value="client.{{ $field }}"
                                                                {{ strtolower(str_replace([' ', '_', '-'], '', $header)) === strtolower(str_replace([' ', '_', '-'], '', $field)) ? 'selected' : '' }}>
                                                                {{ ucwords(str_replace('_', ' ', $field)) }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>

                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">{{ count($headers) }}</span> columns found in CSV
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('working-papers.import') }}"
                                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </a>

                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Import Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-amber-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-amber-800 mb-1">Important Notes</h3>
                        <ul class="text-sm text-amber-700 space-y-1">
                            <li>• Ensure required fields are properly mapped</li>
                            <li>• Duplicate mappings will use the last selected field</li>
                            <li>• Data will be validated before import</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
