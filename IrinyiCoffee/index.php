<?php
session_start();

include_once "common/functions.php";

if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    header("Location: index.php?page=main");
}
// A kiválasztott oldal nevét ebben a változóban tároljuk el (ha a $page értéke üres, betöltjük az "about"-ot)
$page = $_GET["page"] ?? "main";

// Ellenőrizzük a kiválasztott oldalt, majd beállítjuk a megfelelő $prefix értéket (a $prefix értékét a címsorban fogjuk kiiratni)
switch ($page) {
    case "main":
        $prefix = "Főoldal";
        break;
    case "about":
        $prefix = "Rólunk";
        break;
    case "coffees":
        $prefix = "Kávéink";
        break;
    case "sandwiches":
        $prefix = "Szendivcseink";
        break;
    case "cakes":
        $prefix = "Süteményeink";
        break;
    case "register":
        $prefix = "Regisztráció";
        break;
    case "cookie":
        $prefix = "Cookie!";
        break;
    case "booking":
        $prefix = "Asztalfoglalás!";
        break;
    case "profile":
        $prefix = "Profilom";
        break;
    case "admin":
        $prefix = "Admin";
        break;
    default:
        header("Location: index.php?page=main");
        break;
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $prefix; ?> | Irinyi Kávézó
    </title>

    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <!-- NAVIGATION START -->
    <nav>
        <div class="logo">
            <img src="images/logo.png" alt="IrinyiKávézó.hu">
        </div>

        <input type="checkbox" id="toggle-nav" class="toggle-nav">
        <label for="toggle-nav" class="hamburger-box">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </label>
        <ul>
            <li>
                <a href="index.php?page=main" <?php echo $page === "main" ? "class=active" : ""; ?>>Főoldal</a>
            </li>
            <li>
                <a href="index.php?page=about" <?php echo $page === "about" ? "class=active" : ""; ?>>Rólunk</a>
            </li>
            <li>
                <a href="index.php?page=coffees" <?php echo $page === "coffees" ? "class=active" : ""; ?>>Kávéink</a>
            </li>
            <li>
                <a href="index.php?page=sandwiches" <?php echo $page === "sandwiches" ? "class=active" : ""; ?>>Szendvicseink</a>
            </li>
            <li>
                <a href="index.php?page=cakes" <?php echo $page === "cakes" ? "class=active" : ""; ?>>Süteményeink</a>
            </li>
            <li>
                <?php
                if (isset($_SESSION["username"])) {
                    ?>
                    <a href="index.php?page=booking" <?php echo $page === "booking" ? "class=active" : ""; ?>>Asztalfoglalás</a>
                    <?php
                }
                ?>
            </li>
            <li>
                <?php
                if (isset($_SESSION["username"]) && $_SESSION["username"] === "admin") {
                    ?>
                    <a href="index.php?page=admin" <?php echo $page === "admin" ? "class=active" : ""; ?>>ADMIN</a>
                    <?php
                }
                ?>
            </li>
            <li>
                <?php
                if (!isset($_SESSION["username"])) {
                    ?>
                    <a id="loginButton1" class="foglalj" <?php echo $page === "login" ? "class=active" : ""; ?>>Bejelentkezés</a>
                    <?php
                } else {
                    ?>
                    <!-- <a href="index.php?logout" class="foglalj">Profilom</a> -->
                    <a href="index.php?page=profile" class="foglalj" <?php echo $page === "profile" ? "class=active" : ""; ?>>Profil</a>
                    <?php
                }
                ?>
            </li>
        </ul>

        <div class="foglalas">
            <?php
            if (!isset($_SESSION["username"])) {
                ?>
                <a id="loginButton" <?php echo $page === "login" ? "class=active" : ""; ?>>Bejelentkezés</a>
                <?php
            } else {
                ?>
                <!-- Logout -->

                <a href="index.php?page=profile" <?php echo $page === "profile" ? "class=active" : ""; ?>>Profil</a>
                <?php
            }
            ?>


        </div>
    </nav>
    <main>
        <!-- COOKIE START -->
        <div class="cookie-policy">
            <div class="cookie-container">
                <div>
                    <h5>Cookie</h5>
                    <p>Weboldalunk sütiket használ a jobb felhasználás érdekében.
                        <a href="index.php?page=cookie" class="tov-inf" target="_self">
                            <?php echo $page === "cookie" ?> További információ↗
                        </a>
                    </p>
                </div>
                <button class="btn right">Elfogadom</button>
            </div>
        </div>
        <!-- COOKIE END -->

        <?php


        // A kiválasztott oldal tartalmát beágyazzuk
        if (strlen($page) == 0) {
            $page = "main";
        }
        if (
            !isset($_SESSION["username"]) && ($page == "booking" || $page == "profile") || (!isset($_SESSION["username"]) && $page == "admin")
            || ($_SESSION["username"] !== "admin" && $page == "admin")
            || (isset($_SESSION["username"]) && $_SESSION["username"] !== "admin" && $page == "register")
        ) {
            $page = "main";
        }

        include_once "pages/$page.php";

        ?>

        <?php
        // Amennyiben a felhasználó be van jelentkezve, átirányítjuk a bemutatkozás oldalra
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
                foreach ($users as $user) {
                    if ($user["username"] === $username && password_verify($password, $user["password"])) {
                        $_SESSION["username"] = $username;
                        echo "<script>window.location.href='index.php?page=profile';</script>";
                    } else {
                        $errors[] = "invalid_user";
                    }
                }
            }
        }
        ?>

        <div id="myModal" class="modal" <?php if (!empty($errors)) {
            echo ' style="display: block"';
        } ?>>
            <div class="modal-content">
                <span class="close">&times;</span>

                <?php
                if (in_array("invalid_user", $errors)) {
                    ?>
                    <div class="error">
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
                    <div class="password-container">
                        <input type="password" name="password" id="password" class="login-outline-off">
                        <i class="fa fa-eye-slash" style="font-size:1.5rem;"></i>
                    </div>
                    <div class="error">
                        <?php
                        // A bejelentkezés során előforduló, jelszó mezővel kapcsolatos hibákat kiiratjuk
                        if (in_array("empty_password", $errors))
                            echo "A mező kitöltése kötelező!";
                        ?>

                    </div>
                    <input type="submit" name="login" value="Bejelentkezés">
                    <p>Nem regisztráltál még? <br><a href=" index.php?page=register" <?php echo $page === "register" ? "class=active" : ""; ?>>Kattints ide!</a></p>
                </form>
            </div>
        </div>
    </main>


    <!-- FOOTER START -->
    <footer>
        <p>Copyright © 2023 Irinyi Kávézó.</p>
    </footer>
    <!-- FOOTER END -->

    <!-- SCRIPTS START -->
    <script src="js/script.js"></script>
    <script src="js/cookie.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/eye.js"></script>
    <!-- SCRIPTS END -->
</body>

</html>