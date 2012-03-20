<?php

/*
An example of a controller method that uses the $parameters property of the controller class to create a friend object
*/

class FriendsController extends Сontroller {


    function __construct() {
        Сontroller::__construct();
    }

    // GET /friends/bobjones/johnsmith
    function view_friend() {

        $user = new user($this->parameters['user']);
        $other_user = new user($this->parameters['friend']);
        $friend = new friend($user->username,$other_user->username);
        require_once("views/friends/friend.php");
    }

}
