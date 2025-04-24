<?php
class Helper {
    // Statická metóda na zoskupenie príchodov podľa dátumu
    public static function groupByDate($arrivals) {
        $grouped = [];  // Inicializácia prázdneho poľa pre zoskupené príchody

        foreach ($arrivals as $arrival) {
            $date = date('Y-m-d', strtotime($arrival['time'])); // Získanie dátumu vo formáte rok-mesiac-deň
            if (!isset($grouped[$date])) { // Kontrola, či už existuje záznam pre daný dátum
                $grouped[$date] = []; // Ak nie, inicializujeme prázdne pole
            }
            $grouped[$date][] = $arrival; // Pridanie príchodu do zoskupeného poľa
        }

        return $grouped; // Vrátenie zoskupeného poľa príchodov
    }
}
