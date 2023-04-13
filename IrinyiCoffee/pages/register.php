<header id="home">
    <h1>Regisztrálj és foglalj asztalt!</h1>
    <p>Pár kattintás, és már kész is!</p>
</header>

<div class="main-content">
    <h1 style="margin-top:5rem; color:red;text-align:center;">
        <strong>Figyelem!</strong> A <span class="required">*</span>-al jelölt mezők kitöltése kötelező!

    </h1>
    <div class="rules">
        <ul>
            <li>
                <p>- A jelszónak 6 karakter hosszúnak kell lennie, <br>és 1 számot tartalmaznia kell!</p>
            </li>
            <li>
                <p>- Foglalt e-maillel, felhasználónévvel nem lehet regisztrálni!</p>
            </li>
            <li>
                <p>- Meg kell egyeznie a jelszavaknak!</p>
            </li>
        </ul>


    </div>

    <?php
    error_reporting(0);
    $users = json_decode(file_get_contents('data/users.json'), true);

    class User
    {
        public $email;
        public $usernameClass;
        public $password;
    }

    // Amennyiben a felhasználó be van jelentkezve, átirányítjuk a bemutatkozás oldalra
    if (isset($_SESSION["username"]) && $_SESSION["username"] !== "admin")
        header("Location: index.php?page=booking");

    $errors = [];

    if (isset($_POST["register"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password_check = $_POST["password-check"];
        $email = $_POST["email"];
        $confirmations = [];

        if (isset($_POST["confirmations"]))
            $confirmations = $_POST["confirmations"];

        if (trim($email) === "")
            $errors[] = "empty_email";

        if (trim($username) === "")
            $errors[] = "empty_username";

        if (trim($password) === "")
            $errors[] = "empty_password";

        if (trim($password_check) === "")
            $errors[] = "empty_check_password";

        if (strlen($username) > 50)
            $errors[] = "long_username";

        // Ellenőrizzük, hogy a megadott felhasználónév létezik-e már
    
        if ($password !== "" && strlen($password) < 5)
            $errors[] = "short_password";

        if ($password !== "" && strlen($password) >= 5 && (!preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)))
            $errors[] = "password_characters";

        if ($password_check !== "" && $password !== $password_check)
            $errors[] = "match_password";

        if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = "invalid_email";

        if (count($confirmations) < 2)
            $errors[] = "empty_confirmations";

        // Ha az űrlapon megadott adatok helyesek, akkor a regisztráció sikeres
        // Ellenőrizzük, hogy a megadott felhasználónév létezik-e már
        $usernames = array_column($users, 'username');
        if (in_array($username, $usernames)) {
            $errors[] = "existing_username";
        }
        $useremail = array_column($users, 'email');
        if (in_array($email, $useremail)) {
            $errors[] = "existing_email";
        }

        if (count($errors) === 0) {
            $password = password_hash($password, PASSWORD_DEFAULT); // Titkosítsuk a jelszót
            // Inicializáljuk a $user változót
            $user = new User();
            // Hozzunk létre egy tömböt, majd rakjuk bele a megadott adatokat
            $user->email = trim($email) !== "" ? $email : "-";
            $user->usernameClass = $username;
            $user->password = $password;
            // Adjuk hozzá az új felhasználót a users tömbhöz
            $users[] = (array) $user;
            // Frissítsük a users.json fájlt
            file_put_contents('data/users.json', json_encode($users));
            // Átirányítjuk a felhasználót a bemutatkozás oldalra
            echo "<script>window.location.href='index.php?page=main';</script>";
        }
    }

    ?>
    <section id="register">
        <div class="form-container">
            <form method="post" autocomplete="off" enctype="multipart/form-data">
                <label for="email-label">E-mail cím:</label>
                <input type="text" name="email" id="email-label" placeholder="valaki@pelda.com" value="<?php if (isset($_POST["email"]))
                    echo $_POST["email"]; ?>">
                <div class="error">
                    <?php
                    if (in_array("invalid_email", $errors))
                        echo "Az e-mail cím formátuma nem megfelelő!";
                    if (in_array("empty_email", $errors))
                        echo "A mező kitöltése kötelező!";
                    if (in_array("existing_email", $errors))
                        echo "Ez az email már foglalt!";
                    ?>


                </div>
                <label for="username-input">Felhasználónév<span class="required">*</span>:</label>
                <input type="text" name="username" id="username-input" maxlength="16" placeholder="MintaPéter" value="<?php if (isset($_POST["username"]))
                    echo htmlspecialchars($_POST["username"]); ?>">
                <div class="error">
                    <?php

                    // A regisztráció során előforduló, felhasználónév mezővel kapcsolatos hibákat kiiratjuk
                    if (in_array("empty_username", $errors))
                        echo "A mező kitöltése kötelező!";
                    if (in_array("long_username", $errors))
                        echo "A felhasználónév maximum 50 karakter lehet!";
                    if (in_array("existing_username", $errors))
                        echo "A felhasználónév már foglalt!";
                    ?>
                </div>

                <label for="password-label">Jelszó<span class="required">*</span>:</label>
                <input type="password" name="password" id="password-label" placeholder="*****">
                <div class="error">
                    <?php
                    if (in_array("empty_password", $errors))
                        echo "A mező kitöltése kötelező!";
                    if (in_array("short_password", $errors))
                        echo "A jelszónak legalább 5 karakter hosszúnak kell lennie!";
                    if (in_array("password_characters", $errors))
                        echo "A jelszónak tartalmaznia kell betűt és számjegyet is!";
                    ?>
                </div>

                <label for="password-check-label">Jelszó ismét<span class="required">*</span>:</label>
                <input type="password" name="password-check" id="password-check-label" placeholder="*****">
                <div class="error">
                    <?php
                    if (in_array("empty_check_password", $errors))
                        echo "A mező kitöltése kötelező!";
                    if (in_array("match_password", $errors))
                        echo "A jelszavak nem egyeznek meg!";
                    ?>
                </div>



                <div class="checkbox-container">
                    <label>
                        <input type="checkbox" name="confirmations[]" value="confirm-data">
                        Nyilatkozom, hogy a megadott adatok a valóságnak megfelelnek.<span class="required">*</span>
                    </label>
                    <label>
                        <input type="checkbox" name="confirmations[]" value="accept-terms-and-conditions">
                        Elfogadom a <a href="index.php?page=cookie" <?php echo $page === "cookie" ? "class=active" : ""; ?>>felhasználási feltételeket</a>.<span class="required">*</span>
                    </label>
                    <div class="error">
                        <?php
                        if (in_array("empty_confirmations", $errors))
                            echo "A jelölőnégyzetek kitöltése kötelező!";
                        ?>
                    </div>
                </div>

                <input type="reset" name="reset" value="Mezők törlése">
                <input type="submit" name="register" value="Regisztráció">
            </form>
        </div>
    </section>
</div>