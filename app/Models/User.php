<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Tag;

use App\Models\UserAvis;
use App\Models\Appartement;
use App\Models\Reservation;
use App\Models\Subscription;

use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use MBarlow\Megaphone\HasMegaphone;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{

   

    use HasApiTokens, HasFactory, Notifiable, Billable, HasMegaphone;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'avatar',
        'email',
        'password',
        'adresse',
        'code_postal',
        'ville',
        'iban',
        'display_city',
        'bio',
        'number',
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

    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function isAdmin() {
        return $this->roles->contains('nom', 'admin');
    }

    public function isProvider() {
        return $this->roles->contains('nom', 'provider');
    }

    public function getImageUrl() {
        if($this->provider) {
            return Storage::url($this->provider->avatar);
        }

        if($this->avatar) {
            return Storage::url($this->avatar);
        } else {
            return "https://i0.wp.com/sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png?w=300&ssl=1";
        }
    }

    public function appartement(): HasMany {
        return $this->hasMany(Appartement::class);
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function tags():HasMany {
        return $this->hasMany(Tag::class);
    }

    public function provider() : HasOne {
        return $this->hasOne(Provider::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    
    public function sentAvis()
    {
        return $this->hasMany(UserAvis::class, 'sender_user_id');
    }

    public function receivedAvis()
    {
        return $this->hasMany(UserAvis::class, 'receiver_user_id');
    }

    public function attributedTickets(): HasMany {
        return $this->hasMany(Ticket::class, 'attributed_user_id');
    }

    public function askedTickets(): HasMany {
        return $this->hasMany(Ticket::class, 'asker_user_id');
    }

    public function conversations()
    {   
        return $this->hasMany(Conversation::class,'sender_id')->orWhere('receiver_id',$this->id)->whereNotDeleted();
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.'.$this->id;
    }

    public function routeNotificationForVonage(Notification $notification): string
    {
        return $this->number;
    }

    public function hasRole($role, $id)
    {
        return $this->roles()->where('role_id', $role)
                            ->orWhere('nom', $role)
                            ->where('user_id', $id)
                            ->exists();
    }
}
