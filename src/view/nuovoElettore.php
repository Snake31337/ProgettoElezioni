<?php

session_start();
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


if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
  if (isset($_POST['sesso'])) {
    $sesso = mysqli_real_escape_string($conn, $_POST['sesso']);
  } else {
    $_SESSION['errorMessage'] = "Attenzione! Non hai completato il form. Riprova";
    header("Location:nuovoElettore.php");
    exit;
  }
  if (isset($_POST['eta'])) {
    $eta = mysqli_real_escape_string($conn, $_POST['eta']);
  } else {
    $_SESSION['errorMessage'] = "Attenzione! Non hai completato il form. Riprova";
    header("Location:nuovoElettore.php");
    exit;
  }
  if (isset($_POST['seggio'])) {
    $seggio = mysqli_real_escape_string($conn, $_POST['seggio']);
  } else {
    $_SESSION['errorMessage'] = "Attenzione! Non hai completato il form. Riprova";
    header("Location:nuovoElettore.php");
    exit;
  }

  /*if (isset($sesso) && isset($eta) && isset($seggio)) {
    $query = "INSERT INTO scheda (CodiceSeggio) VALUES('$seggio') SELECT @@IDENTITY";

  }*/
}


/*
else {
  $_SESSION['errorMessage'] = "Non hai scelto il sesso";
}*/
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/output.css">
  <title>Crea nuovo elettore</title>
</head>

<body>
  <?php

  if (isset($_SESSION['errorMessage'])) { ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold"><?php echo $_SESSION['errorMessage'] ?></strong>
    </div>

  <?php
    unset($_SESSION['errorMessage']);
  } ?>
  <div class="flex flex-row min-h-screen justify-center items-center">
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" action="./nuovoElettore.php" method="POST">
      <label class="block text-gray-700 text-sm font-bold mb-2" aling="left" for="sesso">
        Sesso:
      </label>
      <div class="flex mb-4" aling="left">
        <div class="form-check form-check-inline">
          <input class="form-check-input form-check-input appearance-none rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="radio" name="sesso" value="M">
          <label class="form-check-label inline-block text-gray-800" for="M">M</label>
        </div>
        <div class="form-check form-check-inline ml-5">
          <input class="form-check-input form-check-input appearance-none rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="radio" name="sesso" value="F">
          <label class="form-check-label inline-block text-gray-800" for="F">F</label>
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="eta">
          Età
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" min="18" name="eta" placeholder="Inserisci l'età dell'elettore">
      </div>
      <div class="mb-4">
        <div class="flex justify-center">
          <div class="mb-3 xl:w-96">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="seggio">
              Seggio
            </label>
            <select class="form-select appearance-none shadow
      block
      w-full
      px-3
      py-1.5
      text-base
      font-normal
      text-gray-700
      bg-white bg-clip-padding bg-no-repeat
      border border-solid
      rounded
      transition
      ease-in-out
      m-0
      focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" name="seggio" aria-label="Default select example">
              <option value="">Seleziona il seggio</option>
              <?php

              $query = "SELECT * FROM seggio";
              $result = $conn->query($query);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
              ?>
                  <option value="<?php echo $row['CodiceSeggio'] ?>"><?php echo $row['Indirizzo'] . " | " . $row['Citta'] . " | " . $row['CAP'] ?></option>
              <?php
                }
              }

              ?>

            </select>
          </div>
        </div>
      </div>
      <div class="flex items-center justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
          Crea elettore
        </button>
      </div>
      <div class="mb-4 mt-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="PIN">
          PIN
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="pin" type="password" placeholder="Inserisci il PIN">
      </div>
    </form>
  </div>
</body>

</html>