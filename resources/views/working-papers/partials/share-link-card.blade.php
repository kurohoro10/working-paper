<div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded shadow-sm">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm text-blue-700 font-bold">Public Access Link</p>
            <p class="text-xs text-blue-600">Share this URL to clients to allow them to add expenses without logging in.</p>

            <div class="mt-2">
                @if($workingPaper->shareTokenIsExpired())
                    <span class="inline-flex items-center text-xs font-medium text-red-600">
                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        Link expired {{ $workingPaper->share_token_expires_at->diffForHumans() }}
                    </span>
                @else
                    <span class="inline-flex items-center text-xs font-medium text-blue-600">
                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        Expires {{ $workingPaper->share_token_expires_at->diffForHumans() }}
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($workingPaper->shareTokenIsExpired())
                <input type="text" name="shareUrl" id="shareUrl" disabled
                    placeholder="This URL has expired"
                    class="text-xs border-gray-300 rounded bg-gray-100 text-gray-500 w-64 cursor-not-allowed opacity-75">

                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    Expired
                </span>
            @else
                <input type="text" name="shareUrl" id="shareUrl" readonly
                    value="{{ route('client.working-paper.show', $workingPaper->share_token) }}"
                    class="text-xs border-gray-300 rounded bg-gray-50 text-gray-700 w-64 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-md">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Active
                </span>

                <button type="button" onclick="copyLink()"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Copy
                </button>
            @endif

            <form method="POST"
                action="{{ route('working-papers.share-token.regenerate', $workingPaper) }}"
                onsubmit="return confirm('Regenerate the share link? The old one will stop working.')">
                @csrf

                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Regenerate
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    async function copyLink() {
        const copyText = document.getElementById("shareUrl");
        if (!copyText) return;

        try {
            await navigator.clipboard.writeText(copyText.value);
            showToast('Link copied to clipboard!');
        } catch (err) {
            copyText.select();
            document.execCommand('copy');
            showToast('Link copied to clipboard!');
        }
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 z-50 transition-opacity duration-300';
        toast.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
