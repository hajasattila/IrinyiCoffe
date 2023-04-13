<header>
    <h1>Admin felület</h1>
    <p>Foglalások áttekintése</p>
</header>


<div class="main-content">
    <form method="post">
        <select id="options" name="select" onchange="">
            <option value="actual" <?php if (isset($_POST['select']) && $_POST['select'] == 'actual')
                echo 'selected'; ?>>
                Aktuális
            </option>
            <option value="despired" <?php if (isset($_POST['select']) && $_POST['select'] == 'despired')
                echo 'selected'; ?>>Lejárt
            </option>
            <option value="all" <?php if (isset($_POST['select']) && $_POST['select'] == 'all')
                echo 'selected'; ?>>Összes
            </option>
        </select>
        <input type="submit" id="gomb" value="Szűrés">
    </form>
    <?php
    global $selected_option;

    if (isset($_POST['select'])) {
        $selected_option = $_POST['select'];
        // your code to filter data based on the selected option
    }
    $current_date = date('Y-m-d');

    $file = "data/foglalas.json";
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    if (!$data) {
        echo '<p style="color:black;margin:0 auto;justify-content: center;text-align: center;align-items: center;font-size:2rem;margin-bottom:20rem;">Nincsenek foglalások.</p>';
    } else {
        $sort_by = 'datum';
        $sort_order = SORT_ASC;
        $url = '?sort_by=' . $sort_by . '&sort_order=asc';
    }
    if (isset($_GET['sort_by'])) {
        $sort_by = $_GET['sort_by'];

        if ($sort_by == 'ido') {
            $sort_by = array('datum', 'ido');
        }

        if (isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc') {
            $sort_order = SORT_DESC;
            $url = '?sort_by=' . $sort_by . '&sort_order=asc';
        } else {
            $sort_order = SORT_ASC;
            $url = '?sort_by=' . $sort_by . '&sort_order=desc';
        }
    }

    $sorted_data = array();
    foreach ($data as $key => $value) {
        $sorted_data[$key] = $value[$sort_by];
    }

    array_multisort($sorted_data, $sort_order, $data);
    ?>
    <!-- HTML Code for the table -->
    <table id="admintable">
        <thead>
            <tr>
                <th>Személy</th>
                <th>Email</th>
                <th>Dátum</th>
                <th>Időpont</th>
                <th>Fő</th>
                <th>Méret</th>
                <th>Hely</th>
                <th>Megjegyzés</th>
            </tr>
        </thead>
        <tbody>
            <?php
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
                        echo '<td>' . $row
                        ['ido'] . '</td>';
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
                    echo '<td>' . $row
                    ['ido'] . '</td>';
                    echo '<td>' . $row['emberekszama'] . '</td>';
                    echo '<td>' . $row['tipus'] . '</td>';
                    echo '<td>' . $row['hely'] . '</td>';
                    echo '<td>' . $row['komment'] . '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>