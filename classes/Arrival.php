<?php

require_once 'HelperMySQLi.php';

class Arrival {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function logArrival() {
        $time = date('Y-m-d H:i:s');
        $hour = (int)date('H');
        $minute = (int)date('i');
        $late = ($hour > 8 || ($hour === 8 && $minute > 0)) ? 1 : 0;

        HelperMySQLi::insertArrival($this->name, $time, $late);
    }
}
