<?php

/*
An example of a controller method that uses the $parameters property of the controller class to create a friend object
*/

class FriendsController extends ApplicationController {

    // GET /friends/bobjones/johnsmith
    function view_friend() {

        $user = new User($this->parameters['user']);
        $other_user = new User($this->parameters['friend']);
        $this->friend = new Friend($user->username,$other_user->username);
        render('friend');
    }

}
