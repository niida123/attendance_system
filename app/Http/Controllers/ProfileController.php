<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\Employee;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the profile edit page.
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Return current user's profile data as JSON.
     */
    public function show()
    {
        $user = Auth::user()->load(['roles', 'employee.department', 'employee.position']);

        $emp = $user->employee;

        // Build avatar URL
        
        $avatarUrl = $user->avatar 
            ? asset('storage/' . $user->avatar) 
            : null;


        // Initials
        $initials = $emp
            ? strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1))
            : strtoupper(substr($user->username, 0, 2));

        // Activity log (stored in user's activity_log JSON column, or from cache/session)
        $activity = $this->getActivity($user);

        return response()->json([
            'success' => true,
            'user' => [
                'id'         => $user->id,
                'username'   => $user->username,
                'email'      => $user->email,
                'role'       => optional($user->roles->first())->name,
                'status'     => $user->status,
                'avatar_url' => $avatarUrl,
                'initials'   => $initials,
                'created_at' => $user->created_at,
            ],
            'employee' => $emp,
            'activity' => $activity,
        ]);
    }

    /**
     * Update username and email.
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        $this->logActivity($user, 'profile_update', 'Updated profile information');

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password'          => 'required',
            'new_password'              => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        $this->logActivity($user, 'password_change', 'Password changed');

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Update avatar photo.
     */
    public function updateAvatar(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('avatar'),
            ]);
        }

        $user = Auth::user();

        // ✅ Delete old user avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // ✅ Delete old employee photo (if exists)
        if ($user->employee && $user->employee->photo &&
            Storage::disk('public')->exists($user->employee->photo)) {
            Storage::disk('public')->delete($user->employee->photo);
        }

        // ✅ Store new image (ONLY once)
        $path = $request->file('avatar')->store('avatars', 'public');

        // ✅ Update user avatar
        $user->update(['avatar' => $path]);

        // ✅ Update employee photo if exists
        if ($user->employee) {
            $user->employee->update(['photo' => $path]);
        }

        $this->logActivity($user, 'avatar_change', 'Profile photo updated');

        return response()->json([
            'success'    => true,
            'message'    => 'Avatar updated successfully.',
            'avatar_url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Store an activity entry in the user's activity_log JSON column.
     * Falls back gracefully if the column doesn't exist.
     */
    private function logActivity($user, string $type, string $description): void
    {
        try {
            $log = $user->activity_log ?? [];

            array_unshift($log, [
                'type'        => $type,
                'label'       => ucwords(str_replace('_', ' ', $type)),
                'description' => $description,
                'time'        => now()->timezone('Asia/Phnom_Penh')->format('d M Y, H:i'),
            ]);

            // Keep only the last 10 entries
            $user->update(['activity_log' => array_slice($log, 0, 10)]);
        } catch (\Throwable $e) {
            // Column may not exist yet — fail silently
        }
    }

    /**
     * Get activity log for display, with sensible fallback.
     */
    private function getActivity($user): array
    {
        try {
            $log = $user->activity_log ?? [];

            // Always prepend account creation as the baseline entry
            $log[] = [
                'type'        => 'login',
                'label'       => 'Account Created',
                'description' => 'Your account was created',
                'time'        => optional($user->created_at)
                                    ->timezone('Asia/Phnom_Penh')
                                    ->format('d M Y, H:i') ?? '—',
            ];

            return array_slice($log, 0, 10);
        } catch (\Throwable $e) {
            return [];
        }
    }
}