@props(['title', 'backRoute' => null])

<div class="flex items-center justify-between max-w-7xl mx-auto">
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $title }}
    </h1>

    <div class="flex gap-3 items-center">
        {{-- To inject specific button/actions --}}
        {{ $slot }}

        @if($backRoute)
            <a href="{{ $backRoute }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back
            </a>
        @endif
    </div>
</div>
