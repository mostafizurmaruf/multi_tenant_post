<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['owner', 'staff'])
            && $user->tenant_id === app('tenant')->id;
    }


    public function create(User $user): bool
    {
        return $user->role === 'owner'
            && $user->tenant_id === app('tenant')->id;
    }


    public function update(User $user, Product $product): bool
    {
        return $user->role === 'owner'
            && $product->tenant_id === app('tenant')->id
            && $user->tenant_id === app('tenant')->id;
    }


    public function delete(User $user, Product $product): bool
    {
        return $user->role === 'owner'
            && $product->tenant_id === app('tenant')->id
            && $user->tenant_id === app('tenant')->id;
    }
}
