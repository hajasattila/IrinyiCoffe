<head>
    <style>
        .box {
            max-width: 100%;
            overflow-x: auto;
            margin: 0 auto;
            justify-content: center;
            text-align: center;
            align-items: center;
        }

        th,
        td {
            justify-content: center;
            text-align: center;
            align-items: center;
            color: black;
        }

        #options {
            color: black;
            text-align: center;
            width: 25%;
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        form {
            background-color: transparent;
        }

        #gomb {
            justify-content: center;
            text-align: center;
            margin: 0 auto;
            align-items: center;
            display: flex;
        }

        footer {
            display: none;
        }
    </style>

</head>

<body>
    <header>
        <h1>Admin felület</h1>
        <p>Foglalások áttekintése</p>
    </header>


    <main>
        <div class="main-content">
            <form method="post">
                <select id="options" name="select" onchange="">
                    <option value="actual" <?php if (isset($_POST['select']) && $_POST['select'] == 'actual')
                        echo 'selected'; ?>>Aktuális
                    </option>
                    <option value="despired" <?php if (isset($_POST['select']) && $_POST['select'] == 'despired')
                        echo 'selected'; ?>>Lejárt
                    </option>
                    <option value="all" <?php if (isset($_POST['select']) && $_POST['select'] == 'all')
                        echo 'selected'; ?>>Összes</option>
                </select>
                <input type="submit" id="gomb" value="Szűrés">
            </form>
            <?php
            if (isset($_POST['select'])) {
                $selected_option = $_POST['select'];
                // your code to filter data based on the selected option
            }
            /* Jelnlegi dátum */
            $current_date = date('Y-m-d');
            // betöltjük a json fájlt
            $file = "data/foglalas.json";
            $json = file_get_contents($file);
            $data = json_decode($json, true);

            // Ha nincsenek foglalások, kiírjuk
            if (!$data) {
                echo '<p style="color:black;margin:0 auto;justify-content: center;text-align: center;align-items: center;font-size:2rem;margin-bottom:20rem;">Nincsenek foglalások.</p>';
            } else {
                // Ha vannak foglalások, akkor létrehozzuk a táblázatot és feltöltjük az adatokkal
                $sort_by = 'datum'; // Alapértelmezett rendezési szempont
                $sort_order = SORT_ASC; // Alapértelmezett rendezési irány
                if (isset($_GET['sort_by'])) {
                    // Ha meg van adva rendezési szempont, akkor az alapján rendezzük az adatokat
                    $sort_by = $_GET['sort_by'];
                    if ($sort_by == 'ido') {
                        $sort_by = array('datum', 'ido'); // Dátum és idő szerinti rendezés
                    }
                    if (isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc') {
                        // Ha az irány csökkenő, akkor azt állítjuk be
                        $sort_order = SORT_DESC;
                        // Módosítjuk az URL-t a növekvő irányra
                        $url = '?sort_by=' . $sort_by . '&sort_order=asc';
                    } else {
                        // Ha az irány növekvő, akkor azt állítjuk be
                        $sort_order = SORT_ASC;
                        // Módosítjuk az URL-t a csökkenő irányra
                        $url = '?sort_by=' . $sort_by . '&sort_order=desc';
                    }
                } else {
                    // Ha nincs rendezési szempont megadva, akkor az alapértelmezett URL-t állítjuk be
                    $url = '?sort_by=' . $sort_by . '&sort_order=asc';
                }

                // Rendezzük az adatokat
                $sorted_data = array();
                foreach ($data as $key => $value) {
                    $sorted_data[$key]
                        = $value[$sort_by];
                }
                array_multisort($sorted_data, $sort_order, $data); // Létrehozzuk a táblázatot és feltöltjük az adatokkal
                echo '<table>';
                echo '<thead>';
                echo '<tr>';

                echo '<th>Személy</th>';
                echo '<th>Email</th>';
                echo '<th>Dátum</a></th>';
                echo '<th>Időpont</a></th>';
                echo '<th>Fő</th>';
                echo '<th>Hely</th>';
                echo '<th>Megjegyzés</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                if ($selected_option === 'actual') {
                    foreach ($data as $row) {
                        if ($row['datum'] >= $current_date) {
                            echo '<tr>';
                            echo '<td>' . $row['nev'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo '<td>' . $row['datum'] . '</td>';
                            echo '<td>' . $row['ido'] . '</td>';
                            echo '<td>' . $row['emberekszama'] . '</td>';
                            echo '<td>' . $row['tipus'] . '</td>';
                            echo '<td>' . $row['hely'] . '</td>';
                            echo '<td>' . $row['komment'] . '</td>';
                            echo '</tr>';
                        }
                    }
                } else if ($selected_option === "despired") {
                    foreach ($data as $row) {
                        if ($row['datum'] < $current_date) {
                            echo '<tr>';
                            echo '<td>' . $row['nev'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo '<td>' . $row['datum'] . '</td>';
                            echo '<td>' . $row['ido'] . '</td>';
                            echo '<td>' . $row['emberekszama'] . '</td>';
                            echo '<td>' . $row['tipus'] . '</td>';
                            echo '<td>' . $row['hely'] . '</td>';
                            echo '<td>' . $row['komment'] . '</td>';
                            echo '</tr>';
                        }
                    }
                } else {
                    foreach ($data as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['nev'] . '</td>';
                        echo '<td>' . $row['email'] . '</td>';
                        echo '<td>' . $row['datum'] . '</td>';
                        echo '<td>' . $row['ido'] . '</td>';
                        echo '<td>' . $row['emberekszama'] . '</td>';
                        echo '<td>' . $row['tipus'] . '</td>';
                        echo '<td>' . $row['hely'] . '</td>';
                        echo '<td>' . $row['komment'] . '</td>';
                        echo '</tr>';

                    }
                    echo '</tbody>';
                    echo '</table>';
                }
            }
            ?>
        </div>
    </main>
</body>