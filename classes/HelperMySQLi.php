<?php

class HelperMySQLi {
    private static $conn;
    private $name;
    private $db;

    // Pripojenie k databáze
    public static function connect() {
        if (!self::$conn) {
            $host = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "logovac";

            self::$conn = new mysqli($host, $username, $password, $dbname);
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error); // Ak sa nepodarí pripojiť, ukončí sa skript
            }
        }
        return self::$conn; // Vráti pripojenie k databáze
    }

    // Vloženie príchodu – teraz s využitím name
    public function insertArrival($name, $time, $late) {
        $conn = self::connect(); // Získanie pripojenia k databáze
        $stmt = $conn->prepare("INSERT INTO arrivals (name, time, late) VALUES (?, ?, ?)"); // Pripraví SQL dotaz
        $stmt->bind_param("ssi", $name, $time, $late); // Naviaže parametre
        $stmt->execute(); // Vykoná dotaz
        $conn->close(); // Zatvorí pripojenie k databáze
    }

    // Načítanie všetkých príchodov pre konkrétneho študenta
    public static function getArrivalsByStudent($name) {
        $conn = self::connect(); // Získanie pripojenia k databáze
        $stmt = $conn->prepare("SELECT * FROM arrivals WHERE name = $name"); // Pripraví SQL dotaz
        $stmt->bind_param("i", $name); // Naviaže parameter
        $stmt->execute(); // Vykoná dotaz
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Vráti výsledky ako asociatívne pole
    }

    // Načítanie všetkých príchodov (používa sa, keď je prihlásený user "all")
    public static function getAllArrivals() {
        $db = HelperMySQLi::connect(); // Získanie pripojenia k databáze
        $query = "SELECT * FROM arrivals ORDER BY time DESC"; // SQL dotaz na načítanie všetkých príchodov
        $result = $db->query($query); // Vykoná dotaz
        return $result->fetch_all(MYSQLI_ASSOC); // Vráti výsledky ako asociatívne pole
    }

    // Načítanie príchodov konkrétneho študenta
    public static function getStudentArrivals($name) {
        $db = self::connect(); // Získanie pripojenia k databáze
        $stmt = $db->prepare("SELECT * FROM arrivals WHERE name = ? ORDER BY time DESC"); // Pripraví SQL dotaz
        $stmt->bind_param("s", $name); // Naviaže parameter
        $stmt->execute(); // Vykoná dotaz
        $result = $stmt->get_result(); // Získa výsledky
        $arrivals = $result->fetch_all(MYSQLI_ASSOC); // Vráti výsledky ako asociatívne pole
        $stmt->close(); // Zatvorí statement
        return $arrivals; // Vráti príchody
    }

    // Kontrola, či študent existuje
    public static function checkIfStudentExists($name, $db) {
        $query = "SELECT * FROM students WHERE name = ?"; // SQL dotaz na kontrolu existencie študenta
        $stmt = $db->prepare($query); // Pripraví dotaz
        $stmt->bind_param("s", $name); // Naviaže parameter
        $stmt->execute(); // Vykoná dotaz
        return $stmt->get_result(); // Vráti výsledky
    }

    // Vytvorenie nového študenta
    public static function createStudent($name, $db) {
        $stmt = $db->prepare("INSERT INTO students (name) VALUES (?)"); // Pripraví SQL dotaz na vloženie študenta
        $stmt->bind_param("s", $name); // Naviaže parameter
        $stmt->execute(); // Vykoná dotaz
        return $stmt; // Vráti statement
    }
}
?>
