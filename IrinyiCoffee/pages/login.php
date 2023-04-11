<?php
// Amennyiben a felhasználó be van jelentkezve, átirányítjuk a bemutatkozás oldalra
if (isset($_SESSION["username"]))
    header("Location: index.php?page=about");

// Az összes regisztrált felhasználót ebben a változóban tároljuk el
$users = load_data("data/users.json");

$errors = [];

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (trim($username) === "")
        $errors[] = "empty_username";

    if (trim($password) === "")
        $errors[] = "empty_password";

    if (count($errors) === 0) {

        // Ellenőrizzük le, hogy a belépési adatok helyesek-e! Amennyiben igen, akkor jelentkeztessük be a felhasználót
        // (hozzunk létre egy "username" nevű munkamenet változót, amely a bejelentkezett felhasználó felhasználónevét
        // tárolja el), és irányítsuk át a felhasználót a bemutatkozás oldalra!

        foreach ($users["users"] as $user) {
            // A password_verify() függvénnyel hasonlíthatunk össze egy szöveges jelszót egy hashelt jelszóval
            if ($user["username"] === $username && password_verify($password, $user["password"])) {
                $_SESSION["username"] = $username;
                header("Location: index.php?page=about");
            } else
                $errors[] = "invalid_user";
        }
    }
}
?>
<section id="login">
    <div class="form-container">
        <img src="img/profile.png" alt="profile.png" class="profile-icon">

        <!-- Amennyiben a felhasználónév - jelszó páros érvénytelen, kiiratjuk a hibát -->
        <?php
        if (in_array("invalid_user", $errors)) {
            ?>
            <div class="alert error">
                Hibás felhasználónév vagy jelszó!
            </div>
            <?php
        }
        ?>

        <form method="post" autocomplete="off">
            <label for="username-label">Felhasználónév:</label>
            <input type="text" name="username" id="username-label" class="login-outline-off">
            <div class="error">
                <?php
                // A bejelentkezés során előforduló, felhasználónév mezővel kapcsolatos hibákat kiiratjuk
                if (in_array("empty_username", $errors))
                    echo "A mező kitöltése kötelező!";
                ?>
            </div>

            <label for="password-label">Jelszó:</label>
            <input type="password" name="password" id="password-label" class="login-outline-off">
            <div class="error">
                <?php
                // A bejelentkezés során előforduló, jelszó mezővel kapcsolatos hibákat kiiratjuk
                if (in_array("empty_password", $errors))
                    echo "A mező kitöltése kötelező!";
                ?>
            </div>

            <input type="submit" name="login" value="Bejelentkezés">
            Nincs még fiókod? <a href="index.php?page=register">Regisztráció</a>
        </form>
    </div>
</section>