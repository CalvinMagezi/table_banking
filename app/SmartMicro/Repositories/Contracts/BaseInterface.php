<?php


namespace  App\SmartMicro\Repositories\Contracts;

/**
 * Interface BaseInterface
 * @package App\Sproose\Repositories\Contracts
 */

interface BaseInterface {

    /**
     * Fetch a collection of records for a given entity
     * @param array $load
     * @return mixed
     */
    function getAll($load = array());

    /**
     * Fetch a single item by its id
     * @param $id
     * @return mixed
     */
    function getById($id);

    /**
     * Fetch multiple specified orders
     * @param array $ids comma separated list of uuids to fetch for
     * @param array $load
     * @return mixed
     */
    function getByIds($ids = array(), $load = array());


    /**
     * @param $field
     * @param $value
     * @param array $load
     * @return mixed
     */
    function getWhere($field, $value, $load = array());

    /**
     * @param $field
     * @param array $values
     * @param array $load
     * @return mixed
     */
    function getManyWhere($field, $values = array(), $load = array());

    /**
     * @param array $load
     * @param $filters
     * @param array $pagination
     * @return mixed
     */
    function getFiltered($load = array(), $filters, $pagination = array());

    /**
     * Create a new record
     * @param array $data
     * @return mixed
     */
    function create(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function firstOrCreate(array $data);

    /**
     * Update existing record
     * @param array $data
     * @param $uuid
     * @return mixed
     */
    function update(array $data, $uuid);

    /**
     * Remove record from db
     * @param $id
     * @return mixed
     */
    function delete($id);

    /**
     * get the first record from the db
     * @return mixed
     */
    function first();


    function generateRefNumber($data = array());

    public function calculateOrderTotal($id);

    function updateSettings();

    function confirm($confirmation_code);


}