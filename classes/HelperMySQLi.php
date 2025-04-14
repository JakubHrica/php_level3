<?php

class HelperMySQLi {
    private static $conn;

    public static function connect() {
        if (!self::$conn) {
            $host = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "php-level3-projekt";

            self::$conn = new mysqli($host, $username, $password, $dbname);

            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }

        return self::$conn;
    }

    public static function getStudentByName($name) {
        $conn = self::connect();
        $stmt = $conn->prepare("SELECT * FROM students WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function insertStudent($name) {
        $conn = self::connect();
        $stmt = $conn->prepare("INSERT INTO students (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }

    public static function insertArrival($name, $time, $late) {
        $conn = self::connect();
        $stmt = $conn->prepare("INSERT INTO arrivals (name, time, late) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $time, $late);
        $stmt->execute();
    }

    public static function getArrivalsByStudent($name) {
        $conn = self::connect();
        $stmt = $conn->prepare("SELECT * FROM arrivals WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function getAllArrivals() {
        $conn = self::connect();
        $sql = "SELECT * FROM arrivals";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
