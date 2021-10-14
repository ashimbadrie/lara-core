# LaraCore

Quickly and easily create CRUD for Laravel API using these handy base classes.

## Installation

```bash
composer require ashimbadrie/lara-core
```

## Pre-Requisites

This package expects your database migrations to include the following required fields. By default, we turned off laravel's timestamp usage and opt-in for our custom create/update UNIX timestamp. We also added the ability to log who created/updated a record once authenticated.

```php

$table->integer('created_by')->nullable()->comment('User who created the record');
$table->integer('created_on')->nullable()->comment('The date of creation in unix timestamp');
$table->integer('modified_by')->nullable()->comment('User who modified the record');
$table->integer('modified_on')->nullable()->comment('The date of modification in unix timestamp');

```

## Creating a Data Model

A *data model* is the entrypoint to creating records in a database table. When a record is saved, the model tracks which *authenticated* user **creates/modifies** a record and also tracks the **UNIX timestamp** for these operations.

Creating a data model is as simple as follows:

```php

class ExampleModel extends BaseModel {

  protected $table = 'example_table';
  
  protected function defaultLookupField(): String
  {
      return '';
  }
  
}

```

To handle basic REST API CRUD operations, we need to create a data manager and data controller that has basic business logic.

```php

class ExampleManager extends DataManager {

  public function __construct()
  {
      $model = "App\Models\ExampleModel"; // Path to the ExampleModel we created above
      parent::__construct($model);
  }
  
}

class ExampleController extends DataController {

  private $exampleManager;

  public function __construct()
  {
      $this->exampleManager = new ExampleManager();
      parent::__construct($this->exampleManager);
  }
  
}

```

Once we have our model, data controller and manager we can now create our API resources to point to the CRUD logic. Inside the routes/api.php file, we add the following

```php

Route::get('examples/{id}', 'ExampleController@show');
Route::post('examples', 'ExampleController@store');
Route::patch('examples/{id}', 'ExampleController@update');
Route::delete('examples/{id}', 'ExampleController@destroy');

```

To have a paged list of records, we need the list manager and controller

```php

class ExampleListManager extends DataListManager
{
    public function __construct()
    {
        $model = "App\Models\Example";
        parent::__construct($model);
    }
}

class ExampleListController extends DataListController
{
    private $exampleListManager; 
    
    public function __construct()
    {
        $this->exampleListManager = new ExampleListManager();
        parent::__construct($this->exampleListManager);
    }
}

```

Once we have our list data controller and manager we can now create our API resources to point to the listing logic. Inside the routes/api.php file, we add the following

```php

Route::post('examples/list', 'ExampleListController@page');
Route::get('examples/list', 'ExampleListController@index');

```
