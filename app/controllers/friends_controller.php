<?php

/*
An example of a controller method that uses the $parameters property of the controller class to create a friend object
*/

class FriendsController extends ApplicationController {

    // GET /friends/bobjones/johnsmith
    function view_friend() {

        $user = new user($this->parameters['user']);
        $other_user = new user($this->parameters['friend']);
        $this->friend = new friend($user->username,$other_user->username);
        render('friend');
    }

}
