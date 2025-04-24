<?php

// Trieda Student predstavuje študenta
class Student {
    private $name; // Meno študenta
    private $db;   // Pripojenie k databáze

    // Konštruktor triedy Student
    public function __construct($name) {
        $this->name = $name; // Nastavenie mena študenta
        $this->db = HelperMySQLi::connect(); // Pripojenie k databáze pomocou HelperMySQLi
    }

    // Statická metóda na získanie alebo vytvorenie študenta
    public static function getOrCreateStudent($name) {
        // Predpokladáme, že máme pripojenie k databáze cez HelperMySQLi
        $db = HelperMySQLi::connect();

        // Skontrolujeme, či študent už existuje
        $result = HelperMySQLi::checkIfStudentExists($name, $db);

        if ($result->num_rows > 0) {
            // Ak študent existuje, vrátime jeho údaje
            return $result->fetch_assoc();
        } else {
            // Ak študent neexistuje, vytvoríme nového študenta pomocou HelperMySQLi
            $insertStmt = HelperMySQLi::createStudent($name, $db);

            // Vrátime údaje o novo vytvorenom študentovi
            return [
                'id' => $insertStmt->insert_id, // ID nového študenta
                'name' => $name // Meno nového študenta
            ];
        }
    }
}
