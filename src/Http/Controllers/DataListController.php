<?php

namespace AshimBadrie\LaraCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DataListController extends Controller {

    protected $manager;

    public function __construct(IDataListManager $manager) {
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

}
