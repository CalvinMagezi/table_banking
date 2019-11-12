<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 31/08/2019
 * Time: 16:15
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $id_tenant = 0;
        if (Auth::check()) {
            $id_tenant = auth('api')->user()->branch_id;
            return $builder->where($model->getTable() . '.branch_id',  '=', $id_tenant);
        }
      //  return $builder->where($model->getTable() . '.branch_id',  '=', $id_tenant);
        return $builder;
    }
}