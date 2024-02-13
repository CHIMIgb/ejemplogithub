<?php
session_start();

// Verifica si la sesión está iniciada
if (!isset($_SESSION['usuario'])) {
    echo '<script>alert("Nesecitas iniciar sesion para acceder aquí");
    window.location = "../index.php";
    </script>';
    exit();
}

// Verifica si el usuario es de tipo "Admin" (tipo 1) estan invertidos XD
if ($_SESSION['tipo'] == 1) {
    echo '<script>alert("Los admin no pueden acceder aquí");
    window.location = "../cerrar_sesion.php";
    </script>';
    exit();
}

include("../conexion.php");

//mostrar el nombre del usuario que tiene sesion iniciada
if(isset($_SESSION['usuario'])){
    // Obtener el nombre de usuario de la sesión
    $usuario = $_SESSION['usuario'];

    // Consulta para obtener el nombre, apellido1 y apellido2 del usuario actual
    $query = "SELECT u.nombres, u.apellido1, u.apellido2 
              FROM usuarios2 u 
              INNER JOIN datos d ON u.pk_usuario2 = d.fk_usuario
              WHERE d.usuario = '$usuario'";
    $resultado = mysqli_query($mysqli, $query);

    // Verificar si se obtuvieron resultados
    if ($resultado && mysqli_num_rows($resultado) == 1){
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
    <title>Mortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../CSS/main.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php
    include("menu.php");
    ?>
    <h1>Mortal: <?php echo $usuarioActual['nombres']." ".$usuarioActual['apellido1']." ".$usuarioActual['apellido2']; ?></h1>

    <div class="container__landing" data-aos="fade-up">
        <div class="text">
            <h1>Chiqueados S.A. de C.V.</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate quo nostrum eligendi sequi iste quae voluptates assumenda nemo odit cum! Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe, repudiandae pariatur natus quo voluptate beatae deleniti quos dolores eveniet dolorem enim aspernatur deserunt recusandae nam sit maxime similique blanditiis veniam!</p>
        </div>
        <div class="images">
        <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
        <div class="elfsight-app-2bf4cdbe-049d-410c-834f-024174c14ed9" data-elfsight-app-lazy></div>
        </div>
    </div>
</body>
</html>