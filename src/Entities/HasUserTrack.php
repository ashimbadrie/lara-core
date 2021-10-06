<?php

namespace AshimBadrie\LaraCore\Entities;


use Illuminate\Support\Facades\Auth;

trait HasUserTrack {

    /**
     * Indicates if the model should be tracked by user.
     *
     * @var bool
     */
    public $userTrack = true;

    /**
     * Update the model's update unix timestamp.
     *
     * @return bool
     */
    public function touchUserTrack() {
        if (! $this->usesUserTrack()) {
            return false;
        }

        $this->updateUserTrack();

        return true;
    }

    /**
     * Update the created by and modified by fields.
     *
     * @return void
     */
    public function updateUserTrack() {
        $userID = $this->userIdentifier();

        if (! is_null(static::MODIFIED_BY) && ! $this->isDirty(static::MODIFIED_BY)) {
            $this->setModifiedBy($userID);
        }

        if (! $this->exists && ! is_null(static::CREATED_BY) &&
            ! $this->isDirty(static::CREATED_BY)) {
            $this->setCreatedBy($userID);
        }
    }

    /**
     * Gets the identifier of the user who is creating/updating the model.
     *
     * @return int
     */
    public function userIdentifier() {
        $user = Auth::user();
        if ($user) {
            return $user->id;
        }

        return null;
    }

    /**
     * Determine if the model tracks user create/update.
     *
     * @return bool
     */
    public function usesUserTrack() {
        return $this->userTrack;
    }

    /**
     * Set the value of the "created by" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setCreatedBy($value) {
        $this->{static::CREATED_BY} = $value;

        return $this;
    }

    /**
     * Set the value of the "modified by" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setModifiedBy($value) {
        $this->{static::MODIFIED_BY} = $value;

        return $this;
    }

    /**
     * Get the name of the "created by" column.
     *
     * @return string
     */
    public function getCreatedByColumn() {
        return static::CREATED_BY;
    }

    /**
     * Get the name of the "modified by" column.
     *
     * @return string
     */
    public function getModifiedByColumn() {
        return static::MODIFIED_BY;
    }

}
