<?php

namespace AshimBadrie\LaraCore\Entities;

abstract class BaseDataModel extends BaseModel {

    /**
     * @inheritDoc
     */
    public function save(array $options = []) {
        $this->touchUnixTimestamp();
        $this->touchUserTrack();

        return parent::save($options);
    }

}
