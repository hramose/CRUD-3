<?php

namespace Sone\CRUD\Tests\Unit\CrudPanel;

use Sone\CRUD\CrudPanel;
use Sone\CRUD\Tests\BaseTest;
use Sone\CRUD\Tests\Unit\Models\TestModel;

abstract class BaseCrudPanelTest extends BaseTest
{
    /**
     * @var CrudPanel
     */
    protected $crudPanel;

    protected $model;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->crudPanel = new CrudPanel();
        $this->crudPanel->setModel(TestModel::class);
        $this->model = $this->crudPanel->getModel();
    }
}
