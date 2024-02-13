<?php
    session_start();

    // Verifica si la sesión está iniciada
    if (!isset($_SESSION['usuario'])) {
        echo '<script>alert("Nesecitas iniciar sesion para acceder aquí");
        window.location = "../index.php";
        </script>';
        exit();
    }

    // Verifica si el usuario es de tipo "Mortal" (tipo 2) 
if ($_SESSION['tipo'] == 2) {
    echo '<script>alert("Los mortales no pueden acceder aquí");
    window.location = "../cerrar_sesion.php";
    </script>';
    exit();
}

    include("../conexion.php");

    // Mostrar el nombre del usuario que tiene sesión iniciada
    if (isset($_SESSION['usuario'])) {
        // Obtener el nombre de usuario de la sesión
        $usuario = $_SESSION['usuario'];

        // Consulta para obtener el nombre, apellido1 y apellido2 del usuario actual
        $query = "SELECT u.nombres, u.apellido1, u.apellido2, u.sexo
                FROM usuarios2 u 
                INNER JOIN datos d ON u.pk_usuario2 = d.fk_usuario
                WHERE d.usuario = '$usuario'";
        $resultado = mysqli_query($mysqli, $query);

        // Verificar si se obtuvieron resultados
        if ($resultado && mysqli_num_rows($resultado) == 1) {
            $usuarioActual = mysqli_fetch_assoc($resultado);
        } else {
            // Manejar el caso en que no se encuentren datos
            echo "No se encontraron datos para el usuario actual.";
        }
    } else {
        // Manejar el caso en que no haya una sesión iniciada
        echo "No hay una sesión iniciada.";
    }
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../CSS/main.css">
</head>
<body>
<?php
    include("menu.php");
    ?>
    <h1>Admin: <?php echo $usuarioActual['nombres']." ".$usuarioActual['apellido1']." ".$usuarioActual['apellido2']; ?></h1>


    <form action="alta_productos.php" method="POST">
        <div class="container" style="width: 50%; border: 1px solid black; padding: 1rem; border-radius: 10px; margin: 2rem auto;">

        <div class="row">
                <div class="col">
                    <h1 align="center">Alta productos</h1>
                </div>
            </div>

        <br>

        <div class="row">
                <div class="col">
                    <label for="" class="form-label"><i class="fa-solid fa-camera-retro"></i> Nombre del producto</label>
                    <input type="text" class="form-control is-invalid" name="nombre_producto" id="nombre_producto" required>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col">
                    <label for="" class="form-label"><i class="fa-solid fa-dollar-sign"></i> Precio</label>
                    <input type="text" class="form-control is-invalid" name="precio" id="precio" required onkeypress="return filterFloat(event,this);">
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col" align="center">
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-plus"></i> Registrar producto</button>
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
    });


    //limpiar formulario
    function limpiarFormulario(){
                //limpiar los campos del formulario
                document.getElementById('nombre_producto').value = '';
                document.getElementById('precio').value = '';

                // Establecer los campos como inválidos
                const inputs = document.querySelectorAll('input');
                inputs.forEach(input => {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                });

            }
            //


            function filterFloat(evt, input) {
    // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
    var key = window.Event ? evt.which : evt.keyCode;    
    var chark = String.fromCharCode(key);
    var tempValue = input.value + chark;
    if (key >= 48 && key <= 57) {
        if (filter(tempValue) === false) {
            return false;
        } else {       
            return true;
        }
    } else if (key == 46) {
        if (input.value.indexOf('.') !== -1 || input.value === '') {
            return false;
        }
    } else {
        if (key == 8 || key == 13 || key == 0) {     
            return true;              
        } else {
            return false;
        }
    }
}

function filter(__val__) {
    var preg = /^\d{0,6}(\.\d{0,2})?$/;
    if (preg.test(__val__) === true) {
        return true;
    } else {
        return false;
    }
}


</script>

    <?php

        if(isset($_POST['nombre_producto']) && isset($_POST['precio'])){

            $nombre_producto = $_POST['nombre_producto'];
            $precio = $_POST['precio'];

            //configuramos la fecha y la hora
            date_default_timezone_set("America/Mazatlan");
            $hora=date("H:i:s");
            $fecha = date("Y-m-d");

            // Verificar si el nombre_producto ya existe en la tabla productos
    $checkUserQuery = "SELECT COUNT(*) as count FROM productos WHERE nombre_producto = ?";
    $stmtCheckUser = mysqli_prepare($mysqli, $checkUserQuery);
    mysqli_stmt_bind_param($stmtCheckUser, "s", $_POST['nombre_producto']);
    mysqli_stmt_execute($stmtCheckUser);
    $resultCheckUser = mysqli_stmt_get_result($stmtCheckUser);
    $countUser = mysqli_fetch_assoc($resultCheckUser)['count'];
    mysqli_stmt_close($stmtCheckUser);

    if ($countUser > 0) {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Este producto ya existe',
          });
        </script>";
    } else {

            $sentencia = $mysqli -> prepare("INSERT INTO productos (nombre_producto, precio, fecha, hora) VALUES (?, ?, ?, ?)");

            $sentencia->bind_param("sdss", $nombre_producto, $precio, $fecha, $hora);

            //ejecutamos la consulta 
            if($sentencia->execute()){
                echo'<script>
                Swal.fire({
                    title: "El producto se ha registrado exitosamente",
                    icon: "success"
                  });
                    </script>';
            } else {
                echo'<script>
                Swal.fire({
                    icon: "error",
                    title: "Error al ingresar el producto",
                  });
                    </script>';
            }

        }
    }

    ?>

</body>
</html>