<?php

namespace Vanguard\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Vanguard\Repositories\Country\CountryRepository;
use Vanguard\Repositories\Country\EloquentCountry;
use Vanguard\Repositories\Permission\EloquentPermission;
use Vanguard\Repositories\Permission\PermissionRepository;
use Vanguard\Repositories\Role\EloquentRole;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\Session\DbSession;
use Vanguard\Repositories\Session\SessionRepository;
use Vanguard\Repositories\User\EloquentUser;
use Vanguard\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;
use Vanguard\Repositories\Project\EloquentProject;
use Vanguard\Repositories\Project\ProjectRepository;
use Vanguard\Repositories\AsignProject\EloquentAsign;
use Vanguard\Repositories\AsignProject\AsignRepository;
use Vanguard\Repositories\AsignDevice\EloquentAsignDevice;
use Vanguard\Repositories\AsignDevice\AsignDeviceRepository;
use Vanguard\Repositories\CommonCode\EloquentCommonCode;
use Vanguard\Repositories\CommonCode\CommonCodeRepository;
use Vanguard\Repositories\Folder\EloquentFolder;
use Vanguard\Repositories\Folder\FolderRepository;
use Vanguard\Repositories\AsignFolder\EloquentAsignFolder;
use Vanguard\Repositories\AsignFolder\AsignFolderRepository;
use Vanguard\Repositories\Feature\EloquentFeature;
use Vanguard\Repositories\Feature\FeatureRepository;
use Vanguard\Repositories\AsignDeviceFeature\AsignDeviceFeatureRepository;
use Vanguard\Repositories\AsignDeviceFeature\EloquentAsignDeviceFeature;

use Vanguard\Repositories\Device\DeviceRepository;
use Vanguard\Repositories\Device\EloquentDevice;

use Vanguard\Repositories\AsignUser\AsignUserRepository;
use Vanguard\Repositories\AsignUser\EloquentAsignUser;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(config('app.locale'));
        config(['app.name' => setting('app_name')]);
        \Illuminate\Database\Schema\Builder::defaultStringLength(191);

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\Factories\\'.class_basename($modelName).'Factory';
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserRepository::class, EloquentUser::class);
        $this->app->singleton(RoleRepository::class, EloquentRole::class);
        $this->app->singleton(PermissionRepository::class, EloquentPermission::class);
        $this->app->singleton(SessionRepository::class, DbSession::class);
        $this->app->singleton(CountryRepository::class, EloquentCountry::class);
        $this->app->singleton(ProjectRepository::class, EloquentProject::class);
        $this->app->singleton(AsignRepository::class, EloquentAsign::class);
        $this->app->singleton(AsignDeviceRepository::class, EloquentAsignDevice::class);
        $this->app->singleton(CommonCodeRepository::class, EloquentCommonCode::class);
        $this->app->singleton(FolderRepository::class, EloquentFolder::class);
        $this->app->singleton(AsignFolderRepository::class, EloquentAsignFolder::class);
        $this->app->singleton(FeatureRepository::class, EloquentFeature::class);
        $this->app->singleton(AsignDeviceFeatureRepository::class, EloquentAsignDeviceFeature::class);
        $this->app->singleton(DeviceRepository::class, EloquentDevice::class);
        $this->app->singleton(AsignUserRepository::class, EloquentAsignUser::class);


        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
