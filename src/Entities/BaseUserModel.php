<?php

namespace AshimBadrie\LaraCore\Entities;

abstract class BaseUserModel extends BaseModel {

    /**
     * The default roles associated with the model.
     *
     * @return mixed
     */
    abstract function defaultRoles();

}
