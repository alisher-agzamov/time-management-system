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

class UserModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function users_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id',
                'name',
                'email',
                'email_verified_at',
                'password',
                'remember_token',
                'created_at',
                'updated_at'
            ]), 1);
    }

    /** @test */
    public function a_user_has_many_tasks()
    {
        $user    = factory(User::class)->create();
        $task    = factory(Task::class)->create(['user_id' => $user->id]);

        $this->assertTrue($user->tasks->contains($task));
    }

    /** @test */
    public function a_user_has_many_settings()
    {
        $user    = factory(User::class)->create();
        $task    = factory(UserSetting::class)->create(['user_id' => $user->id]);

        $this->assertTrue($user->settings->contains($task));
    }

    /** @test */
    public function create_a_new_user()
    {
        Artisan::call('db:seed');
        $email = $this->faker->email;

        $user = new User;
        $user->createNewUser([
            'name'  => $this->faker->randomLetter,
            'email'  => $email,
            'password'  => $this->faker->password,
            'preferred_working_hour_per_day'    => 10
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

        $user->deleteUser();
    }

    /** @test */
    public function create_a_new_user_with_user_role()
    {
        Artisan::call('db:seed');

        $email = $this->faker->email;

        $user = new User;
        $user->createNewUser([
            'name'  => $this->faker->randomLetter,
            'email'  => $email,
            'password'  => $this->faker->password,
            'preferred_working_hour_per_day'    => 10
        ], 'user');

        $this->assertTrue($user->hasRole('user'));

        $user->deleteUser();
    }

    /** @test */
    public function create_a_new_user_with_manager_role()
    {
        Artisan::call('db:seed');

        $email = $this->faker->email;

        $user = new User;
        $user->createNewUser([
            'name'  => $this->faker->randomLetter,
            'email'  => $email,
            'password'  => $this->faker->password,
            'preferred_working_hour_per_day'    => 10
        ], 'manager');

        $this->assertTrue($user->hasRole('manager'));

        $user->deleteUser();
    }

    /** @test */
    public function create_a_new_user_with_admin_role()
    {
        Artisan::call('db:seed');

        $email = $this->faker->email;

        $user = new User;
        $user->createNewUser([
            'name'  => $this->faker->randomLetter,
            'email'  => $email,
            'password'  => $this->faker->password,
            'preferred_working_hour_per_day'    => 10
        ], 'admin');

        $this->assertTrue($user->hasRole('admin'));

        $user->deleteUser();
    }

    /** @test */
    public function preferred_working_hours_setting_should_be_in_database()
    {
        Artisan::call('db:seed');
        $email = $this->faker->email;

        $user = new User;
        $user->createNewUser([
            'name'  => $this->faker->randomLetter,
            'email'  => $email,
            'password'  => $this->faker->password,
            'preferred_working_hour_per_day'    => 10
        ]);

        $this->assertDatabaseHas('user_settings', [
            'key'     => 'preferred_working_hour_per_day',
            'user_id' => $user->id,
        ]);

        $user->deleteUser();
    }

    /** @test */
    public function deleted_user_should_be_missing_in_database()
    {
        $user = factory(User::class)->create();

        $user->deleteUser();

        $this->assertDeleted('users', [
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function task_of_deleted_user_should_be_missing_in_database()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create(['user_id' => $user->id]);

        $user->deleteUser();

        $this->assertDeleted('tasks', [
            'id'      => $task->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_setting_of_deleted_user_should_be_missing_in_database()
    {
        $user = factory(User::class)->create();
        $setting = factory(UserSetting::class)->create(['user_id' => $user->id]);

        $user->deleteUser();

        $this->assertDeleted('user_settings', [
            'id'      => $setting->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_read_his_user_data()
    {
        $user = $this->_createUser('user');

        $this->assertTrue($user->canBeReadBy(
            $user
        ));
    }

    /** @test */
    public function user_can_not_read_another_user_data()
    {
        $user = $this->_createUser('user');

        $this->assertFalse($user->canBeReadBy(
            factory(User::class)->create()
        ));
    }

    /** @test */
    public function manager_can_read_his_user_data()
    {
        $user = $this->_createUser('manager');

        $this->assertTrue($user->canBeReadBy(
            $user
        ));
    }

    /** @test */
    public function manager_can_read_another_user_data()
    {
        $user = $this->_createUser('manager');

        $this->assertFalse($user->canBeReadBy(
            factory(User::class)->create()
        ));
    }

    /** @test */
    public function admin_can_read_his_user_data()
    {
        $user = $this->_createUser('admin');

        $this->assertTrue($user->canBeReadBy(
            $user
        ));
    }

    /** @test */
    public function admin_can_read_another_user_data()
    {
        $user = $this->_createUser('admin');

        $this->assertFalse($user->canBeReadBy(
            factory(User::class)->create()
        ));
    }

    /** @test */
    public function user_can_edit_his_user_data()
    {
        $user = $this->_createUser('user');

        $this->assertTrue($user->canBeEditedBy(
            $user
        ));
    }

    /** @test */
    public function user_can_not_edit_another_user_data()
    {
        $user = $this->_createUser('user');

        $this->assertFalse($user->canBeEditedBy(
            factory(User::class)->create()
        ));
    }

    /** @test */
    public function manager_can_edit_his_user_data()
    {
        $user = $this->_createUser('manager');

        $this->assertTrue($user->canBeEditedBy(
            $user
        ));
    }

    /** @test */
    public function manager_can_edit_another_user_data()
    {
        $user = $this->_createUser('manager');

        $this->assertFalse($user->canBeEditedBy(
            factory(User::class)->create()
        ));
    }

    /** @test */
    public function admin_can_edit_his_user_data()
    {
        $user = $this->_createUser('admin');

        $this->assertTrue($user->canBeEditedBy(
            $user
        ));
    }

    /** @test */
    public function admin_can_edit_another_user_data()
    {
        $user = $this->_createUser('admin');

        $this->assertFalse($user->canBeEditedBy(
            factory(User::class)->create()
        ));
    }

    /** @test */
    public function user_can_not_edit_his_role()
    {
        $user = $this->_createUser('user');

        $this->assertFalse($user->roleCanBeEditedBy(
            $user
        ));
    }

    /** @test */
    public function user_can_not_edit_another_user_role()
    {
        $user = $this->_createUser('user');

        $this->assertFalse($user->roleCanBeEditedBy(
            factory(User::class)->create()
        ));
    }

    /** @test */
    public function manager_can_not_edit_his_role()
    {
        $user = $this->_createUser('manager');

        $this->assertFalse($user->roleCanBeEditedBy(
            $user
        ));
    }

    /** @test */
    public function manager_can_not_edit_another_user_role()
    {
        $user = $this->_createUser('manager');

        $this->assertFalse($user->roleCanBeEditedBy(
            factory(User::class)->create()
        ));
    }

    /** @test */
    public function admin_can_edit_his_role()
    {
        $user = $this->_createUser('admin');

        $this->assertTrue($user->roleCanBeEditedBy(
            $user
        ));
    }

    /** @test */
    public function admin_can_edit_another_user_role()
    {
        $user = $this->_createUser('admin');

        $this->assertTrue($user->roleCanBeEditedBy(
            $user
        ));
    }

    /** @test */
    public function user_can_not_delete_himself()
    {
        $user = $this->_createUser('user');

        $this->assertFalse($user->canBeDeletedBy(
            $user
        ));
    }

    /** @test */
    public function user_can_not_delete_another_user()
    {
        $user = $this->_createUser('user');
        $anotherUser = factory(User::class)->create();
        $anotherUser->assignRole('user');

        $this->assertFalse($user->canBeDeletedBy(
            $anotherUser
        ));
    }

    /** @test */
    public function manager_can_not_delete_himself()
    {
        $manager = $this->_createUser('manager');

        $this->assertFalse($manager->canBeDeletedBy(
            $manager
        ));
    }

    /** @test */
    public function manager_can_delete_another_user()
    {
        $manager = $this->_createUser('manager');
        $user = factory(User::class)->create();

        $this->assertTrue($user->canBeDeletedBy(
            $manager
        ));
    }

    /** @test */
    public function manager_can_delete_another_manager()
    {
        $manager = $this->_createUser('manager');
        $anotherManager = factory(User::class)->create();
        $anotherManager->assignRole('manager');

        $this->assertTrue($anotherManager->canBeDeletedBy(
            $manager
        ));
    }

    /** @test */
    public function manager_can_not_delete_admin()
    {
        $admin = $this->_createUser('admin');
        $manager = factory(User::class)->create();
        $manager->assignRole('manager');

        $this->assertFalse($admin->canBeDeletedBy(
            $manager
        ));
    }

    /** @test */
    public function admin_can_not_delete_himself()
    {
        $user = $this->_createUser('admin');

        $this->assertFalse($user->canBeDeletedBy(
            $user
        ));
    }

    /** @test */
    public function admin_can_delete_another_user()
    {
        $admin = $this->_createUser('admin');
        $user = factory(User::class)->create();

        $this->assertTrue($user->canBeDeletedBy(
            $admin
        ));
    }

    /** @test */
    public function admin_can_delete_manager()
    {
        $admin = $this->_createUser('admin');
        $manager = factory(User::class)->create();
        $manager->assignRole('manager');

        $this->assertTrue($manager->canBeDeletedBy(
            $admin
        ));
    }

    /** @test */
    public function admin_can_delete_another_admin()
    {
        $admin = $this->_createUser('admin');
        $anotherAdmin = factory(User::class)->create();
        $anotherAdmin->assignRole('admin');

        $this->assertTrue($anotherAdmin->canBeDeletedBy(
            $admin
        ));
    }

    /** @test */
    public function update_existing_user_name_should_pass()
    {
        $user = $this->_createUser();
        $newName = $this->faker->sentence(2);

        $user->updateUser([
            'name' => $newName,
            'preferred_working_hour_per_day' => $this->faker->numberBetween(1, 1439)
        ]);

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => $newName
        ]);

        $user->deleteUser();
    }

    /** @test */
    public function update_existing_user_email_should_pass()
    {
        $user = $this->_createUser();
        $newEmail = $this->faker->email;

        $user->updateUser([
            'name' => $this->faker->sentence(2),
            'preferred_working_hour_per_day' => $this->faker->numberBetween(1, 1439),
            'email' => $newEmail
        ], true);

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'email'  => $newEmail
        ]);

        $user->deleteUser();
    }

    /** @test */
    public function update_existing_user_email_should_fail_without_second_param()
    {
        $user = $this->_createUser();
        $newEmail = $this->faker->email;

        $user->updateUser([
            'name' => $this->faker->sentence(2),
            'preferred_working_hour_per_day' => $this->faker->numberBetween(1, 1439),
            'email' => $newEmail
        ]);

        $this->assertDatabaseMissing('users', [
            'id'    => $user->id,
            'email'  => $newEmail
        ]);

        $user->deleteUser();
    }

    /** @test */
    public function update_existing_user_working_hours_should_pass()
    {
        $user = $this->_createUser();
        $workingHours = $this->faker->numberBetween(1, 1439);

        $user->updateUser([
            'name' => $this->faker->sentence(2),
            'preferred_working_hour_per_day' => $workingHours
        ]);

        $this->assertDatabaseHas('user_settings', [
            'user_id'   => $user->id,
            'key'       => 'preferred_working_hour_per_day',
            'value'     => $workingHours
        ]);

        $user->deleteUser();
    }

    /** @test */
    public function update_existing_user_role_should_pass()
    {
        $user = $this->_createUser('user');

        $user->updateUser([
            'name' => $this->faker->sentence(2),
            'preferred_working_hour_per_day' => $this->faker->numberBetween(1, 1439),
            'role'  => 'manager'
        ]);

        $this->assertTrue($user->hasRole('manager'));

        $user->deleteUser();
    }

    /** @test */
    public function get_all_users_should_pass_with_seeds_data()
    {
        Artisan::call('db:seed');

        $this->assertCount(3, User::getAllUsers());
    }

    /** @test */
    public function get_preferred_working_hour_per_day_should_pass()
    {
        $user = $this->_createUser('user');
        $workingHours = $this->faker->numberBetween(1, 1439);

        $user->updateUser([
            'name' => $this->faker->sentence(2),
            'preferred_working_hour_per_day' => $workingHours,
            'role'  => 'manager'
        ]);

        $this->assertEquals($workingHours, $user->getPreferredHours());

        $user->deleteUser();
    }
}
