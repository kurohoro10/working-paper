<?php
/**
 * AuditLogController
 *
 * This controller handles the working paper logs.
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

use App\Models\AuditLog;
use Illuminate\View\View;

/**
 * Class AuditLogController
 *
 * Handles the retrieval and display of system-wide audit trails
 * to track user activities and model changes.
 */
class AuditLogController extends Controller
{
    /**
     * Display a paginated listing of audit logs.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(25);

        return view('admin.audit-logs.index', compact('logs'));
    }
}
