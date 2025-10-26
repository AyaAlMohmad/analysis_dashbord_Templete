<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    // use HasProfilePhoto;
    use Notifiable;
    // use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_progresses',
        'is_manger',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
      public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasPermission($permissionName)
    {
        // التحقق من الصلاحيات المباشرة
        if ($this->permissions()->where('name', $permissionName)->exists()) {
            return true;
        }

        // التحقق من الصلاحيات عبر الأدوار
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permissionName)) {
                return true;
            }
        }

        return false;
    }

    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }
    }

    public function assignPermission($permissionName)
    {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            $this->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }

    /**
     * التحقق إذا كان المستخدم مدير
     */
    public function isAdmin()
    {
        // يمكنك تعديل هذا الشرط حسب نظامك
        return $this->id === 1 ||
               $this->email === 'admin@example.com' ||
               $this->hasPermission('manage_permissions') ||
               $this->hasPermission('manage_project_plans');
    }

    /**
     * الصلاحيات الخاصة بالمشاريع - جميع الدوال المطلوبة
     */
    public function canViewProjects()
    {
        return $this->hasPermission('view_projects') ||
               $this->hasPermission('view_project_plans') ||
               $this->isAdmin();
    }

    public function canCreateProjects()
    {
        return $this->hasPermission('create_projects') ||
               $this->hasPermission('manage_project_plans') ||
               $this->isAdmin();
    }

    public function canEditProjects()
    {
        return $this->hasPermission('edit_projects') ||
               $this->hasPermission('manage_project_plans') ||
               $this->isAdmin();
    }

    public function canEditOwnProjects()
    {
        return $this->hasPermission('edit_own_projects') ||
               $this->hasPermission('edit_projects') ||
               $this->hasPermission('manage_project_plans') ||
               $this->isAdmin();
    }

    public function canDeleteProjects()
    {
        return $this->hasPermission('delete_projects') ||
               $this->hasPermission('manage_project_plans') ||
               $this->isAdmin();
    }

    public function canDeleteOwnProjects()
    {
        return $this->hasPermission('delete_own_projects') ||
               $this->hasPermission('delete_projects') ||
               $this->hasPermission('manage_project_plans') ||
               $this->isAdmin();
    }

    public function canUpdateStatus()
    {
        return $this->hasPermission('update_status') ||
               $this->hasPermission('manage_project_plans') ||
               $this->isAdmin();
    }

    public function canUpdateOwnStatus()
    {
        return $this->hasPermission('update_own_status') ||
               $this->hasPermission('update_status') ||
               $this->hasPermission('manage_project_plans') ||
               $this->isAdmin();
    }

    public function canExportData()
    {
        return $this->hasPermission('export_data') ||
               $this->hasPermission('export_project_data') ||
               $this->isAdmin();
    }

    public function canManagePermissions()
    {
        return $this->hasPermission('manage_permissions') || $this->isAdmin();
    }
}
