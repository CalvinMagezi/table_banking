<?php

namespace App\Providers;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addDays(15));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

        $data = [];

        if(Schema::hasTable('permissions')){
            //Fetch all available permissions to be used for tokensCan
            try{
                $permissions = Permission::all()->toArray();
                if(!is_null($permissions)){
                    foreach ($permissions as $key => $value)
                        $data[trim($value['permission_name'])] =  trim($value['permission_display_name'] );
                }

                if (!is_null($data))
                    Passport::tokensCan($data);

            }catch (\Exception $exception){
                Passport::tokensCan(null);
            }

        }

    }
}
