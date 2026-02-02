<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Working Papers
            </h1>

            @if(auth()->check() && auth()->user()->getRoleRank() >= 2)
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
            @endif

        </div>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">
        {{-- Bulk Actions Bar --}}
        <div id="bulkActionsBar" class="hidden mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span id="selectedCount" class="text-sm font-medium text-blue-900">0 selected</span>
                    <button type="button" onclick="clearSelection()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Clear selection
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export Selected
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Selected
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Job Reference
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($workingPapers as $wp)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-3">
                                <input type="checkbox" class="wp-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="{{ $wp->id }}" onchange="updateBulkActions()">
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                <div>{{ $wp->job_reference }}</div>
                                <div class="text-gray-600 font-normal">{{ $wp->client_name }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $wp->status === 'finalised' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $wp->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $wp->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                ">
                                    {{ ucfirst($wp->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('working-papers.show', $wp) }}"
                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>

                                    @can('delete', $wp)
                                        @if($wp->status !== 'finalised')
                                            <form method="POST" action="{{ route('working-papers.destroy', $wp) }}" class="inline-flex" onsubmit="return confirm('This action cannot be undone. Delete this working paper?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-800 font-medium transition-colors duration-150">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm">No working papers found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($workingPapers->hasPages())
            <div class="mt-4">
                {{ $workingPapers->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.wp-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateBulkActions();
        }

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.wp-checkbox:checked');
            const bulkActionsBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            const count = checkboxes.length;

            if (count > 0) {
                bulkActionsBar.classList.remove('hidden');
                selectedCount.textContent = `${count} selected`;
            } else {
                bulkActionsBar.classList.add('hidden');
            }

            // Update "select all" checkbox state
            const allCheckboxes = document.querySelectorAll('.wp-checkbox');
            const selectAll = document.getElementById('selectAll');
            selectAll.checked = allCheckboxes.length === count && count > 0;
        }

        function clearSelection() {
            document.getElementById('selectAll').checked = false;
            document.querySelectorAll('.wp-checkbox').forEach(cb => cb.checked = false);
            updateBulkActions();
        }
    </script>
</x-app-layout>
