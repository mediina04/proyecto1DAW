<?php
$protocol = 'http';
$host = $_SERVER['HTTP_HOST'];
$path = '/proyecto1DAW/';

define("default_action", "index");
define("url_base", "$protocol://$host$path");
