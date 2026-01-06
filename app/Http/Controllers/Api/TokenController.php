<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Tenant,User};
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function token()
    {

        $tenant = Tenant::create([
                'uuid' => Str::uuid(),
                'name' => 'Demo Tenant',
            ]);

        $user = User::create([
                'name' => 'Owner User',
                'email' => 'owner@example.com',
                'password' => bcrypt('password123'),
                'role' => 'owner',
                'tenant_id' => $tenant->id
            ]);

        $token = $user->createToken('API Token')->plainTextToken;
        
        return response()->json([
            'token' => $token,
            'tenant_uuid' => $tenant->uuid,
            'user_id' => $user->id,
        ]);
    }
}
