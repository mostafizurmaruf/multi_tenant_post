<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role, ['owner', 'staff'])
            && $user->tenant_id === app('tenant')->id;
    }

    public function cancel(User $user, Order $order): bool
    {
        return $user->role === 'owner'
            && $order->tenant_id === app('tenant')->id
            && $user->tenant_id === app('tenant')->id;
    }

    public function viewReports(User $user): bool
    {
        return $user->role === 'owner'
            && $user->tenant_id === app('tenant')->id;
    }
}
