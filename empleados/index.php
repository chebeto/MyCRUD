
<?php

      $textID=(isset($_POST["textID"]))?$_POST["textID"]:"";
      $textNombre=(isset($_POST["textNombre"]))?$_POST["textNombre"]:"";
      $textApellidoPat=(isset($_POST["textApellidoPat"]))?$_POST["textApellidoPat"]:"";
      $textApellidoMat=(isset($_POST["textApellidoMat"]))?$_POST["textApellidoMat"]:"";
      $textCorreo=(isset($_POST["textCorreo"]))?$_POST["textCorreo"]:"";
      $textIMG=(isset($_FILES["textIMG"]["name"]))?$_FILES["textIMG"]["name"]:"";

      $accion=(isset($_POST["accion"]))?$_POST["accion"]:"";

      $accionAgregar="";
      $accionModificar=$accionEliminar=$accionCancelar="disabled";
      $mostrarModal=false;

include ("../conexion/conexion.php");

switch ($accion){
  case "btnAgregar":
        $sentencia = $conn->prepare ("INSERT INTO empleados (Nombre, ApellidoPat, ApellidoMat, Correo, Fotografia)
        VALUES (:Nombre,:ApellidoPat,:ApellidoMat,:Correo,:Fotografia)");

        $sentencia->bindParam(":Nombre", $textNombre);
        $sentencia->bindParam(":ApellidoPat",$textApellidoPat);
        $sentencia->bindParam(":ApellidoMat",$textApellidoMat);
        $sentencia->bindParam(":Correo",$textCorreo);

// Se inserta la fecha y hora de subida del archivo para evitar duplicidad
// en archivos cuando varios usuarios suban un mismo nombre de archivos
// En caso de que el usuario no suba ninguna foto, se elegira USER.PNG
// para que sea su imagen default
        $Fecha= new DateTime();
        $nombreArchivo=($textIMG!="")?$Fecha->getTimestamp()."_".$_FILES["textIMG"]["name"]:"user.png";
        $tmpFoto=$_FILES["textIMG"]["tmp_name"];
        if ($tmpFoto!="") {
          move_uploaded_file($tmpFoto,"../imgs/".$nombreArchivo);
        }
        $sentencia->bindParam(":Fotografia",$nombreArchivo);
        $sentencia->execute();

        header("Location: index.php");
    break;

  case 'btnEditar':
        $sentencia = $conn->prepare ("UPDATE empleados SET
        Nombre=:Nombre,
        ApellidoPat=:ApellidoPat,
        ApellidoMat=:ApellidoMat,
        Correo=:Correo WHERE Id=:Id");

        $sentencia->bindParam(":Nombre", $textNombre);
        $sentencia->bindParam(":ApellidoPat",$textApellidoPat);
        $sentencia->bindParam(":ApellidoMat",$textApellidoMat);
        $sentencia->bindParam(":Correo",$textCorreo);

        $sentencia->bindParam(":Id",$textID);
        $sentencia->execute();

        $Fecha= new DateTime();
        $nombreArchivo=($textIMG!="")?$Fecha->getTimestamp()."_".$_FILES["textIMG"]["name"]:"user.png";
        $tmpFoto=$_FILES["textIMG"]["tmp_name"];

        if ($tmpFoto!="") {
          move_uploaded_file($tmpFoto,"../imgs/".$nombreArchivo);

          /*
          Realiza el Select de la foto del usuario en base al ID elegido
          */
        $sentencia = $conn->prepare ("SELECT Fotografia FROM empleados WHERE Id=:Id");
        $sentencia->bindParam(":Id",$textID);
        $sentencia->execute();
        $empleados=$sentencia->fetch(PDO::FETCH_LAZY);
        print_r($empleados);
          /*
          Realiza el borrado del archivo dentro de la carpeta de IMGS
          */
        if (isset($empleados["Fotografia"])) {
          if (file_exists("../imgs/".$empleados["Fotografia"])) {
            if ($item["Fotografia"]!="user.png") {
              unlink("../imgs/".$empleados["Fotografia"]);
            }
          }
        }
        /*
        Realiza el update del archivo dentro de la carpeta de IMGS
        */
          $sentencia = $conn->prepare ("UPDATE empleados SET
          Fotografia=:Fotografia WHERE Id=:Id");
          $sentencia->bindParam(":Fotografia",$nombreArchivo);
          $sentencia->bindParam(":Id",$textID);
          $sentencia->execute();
        }

        header("Location: index.php");
    break;

  case 'btnEliminar':
          /*
          Realiza el Select de la foto del usuario en base al ID elegido
          */
        $sentencia = $conn->prepare ("SELECT Fotografia FROM empleados WHERE Id=:Id");
        $sentencia->bindParam(":Id",$textID);
        $sentencia->execute();
        $empleados=$sentencia->fetch(PDO::FETCH_LAZY);
        print_r($empleados);
          /*
          Realiza el borrado del archivo dentro de la carpeta de IMGS
          */
        if (isset($empleados["Fotografia"])&&($item["Fotografia"]!="user.png")) {
          if (file_exists("../imgs/".$empleados["Fotografia"])) {
            unlink("../imgs/".$empleados["Fotografia"]);
          }
        }

        $sentencia = $conn->prepare ("DELETE FROM empleados WHERE Id=:Id");
        $sentencia->bindParam(":Id",$textID);
        $sentencia->execute();
        header("Location: index.php");

    break;

  case 'btnCancelar':
      header("Location: index.php");
    break;

  case "Seleccionar":
      $accionAgregar="disabled";
      $accionModificar=$accionEliminar=$accionCancelar="";
      $mostrarModal=true;

      $sentencia = $conn->prepare ("SELECT * FROM empleados WHERE Id=:Id");
      $sentencia->bindParam(":Id",$textID);
      $sentencia->execute();
      $empleados=$sentencia->fetch(PDO::FETCH_LAZY);

      $textNombre=$empleados["Nombre"];
      $textApellidoPat=$empleados["ApellidoPat"];
      $textApellidoMat=$empleados["ApellidoMat"];
      $textCorreo=$empleados["Correo"];
      $textImg=$empleados["Fotografia"];
    break;
}


//Se selecciona todos los datos de la tabla empleados
    $lista = $conn->prepare("SELECT * FROM `empleados` WHERE 1");
    $lista->execute();
//Se recogen todos los datos
    $listaEmpleados = $lista->FetchAll(PDO::FETCH_ASSOC);
//Se imprimen en pantalla
//    print_r($listaEmpleados);

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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Empleados</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-row">
              <input type="hidden" name="textID" value="<?php echo $textID; ?>" placeholder="" id="textID" require="">

              <label for="">Nombre(s):</label>
              <input type="text" class="form-control" name="textNombre" required value="<?php echo $textNombre; ?>" placeholder="" id="textNombre" require="">
              <br>

              <label for="">Apellido Paterno:</label>
              <input type="text" class="form-control" name="textApellidoPat" required value="<?php echo $textApellidoPat; ?>" placeholder="" id="textApellidoPat" require="">
              <br>

              <label for="">Apellido Materno:</label>
              <input type="text" class="form-control" name="textApellidoMat" required value="<?php echo $textApellidoMat; ?>" placeholder="" id="textApellidoMat" require="">
              <br>

              <label for="">Correo:</label>
              <input type="email" class="form-control" name="textCorreo" required value="<?php echo $textCorreo; ?>" placeholder="" id="textCorreo" require="">
              <br>

              <label for="">Imagen:</label>
              <?php if ($textIMG!=""){}?>
              <br>
              <img class="img-thumbnail rounded mx-auto d-block" width="100px" src="../imgs/<?php echo $textIMG; ?>"/>
              <br>
              <?php }?>


              <input type="file" class="form-control" accept="image/*" class="form-control-file" name="textIMG" value="<?php echo $textIMG; ?>" placeholder="" id="textIMG" require="">
              <br>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" <?php echo $accionAgregar ?> value="btnAgregar" type="submit" name="accion">Agregar</button>
            <button class="btn btn-warning" <?php echo $accionModificar ?> value="btnEditar" type="submit" name="accion">Editar</button>
            <button class="btn btn-danger" <?php echo $accionEliminar ?> value="btnEliminar" type="submit" name="accion">Eliminar</button>
            <button class="btn btn-primary" <?php echo $accionCancelar ?> value="btnCancelar" type="submit" name="accion">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <br>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Agregar empleados +
    </button>

    </form>


  <!-- Se crea la tabla para muestra de informacion -->
    <div class="row">
      <table class="table">
        <thead>
          <tr>
            <th>Fotografia</th>
            <th>Nombre Completo</th>
            <th>Correo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <?php foreach ($listaEmpleados as $empleados) {
          ?>
          <tr>
            <td><img class="img-thumbnail" width="100px" src="../imgs/<?php echo $empleados["Fotografia"]; ?>" /></td>
            <td><?php echo $empleados["Nombre"]; ?> <?php echo $empleados["ApellidoPat"]; ?> <?php echo $empleados["ApellidoMat"]; ?></td>
            <td><?php echo $empleados["Correo"]; ?></td>
            <td>
              <form action="" method="post">
                <input type="hidden" name="textID" value="<?php echo $empleados["ID"]; ?>">
                <input type="submit"  value="Seleccionar" name="accion" class="btn btn-outline-primary">
                <button class="btn btn-outline-danger" value="btnEliminar" type="submit" name="accion">Eliminar</button>
              </form>
            </td>
          </tr>
          <?php
        } ?>
      </table>
    </div>
  <?php if ($mostrarModal) {?>
    <script>
      $("#exampleModal").modal("show");
    </script>
  <?php }?>

  </div>
</body>
</html>
