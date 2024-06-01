<?php
require_once '../../db/conexion.php';
require_once '../../modals/productoModel.php';

$model = new ProductoModel($conn);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$limit = 10; // Número de empleados por página
$offset = ($page - 1) * $limit; // Calcular el desplazamiento

$totalProductos = $model->countProductos(); // Método que cuenta el total de empleados
$totalPages = ceil($totalProductos / $limit);

$Productos = $model->getProductosPaginated($limit, $offset); // Método que obtiene empleados con límite y desplazamiento

$Id = isset($_GET['Id']) ? intval($_GET['Id']) : null; // Asegurando que el ID sea un entero

$Nombre = "";
$Proveedor = "";
$Fecha_de_caducidad	 = "";
$Stock = "";
$Tipo = "";

if ($Id) {
    $producto = $model->getProductoById($Id); // Suponiendo que tengas un método getEmpleadoById en tu modelo
    if ($producto) {
        // Si se encuentra el empleado, asignamos sus valores a las variables correspondientes
        $Nombre = $producto['Nombre'];
        $Proveedor = $producto['Proveedor'];
        $Fecha_de_caducidad = $producto['Fecha_de_caducidad'];
        $Stock = $producto['Stock'];
        $Tipo = $producto['Tipo'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css"> 
    <title>Lista de Productos</title>
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
    .center-buttons {
        display: flex; /* Utiliza flexbox */
        justify-content: center; /* Centra horizontalmente los elementos */
    }
    .form-control{
        font-size:15px;
    }
    #agregarDatosModalLabel{
        font-size:40px;
    }
    .icon-btn-update{
        text-align: center;
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
        <h1>Lista de Productos</h1>
        <button class="btn btn-success icon-btn " data-bs-toggle="modal" data-bs-target="#insertarRegistroModal">Agregar Producto</button>
        <table>
            <thead class="thead-dark">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Proveedor</th>
                    <th>Fecha_de_caducidad</th>
                    <th>Stock</th>
                    <th>Tipo</th>
                    <th colspan="2">Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($Productos as $producto): ?>
                    <tr>
                        <td><?= $producto['Id'] ?></td>
                        <td><?= $producto['Nombre'] ?></td>
                        <td><?= $producto['Proveedor'] ?></td>
                        <td><?= $producto['Fecha_de_caducidad'] ?></td>
                        <td><?= $producto['Stock'] ?></td>
                        <td><?= $producto['Tipo'] ?></td>
                        <td>
                            <div class="icon-container">
                                <div class="center-buttons">
                                <button type="button" class="icon-btn update" data-id="<?= $producto['Id'] ?>" onclick="editProducto(<?= $producto['Id'] ?>)">
                                    <img src="../../icons/pencil-solid.svg" alt="Editar">
                                </button>
                                <form method="POST" action="../../controller/productoController.php" style="display:inline;">
                                    <input type="hidden" name="Id" value="<?= $producto['Id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="icon-btn delete" onclick="return confirmDelete()">
                                        <img src="../../icons/trash-solid.svg" alt="Eliminar">
                                    </button>
                                </form>
                            </div>
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
                <h5 class="modal-title" id="agregarDatosModalLabel">Agregar Producto</h5>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <form action="../../controller/productoController.php" method="POST" name="registroForm" id="registroForm">
                <input type="hidden" name="Id" value="<?= $Id ?>">
                <input type="hidden" name="action" value="<?= $Id ? 'update' : 'insert' ?>">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="Nombre" class="form-control" placeholder="Ingrese el nombre" value="<?= $Nombre ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Proveedor</label>
                            <input type="text" id="Proveedor" name="Proveedor" class="form-control" placeholder="Ingrese el Proveedor" value="<?= $Proveedor ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="cargo">Fecha de caducidad</label>
                            <input type="date" id="Fecha_de_caducidad" name="Fecha_de_caducidad" class="form-control" placeholder="Ingrese la fecha de caducidad" value="<?= $Fecha_de_caducidad ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Stock</label>
                            <input type="text" id="Stock" name="Stock" class="form-control" placeholder="Ingrese el stock" value="<?= $Stock ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="celular">Tipo</label>
                            <input type="text" id="Tipo" name="Tipo" class="form-control" placeholder="Ingrese el tipo" value="<?= $Tipo	 ?>" required>
                        </div>
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary icon-btn" data-bs-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary icon-btn" value="<?= $Id ? 'Actualizar' : 'Agregar' ?>" id="submitButton">
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

        function editProducto(Id) {
            $.ajax({
                url: '../../controller/productoController.php',
                type: 'GET',
                data: {
                    action: 'getProducto',
                    Id: Id
                },
                dataType: 'json',
                success: function(data) {
                    $('#insertarRegistroModal').modal('show');
                    $('#agregarDatosModalLabel').text('Editar Producto');
                    $('.modal-footer > .btn-primary').text('Actualizar');
                    $('#registroForm').find('input[name="Id"]').val(data.Id);
                    $('#registroForm').find('input[name="Nombre"]').val(data.Nombre);
                    $('#registroForm').find('input[name="Proveedor"]').val(data.Proveedor);
                    $('#registroForm').find('input[name="Fecha_de_caducidad"]').val(data.Fecha_de_caducidad);
                    $('#registroForm').find('input[name="Stock"]').val(data.Stock);
                    $('#registroForm').find('input[name="Tipo"]').val(data.Tipo);
                    $('#registroForm').find('input[name="action"]').val('update');
                    $('#submitButton').val('Actualizar');
                }
            });
        }

        $(document).ready(function() {
            $("#insertarRegistroModal").on("show.bs.modal", function(event) {
                var boton = $(event.relatedTarget);
                var titulo = "Editar Producto";

                if (Object.keys(boton).length > 0 && boton[0].textContent) {
                    titulo = "Agregar Producto";
                }

                $('#registroForm')[0].reset();
                
                if (titulo == "Agregar Producto") {
                    $('#registroForm').show();
                    $("#agregarDatosModalLabel").text("Agregar Producto");
                    $('#submitButton').val('Agregar');
                    $('#registroForm').find('input[name="action"]').val('insert');
                }

                var buttonText = (titulo === "Agregar Producto") ? "Agregar" : "Actualizar";
                $('.modal-footer > .btn-primary').text(buttonText);
            });

            $("#insertarRegistroModal").on("hidden.bs.modal", function(event) {
                $('#registroForm')[0].reset();
                $('.modal-footer > .btn-primary').text('Agregar');
                $("#agregarDatosModalLabel").text("Agregar Producto");
                $('#registroForm').find('input[name="action"]').val('insert');
            });
        });
        function deleteProducto(Id) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        $.ajax({
            url: '../../controller/productoController.php',
            type: 'POST',
            data: {
                action: 'delete',
                Id: Id
            },
            success: function(response) {
                // Manejar la respuesta según sea necesario, por ejemplo, recargar la página
                location.reload();
            },
            error: function(xhr, status, error) {
                // Manejar errores
                console.error(xhr.responseText);
            }
        });
    }
}
ID

    </script>

</body>
</html>
