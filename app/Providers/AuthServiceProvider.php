<?php

namespace App\Providers;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;
use Torzer\Awesome\Landlord\Facades\Landlord;

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

        Passport::tokensExpireIn(Carbon::now()->addMinutes(600));

        Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(900));

        $data = [];

        if(Schema::hasTable('permissions')){
            //Fetch all available permissions to be used for tokensCan
            try{
                $permissions = Permission::all();
                if(!is_null($permissions)){
                    foreach ($permissions->toArray() as $key => $value)
                        $data[trim($value['name'])] =  trim($value['display_name'] );
                }

                if (!is_null($data))
                    Passport::tokensCan($data);

            }catch (\Exception $exception){
                Passport::tokensCan([]);
            }

        }

    }
}
