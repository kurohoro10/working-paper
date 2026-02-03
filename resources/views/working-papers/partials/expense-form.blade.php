{{-- Add expense form: restricted by status --}}
@if($workingPaper->status !== 'finalised')
    <div class="px-6 pb-8 border-t">
        <h3 class="text-md font-semibold text-gray-900 mt-6 mb-4">
            {{ $editingExpense ? __('Edit Expense') : __('Add Expense') }}
        </h3>

        <form
            method="POST"
            action="{{ $editingExpense
                ? route('expenses.update', $editingExpense)
                : route('expenses.store', $workingPaper)
            }}"
            enctype="multipart/form-data"
            class="space-y-4"
        >
            @csrf

            @if($editingExpense)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text"
                        name="description"
                        class="rounded-md border-gray-300"
                        placeholder="Description"
                        required
                        value="{{ old('description', $editingExpense->description ?? '') }}"
                >

                <input type="number"
                        name="amount"
                        step="0.01"
                        class="rounded-md border-gray-300"
                        placeholder="Amount"
                        required
                        value="{{ old('amount', $editingExpense->amount ?? '') }}"
                >
            </div>

            <textarea name="client_comment" id="client_comment"
                class="w-full rounded-md border-gray-300"
                placeholder="Client comment">{{ old('client_comment', $editingExpense->client_comment ?? '') }}</textarea>

            @auth
                @can('addInternalComment', App\Models\Expense::class)
                    <textarea name="internal_comment" id="internal_comment"
                    class="w-full rounded-md border-gray-300"
                    placeholder="Internal comment">{{ old('internal_comment', $editingExpense->internal_comment ?? '') }}</textarea>
                @endcan
            @endauth

            <input type="file" name="receipt" id="receipt">

            <div class="flex gap-4">
                <button class="inline-flex items-center px-4 py-2
                {{ $editingExpense ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-800 hover:bg-gray-700' }}
                text-white rounded-md text-xs font-semibold uppercase tracking-widest">
                    {{ $editingExpense ? 'Update Expense' : 'Add Expense' }}
                </button>

                @if($editingExpense)
                    <a href="{{ route('working-papers.show', $workingPaper) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold uppercase tracking-widest">
                        Cancel
                    </a>
                @endif
            </div>
        </form>
    </div>
@endif
