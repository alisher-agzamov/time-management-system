<?php

namespace Tests\Unit\Models;

use App\Task;
use App\User;
use App\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function users_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('tasks', [
                'id',
                'title',
                'description',
                'date',
                'duration',
                'user_id',
                'created_at',
                'updated_at'
            ]), 1);
    }

    /** @test */
    public function create_a_new_task_shoud_pass()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $task = new Task;
        $task->createTask([
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ]);

        $this->assertDatabaseHas('tasks', [
            'id'    => $task->id,
            'user_id' => $user->id,
            'title' => $task->title
        ]);

        $user->deleteUser();
    }

    /** @test */
    public function create_a_new_task_by_another_user_shoud_pass()
    {
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $this->actingAs($user);

        $task = new Task;
        $task->createTask([
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100),
            'user_id'   => $anotherUser->id
        ]);

        $this->assertDatabaseHas('tasks', [
            'id'    => $task->id,
            'user_id' => $anotherUser->id,
            'title' => $task->title
        ]);

        $user->deleteUser();
        $anotherUser->deleteUser();
    }

    /** @test */
    public function update_the_task_shoud_pass()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id'   => $user->id
        ]);

        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ];

        $task->updateTask($data);

        $this->assertDatabaseHas('tasks', [
            'id'    => $task->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'date' => $data['date'],
            'duration' => $data['duration'],
        ]);

        $user->deleteUser();
    }

    /** @test */
    public function user_can_read_his_task_data()
    {
        $user = $this->_createUser('user');
        $task = factory(Task::class)->create([
            'user_id'   => $user->id
        ]);

        $this->assertTrue($task->canBeReadBy(
            $user
        ));
    }

    /** @test */
    public function user_can_not_read_another_user_task_data()
    {
        $user = $this->_createUser('user');
        $anotherUser = factory(User::class)->create();

        $task = factory(Task::class)->create([
            'user_id'   => $anotherUser->id
        ]);

        $this->assertFalse($task->canBeReadBy(
            $user
        ));
    }

    /** @test */
    public function manager_can_not_read_another_user_task_data()
    {
        $manager = $this->_createUser('manager');
        $anotherUser = factory(User::class)->create();

        $task = factory(Task::class)->create([
            'user_id'   => $anotherUser->id
        ]);

        $this->assertFalse($task->canBeReadBy(
            $manager
        ));
    }

    /** @test */
    public function admin_can_read_his_task_data()
    {
        $admin = $this->_createUser('admin');
        $task = factory(Task::class)->create([
            'user_id'   => $admin->id
        ]);

        $this->assertTrue($task->canBeReadBy(
            $admin
        ));
    }

    /** @test */
    public function admin_can_read_another_user_task_data()
    {
        $admin = $this->_createUser('admin');
        $anotherUser = factory(User::class)->create();

        $task = factory(Task::class)->create([
            'user_id'   => $anotherUser->id
        ]);

        $this->assertTrue($task->canBeReadBy(
            $admin
        ));
    }

    /** @test */
    public function user_can_edit_his_task_data()
    {
        $user = $this->_createUser('user');
        $task = factory(Task::class)->create([
            'user_id'   => $user->id
        ]);

        $this->assertTrue($task->canBeEditedBy(
            $user
        ));
    }

    /** @test */
    public function user_can_not_edit_another_user_task_data()
    {
        $user = $this->_createUser('user');
        $anotherUser = factory(User::class)->create();

        $task = factory(Task::class)->create([
            'user_id'   => $anotherUser->id
        ]);

        $this->assertFalse($task->canBeEditedBy(
            $user
        ));
    }

    /** @test */
    public function manager_can_not_edit_another_user_task_data()
    {
        $manager = $this->_createUser('manager');
        $anotherUser = factory(User::class)->create();

        $task = factory(Task::class)->create([
            'user_id'   => $anotherUser->id
        ]);

        $this->assertFalse($task->canBeEditedBy(
            $manager
        ));
    }

    /** @test */
    public function admin_can_edit_his_task_data()
    {
        $admin = $this->_createUser('admin');
        $task = factory(Task::class)->create([
            'user_id'   => $admin->id
        ]);

        $this->assertTrue($task->canBeEditedBy(
            $admin
        ));
    }

    /** @test */
    public function user_can_edit_another_user_task_data()
    {
        $admin = $this->_createUser('admin');
        $anotherUser = factory(User::class)->create();

        $task = factory(Task::class)->create([
            'user_id'   => $anotherUser->id
        ]);

        $this->assertTrue($task->canBeEditedBy(
            $admin
        ));
    }

    /** @test */
    public function user_can_delete_his_task_data()
    {
        $user = $this->_createUser('user');
        $task = factory(Task::class)->create([
            'user_id'   => $user->id
        ]);

        $this->assertTrue($task->canBeDeletedBy(
            $user
        ));
    }

    /** @test */
    public function user_can_not_delete_another_user_task_data()
    {
        $user = $this->_createUser('user');
        $anotherUser = factory(User::class)->create();

        $task = factory(Task::class)->create([
            'user_id'   => $anotherUser->id
        ]);

        $this->assertFalse($task->canBeDeletedBy(
            $user
        ));
    }

    /** @test */
    public function manager_can_not_delete_another_user_task_data()
    {
        $manager = $this->_createUser('manager');
        $anotherUser = factory(User::class)->create();

        $task = factory(Task::class)->create([
            'user_id'   => $anotherUser->id
        ]);

        $this->assertFalse($task->canBeDeletedBy(
            $manager
        ));
    }

    /** @test */
    public function admin_can_delete_his_task_data()
    {
        $admin = $this->_createUser('admin');
        $task = factory(Task::class)->create([
            'user_id'   => $admin->id
        ]);

        $this->assertTrue($task->canBeDeletedBy(
            $admin
        ));
    }

    /** @test */
    public function user_can_delete_another_user_task_data()
    {
        $admin = $this->_createUser('admin');
        $anotherUser = factory(User::class)->create();

        $task = factory(Task::class)->create([
            'user_id'   => $anotherUser->id
        ]);

        $this->assertTrue($task->canBeDeletedBy(
            $admin
        ));
    }
}
