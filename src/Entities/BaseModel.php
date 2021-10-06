<?php

namespace AshimBadrie\LaraCore\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseModel extends Model {

    use HasUnixTimestamps,
        HasUserTrack;

    protected $appends = ['created_by_name', 'modified_by_name'];

    /**
     * The name of the "created_on" column.
     */
    const CREATED_ON = 'created_on';

    /**
     * The name of the "modified_on" column.
     */
    const MODIFIED_ON = 'modified_on';

    /**
     * The name of the "created_by" column.
     */
    const CREATED_BY = 'created_by';

    /**
     * The name of the "modified_by" column.
     */
    const MODIFIED_BY = 'modified_by';

    /**
     * Turns off laravel default timestamp usage.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Default lookup field.
     *
     * @return String
     */
    abstract protected function defaultLookupField(): String;

    /**
     * Whether the record is new or not.
     *
     * @return bool
     */
    protected function isNew() {
        if ($this->id) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function save(array $options = []) {
        $this->touchUnixTimestamp();
        $this->touchUserTrack();

        return parent::save($options);
    }

    /**
     * Loads a paged list.
     *
     * @param $start
     * @param $limit
     * @param $sort
     * @param $search
     * @param bool $countOnly
     *
     * @return array
     */
    public function getPage($start, $limit, $sort, $search, $countOnly = FALSE) {

        $records = ['page' => [], 'total' => 0];
        $result = null;

        // Alias for primary table
        $primaryAlias = 'r';

        $query = DB::table($this->table . ' AS ' . $primaryAlias);
        $query->select([$primaryAlias . '.id']);

        // Filters
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                if (is_array($value)) {
                    if (isset($value['clause'])) {}
                    else {
                        $query->where($value['field'], $value['operator'], $value['value']);
                    }
                }
            }
        }

        // Count the amount of records in the table
        $recordCount = $query->count();
        $records['total'] = intval($recordCount);

        // Sorting
        if (!empty($sort)) {}
        else {
            $query->orderBy($primaryAlias . '.id', 'desc');
        }

        // Paging
        $query->skip($start);
        $query->take($limit);

        // Execute query if no counting
        if (!$countOnly) {
            $result = $query->get();
        }

        // Load results
        if (!empty($result)) {
            foreach ($result as $row) {
                $record = $this::find($row->id);
                $records['page'][] = $record->toArray();
            }
        }

        return $records;
    }

    /**
     * Looks up records based on search term.
     *
     * @param $terms
     *
     * @return \Illuminate\Support\Collection
     */
    public function lookup($terms) {
        $records = DB::table($this->table)
            ->where($this->defaultLookupField(), 'LIKE', '%' . $terms . '%')
            ->get();

        return $records;
    }

    /**
     * Gets created by name.
     *
     * @return string
     */
    public function getCreatedByNameAttribute() {
        $name = '';

        // Resolve name
        if (!empty($this->created_by)) {
            $user = User::find($this->created_by);
            $name = $user->name;
        }

        return $name;
    }

    /**
     * Gets modified by name.
     *
     * @return string
     */
    public function getModifiedByNameAttribute() {
        $name = '';

        // Resolve name
        if (!empty($this->modified_by)) {
            $user = User::find($this->modified_by);
            $name = $user->name;
        }

        return $name;
    }

}
