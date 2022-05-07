<?php
/*

Da fare:


    - grafico per i voti che i singoli candidati hanno ottenuto
 */

$servername = "localhost";  // Connessione al DB
$username = "root";
$password = "";
$db_name = "db_elezioni";

// Create connection
$conn = new mysqli($servername, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

$queryDataElettore = "SELECT COUNT(*) AS NumeroVoti, scheda.CodicePartito, Nome FROM scheda INNER JOIN partito ON scheda.CodicePartito = partito.CodicePartito GROUP BY CodicePartito";
$result = $conn->query($queryDataElettore);

$nomiPartiti = array();
$codiciPartiti = array();
$numeroVoti = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        array_push($nomiPartiti, $row['Nome']);
        array_push($codiciPartiti, $row['CodicePartito']);
        array_push($numeroVoti, $row['NumeroVoti']);
    }
} else {
    echo 'Nessun risultato trovato';
}

echo print_r($nomiPartiti);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafici elezioni</title>
    <link rel="stylesheet" href="./dist/output.css">
</head>

<body>
    <div class="shadow-lg rounded-lg overflow-hidden w-1/3">
        <div class="py-3 px-5 bg-gray-50">Numero voti per Partito</div>
        <canvas class="p-10" id="chartPie"></canvas>
    </div>

    <!-- Required chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart pie -->
    <script>
        function getRandomColor(n) {
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            var colors = [];
            for (var j = 0; j < n; j++) {
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                colors.push(color);
                color = '#';
            }
            return colors;
        }

        const dataPie = {
            labels: <?php echo json_encode($nomiPartiti) ?>, //["JavaScript", "Python", "Ruby"],
            datasets: [{
                label: "My First Dataset",
                data: <?php echo json_encode($numeroVoti) ?>, //[300, 50, 100],
                backgroundColor: getRandomColor(<?php echo count($numeroVoti)?>),
                hoverOffset: 4,
            }, ],
        };

        const configPie = {
            type: "pie",
            data: dataPie,
            options: {},
        };

        var chartBar = new Chart(document.getElementById("chartPie"), configPie);
    </script>



</body>

</html>