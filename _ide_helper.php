<?php

/**
 * IDE Helper for Laravel & Intelephense
 * Provides accurate type hinting for Auth::user() and Authenticatable model
 */

namespace Illuminate\Support\Facades {
    /**
     * @method static \App\Models\User|null user()
     * @method static int|string|null id()
     * @method static bool check()
     * @method static bool guest()
     */
    class Auth {}
}

namespace Illuminate\Contracts\Auth {
    /**
     * @property int $id
     * @property string $name
     * @property string $email
     * @property string $phone
     * @property string $role
     * @property string|null $avatar
     * @property bool $is_active
     * @property-read \App\Models\Store|null $store
     * @property-read \App\Models\Rider|null $rider
     * @method bool isAdmin()
     * @method bool isStore()
     * @method bool isRider()
     * @method bool isCustomer()
     * @method bool isVipSubscriber()
     * @method \Illuminate\Database\Eloquent\Relations\HasMany orders()
     * @method \Illuminate\Database\Eloquent\Relations\HasMany reviews()
     * @method \Illuminate\Database\Eloquent\Relations\HasMany subscriptions()
     * @method \Illuminate\Database\Eloquent\Relations\HasOne activeSubscription()
     */
    interface Authenticatable {}
}
