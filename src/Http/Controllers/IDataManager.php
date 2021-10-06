<?php

namespace AshimBadrie\LaraCore\Http\Controllers;


interface IDataManager {

    /**
     * Get record.
     *
     * @param $id
     *
     * @return mixed
     */
    public function get($id);

    /**
     * Create record.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data);

    /**
     * Update records.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function update($data, $id);

    /**
     * Save record.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function save($data, $id = null);

    /**
     * Delete record.
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id);

}
