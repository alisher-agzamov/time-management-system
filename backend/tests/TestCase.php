<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    protected $_user;

    protected function setUp(): void
    {
        parent::setUp();

        //$this->artisan('migrate');
        //$this->seed();
    }

    protected function tearDown(): void
    {
        if($this->_user) {
            $this->_user->delete();
        }

        parent::tearDown();
    }

    /**
     * Create a new user
     * @param string $role
     * @param bool $password
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    protected function _createUser($role = 'user', $password = false)
    {
        Artisan::call('db:seed');

        if(!$password) {
            $password = $this->faker->password;
        }

        $this->_user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        $this->_user->assignRole($role);

        return $this->_user;
    }

    /**
     * Get token
     * @param User $user
     * @return string
     */
    protected function _getToken(User $user)
    {
        return $user
            ->createToken(__('Personal Access Token'))
            ->accessToken;
    }

    /**
     * Create user and get token
     * @param string $role
     * @param bool $password
     * @return string
     */
    protected function _createUserGetToken($role = 'user', $password = false)
    {
        return $this->_getToken($this->_createUser($role, $password));
    }

    /**
     * Auth request headers
     * @param string $role
     * @param bool $password
     * @return array
     */
    protected function _authRequest($role = 'user', $password = false)
    {
        return [
            'Authorization' => 'Bearer ' . $this->_createUserGetToken($role, $password)
        ];
    }
}
