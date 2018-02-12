<?php
define('TYPE','admin');
include('../includes/config.php');
$img = new Captcha(90,25);
$img->getImg();