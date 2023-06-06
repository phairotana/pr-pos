<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }
    public function scopeRead($query, $read = null)
    {
        if (isset($read) && $read==1) {
            return $query->whereNotNull('read_at');
        } elseif (isset($read) && $read==0) {
            return $query->whereNull('read_at');
        }
        return $query;
    }
    public function scopeLimit($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays(30));
    }
    public function scopeAuth($query)
    {
        return $query->where('user_id', \Auth::user()->id);
    }
    public function scopeOrderByDefault($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    public function scopeUserType($query)
    {
        $userConfig = config('backpack.base.user_model_fqn');

        return $query->where('notifiable_type', 'like', '%' . str_replace('\\', '_', $userConfig) . '%');
    }

    /**
     * @param mixed $id
     * @param string $type: must be scope name
     */
    public function scopeNotifableId($query, $id, $type = false)
    {
        if ($type) {
            $query->{$type}();
        } else { // when type = false set to User
            $query->UserType();
        }
        return $query->where('notifiable_id', $id);
    }
    public function scopeOnlyRead($query)
    {
        return $query->whereNotNull('read_at');
    }
    public function scopeOnlyUnread($query)
    {
        return $query->whereNull('read_at');
    }
    /**
     * @param string $only: in: read|unread
     */
    public function scopeReadOrUnread($query, $only)
    {
        if (!in_array($only, ['read', 'unread'])) {
        } else {
            if ($only === 'read') {
                $query->OnlyRead();
            }
            if ($only === 'unread') {
                $query->OnlyUnread();
            }
        }
        return $query;
    }
    /**
     * Accessor
     */
    public function getDiffHumanCreatedAtAttribute()
    {
        if (!$this->created_at) {
            return null;
        }
        return $this->created_at->diffForhumans();
    }

    public function getDiffHumanReadAtAttribute()
    {
        if (!$this->read_at) {
            return null;
        }
        return Carbon::parse($this->read_at)->diffForhumans();
    }

    public function getReadDataAttribute()
    {
        if (isset($this->data['data'])) {
            return $this->data['data'];
        }

        return null;
    }

    public function getReadMessageAttribute()
    {
        return $this->getDataKeyValue('message');
    }

    public function getReadTitleAttribute()
    {
        return $this->getDataKeyValue('title');
    }

    protected function getDataKeyValue($key)
    {
        $readData = $this->ReadData;
        if ($readData) {
            if (isset($readData[$key])) {
                return $readData[$key];
            }
        }
        return null;
    }
}
