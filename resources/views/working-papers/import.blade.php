<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Import Working Papers
            </h1>

            <a href="{{ route('working-papers.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Upload CSV File
                    </h2>

                    <p class="text-sm text-gray-600 mb-6">
                        Select a CSV file to import working papers. After upload, you'll be able to map the columns to the appropriate fields.
                    </p>

                    <form method="POST" action="{{ route('working-paper.import.preview.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label for="csv" class="block text-sm font-medium text-gray-700 mb-2">
                                CSV File
                            </label>

                            <div class="flex items-center justify-center w-full">
                                <label id="dropzone-label" for="csv"
                                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors
                                    {{ $errors->has('csv') ? 'border-red-500' : 'border-gray-300' }}">

                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg id="upload-icon" class="w-12 h-12 mb-4 {{ $errors->has('csv') ? 'text-red-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">CSV files only</p>
                                    </div>
                                    <input type="file" name="csv" id="csv" accept=".csv" class="hidden">
                                </label>
                            </div>

                            @error('csv')
                                <p id="error-text" class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror

                            <p class="mt-2 text-xs text-gray-500" id="file-name"></p>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('working-papers.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload & Map
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-800 mb-1">CSV Format Requirements</h3>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• First row should contain column headers</li>
                            <li>• Ensure all required fields are present</li>
                            <li>• Use UTF-8 encoding for special characters</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('csv').addEventListener('change', function(e) {
            const file            = e.target.files[0];
            const fileNameDisplay = document.getElementById('file-name');
            const label           = document.getElementById('dropzone-label');
            const icon            = document.getElementById('upload-icon');
            const errorText       = document.getElementById('error-text');

            if (file) {
                // 1. Update text display
                fileNameDisplay.textContent = `Selected: ${file.name}`;
                fileNameDisplay.classList.add('text-green-600', 'font-medium');

                // 2. Remove Error Styles (Red)
                label.classList.remove('border-red-500', 'border-gray-300');
                icon.classList.remove('text-red-400', 'text-gray-400');

                if (errorText) {
                    errorText.classList.add('hidden');
                }

                // 3. Add Success Styles (Green)
                label.classList.add('border-green-500', 'bg-green-50');
                icon.classList.add('text-green-500');
            }
        });
    </script>
</x-app-layout>
