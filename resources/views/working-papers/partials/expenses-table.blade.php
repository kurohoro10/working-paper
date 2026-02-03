<!-- Expenses  -->
<div class="px-6 pb-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">
        {{ __('Expenses') }}
    </h2>

    @if($workingPaper->expenses->count())
        <div class="overflow-x-auto">
            <div class="overflow-hidden rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ __('Description') }}
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ __('Amount') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ __('Client Comment') }}
                            </th>

                            @if(auth()->check() && auth()->user()->getRoleRank() >= 2)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    {{ __('Internal Comment') }}
                                </th>
                            @endif

                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ __('Receipt') }}
                            </th>
                            @if(auth()->check() && auth()->user()->getRoleRank() >= 2)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    {{ __('Action') }}
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($workingPaper->expenses as $expense)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $expense->description }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-right font-medium">
                                    {{ number_format($expense->amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $expense->client_comment }}
                                </td>
                                @if(auth()->check() && auth()->user()->getRoleRank() >= 2)
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $expense->internal_comment }}
                                    </td>
                                @endif
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center justify-center">
                                        @if($expense->receipt_path)
                                            <a href="{{ route('expenses.receipt', ['expense' => $expense, 'token' => $workingPaper->share_token]) }}"
                                                target="_blank"
                                                class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </div>
                                </td>

                                @auth
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center gap-3">
                                            @can('update', $expense)
                                                <a href="{{ route('working-papers.show', [$workingPaper, 'expense' => $expense->id]) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $expense)
                                                <form action="{{ route('expenses.destroy', $expense) }}" method="post" class="inline-flex" onsubmit="return confirm('Delete this expense?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-800 font-medium transition-colors duration-150">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                @endauth

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="text-sm text-gray-500">
            {{ __('No expenses added yet.') }}
        </p>
    @endif
</div>
