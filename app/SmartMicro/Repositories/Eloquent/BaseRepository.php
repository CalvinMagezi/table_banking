<?php


namespace App\SmartMicro\Repositories\Eloquent;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\Paginator;

/**
 * Class BaseRepository
 * @package App\Sproose\Repositories\Eloquent
 */
abstract class BaseRepository {

    protected $orderBy  = array('created_at', 'desc'), $model, $transformer;


    /**
     * Fetch a single item from db table.
     * Also load with relationships in load
     * @param $uuid
     * @param array $load
     * @return mixed
     */
    public function getById($uuid, $load = array())
    {
        if(!empty($load))
        {
            return $this->model->with($load)->find($uuid);
        }

        return $this->model->find($uuid);

    }

     /**
     * @return mixed
     */
    public function getAllPaginate(){
        return $this->model->paginate();
    }



    /**
     * Get the first record
     * @return mixed
     */
    public function getFirst()
    {
        return $this->model->first();
    }




    /**
     * Fetch multiple specified orders
     * @param array $ids comma separated list of uuids to fetch for
     * @param array $load related data
     * @return mixed
     */
    public function getByIds($ids = array(), $load = array())
    {
        $limit = \Request::input('limit') ?: 10;

        $query =  $this->model->with($load)->whereIn('uuid', $ids);

        $data = $query->paginate($limit);

        return $data;

    }


    /**
     * @param $field
     * @param $value
     * @param array $load
     * @return mixed
     */
    public function getWhere($field, $value, $load = array())
    {
        $data =  $this->model->with($load)->where($field, $value)->orderBy('updated_at', 'desc')->first();
        return $data;
    }

    /**
     * @param $field
     * @param array $values
     * @param array $load
     * @return mixed
     */
    public function getManyWhere($field, $values = array(), $load = array())
    {
        $limit = \Request::input('limit') ?: 10;

        //sort
        $sortDirection = \Request::input('sort_direction') ?: 'ASC';

        if( null!=$this->transformer )
            $sortProperty = $this->transformer->reverse(\Request::input('sort_property'));

        if(isset($sortProperty) && $sortProperty != false)
        {
            $data = $this->model->with($load)->whereIn($field, $values)->orderBy($sortProperty, $sortDirection)->paginate($limit);
        }else
            $data =  $this->model->with($load)->whereIn($field, $values)->paginate($limit);

        return $data;
    }


    /**
     * @param array $load
     * @param $filters
     * @param array $pagination
     * @return mixed
     */
    public function getFiltered($load = array(), $filters, $pagination = array())
    {
        if(isset($pagination) && array_key_exists('limit', $pagination)){
            $limit = $pagination['limit'];
        }else{
            $limit = \Request::input('limit') ?: 10;
        }

        if(isset($pagination) && array_key_exists('page', $pagination)){
            $page = $pagination['page'];
        }else
            $page = 1;

        $data = $this->model->with($load);

        foreach ($filters as $filter) {
            $data = $this->applyFilter($filter, $data);
        }

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $data = $data->paginate($limit);

        return $data;
    }

    /**
     * @param $filter
     * @param $data
     * @return mixed
     */
    private function applyFilter($filter, $data)
    {
        $whereOperators = [
            'eq'   => '=',
            'neq'  => '!=',
            'gt'   => '>',
            'gte'  => '>=',
            'lt'   => '<',
            'lte'  => '<=',
            'like' => 'LIKE',
        ];

        if (array_key_exists($filter['operator'], $whereOperators)) {
            $data = $data->where($filter['field'], $whereOperators[$filter['operator']], $filter['value']);
        }

        if ($filter['operator'] == 'in') {
            $data = $data->whereIn($filter['field'], $filter['value']);
        }

        if ($filter['operator'] == 'notin') {
            $data = $data->whereNotIn($filter['field'], $filter['value']);
        }

        if ($filter['operator'] == 'between') {
            $data = $data->whereBetween($filter['field'], $filter['value']);
        }

        if ($filter['operator'] == 'notbetween') {
            $data = $data->whereNotBetween($filter['field'], $filter['value']);
        }

        return $data;
    }


    /**
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {

        if(null === $data)
            return [
                'error' => true,
                'message' => "No data was found"
            ];
        //return $data;

        try{
            $record = $this->model->create($data);
        }catch (QueryException $e){
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }catch (\Exception $exception){
            return [
                'error' => true,
                'message' => $exception->getMessage()
            ];
        }

        if(!$record){
            return [
                'error' => true,
                'message' => "Unexpected Error"
            ];
        }

        return [
            'error' => false,
            'message' => $record
        ];

    }


    /**
     * @param array $data
     * @return mixed
     */
    public function firstOrCreate(array $data)
    {
       // return $data;
        return $this->model->firstOrCreate($data);
    }

    /**
     * @param array $data
     * @param $uuid
     * @return array
     */
    public function update(array $data, $uuid)
    {
        if(null === $data)
            return [
                'error' => true,
                'message' => "No data was found"
            ];

        try{
            $record = $this->model->find($uuid);
            if(null === $record)
                return [
                    'error' => true,
                    'message' => "Item not found. Check item id provided."
                ];

            $record->update($data);

        }catch (QueryException $e){
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

        if(!$record){
            return [
                'error' => true,
                'message' => "Unexpected Error"
            ];
        }

        return [
            'error' => false,
            'message' => $record
        ];

    }

    /**
     * Remove a record from db
     * @param $uuid
     * @return bool
     */
    public function delete($uuid)
    {
        $record = $this->model->find($uuid);

        if(is_null($record)){
            return false;
        }

        elseif($record->destroy($uuid)){
            return true;
        }

        return false;
    }

    /**
     * @param array $load
     * @return mixed
     */

    public function first($load = array())
    {
        if(!empty($load))
        {
            return $this->model->with($load)->first();
        }

        return $this->model->first();
    }

    /**
     * Count the number of specified model records in the database
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Sets how the results are sorted
     * @param string $field The field being sorted
     * @param string $direction The direction to sort (ASC or DESC)
     * @return EloquentFooRepository The current instance
     */
    public function sortBy($field, $direction = 'DESC')
    {
        $direction = (strtoupper($direction) == 'ASC') ? 'ASC' : 'DESC';
        $this->orderBy = array($field, $direction);

        return $this;
    }

    public function generateRefNumber($data = array()){}
    public function calculateOrderTotal($id){}

    public function updateSettings(){}

    public function confirm($code){}
}