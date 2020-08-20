<?php

namespace App;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\CannotBeExecutedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'date', 'duration', 'user_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'user_id'
    ];

    /**
     * The attributes that can be updated
     * @var array
     */
    protected $editable = [
        'title', 'duration'
    ];

    /**
     * Does the current user have enough permissions to read task data
     * @param $user
     * @return bool
     */
    public function canRead($user)
    {
        return $user->hasRole('admin')
            || $this->user_id == $user->id;
    }

    /**
     * Does the current user have enough permissions to edit task data
     * @param $user
     * @return bool
     */
    public function canEdit($user)
    {
        return $user->hasRole('admin')
            || $this->user_id == $user->id;
    }

    /**
     * Does the current user have enough permissions to delete the task
     * @param $user
     * @return bool
     */
    public function canDelete($user)
    {
        return $user->hasRole('admin')
            || $this->user_id == $user->id;
    }

    /**
     * Create a new task
     * @param $data
     * @throws AccessDeniedException
     * @throws CannotBeExecutedException
     */
    public function createTask($data)
    {
        $this->title        = $data['title'];
        $this->description  = $data['description'];
        $this->date         = $data['date'];
        $this->duration     = $data['duration'];

        // Use current user ID as task owner by default
        $this->user_id      = Auth::user()->id;

        // Admin creates task using user_id
        if(!empty($data['user_id'])) {

            if(!Auth::user()->hasRole('admin')) {
                throw new AccessDeniedException();
            }

            $this->user_id = $data['user_id'];
        }

        if(!$this->save()) {
            throw new CannotBeExecutedException();
        }
    }

    /**
     * Update existing task
     * @param $data
     * @throws CannotBeExecutedException
     */
    public function updateTask($data)
    {
        $this->title        = $data['title'];
        $this->description  = $data['description'];
        $this->date         = $data['date'];
        $this->duration     = $data['duration'];

        if(!$this->save()) {
            throw new CannotBeExecutedException();
        }
    }

}
