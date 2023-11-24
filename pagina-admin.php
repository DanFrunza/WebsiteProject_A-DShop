<?php
include("database.php");
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>A&DShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Css/style.css">
</head>
<?php
include("header1.php");
?>
<body>
    <h1 class="admin-title">Admin page</h1>
    <h2 class="admin-title">Inserarea si stergerea produselor</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    Daca doriti sa modificati adaugati id-ul produsului. Daca doriti sa adaugati un produs nou, lasati campul id-ului gol:<br>
    <input type="number" name="id_produs" id="id_produs"><br>
    Numele produsului:<br>
    <input type="text" name="nume_produs" id="nume_produs"><br>
    Descrierea produsului:<br>
    <input type="text" name="descriere_produs" id="descriere_produs"><br>
    Selectează imaginea pentru încărcare a produsului:<br>
    <input type="file" name="imagine_produs" id="imagine_produs"><br>
    Pretul produsului:<br>
    <input type="text" name="pret_produs" id="pret_produs"><br>
    Categorie: <br>
    <select id="categorie_produs" name="categorie_produs">
        <option value="electronics">Electronics</option>
        <option value="appliances">Appliances</option>
        <option value="office_supplies">Office Supplies</option>
    </select><br>
    <input type="submit" value="Inserare/modificare produs" name="submit">
</form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produs = filter_input(INPUT_POST, 'id_produs', FILTER_VALIDATE_INT);
    $nume = filter_input(INPUT_POST, "nume_produs", FILTER_SANITIZE_SPECIAL_CHARS);
    $descriere = filter_input(INPUT_POST, "descriere_produs", FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_FILES['imagine_produs']) && !empty($_FILES['imagine_produs']['tmp_name'])) {
        $imagine = file_get_contents($_FILES['imagine_produs']['tmp_name']);
    }
    $pret = filter_input(INPUT_POST, "pret_produs", FILTER_VALIDATE_FLOAT);
    $categorie = filter_input(INPUT_POST, "categorie_produs", FILTER_SANITIZE_SPECIAL_CHARS);

    if ($id_produs !== false && $id_produs !== null) {
        
        $select_sql = "SELECT * FROM produse WHERE id_produs = '$id_produs'";
        $result = mysqli_query($conn, $select_sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            
            if (!empty($nume) && $nume !== $row["nume"]) {
                $update_sql = "UPDATE produse SET nume = '$nume' WHERE id_produs = '$id_produs'";
                mysqli_query($conn, $update_sql);
                echo "<p>Numele produsului a fost modificat.</p>";
            }
            if (!empty($descriere) && $descriere !== $row["descriere"]) {
                $update_sql = "UPDATE produse SET descriere = '$descriere' WHERE id_produs = '$id_produs'";
                mysqli_query($conn, $update_sql);
                echo "<p>Descrierea produsului a fost modificata.</p>";
            }
            if (!empty($pret) && $pret !== $row["pret"]) {
                $update_sql = "UPDATE produse SET pret = '$pret' WHERE id_produs = '$id_produs'";
                mysqli_query($conn, $update_sql);
                echo "<p>Pretul produsului a fost modificat.</p>";
            }
            if (!empty($imagine) && $imagine !== $row["imagine"]) {
                $update_sql = "UPDATE produse SET imagine = ? WHERE id_produs = ?";
                $stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($stmt, "si", $imagine, $id_produs);
                mysqli_stmt_send_long_data($stmt, 0, $imagine);
                mysqli_stmt_execute($stmt);
                echo "<p>Imaginea produsului a fost modificata.</p>";
            }
            if (!empty($categorie) && $categorie !== $row["categorie"]) {
                $update_sql = "UPDATE produse SET categorie = '$categorie' WHERE id_produs = '$id_produs'";
                mysqli_query($conn, $update_sql);
                echo "<p>Categoria produsului a fost modificata.</p>";
            }
        }else{
            echo "<p class='error-message'>Id invalid.</p>";
        }
    } else {
        
        if (!empty($nume) && !empty($imagine) && !empty($pret)) {
            $insert_sql = "INSERT INTO produse (nume, descriere, imagine, pret, categorie) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($stmt, "sssis", $nume, $descriere, $imagine, $pret, $categorie);
            mysqli_stmt_send_long_data($stmt, 2, $imagine);
            mysqli_stmt_execute($stmt);

            echo "<p>Produs adăugat cu succes.</p>";
        } else {
            echo "<p class='error-message'>Nume, imagine și preț sunt necesare pentru a adăuga un produs nou.</p>";
        }
    }
}
?>

    <br><br><br><br>
    <h2 class="admin-title">Stergere produs</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    Daca doriti sa stergeti un produs, adaugatii id-ul:<br>
    <input type="number" name="id_produs1" id="id_produs1"><br>
    <input type="submit" value="Stergere produs" name="submit">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_produs1 = filter_input(INPUT_POST, 'id_produs1', FILTER_VALIDATE_INT);
        if ($id_produs1 !== false && $id_produs1 !== null){
            $sql = "DELETE FROM produse WHERE id_produs = $id_produs1";
            if (mysqli_query($conn, $sql)) {
                if (mysqli_affected_rows($conn) > 0) {
                    echo "Produs șters cu succes.";
                } else {
                    echo "Produsul nu a putut fi șters pentru că nu există în baza de date.";
                }
            } else {
                echo "Produsul nu a putut fi șters: " . mysqli_error($conn);
            }
        }
    }
    ?>
    <br><br><br>


</body>
</html>
