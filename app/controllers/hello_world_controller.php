<?php

/*
An example of a very simple controller that doesn't use views
*/

class HelloWorldController extends Controller {

    // GET /hello-world
    function index() {
    }

    // GET /hello-world/say-hello
    function say_hello() {
        echo "This is hello_world_controller saying hello!";
    }

    // GET /hello-world/another-action
    function another_action() {
        echo "This is the 'another_action' method for the hello_world_controller";
    }

}
