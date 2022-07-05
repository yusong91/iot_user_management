<?php

namespace Vanguard\Repositories\User;

use Carbon\Carbon;
use Vanguard\User;
use \Laravel\Socialite\Contracts\User as SocialUser;

interface UserRepository
{

    public function paginate($perPage, $search = null, $status = null);

    public function find($id);

    public function findByEmail($email);

    public function findBySocialId($provider, $providerId);

    public function findBySessionId($sessionId);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function associateSocialAccountForUser($userId, $provider, SocialUser $user);

    public function count();

    public function newUsersCount();

    public function countByStatus($status);

    public function countOfNewUsersPerMonth(Carbon $from, Carbon $to);

    public function latest($count = 20);

    public function setRole($userId, $roleId);

    public function switchRolesForUsers($fromRoleId, $toRoleId);

    public function getUsersWithRole($roleName);

    public function getUserSocialLogins($userId);

    public function findByConfirmationToken($token);
}