<?php namespace Palmabit\Authentication\Tests\traits;

use Mockery as m;
use Palmabit\Authentication\Repository\SentryUserRepository;

trait CreateUserTrait
{

    public function createSuperadmin()
    {
        $per_page = 5;
        $config = m::mock('ConfigMock');
        $config->shouldReceive('get')
            ->with('authentication::users_per_page')
            ->andReturn($per_page)
            ->getMock();
        $repo = new SentryUserRepository($config);
        $input = [
            "email" => "admin@admin.com",
            "password" => "password",
            "activated" => 1
        ];
        return $repo->create($input);
    }

    public function createAdmin()
    {
        $repo = $this->repository();
        $input = [
            "email" => "user@user.com",
            "password" => "password",
            "activated" => 1
        ];
        return $repo->create($input);
    }

    public function repository()
    {
        $per_page = 5;
        $config = m::mock('ConfigMock');
        $config->shouldReceive('get')
            ->with('authentication::users_per_page')
            ->andReturn($per_page)
            ->getMock();
        return new SentryUserRepository($config);
    }

} 