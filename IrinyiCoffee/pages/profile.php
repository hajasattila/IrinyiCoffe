<header>
    <h1>Nézd át, vagy szerkeszd az adataid!</h1>
</header>

<div class="main-content anim">
    <div class="iras">
        <h1>Profilom</h1>
        <p></p>
    </div>
    <div class="card">
        <div class="profile-image">
            <?php
            // Betöltjük a felhasználók adatait tartalmazó JSON fájlt
            $json = file_get_contents("data/users.json");
            // Átalakítjuk tömbbé
            $users = json_decode($json, true);

            // Ellenőrizzük, hogy a felhasználó feltöltött-e új profilképet
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
                // Ellenőrizzük, hogy a képfájl valóban képet tartalmaz-e
                error_reporting(0);
                $check = getimagesize($_FILES["profile-picture"]["tmp_name"]);
                if ($check !== false) {
                    // Töröljük az összes korábban feltöltött képet a felhasználó neve alapján
                    foreach (glob("uploads/" . $_SESSION['username'] . ".*") as $file) {
                        unlink($file);
                    }
                    // A feltöltött fájl egy kép, elmentjük a "uploads" mappába a felhasználó neve alapján
                    echo '<p style="color:green;">Frissítettük a képed!</p>';
                    move_uploaded_file($_FILES["profile-picture"]["tmp_name"], "uploads/" . $_SESSION['username'] . ".png");
                } else {
                    echo '<p style="color:red;">Hiba történt a kép feltöltésekor.</p>';
                }
            }

            // Ellenőrizzük, hogy a felhasználónak van-e profilképe
            if (file_exists("uploads/" . $_SESSION['username'] . ".png")) {
                // Ha van, akkor megjelenítjük
                echo '<img src="uploads/' . htmlspecialchars($_SESSION['username'], ENT_QUOTES) . '.png" alt="' . htmlspecialchars($_SESSION['username'], ENT_QUOTES) . ' képe" title="' . htmlspecialchars($_SESSION['username'], ENT_QUOTES) . ' felhasználó képe" class="profile-img">';
            }
            ?>
            <?php if (!file_exists("uploads/" . $_SESSION['username'] . ".png")): ?>
                <!-- Ha nincs korábban feltöltött kép, akkor az alapértelmezett képet jelenítjük meg -->
                <img src="https://cdn-icons-png.flaticon.com/512/219/219983.png"
                    alt="<?php echo $_SESSION['username']; ?> képe" title="<?php echo $_SESSION['username']; ?> képe"
                    class="profile-img">
            <?php endif; ?>
            <div class="image-upload">
                <form method="POST" enctype="multipart/form-data">
                    <label for="profile-picture">
                        <i class="fa fa-camera"></i>
                    </label>
                    <input type="file" name="profile-picture" id="profile-picture">
                    <input type="submit" value="Feltöltés" name="submit">
                </form>
            </div>
        </div>
        <h1>Üdvözlünk!<br>
            <span id="userspan">
                <?php echo $_SESSION['username']; ?>
            </span>
        </h1>
        <div class="email">
            <p class="title">Email cím:</p>
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
                    echo '<p id="emailHelye">' . $user['email'] . '</p>';
                    break; // kilépünk a ciklusból
                }
            }
            ?>
        </div>
        <div class="username">
            <p class="title">Felhasználónév</p>
            <p>
                <?php echo $_SESSION['username']; ?>
            </p>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_password"])) {
            // Betöltjük a felhasználók adatait tartalmazó JSON fájlt
            $json = file_get_contents("data/users.json");
            // Átalakítjuk tömbbé
            $users = json_decode($json, true);

            // Végigmegyünk a felhasználókon
            foreach ($users as &$user) {
                // Ha a felhasználónév megegyezik a bejelentkezett felhasználónévvel
                if ($user['username'] === $_SESSION['username']) {
                    // Ellenőrizzük, hogy a jelszó legalább 6 karakter hosszú és van benne legalább 1 betű
                    if (strlen($_POST['new_password']) < 6 || !preg_match("/[a-zA-Z]/", $_POST['new_password'])) {
                        echo '<p class="error">A jelszónak legalább 6 karakterből és legalább 1 betűből kell állnia!</p>';
                    } else {
                        // Ellenőrizzük, hogy a megadott jelszó megegyezik-e a megerősítéssel
                        if ($_POST['new_password'] !== $_POST['confirm_password']) {
                            echo '<p class="error">A jelszó és a megerősítés nem egyezik!</p>';
                        } else {
                            // Hasheljük az új jelszót
                            $new_password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                            // Frissítjük a felhasználó jelszavát
                            $user['password'] = $new_password_hash;
                            // Visszaírjuk a módosított felhasználói adatokat a JSON fájlba
                            file_put_contents("data/users.json", json_encode($users));
                            // Üzenet a felhasználónak a sikeres jelszóváltoztatásról
                            echo "<p>A jelszavad sikeresen megváltoztatva!</p>";
                        }
                    }
                    break; // kilépünk a ciklusból
                }
            }
        }
        ?>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_profile"])) {
            // Betöltjük a felhasználók adatait tartalmazó JSON fájlt
            $json = file_get_contents("data/users.json");
            // Átalakítjuk tömbbé
            $users = json_decode($json, true);


            /* Kép törlése */
            $filename = 'uploads/' . $_SESSION['username'] . '.png'; // a kép neve és elérési útvonala
            if (file_exists($filename)) {
                unlink($filename); // a kép törlése
            }
            // Végigmegyünk a felhasználókon
            foreach ($users as $key => $user) {
                // Ha a felhasználónév megegyezik a bejelentkezett felhasználónévvel
                if ($user['username'] === $_SESSION['username']) {
                    // Töröljük a felhasználót a tömbből
                    unset($users[$key]);
                    // Visszaírjuk a módosított felhasználói adatokat a JSON fájlba
                    file_put_contents("data/users.json", json_encode($users));
                    // Üzenet a felhasználónak a sikeres törlésről
                    echo "<p>A profilod sikeresen törölve!</p>";
                    // Töröljük a bejelentkezési adatokat és átirányítjuk a felhasználót az index oldalra
                    session_unset();
                    session_destroy();
                    echo "<script>window.location.href='index.php?page=profile';</script>";
                    exit;
                }
            }
        }
        ?>
        <form method="POST" id="pwchange">
            <div class="pw">
                <p class="title">Jelszó változtatás</p>
                <input type="password" name="new_password" placeholder="Új jelszó">
                <input type="password" name="confirm_password" placeholder="Jelszó megerősítése">
                <input type="submit" value="Mentés">
            </div>
        </form>
        <a href="index.php?logout" class="logout">Kijelentkezés</a>
        <br><br>
        <?php
        if (isset($_SESSION["username"]) && $_SESSION["username"] !== "admin") {
            echo '<a class="logout er"
          onclick="if (confirm(\'Biztosan szeretnéd törölni a profilodat?\')) { document.getElementById(\'delete-profile\').submit(); }">Profil
          törlése</a>';
        }
        ?>
        <form id="delete-profile" method="POST">
            <input type="hidden" name="delete_profile" value="1">
        </form>
    </div>
</div>