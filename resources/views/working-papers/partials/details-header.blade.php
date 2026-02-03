<div class="flex justify-between items-start border-b p-6">
    <div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $workingPaper->job_reference }}</h3>
        <p class="text-gray-500">{{ $workingPaper->client->name }}</p>
    </div>
    <span class="px-3 py-1 rounded-full text-sm font-semibold
        {{ $workingPaper->status === 'finalised' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
        {{ ucfirst($workingPaper->status) }}
    </span>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 px-6 mb-8">
    <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</label>
        <p class="mt-1 text-lg text-gray-900">{{ $workingPaper->service }}</p>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Code</label>
        <p class="mt-1 text-lg text-gray-900 font-mono">{{ $workingPaper->job_reference }}</p>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Accounting Period</label>
        <p class="mt-1 text-lg text-gray-900">{{ $workingPaper->period ?? 'Not specified' }}</p>
    </div>
</div>
