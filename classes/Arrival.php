<?php

class Arrival {
    private $name;
    private $db;

    // Konštruktor triedy Arrival, inicializuje meno a pripojenie k databáze
    public function __construct($name) {
        $this->name = $name;
        $this->db = HelperMySQLi::connect(); // Pripojenie do databázy
    }

    // Statická metóda na zaznamenanie príchodu
    public static function logArrival($name) {
        $time = date('Y-m-d H:i:s'); // Aktuálny čas vo formáte rok-mesiac-deň hodina:minúta:sekunda
        $hour = (int)date('H'); // Aktuálna hodina
        $minute = (int)date('i'); // Aktuálna minúta
        // Kontrola, či je príchod neskorý (po 8:00)
        $late = ($hour > 8 || ($hour === 8 && $minute > 0)) ? 1 : 0;

        $helper = new HelperMySQLi(); // Vytvorenie inštancie pomocnej triedy pre MySQLi
        $helper->insertArrival($name, $time, $late); // Vloženie príchodu do databázy
    }
}
