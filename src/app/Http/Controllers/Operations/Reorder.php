<?php

namespace Sone\CRUD\app\Http\Controllers\Operations;

trait Reorder
{
    /**
     *  Reorder the items in the database using the Nested Set pattern.
     *
     *  Database columns needed: id, parent_id, lft, rgt, depth, name/title
     *
     *  @return Response
     */
    public function reorder()
    {
        $this->crud->hasAccessOrFail('reorder');

        if (! $this->crud->isReorderEnabled()) {
            abort(403, 'Reorder is disabled.');
        }

        // get all results for that entity
        $this->data['entries'] = $this->crud->getEntries();
        $this->data['crud'] = $this->crud;
        $this->data['title'] = trans('sone::crud.reorder').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/sone/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getReorderView(), $this->data);
    }

    /**
     * Save the new order, using the Nested Set pattern.
     *
     * Database columns needed: id, parent_id, lft, rgt, depth, name/title
     *
     * @return
     */
    public function saveReorder()
    {
        $this->crud->hasAccessOrFail('reorder');

        $all_entries = \Request::input('tree');

        if (count($all_entries)) {
            $count = $this->crud->updateTreeOrder($all_entries);
        } else {
            return false;
        }

        return 'success for '.$count.' items';
    }
}
