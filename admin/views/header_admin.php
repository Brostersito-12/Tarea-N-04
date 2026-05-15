<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Taller Mecánico</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="logo">Taller<span>Admin</span></div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="clientes.php"><i class="fas fa-users"></i> Clientes</a></li>
                    <li><a href="vehiculos.php"><i class="fas fa-car"></i> Vehículos</a></li>
                    <li><a href="mecanicos.php"><i class="fas fa-user-cog"></i> Mecánicos</a></li>
                    <li><a href="servicios.php"><i class="fas fa-tools"></i> Servicios</a></li>
                    <li><a href="ordenes.php"><i class="fas fa-file-invoice"></i> Órdenes</a></li>
                    <li><a href="pagos.php"><i class="fas fa-money-bill-wave"></i> Pagos</a></li>
                    <li><a href="actions/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-content">
            <header class="admin-header">
                <div class="user-info">
                    Bienvenido, <strong><?php echo $_SESSION['admin_nombre']; ?></strong>
                </div>
            </header>
            <div class="admin-container">
