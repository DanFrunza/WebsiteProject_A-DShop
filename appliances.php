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
    <section class="category">
    <?php



$sql = "SELECT * FROM produse WHERE categorie = 'Appliances'";
$result = mysqli_query($conn, $sql);


if ($result && mysqli_num_rows($result) > 0) {
    echo "<section class='category'>";

    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<figure>";
        echo "<a href='detalii_produs.php?id=" . $row['id_produs'] . "'>";
        echo "<img src='data:image/jpeg;base64," . base64_encode($row['imagine']) . "' alt='" . $row['nume'] . "'>";
        echo "<figcaption>" . $row['nume'] . "</figcaption>";
        echo "<p>" . $row['descriere'] . "</p>";
        echo "<p>Pret: $" . $row['pret'] . "</p>";
        echo "<p>Id: " . $row['id_produs']. "</p> </a>";
        echo "</figure>";
    }

    echo "</section>";
} else {
   
    echo "<p>Nu există produse în categoria Appliances.</p>";
}


?>
    </section>
</body>
</html>