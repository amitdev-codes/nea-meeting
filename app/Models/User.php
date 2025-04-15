<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Address;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Modules\Master\Models\Category;
use Modules\Master\Models\Component;
use Modules\Master\Models\Designation;
use Spatie\Permission\Traits\HasRoles;
use Modules\Master\Models\Organization;
use Modules\Master\Models\SubComponent;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;
    use LogsActivity;

    protected $fillable = [
        'username',
        'email',
        'mobile_no',
        'office_email',
        'office_mobile_no',
        'password',
        'phone',
        'status',
        'remarks',
        'designation_id',
        'category_id',
        'clusters',
        'locale',
        'password_changed_at',
        'is_locked',
        'wrong_password_attempts',
        'locked_at',
        'force_password_change',
        'last_login_at',
        'last_logout_at',
        'organization_id'
    ];

    protected static $logName = 'User';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'clusters' => 'array',
            'force_password_change' => 'boolean',
            'is_locked' => 'boolean',
            'password_changed_at' => 'datetime',
            'locked_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_logout_at' => 'datetime',
        ];
    }

    protected $attributes = [
        'status' => true,
        'locale' => 'np',
    ];

    public function avatarUrl(): Attribute
    {
        return new Attribute(
            get: fn () => 'https://ui-avatars.com/api/?name='.$this->name,
        );
    }

    public function getRoleNamesLowercaseAttribute()
    {
        return $this->getRoleNames()->map(function ($role) {
            return strtolower($role);
        });
    }

    public function getClusterNamesAttribute()
    {
        if (!$this->clusters) {
            return '';
        }

        $clusters = Cache::get('clusters');

        $userClusters = $this->clusters; // Already cast to array via casts()
        
        if (!is_array($userClusters)) {
            return '';
        }

        $clusterNames = array_map(function ($clusterId) use ($clusters) {
            $cluster = array_filter($clusters, function ($c) use ($clusterId) {
                return $c['id'] == $clusterId;
            });
            $cluster = array_shift($cluster);
            return $cluster['name'] ?? "Cluster $clusterId";
        }, $userClusters);

        return implode(' & ', $clusterNames);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['username', 'email', 'mobile_no', 'password'])
            ->useLogName('User')
            ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }

    public function getLastLoginAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : 'Never logged in';
    }

    public function getLastLogoutAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : 'Never logged out';
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function designations()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}