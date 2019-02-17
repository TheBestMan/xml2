<?php
spl_autoload_register(function ($class) {
    include 'tools/' . $class . '.php';
});


$con = new Xml2PhpClass();
$con->fromFile('payment.xml');
$ex = $con->execute();
if (!$ex) {
    echo "CLASS NOT CREATED";
}
