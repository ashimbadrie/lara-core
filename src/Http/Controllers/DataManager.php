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
