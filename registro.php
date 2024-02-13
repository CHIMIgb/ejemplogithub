<?php
session_start();

include("conexion.php");

// Obtener el sexo del usuario actualmente registrado en la base de datos
$sexoUsuarioActual = ''; // Inicializar variable
if (isset($_SESSION['usuario'])) {
    // Obtener el nombre de usuario de la sesión
    $usuario = $_SESSION['usuario'];

    // Consulta para obtener el sexo del usuario actual
    $query = "SELECT u.sexo FROM usuarios2 u 
              INNER JOIN datos d ON u.pk_usuario2 = d.fk_usuario
              WHERE d.usuario = ?";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // Verificar si se obtuvieron resultados
    if ($resultado && mysqli_num_rows($resultado) == 1) {
        $usuarioActual = mysqli_fetch_assoc($resultado);
        $sexoUsuarioActual = $usuarioActual['sexo']; // Obtener el valor del sexo
    } 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="CSS/main.css">
</head>
<body>

<div align="center">
        <h1>REGISTRO DE USUARIOS</h1>
    </div>
    
    <form action="registro.php" method="POST">

        <div class="container" style="width: 65%; border: 1px solid black; padding: 1rem; border-radius: 10px; margin: 1rem auto;">

            <br>

            <div class="row">
                <div class="col">
                    <label for="" class="form-label"><i class="fa-regular fa-user"></i> Nombre(s)</label>
                    <input type="text" class="form-control is-invalid" name="nombre" id="nombre" required onkeypress="return soloLetras(event)">
                </div>

                <div class="col">
                    <label for="" class="form-label"><i class="fa-regular fa-user"></i> Apellido paterno</label>
                    <input type="text" class="form-control is-invalid" name="apellido1" id="apellido1" required onkeypress="return soloLetras(event)">
                </div>

                <div class="col">
                    <label for="" class="form-label"><i class="fa-regular fa-user"></i> Apellido materno</label>
                    <input type="text" class="form-control is-invalid" name="apellido2" id="apellido2" required onkeypress="return soloLetras(event)">
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col">
                    <label for="" class="form-label"><i class="fa-solid fa-1"></i><i class="fa-solid fa-9"></i> Edad</label>
                    <input type="text" class="form-control is-invalid" name="edad" id="edad" required onkeypress="return filterFloat(event,this);">
                </div>
                <div class="col">
                    <label for="" class="form-label"><i class="fa-solid fa-venus-mars"></i> Sexo</label>
                    <select class="form-control <?php echo isset($_POST['sexo']) && empty($_POST['sexo']) ? 'is-invalid' : 'is-valid'; ?>" name="sexo" id="sexo" required>
                        <option value="">Elija su sexo..</option>
                        <option value="1" <?php echo ($sexoUsuarioActual == 1) ? 'selected' : ''; ?>>Hombre</option>
                        <option value="2" <?php echo ($sexoUsuarioActual == 2) ? 'selected' : ''; ?>>Mujer</option>
                    </select>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col">
                    <label for="" class="form-label"><i class="fa-regular fa-user"></i> Nombre de usuario</label>
                    <input type="text" class="form-control is-invalid" name="usuario" id="usuario" required>
                </div>

                <div class="col">
                <label for="" class="form-label"><i class="fa-solid fa-lock"></i> Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control is-invalid" name="clave" id="password" required>
                        <button class="btn btn-outline-secondary" type="button" id="show_password"><i class="fa-solid fa-eye"></i></button>
                    </div><br>
                </div>
                
            </div>

            <br>

            <div class="row">
                <div class="col" align="center">
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-plus"></i> Registrar usuario</button>
                    <button type="submit" class="btn btn-danger" onclick="limpiarFormulario();"><i class="fa-solid fa-eraser"></i> Borrar</button>
                </div>
            </div>

        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('input', function () {
                    if (this.value.trim() === '') {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            });

            // Mostrar u ocultar la contraseña al hacer clic en el botón de ojo
            const passwordInput = document.getElementById('password');
            const showPasswordButton = document.getElementById('show_password');
            showPasswordButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>';
            });
        });

            //limpiar formulario
            function limpiarFormulario(){
                        //limpiar los campos del formulario
                        document.getElementById('nombre').value = '';
                        document.getElementById('apellido1').value = '';
                        document.getElementById('apellido2').value = '';
                        document.getElementById('edad').value = '';
                        document.getElementById('sexo').value = '';
                        document.getElementById('usuario').value = '';
                        document.getElementById('password').value = '';

                        // Establecer los campos como inválidos
                        const inputs = document.querySelectorAll('input, select');
                        inputs.forEach(input => {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                        });

                    }
                    //


                    //funcion ingresar solo letras
  function soloLetras(e) {
      key = e.keyCode || e.which;
      tecla = String.fromCharCode(key).toLowerCase();
      letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
      especiales = [8, 37, 39, 46];
  
      tecla_especial = false
      for(var i in especiales) {
          if(key == especiales[i]) {
              tecla_especial = true;
              break;
          }
      }
  
      if(letras.indexOf(tecla) == -1 && !tecla_especial)
          return false;
  }


  //ingresar numeros enteros de dos digitos
function filterFloat(evt,input){//SOLO NUMEROS
            // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
            var key = window.Event ? evt.which : evt.keyCode;    
            var chark = String.fromCharCode(key);
            var tempValue = input.value+chark;
            if(key >= 48 && key <= 57){
                if(filter(tempValue)=== false){
                    return false;
                }else{       
                    return true;
                }
            }else{
                  if(key == 8 || key == 13 || key == 0) {     
                      return true;              
                  }else if(key == 46){
                        if(filter(tempValue)=== false){
                            return false;
                        }else{       
                            return true;
                        }
                  }else{
                      return false;
                  }
            }
        }
        function filter(__val__){
            var preg = /^([0-9]{0,2})$/; 
            if(preg.test(__val__) === true){
                return true;
            }else{
               return false;
            }
            
        }
//

    </script>
<?php

if (isset($_POST['nombre']) && isset($_POST['apellido1']) && isset($_POST['apellido2']) && isset($_POST['edad']) && isset($_POST['sexo']) && isset($_POST['usuario']) && isset($_POST['clave'])) {

    date_default_timezone_set("America/Mazatlan");
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    // Verificar si el usuario ya existe en la tabla datos
    $checkUserQuery = "SELECT COUNT(*) as count FROM datos WHERE usuario = ?";
    $stmtCheckUser = mysqli_prepare($mysqli, $checkUserQuery);
    mysqli_stmt_bind_param($stmtCheckUser, "s", $_POST['usuario']);
    mysqli_stmt_execute($stmtCheckUser);
    $resultCheckUser = mysqli_stmt_get_result($stmtCheckUser);
    $countUser = mysqli_fetch_assoc($resultCheckUser)['count'];
    mysqli_stmt_close($stmtCheckUser);

    if ($countUser > 0) {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Este nombre de usuario ya existe, intenta con otro',
          });
        </script>";
    } else {
        // Inserción en la tabla usuarios2
        $queryInsertUsuario = "INSERT INTO usuarios2 (nombres, apellido1, apellido2, edad, sexo, fk_tipo, fecha, hora) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Definir el valor de fk_tipo
        $fk_tipo = 2;

        $stmtInsertUsuario = mysqli_prepare($mysqli, $queryInsertUsuario);

        mysqli_stmt_bind_param($stmtInsertUsuario, "sssiisss", $_POST['nombre'], $_POST['apellido1'], $_POST['apellido2'], $_POST['edad'], $_POST['sexo'], $fk_tipo, $fecha, $hora);


        $resultInsertUsuario = mysqli_stmt_execute($stmtInsertUsuario);

        // Verificar si la inserción en usuarios2 fue exitosa
        if ($resultInsertUsuario) {
            // Obtener el último ID insertado en la tabla usuarios2
            $lastUserID = mysqli_insert_id($mysqli);

            // Hash de la contraseña
            $hashedPassword = password_hash($_POST['clave'], PASSWORD_DEFAULT);

            // Inserción en la tabla datos
            $queryInsertDatos = "INSERT INTO datos (usuario, clave, fk_usuario, fecha, hora) 
                                 VALUES (?, ?, ?, ?, ?)";

            $stmtInsertDatos = mysqli_prepare($mysqli, $queryInsertDatos);

            mysqli_stmt_bind_param($stmtInsertDatos, "ssiss", $_POST['usuario'], $hashedPassword, $lastUserID, $fecha, $hora);

            $resultInsertDatos = mysqli_stmt_execute($stmtInsertDatos);


            // Verificar si la inserción en datos fue exitosa


            

 // Redirigir al usuario después de registrar
if ($resultInsertDatos) {

    // Establecer las variables de sesión
    $_SESSION['usuario'] = $_POST['usuario']; // Asignar el nombre de usuario
    $_SESSION['tipo'] = $fk_tipo; // Asignar el tipo de usuario

    echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Usuario registrado',
        }).then((result) => {
            // Redireccionar a otro archivo después de que se cierre el cuadro de diálogo
            window.location.href = 'mortal/mortal.php';
        });
    </script>";

            } else {
                echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error al registrar al usuario',
                  });
                </script>" . mysqli_error($mysqli);
            }
        } else {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error al registrar al usuario',
              });
            </script>" . mysqli_error($mysqli);
        }

        mysqli_stmt_close($stmtInsertUsuario);
        mysqli_stmt_close($stmtInsertDatos);
    }
}

?>
</body>
</html>
