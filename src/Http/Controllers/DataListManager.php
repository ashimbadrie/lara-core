<?php

namespace AshimBadrie\LaraCore\Http\Controllers;


class DataListManager implements IDataListManager {

    protected $model;

    public function __construct($model) {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function all() {
        $records = $this->model::all();

        return $records->toArray();
    }

    /**
     * @inheritDoc
     */
    public function lookup($terms) {
        $record = new $this->model();
        $records = $record->lookup($terms);

        return $records->toArray();
    }

    /**
     * @inheritDoc
     */
    public function page($data) {
        $filterBy = array();
        $sortBy = array();
        $countOnly = FALSE;

        if (isset($data['filter_by'])) $filterBy = $data['filter_by'];
        if (isset($data['sort_by'])) $sortBy = $data['sort_by'];
        if (!empty($data['count_only'])) $countOnly = TRUE;

        $record = new $this->model();
        $result = $record->getPage($data['start'], $data['limit'], $sortBy, $filterBy, $countOnly);

        return $result;
    }

}
