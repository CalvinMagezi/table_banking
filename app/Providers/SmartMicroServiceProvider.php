<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 25/10/2018
 * Time: 10:54
 */

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

class SmartMicroServiceProvider extends ServiceProvider {

    /**
     * System repositories
     * @var array
     */
    protected $repositories = [
        'User',
        'Client',
        'Loan',
        'LoanType',
        'LoanApplication',
        'LoanStatus',
        'Branch'
    ];

    /**
     *  Loops through all repositories and binds them with their Eloquent implementation
     */
    public function register()
    {
        array_walk($this->repositories, function($repository) {
            $this->app->bind(
                'App\SmartMicro\Repositories\Contracts\\'. $repository . 'Interface',
                'App\SmartMicro\Repositories\Eloquent\\' . $repository . 'Repository'
            );
        });

    }



}