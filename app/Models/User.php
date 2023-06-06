<?php

namespace App\Models;

use App\Helpers\Helper;
use Laravel\Passport\HasApiTokens;
use App\Traits\UploadFiles\UploadFIle;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use UploadFIle;
    use HasApiTokens, HasFactory, Notifiable;
    use CrudTrait, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'profile',
        'branch_id',
        'address',
        'dob',
        'lat',
        'long'
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
    ];
    public function deviceToken()
    {
        return $this->hasMany(DeviceToken::class)->whereNotNull('user_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'phone', 'customer_phone');
    }
    public function getFullNameAttribute()
    {
        return $this->name ;
    }
    public function getMediumProfileAttribute()
    {
        return Helper::isUrl($this->profile) ? $this->profile : asset($this->getUploadImage($this->profile, 'medium', 'default_image'));
    }
    public function getLargeProfileAttribute()
    {
        return Helper::isUrl($this->profile) ? $this->profile : asset($this->getUploadImage($this->profile, 'large', 'default_image'));
    }
    public function setProfileAttribute($value)
    {
        if (!empty(request()->profile)) {
            if (\Str::startsWith($value, 'data:image')) {
                $this->attributes['profile'] = $this->base64Upload($value);
                // DELETE OLD PROFILE
                $this->deleteFiel($this->getOriginal('profile'));
            } else {
                if (request()->hasFile('profile')) {
                    $this->attributes['profile'] = $this->SingleUpload('profile', request());
                    // DELETE OLD PROFILE
                    $this->deleteFiel($this->getOriginal('profile'));
                }
            }
        } elseif (Helper::isUrl($value)) {
            $this->attributes['profile'] = $value;
        } else {
            $this->attributes['profile'] = $this->base64Upload($value);
        }
    }
}
