<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Working Paper
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('working-papers.update', $workingPaper) }}" method="post" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="client_name" class="block text-sm font-medium text-gray-700">
                        Client Name
                    </label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $workingPaper->client_name) }}" class="mt-1 w-full rounded-md border-gray-300" required>
                </div>

                <div>
                    <label for="service" class="block text-sm font-medium text-gray-700">
                        Service Type
                    </label>
                    <input type="text" name="service" id="service" value="{{ old('service', $workingPaper->service) }}" class="mt-1 w-full rounded-md border-gray-300" required>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('working-papers.show', $workingPaper) }}" class="px-4 py-2 bg-gray-100 rounded-md">
                        Cancel
                    </a>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
