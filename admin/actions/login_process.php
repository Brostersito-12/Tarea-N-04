<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo_input = $_POST['correo'];
    $pass_input = $_POST['password'];

    try {
        // Buscar usuario por correo
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ? AND estado = 'Activo'");
        $stmt->execute([$correo_input]);
        $user = $stmt->fetch();

        if ($user) {
            // Verificar contraseña usando password_verify para mayor seguridad
            if (password_verify($pass_input, $user['contrasena'])) {
                $_SESSION['admin_id'] = $user['id_usuario'];
                $_SESSION['admin_nombre'] = $user['nombre'] . ' ' . $user['apellido'];
                header("Location: ../dashboard.php");
                exit();
            }
            
            // Fallback para contraseñas en texto plano (si ya existen en tu DB)
            if ($pass_input === $user['contrasena']) {
                $_SESSION['admin_id'] = $user['id_usuario'];
                $_SESSION['admin_nombre'] = $user['nombre'] . ' ' . $user['apellido'];
                header("Location: ../dashboard.php");
                exit();
            }
        }
        
        // Fallback para pruebas rápidas
        if ($correo_input === 'admin@admin.com' && $pass_input === 'admin') {
            $_SESSION['admin_id'] = 0;
            $_SESSION['admin_nombre'] = 'Admin Root';
            header("Location: ../dashboard.php");
            exit();
        }

        header("Location: ../login.php?error=1");
    } catch (PDOException $e) {
        header("Location: ../login.php?error=db");
    }
}
?>
