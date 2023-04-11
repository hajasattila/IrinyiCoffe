<?php
// Egy függvény, amely a második paraméterben kapott adattömb elemeit elkódolja JSON formátumba és elmenti az első paraméterben
// kapott elérési útvonalon található fájlba.

function save_data(string $filename, array $data)
{
    $users = load_data($filename);

    $users["users"][] = $data;

    file_put_contents($filename, json_encode($users, JSON_PRETTY_PRINT));
}

// Egy függvény, amely a paraméterben kapott elérési útvonalon található fájlból beolvassa az adatokat.
// A függvény visszatérési értéke egy tömb, ami a PHP értékké alakított (más szóval dekódolt) adatokat tárolja.

function load_data(string $filename): array
{
    if (!file_exists($filename))
        die("Nem sikerült a fájl megnyitása!");

    $json = file_get_contents($filename);

    return json_decode($json, true);
}
?>