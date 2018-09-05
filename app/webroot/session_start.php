<?php
    if(App::import('Core','Session')) { 
     $session = new CakeSession(); 
     $session->start(); 
    }
?>