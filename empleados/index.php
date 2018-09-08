<?php
require 'empleados.php';
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

              <div class="form-group col-md-12">
              <label for="">Nombre(s):</label>
              <input type="text" class="form-control <?php echo (isset($error["textNombre"]))?"is-invalid":"";?>" name="textNombre"  value="<?php echo $textNombre; ?>" placeholder="" id="textNombre" require="">
              <div class="invalid-feedback">
                <?php echo (isset($error["textNombre"]))?$error["textNombre"]:"";?>
              </div>
              <br>
              </div>

              <div class="form-group col-md-6">
              <label for="">Apellido Paterno:</label>
              <input type="text" class="form-control <?php echo (isset($error["textApellidoPat"]))?"is-invalid":"";?>" name="textApellidoPat"  value="<?php echo $textApellidoPat; ?>" placeholder="" id="textApellidoPat" require="">
              <div class="invalid-feedback">
                <?php echo (isset($error["textApellidoPat"]))?$error["textApellidoPat"]:"";?>
              </div>
              <br>
              </div>

              <div class="form-group col-md-6">
              <label for="">Apellido Materno:</label>
              <input type="text" class="form-control <?php echo (isset($error["textApellidoMat"]))?"is-invalid":"";?>" name="textApellidoMat"  value="<?php echo $textApellidoMat; ?>" placeholder="" id="textApellidoMat" require="">
              <div class="invalid-feedback">
                <?php echo (isset($error["textApellidoMat"]))?$error["textApellidoMat"]:"";?>
              </div>
              <br>
              </div>

              <div class="form-group col-md-12">
              <label for="">Correo:</label>
              <input type="email" class="form-control <?php echo (isset($error["textCorreo"]))?"is-invalid":"";?>" name="textCorreo" required value="<?php echo $textCorreo; ?>" placeholder="" id="textCorreo" require="">
              <div class="invalid-feedback">
                <?php echo (isset($error["textCorreo"]))?$error["textCorreo"]:"";?>
              </div>
              <br>
              </div>

              <div class="form-group col-md-12">
              <label for="">Imagen:</label>
              <?php if ($textIMG!=""){?>
              <br />
              <img class="img-thumbnail rounded mx-auto d-block" width="150px" src="../imgs/<?php echo $textIMG; ?>"/>
              <br/>
              <br/>
              <?php }?>
              </div>

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
    <!-- Button trigger modal activar/desac -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Agregar empleados +
    </button>
    <br>
    <br>

    </form>


  <!-- Se crea la tabla para muestra de informacion -->
    <div class="row">
      <table class="table table-hover">
        <thead class="thead-dark">
          <tr >
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
