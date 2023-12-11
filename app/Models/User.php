<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function pushNotifications()
    {
        return $this->morphMany(PushNotification::class, 'subscribable');
    }
    

    /**
    * Determine if the model owns the given subscription.
    *
    * @param  \NotificationChannels\WebPush\PushSubscription  $subscription
    * @return bool
    */
    public function ownsPushNotification($subscription)
    {
            return (string) $subscription->subscribable_id === (string) $this->getKey() &&
                        $subscription->subscribable_type === $this->getMorphClass();
    }

    /**
    * Delete subscription by endpoint.
    *
    * @param  string  $endpoint
    * @return void
    */
    public function deletePushNotification($endpoint)
    {
        $this->pushNotifications()
                ->where('endpoint', $endpoint)
                ->delete();
    }

    /**
    * Get all of the subscriptions.
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function routeNotificationForWebPush()
    {
        return $this->pushNotifications;
    }
    
}
