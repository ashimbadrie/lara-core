<?php

namespace AshimBadrie\LaraCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    /**
     * Data manager.
     *
     * @var \AshimBadrie\LaraCore\Http\Controllers\IDataManager
     */
    protected $manager;

    /**
     * Request input validators.
     *
     * @var array
     */
    protected $validators = [];

    public function __construct(IDataManager $manager) {
        $this->manager = $manager;
    }

    public function show($id) {
        $result = $this->manager->get($id);

        return response()->json($result, 200);
    }

    public function store(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, $this->validators);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $result = $this->manager->create($input);

        return response()->json($result, 200);
    }

    public function update(Request $request, $id) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $result = $this->manager->update($input, $id);

        return response()->json($result, 200);
    }

    public function destroy($id) {
        $result = $this->manager->delete($id);

        return response()->json($result, 200);
    }

}
