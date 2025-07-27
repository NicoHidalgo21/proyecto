<?php
header('Content-Type: application/json');

// Configuración conexión - AJUSTA según tu entorno
$servername = "localhost";
$username = "root";
$password = ""; // tu contraseña de base de datos
$dbname = "tu_base_de_datos"; // cambia por tu nombre

// Obtener datos JSON enviados desde JS
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["success" => false, "msg" => "Datos inválidos"]);
    exit;
}

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "msg" => "Error de conexión a la base de datos"]);
    exit;
}

// Escapar datos para evitar inyección SQL
$nombre = $conn->real_escape_string($input["nombre"]);
$direccion = $conn->real_escape_string($input["direccion"]);
$telefono = $conn->real_escape_string($input["telefono"]);
$pago = $conn->real_escape_string($input["pago"]);
$carrito_json = $conn->real_escape_string(json_encode($input["carrito"]));

// Insertar la compra
$sql = "INSERT INTO compras (nombre, direccion, telefono, forma_pago, carrito_json) VALUES ('$nombre', '$direccion', '$telefono', '$pago', '$carrito_json')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "msg" => "Error al guardar la compra"]);
}

$conn->close();
?>
