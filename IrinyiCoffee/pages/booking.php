<header>
    <h1>Foglalj asztalt!</h1>
    <p>Ne habozz, és foglalj minél hamarabb időpontot!</p>
</header>

<div class="main-content anim">
    <?php
    error_reporting(0);
    $users = json_decode(file_get_contents('data/foglalas.json'), true);

    // Amennyiben a felhasználó be van jelentkezve, átirányítjuk a bemutatkozás oldalra
    
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $people = $_POST['people'];
        $tableType = $_POST['table-type'];
        $location = $_POST['location'];
        $comments = $_POST['comments'];

        $reservationData = array(
            'nev' => $_SESSION["username"],
            'email' => $email,
            'datum' => $date,
            'ido' => $time,
            'emberekszama' => $people,
            'tipus' => $tableType,
            'hely' => $location,
            'komment' => $comments
        );
        $users[] = $reservationData;

        file_put_contents('data/foglalas.json', json_encode($users));

        echo '<script>alert("Sikeres foglalás!");window.location.href = "index.php?page=main";</script>';
    }

    ?>
    <form method="post">
        <label for="name">Név:</label>
        <input type="text" id="name" name="name" placeholder="Minta Péter" value="<?php echo $_SESSION["username"] ?>"
            disabled><br>
        <label for="email">Email:</label>
        <?php
        error_reporting(0);
        // Betöltjük a felhasználók adatait tartalmazó JSON fájlt
        $json = file_get_contents("data/users.json");
        // Átalakítjuk tömbbé
        $users = json_decode($json, true);

        // Végigmegyünk a felhasználókon
        foreach ($users as $user) {
            // Ha a felhasználónév megegyezik a bejelentkezett felhasználónévvel
            if ($user['username'] === $_SESSION['username']) {
                // Beillesztjük a megfelelő e-mail címet
        
                echo '<input type="email" id="email" name="email" placeholder="mintapeter@gmail.com" value="' . $user['email'] . '" required><br>';
                break; // kilépünk a ciklusból
            }
        }
        ?>


        <?php
        // jelenlegi dátum és idő lekérdezése
        $now = new DateTime();

        // minimális dátum és idő kiszámítása (a jelenlegi időponthoz 30 percet hozzáadunk)
        $minTime = $now->add(new DateInterval('PT30M'))->format('H:i');

        // ha a minimális időpont már a következő napon van, akkor a minimális dátumot is a következő napra kell állítani
        if ($minTime < $now->format('H:i')) {
            $minDate = $now->add(new DateInterval('P1D'))->format('Y-m-d');
        } else {
            $minDate = $now->format('Y-m-d');
        }

        // űrlap megjelenítése
        ?>
        <label for="date">Foglalás dátuma:</label>
        <input type="date" id="date" name="date" value="<?php echo $minDate; ?>" min="<?php echo $minDate; ?>"
            required><br>

        <label for="time">Időpont:</label>
        <input type="time" id="time" name="time" value="<?php echo $minTime; ?>" required><br>


        <label for="people">Hány főre?:</label>
        <input type="number" id="people" name="people" min="1" max="10" placeholder="1-10" value="2" required><br>

        <fieldset>
            <legend>Asztal igények:</legend>
            <label for="table-type">Asztal típus:</label>
            <select id="table-type" name="table-type">
                <option value="small">Kicsi</option>
                <option value="medium">Közepes</option>
                <option value="large">Nagy</option>
            </select>
            <br>

            <label id="location">Hely:</label>
            <div class="flex">
                <label for="inside">Beltéri</label>
                <input type="radio" id="inside" name="location" value="inside" checked>
            </div>
            <div class="flex">
                <label for="outside">Kültéri</label>
                <input type="radio" id="outside" name="location" value="outside">
            </div>

            <textarea id="comments" name="comments" placeholder="Megjegyzés..." style="resize: vertical;"></textarea>
        </fieldset>
        <input type="submit" value="Asztalfoglalás">
        <input type="reset" value="Visszaállítás">
    </form>
</div>