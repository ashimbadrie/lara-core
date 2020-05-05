<?php

namespace AshimBadrie\LaraCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class DataController extends Controller {

    protected $manager;

    public function __construct(IDataManager $manager) {
        $this->manager = $manager;
    }

    public function index() {
        $result = $this->manager->all();

        return response()->json($result, 200);
    }

    public function lookup($terms) {
        $records = $this->manager->lookup($terms);

        return response()->json($records, 200);
    }

    public function page(Request $request) {
        $result = $this->manager->page($request->all());

        return response()->json($result, 200);
    }

    public function show($id) {
        $result = $this->manager->get($id);

        return response()->json($result, 200);
    }

    public function destroy($id) {
        $result = $this->manager->delete($id);

        return response()->json($result, 200);
    }

}
