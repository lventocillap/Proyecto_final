<?php
require_once '../../db/conexion.php';
require_once '../../modals/empleadoModel.php';

$model = new EmpleadoModel($conn);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$limit = 10; // Número de empleados por página
$offset = ($page - 1) * $limit; // Calcular el desplazamiento

$totalEmpleados = $model->countEmpleados(); // Método que cuenta el total de empleados
$totalPages = ceil($totalEmpleados / $limit);

$empleados = $model->getEmpleadosPaginated($limit, $offset); // Método que obtiene empleados con límite y desplazamiento

$id = isset($_GET['id']) ? intval($_GET['id']) : null; // Asegurando que el ID sea un entero

$nombre = "";
$apellido = "";
$cargo = "";
$correo = "";
$celular = "";
$estado_civil = "";
$direccion = "";
$fecha_nacimiento = "";
$sueldo = "";

if ($id) {
    $empleado = $model->getEmpleadoById($id); // Suponiendo que tengas un método getEmpleadoById en tu modelo
    if ($empleado) {
        // Si se encuentra el empleado, asignamos sus valores a las variables correspondientes
        $nombre = $empleado['nombre'];
        $apellido = $empleado['apellido'];
        $cargo = $empleado['cargo'];
        $correo = $empleado['correo'];
        $celular = $empleado['celular'];
        $estado_civil = $empleado['estado_civil'];
        $direccion = $empleado['direccion'];
        $fecha_nacimiento = $empleado['fecha_nacimiento'];
        $sueldo = $empleado['sueldo'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../../js/funciones.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css"> 
    <title>Lista de Empleados</title>
    <style>
    body {
        background: rgb(64,166,154);
background: radial-gradient(circle, rgba(64,166,154,1) 0%, rgba(110,156,145,0.07326680672268904) 100%);
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed; /* Para mantener el fondo en su lugar */
    background-position: center; /* Para centrar el fondo */
    font-family: 'Arial', sans-serif;
    color: black; /* Para que el texto sea visible en el fondo negro */
    margin: 0; /* Eliminar márgenes */
    padding: 0; /* Eliminar relleno */
    height: 100vh; /* Altura del viewport */
    overflow: hidden; /* Evitar que el contenido se desborde */
}

    .container-fluid > h1 {
        border: 8px solid #23736A;
        padding: 15px 30px ;
        border-radius: 16px;
        box-shadow: inset 0 0 15px #23736A, 0 0 30px #23736A;
        position: relative;
    }
    h1 {
        margin-top: 100px;
        margin-bottom: 50px;
        text-transform: uppercase;
        font-size: 60px;
        background-image: url('https://cdn.pixabay.com/photo/2018/04/27/08/56/water-3354063_1280.jpg');
        background-position: center;
        -webkit-background-clip: text;
        color: transparent; 
        letter-spacing: 3px;
        cursor: pointer;
        transition: 0.5s; 
        
        text-align: center; /* Centrar horizontalmente */
        display: flex; /* Activar el modelo de caja flexible */
        justify-content: center; /* Centrar horizontalmente los elementos flexibles */
        align-items: center;

        font-weight: bold;
    }
    h1:hover{
        color: #fff;
        text-shadow: 0 0 5px #76A494,
        0 0 25px #76A494,
        0 0 50px #76A494,
        0 0 100px #76A494;
    }
    table {
        margin-top: 20px;
        width: 100%;
        background-color: rgba(210, 207, 203, 0.5); /* Celeste con opacidad */
        border-collapse: separate; /* Separar los bordes */
        border-spacing: 0; /* Espacio entre las celdas */
        border-radius: 10px; /* Bordes redondeados */
        overflow: hidden; /* Para ocultar cualquier contenido que sobresalga */
    }
    th {
        font-size: 20px;
    }
    th,td {
        text-align: center; /* Centrar los títulos de las columnas y los datos */
        padding: 3px; /* Añadir relleno a las celdas */
    }
    td{
        font-size: 15px;
    }
    .modal-header {
        background-color: rgba(173, 216, 230, 0.5); /* Asegurar que el encabezado del modal tenga el color de fondo */
        color: white;
    }
    .modal-content {
        background-color: rgba(173, 216, 230, 0.5); /* Ajusta el último valor (0.7) para cambiar el nivel de transparencia */
        border: 1px solid rgba(255, 255, 255, 0.2); /* Añade un borde semi-transparente */
        border-radius: 10px; /* Añade bordes redondeados si deseas */
    }
    .modal-title {
        color: white; /* Cambia el color del texto del título si es necesario */
    }
    .modal-footer .btn-primary {
        color: white; /* Cambia el color del texto del botón de acción */
        background-color: #007bff; /* Color de fondo del botón de acción */
        border-color: #007bff; /* Color del borde del botón de acción */
    }
    .btn-close {
        color: white; /* Cambia el color del botón de cerrar si es necesario */
    }
    .btn-success {
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
        font-weight: bold;
        font-size: 18px;
    }
    .icon-btn {
        background: none;
        border-radius: 10px;
        cursor: pointer;
        padding: 5px;
        transition: background-color 0.3s ease, transform 0.3s ease; /* Transición suave del color de fondo y del escalado */
    }
    .icon-btn img {
        width: 20px;
        height: 20px;
    }
    .icon-container {
        display: flex;
    }
    .icon-btn.update {
        background-color: yellow; /* Cambia el color de fondo del icono del lápiz a amarillo */
    }
    .icon-btn.delete {
        background-color: red; /* Cambia el color de fondo del icono del tacho de basura a rojo */
    }
    .icon-btn:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        transform: scale(1.1); /* Escalar el botón al 110% del tamaño original al pasar el cursor sobre él */
    }
    .form-control{
        font-size:15px;
    }
    #agregarDatosModalLabel{
        font-size:40px;

    }
    
</style>
</head>

<body>
<header class="header">
    <a href="#" class="logo">
        <img src="images/images.png" alt="">
    </a>

    <nav class="navbar">
        <a href="paginaPrincipal.php">Inicio</a>
        <a href="empleado.php">Empleado</a>
        <a href="Producto.php">Producto</a>
    </nav>
</header>

    <div class="container-fluid">
        <h1>Lista de Empleados</h1>
        <button class="btn btn-success icon-btn " data-bs-toggle="modal" data-bs-target="#insertarRegistroModal">Agregar empleado</button>
        <table>
            <thead class="thead-dark">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cargo</th>
                    <th>Correo</th>
                    <th>Celular</th>
                    <th>Estado civil</th>
                    <th>Dirección</th>
                    <th>Fecha de nacimiento</th>
                    <th>Sueldo</th>
                    <th colspan="2">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleados as $empleado): ?>
                    <tr>
                        <td><?= $empleado['id'] ?></td>
                        <td><?= $empleado['nombre'] ?></td>
                        <td><?= $empleado['apellido'] ?></td>
                        <td><?= $empleado['cargo'] ?></td>
                        <td><?= $empleado['correo'] ?></td>
                        <td><?= $empleado['celular'] ?></td>
                        <td><?= $empleado['estado_civil'] ?></td>
                        <td><?= $empleado['direccion'] ?></td>
                        <td><?= $empleado['fecha_nacimiento'] ?></td>
                        <td><?= $empleado['sueldo'] ?></td>
                        <td>
                            <div class="icon-container">
                                <button type="button" class="icon-btn update" data-id="<?= $empleado['id'] ?>" onclick="editEmpleado(<?= $empleado['id'] ?>)">
                                    <img src="../../icons/pencil-solid.svg" alt="Editar">
                                </button>
                                <form method="POST" action="../../controller/empleadoController.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $empleado['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="icon-btn delete" onclick="return confirmDelete()">
                                        <img src="../../icons/trash-solid.svg" alt="Eliminar">
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav>
            <ul class="pagination justify-content-center ">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Modal Agregar -->
    <div class="modal fade" id="insertarRegistroModal" tabindex="-1" role="dialog" aria-labelledby="agregarDatosModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarDatosModalLabel">Agregar Empleado</h5>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <form action="../../controller/empleadoController.php" method="POST" name="registroForm" id="registroForm">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="action" value="<?= $id ? 'update' : 'insert' ?>">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese el nombre" value="<?= $nombre ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Ingrese el apellido" value="<?= $apellido ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="cargo">Cargo</label>
                            <input type="text" id="cargo" name="cargo" class="form-control" placeholder="Ingrese el cargo" value="<?= $cargo ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" id="correo" name="correo" class="form-control" placeholder="Ingrese el correo" value="<?= $correo ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="text" id="celular" name="celular" class="form-control" placeholder="Ingrese el celular" value="<?= $celular ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="estado_civil">Estado civil</label>
                            <input type="text" id="estado_civil" name="estado_civil" class="form-control" placeholder="Ingrese el estado civil" value="<?= $estado_civil ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Direccion</label>
                            <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Ingrese la direccion" value="<?= $direccion ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="<?= $fecha_nacimiento ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="sueldo">Sueldo</label>
                            <input type="text" id="sueldo" name="sueldo" class="form-control" placeholder="Ingrese el sueldo" value="<?= $sueldo ?>" required>
                        </div>
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary icon-btn" data-bs-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary icon-btn" value="<?= $id ? 'Actualizar' : 'Agregar' ?>" id="submitButton">
                </div>
            </form>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function confirmDelete() {
            return confirm('¿Estás seguro de que deseas eliminar este registro?');
        }

        function editEmpleado(id) {
            $.ajax({
                url: '../../controller/empleadoController.php',
                type: 'GET',
                data: {
                    action: 'getEmpleado',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $('#insertarRegistroModal').modal('show');
                    $('#agregarDatosModalLabel').text('Editar Empleado');
                    $('.modal-footer > .btn-primary').text('Actualizar');
                    $('#registroForm').find('input[name="id"]').val(data.id);
                    $('#registroForm').find('input[name="nombre"]').val(data.nombre);
                    $('#registroForm').find('input[name="apellido"]').val(data.apellido);
                    $('#registroForm').find('input[name="cargo"]').val(data.cargo);
                    $('#registroForm').find('input[name="correo"]').val(data.correo);
                    $('#registroForm').find('input[name="celular"]').val(data.celular);
                    $('#registroForm').find('input[name="estado_civil"]').val(data.estado_civil);
                    $('#registroForm').find('input[name="direccion"]').val(data.direccion);
                    $('#registroForm').find('input[name="fecha_nacimiento"]').val(data.fecha_nacimiento);
                    $('#registroForm').find('input[name="sueldo"]').val(data.sueldo);
                    $('#registroForm').find('input[name="action"]').val('update');
                    $('#submitButton').val('Actualizar');
                }
            });
        }

        $(document).ready(function() {
            $("#insertarRegistroModal").on("show.bs.modal", function(event) {
                var boton = $(event.relatedTarget);
                var titulo = "Editar Empleado";

                if (Object.keys(boton).length > 0 && boton[0].textContent) {
                    titulo = "Agregar Empleado";
                }

                $('#registroForm')[0].reset();
                
                if (titulo == "Agregar Empleado") {
                    $('#registroForm').show();
                    $("#agregarDatosModalLabel").text("Agregar Empleado");
                    $('#submitButton').val('Agregar');
                    $('#registroForm').find('input[name="action"]').val('insert');
                }

                var buttonText = (titulo === "Agregar Empleado") ? "Agregar" : "Actualizar";
                $('.modal-footer > .btn-primary').text(buttonText);
            });

            $("#insertarRegistroModal").on("hidden.bs.modal", function(event) {
                $('#registroForm')[0].reset();
                $('.modal-footer > .btn-primary').text('Agregar');
                $("#agregarDatosModalLabel").text("Agregar Empleado");
                $('#registroForm').find('input[name="action"]').val('insert');
            });
        });
        
    </script>
    <script src="../../js/funciones.js"></script>
</body>
</html>
