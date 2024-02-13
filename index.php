<?php

session_start();

include("conexion.php");

// Variable tipo arreglo para gestionar los errores
$errores = [];

// Revisamos si se ha enviado el formulario
if (isset($_POST['login_button'])) {
    // Evitamos inyecciones SQL
    $usuario = mysqli_real_escape_string($mysqli, $_POST['usuario']);
    $clave = mysqli_real_escape_string($mysqli, $_POST['clave']);

    // Comprobamos si el nombre de usuario es válido
    $query = "SELECT datos.usuario, datos.clave, usuarios2.fk_tipo FROM datos 
              JOIN usuarios2 ON datos.fk_usuario = usuarios2.pk_usuario2
              WHERE datos.usuario = '$usuario'";
    $resultado = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($resultado) == 1) {
        $row = mysqli_fetch_assoc($resultado);
        if (password_verify($clave, $row['clave'])) {
            //password_verify($clave, $row['clave'])
            // Validar el acceso dependiendo del tipo (tipo==1 admin, tipo==2 mortal)
            // Al admin le debe aparecer un formulario para insertar nuevos usuarios
            // El mortal solo debe ver los correos existentes
            $_SESSION['usuario'] = $usuario;
            $_SESSION['tipo'] = $row['fk_tipo'];

            if ($row['fk_tipo'] == 1) {
                header('Location: admin/admin.php');
            } else if ($row['fk_tipo'] == 2) {
                header('Location: mortal/mortal.php');
            }
        } else {
            // Contraseña inválida
            $errores[] = "Nombre de usuario/contraseña inválidos";
        }
    } else {
        // Usuario no encontrado
        $errores[] = "Nombre de usuario/contraseña inválidos";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="CSS/main.css">
</head>
<body>
    <div align="center">
        <h1>INICIAR SESION</h1>
    </div>
    <div align="center">
        <?php
            if(count($errores) > 0){
                foreach($errores as $error){
                    echo "<p style='color: red'>$error</p><br>";
                }
            }
        ?>
    </div>
    <form action="index.php" method="POST"> 

        <div class="container" style="width: 45%; border: 1px solid black; padding: 1rem; border-radius: 10px;">

            <label for="" class="form-label"><i class="fa-regular fa-circle-user"></i> Nombre de usuario</label>
            <input type="text" class="form-control is-invalid" name="usuario" required><br>

            <label for="" class="form-label"><i class="fa-solid fa-lock"></i> Contraseña</label>
            <div class="input-group">
                <input type="password" class="form-control is-invalid" name="clave" id="password" required>
                <button class="btn btn-outline-secondary" type="button" id="show_password"><i class="fa-solid fa-eye"></i></button>
            </div><br>

            <div align="center">
                <button type="submit" class="btn btn-success" name="login_button">Iniciar sesion</button>

        </div>
        <br>
        <div align="center">
            <span>¿No tienes uan cuenta?</span><a href="registro.php"> Registrate aqui</a>
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
    </script>
</body>
</html>