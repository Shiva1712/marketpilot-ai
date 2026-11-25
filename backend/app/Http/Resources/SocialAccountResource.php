<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_id' => $this->business_id,
            'provider' => $this->provider,
            'provider_account_id' => $this->provider_account_id,
            'account_name' => $this->account_name,
            'username' => $this->username,
            'email' => $this->email,
            'avatar_url' => $this->avatar_url,
            'scopes' => $this->scopes,
            'expires_at' => $this->expires_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
