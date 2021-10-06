<?php

namespace AshimBadrie\LaraCore\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManager implements IDataManager {

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

        // If no email was sent with the request then we generate one
        // Ensure there is a unique email if it was sent with the request
        if (empty($data['email'])) {
            $data['email'] = User::generateUniqueEmail();
        }
        else {
            $user = User::loadByEmail($data['email']);
            if ($user) {
                abort(422, 'A user with the specified username already exists');
            }
        }

        $result = DB::transaction(function () use ($data) {
            $email = $data['email'];

            $userData = array(
                'name' => $email,
                'password' => Hash::make($data['password']),
                'email' => $email
            );

            if (isset($data['user_roles'])) {
                $userData['user_roles'] = $data['user_roles'];
            }
            else {
                $object = new $this->model();
                $userData['user_roles'] = $object->defaultRoles();
            }

            $userAccount = new User($userData);
            $userAccount->save();

            $userAccount->saveRoles($userData['user_roles']);
            if (!$userAccount) {
                abort(422, 'Unable to create account');
            }

            $data['uid'] = $userAccount->id;
            $object = new $this->model($data);
            $object->save();

            return $object->toArray();
        });

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function update($data, $id) {

        // If no email was sent with the request then we generate one
        // Ensure there is a unique email if it was sent with the request
        $user = User::loadByEmail($data['email']);
        if ($user && ($user->id != $data['uid'])) {
            abort(422, 'A user with the specified email already exists');
        }

        $result = DB::transaction(function () use ($data, $id) {
            $email = $data['email'];

            $userData = array(
                'id' => $data['uid'],
                'name' => $email,
                'email' => $email
            );

            if (isset($input['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            if (isset($input['user_roles'])) {
                $userData['user_roles'] = $data['user_roles'];
            }
            else {
                $object = new $this->model();
                $userData['user_roles'] = $object->defaultRoles();
            }

            // Update user account details
            $userAccount = User::find($data['uid']);
            $userAccount->update($userData);
            $userAccount->saveRoles($userData['user_roles']);
            if (!$userAccount) {
                abort(422, 'Unable to create account');
            }

            $data['uid'] = $userAccount->id;
            $object = $this->model::find($data['id']);
            $object->update($data);

            return $object->toArray();
        });

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function save($data, $id = NULL) {
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
        if ($id == 1) abort(422, 'You cannot delete the administrator of this application.');

        $result = DB::transaction(function () use ($id) {
            // Remove the record
            $record = $this->model::find($id);
            $record->delete();

            return [];
        });

        return $result;
    }
}
