<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    try {
        // Verificar si el correo ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->fetchColumn() > 0) {
            header("Location: ../register.php?error=email_exists");
            exit();
        }

        // Encriptar contraseña
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Insertar nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, apellido, correo, contrasena, tipo_usuario, estado) 
                VALUES (?, ?, ?, ?, 'Administrador', 'Activo')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $apellido, $correo, $password_hashed]);

        header("Location: ../login.php?registered=1");
    } catch (PDOException $e) {
        header("Location: ../register.php?error=db");
    }
}
?>
