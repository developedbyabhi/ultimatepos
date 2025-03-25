<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SessionController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        // Apply middleware to ensure only admin/superadmin can access
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('superadmin') && !auth()->user()->can('admin')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of active user sessions
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Query the sessions table and join with users table
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.id',
                'sessions.user_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'sessions.last_logout',
                'sessions.payload',
                'users.username',
                'users.email',
                'users.first_name',
                'users.last_name'
            )
            ->whereNotNull('sessions.user_id')
            ->get();
        // Format the last_activity timestamp to be more readable
        $sessions = $sessions->map(function ($session) {
            // Convert last_activity from Unix timestamp to Carbon instance
            $session->last_activity = Carbon::createFromTimestamp($session->last_activity)
                ->diffForHumans();
                
            // Format last_logout timestamp if it exists
            if (!is_null($session->last_logout)) {
                $session->last_logout = Carbon::createFromTimestamp($session->last_logout)
                    ->diffForHumans();
            } else {
                $session->last_logout = 'Still active';
            }
            
            // Check if this session belongs to the current user
            $session->is_current_user = (int)$session->user_id === auth()->id();
            
            // Extract additional information from user agent
            $session->browser = $this->getBrowserInfo($session->user_agent);
            
            return $session;
        });

        // Return view with sessions data
        return view('sessions.index', compact('sessions'));
    }

    /**
     * Remove the specified session from storage (force logout)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $sessionId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $sessionId)
    {
        // Get session details before deletion
        $session = DB::table('sessions')
            ->where('id', $sessionId)
            ->first();

        if (!$session) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'msg' => 'Session not found.']);
            } else {
                return redirect()
                    ->route('sessions.index')
                    ->with('error', 'Session not found.');
            }
        }

        // Check if the session belongs to the current user
        $isCurrentUserSession = (int)$session->user_id === auth()->id();

        // Update the last_logout timestamp for historical purposes
        DB::table('sessions')
            ->where('id', $sessionId)
            ->update([
                'last_logout' => Carbon::now()->timestamp
            ]);

        // Delete the session record to force logout
        DB::table('sessions')
            ->where('id', $sessionId)
            ->delete();

        // If the session belongs to the current user, log them out
        if ($isCurrentUserSession) {
            Auth::logout();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'msg' => 'You have been logged out.']);
            } else {
                return redirect()
                    ->route('login')
                    ->with('success', 'You have been logged out.');
            }
        }

        // Return appropriate response based on request type
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'msg' => 'User has been logged out successfully.']);
        } else {
            return redirect()
                ->route('sessions.index')
                ->with('success', 'User has been logged out successfully.');
        }
    }

    /**
     * Extract browser information from user agent string
     *
     * @param  string  $userAgent
     * @return string
     */
    private function getBrowserInfo($userAgent)
    {
        // Simple browser detection
        $browser = 'Unknown';
        
        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            $browser = 'Internet Explorer';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            $browser = 'Opera';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        }
        
        // Extract device type (mobile/desktop)
        $device = (strpos($userAgent, 'Mobile') !== false) ? 'Mobile' : 'Desktop';
        
        return "$browser ($device)";
    }
}
