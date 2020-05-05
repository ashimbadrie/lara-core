<?php

namespace AshimBadrie\LaraCore\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;

class DataManager implements IDataManager {

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

    /**
     * @inheritDoc
     */
    public function get($id) {
        $record = $this->model::find($id);

        if (is_null($record)) {
            throw new Exception("Unable to find record.");
        }

        return $record->toArray();
    }

    /**
     * @inheritDoc
     */
    public function create($data) {
        $result = DB::transaction(function () use ($data) {
            $record = $this->model::create($data);

            return $record->toArray();
        });

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function update($data, $id) {
        $result = DB::transaction(function () use ($data, $id) {
            $record = $this->model::find($id);
            $record->update($data);

            return $record->toArray();
        });

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function save($data, $id = null) {
        $record = new $this->model($data);
        if ($record->isNew()) {
            return $this->create($data);
        }
        else {
            return $this->update($data, $id);
        }
    }

    /**
     * @inheritDoc
     */
    public function delete($id) {
        $result = DB::transaction(function () use ($id) {
            // Remove the record
            $record = $this->model::find($id);
            $record->delete();

            return [];
        });

        return $result;
    }
}
