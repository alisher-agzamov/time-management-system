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

    /**
     * Get grouped user tasks
     * @param User $user
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public static function getGroupedUserTasks(User $user, $dateFrom, $dateTo)
    {
        $tasks = $user->tasks()
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date', 'DESC')
            ->get()
            ->toArray();

        $groupedTasks = [
            'tasks'             => [],
            'total_duration'    => 0
        ];

        $preferredHours = $user->getPreferredHours();

        // Group tasks with date index
        foreach ($tasks as $task) {
            $groupedTasks['tasks'][$task['date']]['tasks'][] = $task;

            if(empty($groupedTasks['tasks'][$task['date']]['total_duration'])) {
                $groupedTasks['tasks'][$task['date']]['total_duration'] = 0;
            }

            // total group duration
            $groupedTasks['tasks'][$task['date']]['total_duration'] += $task['duration'];

            // calculate under or over worked hours per day
            $groupedTasks['tasks'][$task['date']]['covered_day_hours'] = $groupedTasks['tasks'][$task['date']]['total_duration'] >= $preferredHours;

            // total duration
            $groupedTasks['total_duration'] += $task['duration'];
        }

        return $groupedTasks;
    }
}
