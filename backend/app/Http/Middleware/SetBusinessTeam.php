<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SetBusinessTeam
{
    public function handle(Request $request, Closure $next): Response
    {
        $business = $request->route('business');

        if (!$business) {
            abort(400, 'Business context is required.');
        }

        $user = $request->user();

        // Verify business membership before setting context.
        $isMember = $business->members()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isMember && !$user->hasRole('super-admin')) {
            abort(403, 'You do not belong to this business.');
        }

        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($business->id);

        $user->unsetRelation('roles');
        $user->unsetRelation('permissions');

        try {
            return $next($request);
        } finally {
            app(PermissionRegistrar::class)
                ->setPermissionsTeamId(null);
        }
    }
}