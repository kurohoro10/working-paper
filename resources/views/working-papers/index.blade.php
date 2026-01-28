<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center justify-between max-w-7xl mx-auto">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Working Papers
        </h1>

        <div class="flex items-center gap-3">
            <a href="{{ route('working-papers.export') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export
            </a>

            <a href="{{ route('working-papers.import') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Import
            </a>

            <a href="{{ route('working-papers.create') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New
            </a>
        </div>
    </div>
</x-slot>


    <div class="p-6">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 text-left">Job Ref</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($workingPapers as $wp)
                    <tr class="border-t">
                        <td class="p-3">
                            {{ $wp->job_reference }} - {{ $wp->client_name }}
                        </td>

                        <td class="p-3">
                            <span class="text-sm">
                                {{ ucfirst($wp->status) }}
                            </span>
                        </td>

                        <td class="p-3 flex gap-3">
                            <a href="{{ route('working-papers.show', $wp) }}"
                            class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-900">
                                View Details
                            </a>

                            @can('delete', $wp)
                                @if($wp->status !== 'finalised')
                                    <form method="POST"
                                        action="{{ route('working-papers.destroy', $wp) }}"
                                        onsubmit="return confirm('This action cannot be undone. Delete this working paper?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="text-sm font-semibold text-red-600 hover:text-red-800">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            @endcan
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
