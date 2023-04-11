<head>
    <style>
        .heading {
            color: #666666;
            text-align: center;
        }

        .container {
            width: 40rem;
            margin: 0 auto;
            justify-content: center;
        }

        @media (max-width: 1200px) {
            .container {
                width: 20rem;
            }
        }

        .button-group {
            margin-bottom: 20px;
        }

        .counter {
            display: inline;
            margin-top: 0;
            margin-bottom: 0;
            margin-right: 10px;
            color: black;
        }

        .posts {
            clear: both;
            list-style: none;
            padding-left: 0;
            width: 100%;
            text-align: left;
        }

        .posts li {
            background-color: #fff;
            border: 1.5px solid #d8d8d8;
            border-radius: 10px;
            padding-top: 10px;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: 10px;
            margin-bottom: 10px;
            word-wrap: break-word;
            min-height: 42px;
            box-shadow: 8px 8px 5px #888888;
        }

        .btn-primary {
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
            transition: 0.3s;
        }

        .btn-primary:hover {
            color: black;
        }

        textarea {
            resize: none;
            background-color: #d8d8d8;
        }

        textarea:focus {
            outline: none;
        }

        form {
            background-color: transparent;
        }
    </style>
</head>

<body>
    <header class="header-about" id="about">
        <h1>Tudj meg többet rólunk!</h1>
        <p>Érdekel kik, és mik vagyunk?</p>
        <blockquote>Lépj be hozzánk!</blockquote>
    </header>

    <!-- MAIN START -->
    <main>

        <div class="main-content anim">
            <div class="row-content-box__wrapper welcome">
                <h1><strong>Üdvözlünk ! </strong></h1>
                <h4>
                    Üdvözöljük kávézónkban! <br>Kávéink mindig frissen pörkölt babokból készülnek, <br>és étlapunkon
                    finom
                    sütiket,
                    szendvicseket és
                    teákat is talál. <br>Várjuk szeretettel!
                    <br>
                    <a href="booking.html">Foglalj most asztalt!</a>
                </h4>
            </div>
            <div class="direct-contact-container">

                <ul>
                    <li class="list-item"><i class="fa fa-map-marker fa-2x">
                            <span class="contact-text place">
                                <a href="#googlemap">Szeged, Tisza Lajos krt. 103, 6725
                                </a>
                            </span>
                        </i>
                    </li>

                    <li class="list-item"><i class="fa fa-phone fa-2x"><span class="contact-text phone">
                                <a href="tel:3662544138" title="Hívj fel!">(06 62) 544 138</a></span></i>
                    </li>

                    <li class="list-item"><i class="fa fa-envelope fa-2x"><span class="contact-text gmail"><a
                                    href="mailto:#irinyikavezo@irinyikavezo.hu"
                                    title="Send me an email">irinyikavezo@irinyikavezo.hu</a></span></i>
                    </li>

                </ul>

                <hr>
                <ul class="social-media-list">
                    <li>
                        <a href="https://www.instagram.com/p/CcnyoHjKIb7/" target="_blank" class="contact-icon">
                            <i class="fa fa-instagram" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/p/CcnyoHjKIb7/" target="_blank" class="contact-icon">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/p/CcnyoHjKIb7/" target="_blank" class="contact-icon">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
                <hr>
            </div>

            <section class="map_sec" id="googlemap">
                <div class="row">
                    <div class="map_inner">
                        <h2>Ha erre jársz, nézz be hozzánk!</h2>
                        <p><strong>Garantált élmény!</strong></p>
                        <div class="map_bind">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2759.1688510840213!2d20.144734515583853!3d46.24687097911799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4744886557ac1407%3A0x8ef6cdceb30443a2!2sSzegedi%20Tudom%C3%A1nyegyetem%20Irinyi%20%C3%A9p%C3%BClet!5e0!3m2!1shu!2shu!4v1678894801693!5m2!1shu!2shu"
                                width="1000" height="600" style="border:0;" allowfullscreen="" aria-hidden="false"
                                tabindex="0"></iframe>
                        </div>
                    </div>
                </div>
            </section>
            <?php
            // Initialize the $comments array
            $comments = array();

            // Load stored comments
            if (file_exists('data/comments.json')) {
                $json = file_get_contents('data/comments.json');
                $comments = json_decode($json, true);
            }

            if (isset($_POST['comment']) && isset($_SESSION['username'])) {
                // The user submitted a comment and is logged in to the session
                $username = $_SESSION['username'];
                $comment = $_POST['comment'];

                // Add the new comment
                $comments[] = array('username' => $username, 'comment' => $comment, 'date' => date('Y-m-d H:i:s'));

                // Save the updated comments to the file
                $json = json_encode($comments);
                file_put_contents('data/comments.json', $json);
            } elseif (isset($_POST['delete-comment']) && isset($_SESSION['username'])) {
                // The user clicked on the "Delete" button for their own comment
                $key = $_POST['delete-comment'];
                if (array_key_exists($key, $comments) && $comments[$key]['username'] === $_SESSION['username']) {
                    // Remove the comment from the array
                    unset($comments[$key]);
                    echo ("Deleted comment $key by user {$_SESSION['username']}");
                    // Save the updated comments to the file
                    $json = json_encode($comments);
                    file_put_contents('data/comments.json', $json);
                }
            } elseif (isset($_POST['delete-comment']) && isset($_SESSION['username']) && $_SESSION['username'] === 'admin') {
                // The admin user clicked on the "Delete" button for another user's comment
                $key = $_POST['delete-comment'];
                if (array_key_exists($key, $comments)) {
                    // Remove the comment from the array
                    unset($comments[$key]);
                    // Save the updated comments to the file
                    $json = json_encode($comments);
                    file_put_contents('data/comments.json', $json);
                }
            }

            $is_logged_in = isset($_SESSION['username']);
            ?>
            <h3 class="heading">Írj véleményt!</h3>
            <div class="container">
                <form method="post">
                    <div class="form-group">
                        <textarea class="form-control status-box" name="comment" rows="3" maxlength="50"
                            placeholder="Mond el a véleményed!"></textarea>
                    </div>
                    <div class="button-group pull-right">
                        <p class="counter">50</p>
                        <script>
                            const textarea = document.querySelector('textarea');
                            const counter = document.querySelector('.counter');
                            const MAX_CHARS = 50;

                            textarea.addEventListener('input', () => {
                                const remainingChars = MAX_CHARS - textarea.value.length;
                                counter.textContent = remainingChars;
                            });
                        </script>
                        <a id="post-btn" class="btn <?php if (!isset($_SESSION['username'])) {
                            echo 'btn-danger" style="background-color: red; cursor: not-allowed;"';
                            echo "disabled";
                        } else {
                            echo "btn-primary";
                        } ?>" onclick="event.preventDefault(); document.querySelector('form').submit();">Post</a>
                    </div>
                </form>
                <ul class="posts">
                    <?php
                    // Megjelenítjük az eltárolt kommenteket
                    foreach ($comments as $key => $comment) {
                        $username = $comment['username'];
                        $date = $comment['date'];
                        $avatar_path = "uploads/" . $username . ".png";
                        if (!file_exists($avatar_path)) {
                            $avatar_path = "https://cdn-icons-png.flaticon.com/512/219/219983.png";
                        }
                        echo '<li><div style="display: flex; justify-content: space-between;"><div><img src="' . $avatar_path . '" alt="' . $username . '" title="' . $username . ' képe" style="width: 50px; height: 50px; border-radius: 50%;"> <strong style="margin-bottom:5rem; display: table-cell;">' . $username . ':</strong></div><div style="text-align: right;"><span>' . $date . '</span>';
                        // Ha felhasználó van bejelentkezve, hozzáadjuk a "Törlés" gombot
                        if (isset($_SESSION['username']) && ($_SESSION['username'] == 'admin' || $_SESSION['username'] == $username)) {
                            echo ' <a style="color:red;font-size:1.2rem;cursor:pointer;" onclick="event.preventDefault(); if (confirm(\'Biztosan törölni akarod a kommentet?\')) { document.querySelector(\'#delete-comment-' . $key . '\').submit(); }">X</a>';
                        }
                        echo '</div></div><div>' . $comment['comment'] . '</div></li>';
                        // Létrehozzuk a "Törlés" gombhoz tartozó formot
                        if (isset($_SESSION['username']) && ($_SESSION['username'] == 'admin' || $_SESSION['username'] == $username)) {
                            echo '<form id="delete-comment-' . $key . '" method="post"><input type="hidden" name="delete-comment" value="' . $key . '"></form>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </main>
    <!-- MAIN END -->

    <!-- SCRIPTS START -->
    <script src="js/script.js"></script>
    <!-- SCRIPTS END -->
</body>