<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center justify-between max-w-7xl mx-auto">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Working Papers
        </h1>

        <a href="{{ route('working-papers.create') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create New
        </a>
    </div>
</x-slot>


    <div class="p-6">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Job Ref</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($workingPapers as $wp)
                    <tr class="border-t">
                        <td class="p-3" style="text-align: center">
                            <a href="{{ route('working-papers.show', $wp) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $wp->job_reference }} - {{ $wp->client_name }}
                            </a>
                        </td>

                        <td class="text-center p-3" style="text-align: center">
                            <span class="text-sm">
                                {{ ucfirst($wp->status) }}
                            </span>
                        </td>

                        <td class="text-center p-3" style="text-align: center">
                            <a href="{{ route('working-papers.show', $wp) }}"
                            class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-900">
                                View Details
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $workingPapers->links()}}
        </div>
    </div>
</x-app-layout>
