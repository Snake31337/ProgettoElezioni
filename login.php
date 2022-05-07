<?php

session_start();
if (isset($_SESSION['PIN'])) {
  session_destroy();  // Se esiste già un PIN salvato nella sessione, viene distrutto
}
if (isset($_POST['PIN'])) { // Controllo se il PIN è stato inserito dalla pagina di login

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

  $pin = mysqli_real_escape_string($conn, $_POST['PIN']);
  $query = "SELECT * FROM elettore WHERE PIN='$pin'";
  $select_elettore_result = $conn->query($query);

  if ($select_elettore_result->num_rows == 1) {
    $_SESSION['PIN'] = $pin;
    while ($row = $select_elettore_result->fetch_assoc()) {
      if (isset($row['CodiceScheda'])) {
        $_SESSION['CodiceScheda'] = $row['CodiceScheda'];

        header('Location:scheda.php');
        exit;
      } else {
        header("Location:login.php");
        exit;
      }
    }
  } else {
    header("Location:login.php"); // PIN errato
    exit;
  }
} else {

?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/dist/output.css" rel="stylesheet">
    <title>Login Scheda</title>
  </head>

  <body>
    <?php

    if (isset($_SESSION['errorMessage'])) { ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold"><?php echo $_SESSION['errorMessage'] ?></strong>
      </div>

    <?php
      unset($_SESSION['errorMessage']);
    }

    ?>
    <div class="flex flex-row min-h-screen justify-center items-center">
      <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" action="./login.php" method="POST">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="PIN">
            PIN
          </label>
          <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="PIN" name="PIN" type="password" placeholder="Inserisci il PIN">
        </div>
        <div class="flex items-center justify-between">
          <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            Entra
          </button>
        </div>
      </form>
    </div>
  </body>

  </html>

<?php
}
?>