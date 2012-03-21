<?php

/*
An example of a simple controller using views
*/

class ProjectsController extends ApplicationController {

    // GET /projects
    function index() {
        //require_once("views/projects/index.php");
    }

    // GET /projects/1234
    function view($project) {
        require_once("views/projects/project.php");
    }

    // GET /projects/1234/delete
    function delete($project) {
        //This is just an example, so it doesn't actually delete anything
        $project->delete();
        header("Location: /projects");
    }

    // GET //projects/1234/items/567
    function view_item($project,$item) {
        require_once("views/projects/item.php");
    }

}
