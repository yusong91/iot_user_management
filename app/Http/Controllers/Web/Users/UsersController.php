<?php

namespace Vanguard\Http\Controllers\Web\Users;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Vanguard\Events\User\Deleted;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\User\CreateUserRequest;
use Vanguard\Repositories\Activity\ActivityRepository;
use Vanguard\Repositories\Country\CountryRepository;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\User;


class UsersController extends Controller
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }
 
    public function index(Request $request)
    {
        $users = $this->users->paginate($perPage = 20, $request->search, $request->status);
        $raw_paginate = json_encode($users); 
        $paginate = json_decode($raw_paginate);
        $statuses = ['' => __('All')] + UserStatus::lists();
        return view('user.list', compact('users', 'paginate', 'statuses'));
    }

    public function show(User $user)
    {
        return view('user.view', compact('user'));
    } 

    public function create(CountryRepository $countryRepository, RoleRepository $roleRepository)
    {
        $role_lists = [];
        $role_id = auth()->user()->role_id;
        
        if($role_id != 1)
        {
            foreach($roleRepository->lists() as $key => $item)
            {
                if($key == 1 || $role_id > $key){continue;} else if($role_id == 3 && $key == 3){continue;}

                $role_lists[$key] = $item;
            }
        
        } else 
        {
            foreach($roleRepository->lists() as $key => $item)
            {
                if($key == 3 || $key == 4)
                {
                    continue;
                }

                $role_lists[$key] = $item;
            }
        }

        return view('user.add', [
            'countries' => $this->parseCountries($countryRepository),
            'roles' =>count($role_lists) == 0 ? $roleRepository->lists() : $role_lists,
            'statuses' => UserStatus::lists()
        ]);
    }

    private function parseCountries(CountryRepository $countryRepository)
    {
        return [0 => __('Select a Country')] + $countryRepository->lists()->toArray();
    }

    public function store(CreateUserRequest $request)
    {
        $family = 0;
        $role_id = $request->role_id;
        $auth_role_id = auth()->user()->role_id;
        $parent_id = 0;

        if($auth_role_id == 1)
        {
            $parent_id = 0;
            $family = 0;
        } else 
        {
            $family = auth()->user()->parent_id == 0 ? auth()->user()->id : auth()->user()->family;
            $parent_id = auth()->user()->id;
        }

        $data = $request->all() + [
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
            'parent_id'=>$parent_id,
            'family'=>$family
        ];

        if (! data_get($data, 'country_id')) {
            $data['country_id'] = null;
        }

        // Username should be updated only if it is provided.
        if (! data_get($data, 'username')) {
            $data['username'] = null;
        }

        $this->users->create($data);

        return redirect()->route('users.index')->withSuccess(__('User created successfully.'));
    }

    public function edit(User $user, CountryRepository $countryRepository, RoleRepository $roleRepository)
    {
        $role_lists = [];

        if(auth()->user()->role_id != 1 && $user->role_id == 1)
        {
            return redirect()->route('users.index')->withErrors(__('You cannot update yourself or super admin.'));
        }

        if(auth()->user()->role_id == 1)
        {
            foreach($roleRepository->lists() as $key => $item)
            {
                if($key == 3 || $key == 4){continue;}
                
                $role_lists[$key] = $item;
            }
        }
        elseif(auth()->user()->role_id == 2)
        {
            foreach($roleRepository->lists() as $key => $item)
            {
                if($key == 1 ){continue;}
                
                $role_lists[$key] = $item;
            }

        } elseif(auth()->user()->role_id == 3)
        {
            foreach($roleRepository->lists() as $key => $item)
            {
                if($key == 1 || $key == 2 || $key == 3){continue;}
                
                $role_lists[$key] = $item;
            }
        }

        return view('user.edit', [
            'edit' => true,
            'user' => $user,
            'countries' => $this->parseCountries($countryRepository),
            'roles' => count($role_lists) == 0 ? $roleRepository->lists() : $role_lists,
            'statuses' => UserStatus::lists(),
            'socialLogins' => $this->users->getUserSocialLogins($user->id)
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('users.index')->withErrors(__('You cannot delete yourself.'));
        }

        if(auth()->user()->role_id != 1 && $user->role_id == 1)
        {
            return redirect()->route('users.index')->withErrors(__('You cannot delete yourself.'));
        }

        $this->users->delete($user->id);

        event(new Deleted($user));

        return redirect()->route('users.index')->withSuccess(__('User deleted successfully.'));
    }
}
