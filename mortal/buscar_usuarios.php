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

    $resultado = $mysqli -> query("SELECT usuarios2.nombres, usuarios2.apellido1, usuarios2.apellido2, usuarios2.edad, 
                                    CASE WHEN usuarios2.sexo = 1 THEN 'Hombre' ELSE 'Mujer' END as sexo,
                                    tipo.tipo, datos.usuario, datos.clave
                                    FROM usuarios2
                                    JOIN tipo ON usuarios2.fk_tipo = tipo.pk_tipo
                                    JOIN datos ON usuarios2.pk_usuario2 = datos.fk_usuario
                                    ORDER BY usuarios2.apellido1;
                                ");
    $datos = $resultado ->fetch_all(MYSQLI_ASSOC);
    $total = mysqli_num_rows($resultado);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="../CSS/main.css">
<script>
    $(document).ready(function(){
    // Este código se ejecuta cuando el documento HTML ha sido completamente cargado

    $('#buscarUsuario').on('input', function(){
        // Este código se ejecuta cada vez que el valor de la barra de búsqueda cambia

        var valor = $(this).val().toLowerCase();
        // Obtiene el valor ingresado en la barra de búsqueda y lo convierte a minúsculas

        $('#tablaUsuarios tbody tr').filter(function() {
            // Filtra las filas de la tabla de usuarios

            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1)
            // Muestra u oculta las filas según si contienen el valor de búsqueda
        });
    });
});
</script>

</head>
<body>
    
<?php
    include("menu.php");
    ?>
    <h1>Mortal: <?php echo $usuarioActual['nombres']." ".$usuarioActual['apellido1']." ".$usuarioActual['apellido2']; ?></h1>

    <div class="container-fluid">

    <input type="text" id="buscarUsuario" class="form-control mb-3" placeholder="Buscar usuario">

    <table id="tablaUsuarios" class="table table-striped table-bordered border-secondary">
  <thead>
    <tr>
      <th scope="col">#</th>  
      <th scope="col">Nombre(s)</th>
      <th scope="col">Apellido paterno</th>
      <th scope="col">Apellido materno</th>
      <th scope="col">Edad</th>
      <th scope="col">sexo</th>
      <th scope="col">Usuario</th>
      <th scope="col">Clave</th>
      <th scope="col">Tipo</th>
    </tr>
  </thead>
  <tbody class="table-group-divider">
    <?php
        $contador = 1; // Inicializar el contador
        foreach($datos as $registro){
    ?>
    <tr>
        <td scope="row"><?php echo $contador; ?></td>
        <td scope="row"><?php echo $registro['nombres']; ?></td>
        <td scope="row"><?php echo $registro['apellido1']; ?></td>
        <td scope="row"><?php echo $registro['apellido2']; ?></td>
        <td scope="row"><?php echo $registro['edad']; ?></td>
        <td scope="row"><?php echo $registro['sexo']; ?></td>
        <td scope="row"><?php echo $registro['usuario']; ?></td>
        <td scope="row"><?php echo $registro['clave']; ?></td>
        <td scope="row"><?php echo $registro['tipo']; ?></td>
    </tr>
    <?php
        $contador++; // Incrementar el contador en cada iteración
        }
    ?>
  </tbody>
</table>

    </div>

</body>
</html>