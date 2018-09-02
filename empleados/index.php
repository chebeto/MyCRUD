
<?php

$textID=(isset($_POST["textID"]))?$_POST["textID"]:"";
$textNombre=(isset($_POST["textNombre"]))?$_POST["textNombre"]:"";
$textApellidoPat=(isset($_POST["textApellidoPat"]))?$_POST["textApellidoPat"]:"";
$textApellidoMat=(isset($_POST["textApellidoMat"]))?$_POST["textApellidoMat"]:"";
$textCorreo=(isset($_POST["textCorreo"]))?$_POST["textCorreo"]:"";
$textIMG=(isset($_POST["textIMG"]))?$_POST["textIMG"]:"";

$accion=(isset($_POST["accion"]))?$_POST["accion"]:"";

include ("../conexion/conexion.php");

switch ($accion){
  case "btnAgregar":
        echo $textID;
        echo "Presionaste Agregar";

        $sentencia = $conn->prepare ("INSERT INTO empleados (Nombre, ApellidoPat, ApellidoMat, Correo, Fotografia)
        VALUES (:Nombre,:ApellidoPat,:ApellidoMat,:Correo,:Fotografia)");

        $sentencia->bindParam(":Nombre", $textNombre);
        $sentencia->bindParam(":ApellidoPat",$textApellidoPat);
        $sentencia->bindParam(":ApellidoMat",$textApellidoMat);
        $sentencia->bindParam(":Correo",$textCorreo);
        $sentencia->bindParam(":Fotografia",$textIMG);
        $sentencia->execute();

    break;
  case 'btnEditar':
      echo "Presionaste Editar";
    break;
  case 'btnEliminar':
      echo "Presionaste Eliminar";
    break;
  case 'btnCancelar':
      echo "Presionaste Cancelar";
    break;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!-- JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

  <title>My First CRUD</title>
</head>
<body>
  <div class="container">

    <form class="" action="" method="post" enctype="multipart/form-data">

      <label for="">ID:</label>
      <input type="text" name="textID" placeholder="" id="textID" require="">
      <br>

      <label for="">Nombre:</label>
      <input type="text" name="textNombre" placeholder="" id="textNombre" require="">
      <br>

      <label for="">Apellido Paterno:</label>
      <input type="text" name="textApellidoPat" placeholder="" id="textApellidoPat" require="">
      <br>

      <label for="">Apellido Materno:</label>
      <input type="text" name="textApellidoMat" placeholder="" id="textApellidoMat" require="">
      <br>

      <label for="">Correo:</label>
      <input type="text" name="textCorreo" placeholder="" id="textCorreo" require="">
      <br>

      <label for="">Imagen:</label>
      <input type="text" name="textIMG" placeholder="" id="textIMG" require="">
      <br>

      <button class="btn btn-success" value="btnAgregar" type="submit" name="accion">Agregar</button>
      <button class="btn btn-primary" value="btnEditar" type="submit" name="accion">Editar</button>
      <button class="btn btn-danger" value="btnEliminar" type="submit" name="accion">Eliminar</button>
      <button class="btn btn-warning" value="btnCancelar" type="submit" name="accion">Cancelar</button>
    </form>

  </div>
</body>
</html>
