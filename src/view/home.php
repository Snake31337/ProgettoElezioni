<?php
/*

Da fare:
    - aggiungere partitii senza voti nel grafico

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

$queryDataElettore = "SELECT COUNT(*) AS NumeroVoti, scheda.CodicePartito, Nome FROM scheda INNER JOIN partito ON scheda.CodicePartito = partito.CodicePartito GROUP BY CodicePartito";
$result = $conn->query($queryDataElettore);

$nomiPartiti = array();
$codiciPartiti = array();
$numeroVotiPartito = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        array_push($nomiPartiti, $row['Nome']);
        array_push($codiciPartiti, $row['CodicePartito']);
        array_push($numeroVotiPartito, $row['NumeroVoti']);
    }
} else {
    echo 'Nessun risultato trovato';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elezioni 2022</title>
    <link rel="stylesheet" href="../style/output.css">
    <link rel="stylesheet" href="../style/home.css">
</head>

<body>
    <nav class="flex bg-gray-100 h-20">
        <div class="ml-10 flex items-center w-4/5">
            <a href="./home.php" class="text-2xl font-semibold">Elezioni 2022</a>
        </div>
        <div class="flex justify-end items-center w-1/5 mr-10">
            <a href="./login.php" class="text-lg mr-10 text-blue-700 hover:bg-blue-100">Login</a>
            <a href="./nuovoElettore.php" class="text-lg text-blue-700 hover:bg-blue-100">Amministrazione</a>
        </div>
    </nav>

    <div class="flex mt-5 justify-center flex-wrap flex-row">
        <div class="shadow-lg rounded-lg overflow-hidden w-1/3 mr-3 bg-white">
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
                    label: "Numero voti per partito",
                    data: <?php echo json_encode($numeroVotiPartito) ?>, //[300, 50, 100],
                    backgroundColor: getRandomColor(<?php echo count($numeroVotiPartito) ?>),
                    hoverOffset: 4,
                }, ],
            };

            const configPie = {
                type: "pie",
                data: dataPie,
                options: {
                    plugins: {}
                },
            };

            var chartBar = new Chart(document.getElementById("chartPie"), configPie);
        </script>


        <div class="shadow-lg rounded-lg overflow-hidden w-1/2 bg-white">
            <div class="py-3 px-5 bg-gray-50">Numero voti per candidato</div>
            <canvas class="p-10" id="barChart"></canvas>
        </div>

        <?php
        $queryDataCandidato = "SELECT COUNT(*) AS NumeroVoti, Nome, Cognome, candidato.CodiceCandidato FROM candidato INNER JOIN vota ON candidato.CodiceCandidato = vota.CodiceCandidato GROUP BY candidato.CodiceCandidato";
        $result = $conn->query($queryDataCandidato);

        $nomiCandidati = array();
        $codiciCandidati = array();
        $numeroVotiCandidato = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                array_push($nomiCandidati, $row['Nome'] . ' ' . $row['Cognome']);
                array_push($codiciCandidati, $row['CodiceCandidato']);
                array_push($numeroVotiCandidato, $row['NumeroVoti']);
            }
        } else {
            echo 'Nessun risultato trovato';
        }

        ?>

        <script>
            const labels = <?php echo json_encode($nomiCandidati) ?>;
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Numero voti per candidato',
                    data: <?php echo json_encode($numeroVotiCandidato) ?>,
                    backgroundColor: getRandomColor(<?php echo count($numeroVotiCandidato) ?>),
                    borderWidth: 1,
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    indexAxis: 'y',
                },
            };

            var barChart = new Chart(document.getElementById("barChart"), config);
        </script>



    </div>
</body>

</html>