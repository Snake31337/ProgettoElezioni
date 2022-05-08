<?php


/*

Da fare:

Controlla se esiste già il voto delle preferenze e dei partiti
  * controllo partiti già fatto riga 40

Fare un errore quando l'utente non sceglie un partito




*/

session_start();
if (!isset($_SESSION['PIN'])) { // Controllo se l'utente ha inserito il PIN dalla pagina login altrimenti lo rimando su login.php
  $_SESSION['errorMessage'] = "Hai fatto accesso alla pagina scheda.php senza inserire il PIN";
  header("Location:login.php");
  exit;
} else {  // Altrimenti se il pin è settato, procedo con la connessione al database
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

  $CodiceScheda = $_SESSION['CodiceScheda'];  // ottengo il CodiceScheda dal session

  $queryCheckVoto = "SELECT CodiceScheda FROM scheda WHERE (CodiceScheda='$CodiceScheda') AND (CodicePartito IS NOT NULL) "; // Verifico se il voto esiste già
  $result = $conn->query($queryCheckVoto);

  if ($result->num_rows == 1) {  // Controllo se esiste già una scheda con un voto assegnato, se sì reindirizzo l'utente su pagina dove ci sono i dati
    header("Location:data.php");
    exit;
  }

  echo "<br>CodiceScheda:" . $CodiceScheda;
}

if (isset($_POST['sceltaPartito'])) {  // Ottieni il dato di quale partito è stato scelto
  $codicePartito = mysqli_real_escape_string($conn, $_POST['sceltaPartito']);

  echo "<br>CodicePartito: " . $codicePartito;

  $queryCheckPartito = "SELECT CodicePartito FROM partito WHERE CodicePartito='$codicePartito'"; // Controllo se esiste il partito scelto
  $result = $conn->query($queryCheckPartito);

  if ($result->num_rows != 1) {
    $_SESSION['errorMessage'] = "Il partito inserito non esiste";
    header("Location:scheda.php");
    exit;
  }

  if (isset($_POST['pref1-' . $codicePartito]) && !empty($_POST['pref1-' . $codicePartito])) { // Controllo se la prima preferenza è stata impostata e se non è di valore default
    $preferenza1 = mysqli_real_escape_string($conn, $_POST['pref1-' . $codicePartito]); // CodiceCandidato preferenza 1
    $queryInsertPreferenza1 = "INSERT INTO vota (CodiceScheda, CodiceCandidato) VALUES ($CodiceScheda, $preferenza1)";

    $queryCheckCandidato = "SELECT CodicePartito FROM candidato WHERE CodiceCandidato='$preferenza1'";   // Controllo se il candidato inserto appartiene al partito che è stato scelto
    $result = $conn->query($queryCheckCandidato);

    if ($result->num_rows == 1) {
      while ($row = $result->fetch_assoc()) {
        if ($row["CodicePartito"] != $codicePartito) {
          $_SESSION['errorMessage'] = "Il primo candidato inserito non appartiene al partito scelto";
          header("Location:scheda.php");  // Il candidato inserito non appartiene al partito scelto
          exit;
        }
      }
    } else {
      $_SESSION['errorMessage'] = "Non esiste il primo candidato scelto";
      header("Location:scheda.php"); // Non esiste il candidato scelto
      exit;
    }

    echo "<br>Preferenza 1: " . $preferenza1;
  }
  if (isset($_POST['pref2-' . $codicePartito]) && !empty($_POST['pref2-' . $codicePartito])) { // Controllo se la seconda preferenza è stata impostata e se non è di valore default
    $preferenza2 = mysqli_real_escape_string($conn, $_POST['pref2-' . $codicePartito]); // CodiceCandidato preferenza 2
    $queryInsertPreferenza2 = "INSERT INTO vota (CodiceScheda, CodiceCandidato) VALUES ($CodiceScheda, $preferenza2)";

    $queryCheckCandidato = "SELECT CodicePartito FROM candidato WHERE CodiceCandidato='$preferenza2'";   // Controllo se il candidato inserto appartiene al partito che è stato scelto
    $result = $conn->query($queryCheckCandidato);

    if ($result->num_rows == 1) {
      while ($row = $result->fetch_assoc()) {
        if ($row["CodicePartito"] != $codicePartito) {
          $_SESSION['errorMessage'] = "Il secondo candidato inserito non appartiene al partito scelto";
          header("Location:scheda.php");  // Il candidato inserito non appartiene al partito scelto
          exit;
        }
      }
    } else {
      $_SESSION['errorMessage'] = "Non esiste il secondo candidato scelto";
      header("Location:scheda.php"); // Non esiste il candidato scelto
      exit;
    }

    echo "<br>Preferenza2: " . $preferenza2;
  }

  if (isset($preferenza1) && isset($preferenza2) && ($preferenza1 == $preferenza2)) {  // Controllo se le preferenze sono state inserite e non sono vuote (valore default = "")
    $_SESSION['errorMessage'] = "Non si possono inserire due preferenze uguali";
    header("Location:scheda.php");  // Non si possono inserire due preferenze uguali
    exit;
  }

  if (isset($preferenza1)) {
    if (mysqli_query($conn, $queryInsertPreferenza1)) { // Inserisco nel database la prima preferenza
      echo "Preferenza inserita correttamente";
    } else {
      echo "Non è stato possibile inserire la preferenza";
    }
  }

  if (isset($preferenza2)) {
    if (mysqli_query($conn, $queryInsertPreferenza2)) {   // Inserisco nel database la seconda preferenza
      echo "Preferenza inserita correttamente";
    } else {
      echo "Non è stato possibile inserire la preferenza";
    }
  }

  $querySetPartito = "UPDATE scheda SET CodicePartito = '$codicePartito' WHERE CodiceScheda = $CodiceScheda";

  if (mysqli_query($conn, $querySetPartito)) {
    echo "Tabella scheda aggiornata correttamente";
  } else {
    echo "Errore nell'aggiornamento della tabella scheda";
  }
} 

if(!isset($_POST['sceltaPartito'])){
  echo "Non è stato scelto un partito";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../style/output.css" rel="stylesheet">
  <title>Scheda Elettorale</title>
</head>

<body>
  <?php

  if (isset($_SESSION['errorMessage'])) {?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold"><?php echo $_SESSION['errorMessage']?></strong>
    </div>

  <?php
    unset($_SESSION['errorMessage']);
  }

  $queryPartito = "SELECT * FROM Partito";
  $resultQueryPartito = $conn->query($queryPartito);

  /*if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      echo "Nome:" . $row["Nome"];
    }
  } else {
    echo "0 results";
  }*/
  //$conn->close();
  ?>
  <div class="gap-2 columns-xl">
    <form action="./scheda.php" method="POST">
      <?php
      if ($resultQueryPartito->num_rows > 0) {
        // output data of each row
        while ($rowQueryPartito = $resultQueryPartito->fetch_assoc()) {
          $codicePartito = $rowQueryPartito["CodicePartito"];
      ?>
          <div class="flex flex-row p-6 rounded-lg shadow-lg bg-white max-w mb-2">
            <div class="form-check">
              <input class="form-check-input appearance-none rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="radio" name="sceltaPartito" value="<?php echo $codicePartito ?>">
              <label class="form-check-label inline-block text-gray-800" for="sceltaPartito">
              </label>
            </div>
            <img class="object-scale-down w-32" src="https://upload.wikimedia.org/wikipedia/commons/f/fb/Partito_comunista_logo.png" alt="">
            <div class="ml-5">
              <h5 class="text-grey-900 text-xl leading-tight font-medium mb-2"><?php echo $rowQueryPartito["Nome"]; ?></h5>
              <div class="flex justify-center">
                <div class="mb-3 xl:w-96">
                  <select name="pref1-<?php echo $codicePartito; ?>" class="form-select appearance-none
                  block
                  w-full
                  px-3
                  py-1.5
                  text-base
                  font-normal
                  text-gray-700
                  bg-white bg-clip-padding bg-no-repeat
                  border border-solid border-gray-300
                  rounded
                  transition
                  ease-in-out
                  m-0
                  focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example">
                    <option value="">Scegli la prima preferenza</option>
                    <?php
                    $queryCandidato = "SELECT CodiceCandidato, Nome, Cognome
                  FROM candidato
                  WHERE CodicePartito=$codicePartito";
                    $resultQueryCandidato = $conn->query($queryCandidato);
                    if ($resultQueryCandidato->num_rows > 0) {
                      // output data of each row
                      while ($rowQueryCandidato = $resultQueryCandidato->fetch_assoc()) {

                        $codiceCandidato = $rowQueryCandidato["CodiceCandidato"];
                    ?>
                        <option value="<?php echo $codiceCandidato ?>"><?php echo $rowQueryCandidato["Nome"] . " " . $rowQueryCandidato["Cognome"] ?></option>
                    <?php
                      }
                    } else {
                      echo "0 results";
                    }

                    ?>
                  </select>
                </div>
              </div>
              <div class="flex justify-center">
                <div class="mb-3 xl:w-96">
                  <select name="pref2-<?php echo $codicePartito; ?>" class="form-select appearance-none
                  block
                  w-full
                  px-3
                  py-1.5
                  text-base
                  font-normal
                  text-gray-700
                  bg-white bg-clip-padding bg-no-repeat
                  border border-solid border-gray-300
                  rounded
                  transition
                  ease-in-out
                  m-0
                  focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example">
                    <option value="">Scegli la seconda preferenza</option>
                    <?php
                    $queryCandidato = "SELECT CodiceCandidato, Nome, Cognome
                  FROM candidato
                  WHERE CodicePartito=$codicePartito";
                    $resultQueryCandidato = $conn->query($queryCandidato);
                    if ($resultQueryCandidato->num_rows > 0) {
                      // output data of each row
                      while ($rowQueryCandidato = $resultQueryCandidato->fetch_assoc()) {

                        $codiceCandidato = $rowQueryCandidato["CodiceCandidato"];
                    ?>
                        <option value="<?php echo $codiceCandidato; ?>"> <?php echo $rowQueryCandidato["Nome"] . " " . $rowQueryCandidato["Cognome"] ?></option>
                    <?php
                      }
                    } else {
                      echo "0 results";
                    }

                    ?>
                  </select>
                </div>
              </div>
              <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">
                Invia
              </button>
            </div>
          </div>
      <?php
        }
      } else {
        echo "0 results";
      }

      ?>
    </form>
  </div>


</body>

</html>