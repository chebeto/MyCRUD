<?php
      $textID=(isset($_POST["textID"]))?$_POST["textID"]:"";
      $textNombre=(isset($_POST["textNombre"]))?$_POST["textNombre"]:"";
      $textApellidoPat=(isset($_POST["textApellidoPat"]))?$_POST["textApellidoPat"]:"";
      $textApellidoMat=(isset($_POST["textApellidoMat"]))?$_POST["textApellidoMat"]:"";
      $textCorreo=(isset($_POST["textCorreo"]))?$_POST["textCorreo"]:"";
      $textIMG=(isset($_FILES["textIMG"]["name"]))?$_FILES["textIMG"]["name"]:"";

      $accion=(isset($_POST["accion"]))?$_POST["accion"]:"";

      $error=array();

      $accionAgregar=" ";
      $accionModificar=$accionEliminar=$accionCancelar="disabled";
      $mostrarModal=false;

include ("../conexion/conexion.php");

switch ($accion){
  case "btnAgregar":

        if ($textNombre=="") {
          $error["textNombre"]="Escribe el nombre";
        }
        if ($textApellidoPat=="") {
          $error["textApellidoPat"]="Escribe el apellido paterno";
        }
        if ($textApellidoMat=="") {
          $error["textApellidoMat"]="Escribe el apellido materno";
        }
        if ($textCorreo=="") {
          $error["textCorreo"]="Escribe el correo electronico";
        }

        if (count($error)>0) {
          $mostrarModal=true;
          break;
        }

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
      $accionModificar=$accionEliminar=$accionCancelar=" ";
      $mostrarModal=true;

      $sentencia = $conn->prepare ("SELECT * FROM empleados WHERE Id=:Id");
      $sentencia->bindParam(":Id",$textID);
      $sentencia->execute();
      $empleados=$sentencia->fetch(PDO::FETCH_LAZY);

      $textNombre=$empleados["Nombre"];
      $textApellidoPat=$empleados["ApellidoPat"];
      $textApellidoMat=$empleados["ApellidoMat"];
      $textCorreo=$empleados["Correo"];
      $textIMG=$empleados["Fotografia"];
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
