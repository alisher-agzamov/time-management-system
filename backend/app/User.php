<?php

namespace App;

use App\Exceptions\CannotBeExecutedException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Define relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    /**
     * Define relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany('App\UserSetting');
    }

    /**
     * Does the current user have enough permissions to read user data
     * @param $user
     * @return bool
     */
    public function canRead($user)
    {
        return $user->hasRole('admin')
            || $user->hasRole('manager')
            || $this->id == $user->id;
    }

    /**
     * Does the current user have enough permissions to edit user data
     * @param $user
     * @return bool
     */
    public function canEdit($user)
    {
        return $user->hasRole('admin')
            || $user->hasRole('manager')
            || $this->id == $user->id;
    }

    /**
     * Does the current user have enough permissions to edit user role
     * @param $user
     * @return bool
     */
    public function canEditRole($user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Does the current user have enough permissions to delete the user
     * @return bool
     */
    public function canDelete($user)
    {
        // User cannot delete himself
        if($user->id == $this->id) {
            return false;
        }

        if($user->hasRole('admin')) {
            return true;
        }

        // Only admins can delete users with admin role
        if($this->hasRole('admin')) {
            return false;
        }

        // Managers can delete regular users and other managers
        return $user->hasRole('manager');
    }

    /**
     * Update the user
     * @param array $data
     * @param bool $isNeedToUpdateEmail
     * @throws CannotBeExecutedException
     */
    public function updateUser(array $data, bool $isNeedToUpdateEmail = false)
    {
        $this->name = $data['name'];

        // Update email if all validation rules are passed on the request level
        if($isNeedToUpdateEmail
            && !empty($data['email'])) {

            $this->email = $data['email'];
        }

        if(!$this->save()) {
            throw new CannotBeExecutedException();
        }

        // Set preferred working hours setting
        UserSetting::updateOrCreate([
            'user_id' => $this->id,
            'key' => 'preferred_working_hour_per_day'
        ], ['value' => $data['preferred_working_hour_per_day']]);

        // Update the user role
        if(!empty($data['role'])) {

            // Remove all current roles and assign a new one
            $this->syncRoles([$data['role']]);
        }
    }

    /**
     * Delete user
     * @return \Illuminate\Http\Response
     * @throws CannotBeExecutedException
     */
    public function deleteUser()
    {
        // Delete all user tasks
        $this->tasks()->delete();

        // Delete all user settings
        $this->settings()->delete();

        if(!$this->delete()) {
            throw new CannotBeExecutedException();
        }

        return response()->noContent();
    }

    /**
     * Create a new user
     * @param $data
     * @param string $role
     * @throws CannotBeExecutedException
     */
    public function createNewUser($data, $role = 'user')
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = bcrypt($data['password']);

        if(!$this->save()) {
            throw new CannotBeExecutedException();
        }

        // Assign a custom role
        if(!empty($data['role'])
            && Auth::user()
            && $this->canEditRole(Auth::user())) {
            $role = $data['role'];
        }

        // Assign a new role
        $this->assignRole($role);

        // Set preferred working hours
        $setting = new UserSetting;

        $setting->user_id = $this->id;
        $setting->key = 'preferred_working_hour_per_day';
        $setting->value = (int) $data['preferred_working_hour_per_day'];
        $setting->save();
    }

    /**
     * Get preferred working hours of the user
     * @return int
     */
    public function getPreferredHours()
    {
        if(!$setting = $this->settings()
            ->where(['key' => 'preferred_working_hour_per_day'])
            ->get()
            ->first()) {
            return 0;
        }

        return (int) $setting->value;
    }

    /**
     * Get all users with the first role
     * @return mixed
     */
    public static function getAllUsers()
    {
        $users = User::orderBy('id', 'DESC')
            ->with('roles:name')
            ->get()
            ->toArray();

        foreach ($users as $key => $user) {

            // Default role
            $role = 'user';

            if(!empty($user['roles'][0])) {
                $role = $user['roles'][0]['name'];
            }

            $users[$key]['role'] = $role;
            unset($users[$key]['roles']);
        }

        return $users;
    }
}
