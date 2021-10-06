<?php

namespace AshimBadrie\LaraCore\Http\Controllers;


interface IDataListManager {

    /**
     * Load all records.
     *
     * @return mixed
     */
    public function all();

    /**
     * Search record by terms.
     *
     * @param $terms
     *
     * @return mixed
     */
    public function lookup($terms);

    /**
     * Paginates list.
     *
     * @param $data
     *
     * @return mixed
     */
    public function page($data);

}
