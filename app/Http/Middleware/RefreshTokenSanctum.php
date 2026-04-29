<?php

namespace App\Http\Middleware;

use App\Models\ProfileAbility;
use Closure;
use Laravel\Sanctum\PersonalAccessToken;

class RefreshTokenSanctum
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $token = substr($request->headers->get('authorization'), 7);
        $personalAccessToken = (new PersonalAccessToken)->findToken($token);

        if ($personalAccessToken && $this->tokenIsStillValid($personalAccessToken)) {
            $this->updateTokenAbilities($personalAccessToken);
        }

        if ($personalAccessToken) {
            $response->headers->set('Authorization', $token);
        }

        return $response;
    }

    private function tokenIsStillValid($token): bool
    {
        return ! $token->updated_at->gt(now()->subMinutes(5));
    }

    private function updateTokenAbilities($token)
    {
        $abilities = $this->getUserAbilities(auth()->user()->profile->id);
        $token->abilities = $abilities;
        $token->save();
    }

    /**
     * Retorna abilities.slug de abilities com base no id passado como parâmetro.
     */
    public function getUserAbilities(int $id)
    {
        return ProfileAbility::select('abilities.slug as abilities')
            ->join('abilities', 'abilities.id', '=', 'profile_abilities.ability_id')
            ->where('profile_abilities.profile_id', '=', $id)
            ->pluck('abilities')
            ->toArray();
    }
}
