<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if(!$auth){
    header('Location: /centinela/index.php');
    exit;
}

include '../templates/header.php';

?>

<body>
    <main class="contenedor seccion">
        <h1>Panel de Administrador</h1>

        
        <div class="dashboard-cards">

            
            <div class="admin-card">
                <div class="admin-card-header">
                    <span class="card-icon">📋</span>
                    <h2>Gestión de Atletas</h2>
                </div>
                <div class="admin-card-body">
                    <p>Administrar Atletas, Representantes y Categorias</p>
                </div>
                <div class="admin-card-footer">
                    <a class="btn-card" href="../admin/estudiantes/">Panel de Atletas</a>
                </div>
            </div>

            
            <div class="admin-card">
                <div class="admin-card-header">
                    <span class="card-icon">📅</span>
                    <h2>Gestión de Eventos</h2>
                </div>
                <div class="admin-card-body">
                    <p>Administrar Eventos - Torneos, partidos, horarios y resultados.</p>
                </div>
                <div class="admin-card-footer">
                    <a class="btn-card" href="../admin/eventos/">Panel de Eventos</a>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <span class="card-icon">📅</span>
                    <h2>Precios</h2>
                </div>
                <div class="admin-card-body">
                    <p>Administrar el costo de la mensualidad, inscripciones y arbitraje que se va a mostrar en la vista pública.</p>
                </div>
                <div class="admin-card-footer">
                    <a class="btn-card" href="../admin/planes/">Panel de Planes de Pago</a>
                </div>
            </div>

        </div>
        
    </main>

