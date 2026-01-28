<?php
/**
 * TokenController
 *
 * This controller handles security and lifecycle management for access tokens
 * associated with working papers.
 *
 * @category  Controllers
 * @package   App\Http\Controllers
 * @author    Name <email@email.com>
 * @copyright 2026 Name
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   GIT: 1.2.0
 * @link      http://url.com
 */

namespace App\Http\Controllers;

use App\Models\WorkingPaper;
use Illuminate\Http\RedirectResponse;

/**
 * Class TokenController
 *
 * Manages the generation and invalidation of unique access tokens.
 * Provides internal users with the ability to reset client access
 * windows when links expire or security is compromised.
 */
class TokenController extends Controller
{
    public function regenerateShareToken(WorkingPaper $workingPaper): RedirectResponse
    {
        $this->authorize('update', $workingPaper);

        $workingPaper->refreshShareToken();

        // Log token creation
        $workingPaper->auditLogs()->create([
            'action'  => 'share_token_regenerated',
            'user_id' => auth()->id(),
            'meta'    => [
                'expires_at' => $workingPaper->share_token_expires_at,
            ]
        ]);

        return back()->with('success', 'Share link regenerated. The old link is now invalid');
    }
}
