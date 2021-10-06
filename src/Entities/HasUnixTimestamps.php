<?php

namespace AshimBadrie\LaraCore\Entities;


trait HasUnixTimestamps {

    /**
     * Indicates if the model should be unix timestamped.
     *
     * @var bool
     */
    public $unixTimestamps = true;

    /**
     * Update the model's update unix timestamp.
     *
     * @return bool
     */
    public function touchUnixTimestamp() {
        if (! $this->usesUnixTimestamps()) {
            return false;
        }

        $this->updateUnixTimestamps();

        return true;
    }

    /**
     * Update the creation and update unix timestamps.
     *
     * @return void
     */
    public function updateUnixTimestamps() {
        $time = $this->freshUnixTimestamp();

        if (! is_null(static::MODIFIED_ON) && ! $this->isDirty(static::MODIFIED_ON)) {
            $this->setModifiedOn($time);
        }

        if (! $this->exists && ! is_null(static::CREATED_ON) &&
            ! $this->isDirty(static::CREATED_ON)) {
            $this->setCreatedOn($time);
        }
    }

    /**
     * Gets a fresh unix timestamp for the model.
     *
     * @return int
     */
    public function freshUnixTimestamp() {
        return time();
    }

    /**
     * Determine if the model uses unix timestamps.
     *
     * @return bool
     */
    public function usesUnixTimestamps() {
        return $this->unixTimestamps;
    }

    /**
     * Set the value of the "created on" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setCreatedOn($value) {
        $this->{static::CREATED_ON} = $value;

        return $this;
    }

    /**
     * Set the value of the "modified on" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setModifiedOn($value) {
        $this->{static::MODIFIED_ON} = $value;

        return $this;
    }

    /**
     * Get the name of the "created on" column.
     *
     * @return string
     */
    public function getCreatedOnColumn() {
        return static::CREATED_ON;
    }

    /**
     * Get the name of the "modified on" column.
     *
     * @return string
     */
    public function getModifiedOnColumn() {
        return static::MODIFIED_ON;
    }

}
