<?php

/*
An example of a simple controller using views
*/

class ProjectsController extends ApplicationController {

    // GET /projects
    function index() {

    }

    // GET /projects/1234
    function view($project) {
        $this->project = $project;
        render('project');
    }

    // GET /projects/1234/delete
    function delete($project) {
        //This is just an example, so it doesn't actually delete anything
        $project->delete();
        header('Location: /projects');
    }

    // GET //projects/1234/items/567
    function view_item($project,$item) {
        $this->project = $project;
        $this->item = $item;
        render('item');
    }

}
