<?php
// head.php - VERSIÓN CORREGIDA
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo redirigir si estamos en una página que requiere login
// No redirigir desde head.php directamente, dejar que cada página lo maneje
// La función mostrarMensaje sigue igual

// Función para mostrar mensajes
function mostrarMensaje() {
    if(isset($_SESSION['msg'])) {
        $m = $_SESSION['msg'];
        echo "<div class='mb-4 px-4 py-2 rounded-lg text-white "
            . ($m['tipo']=="success"?"bg-green-500":($m['tipo']=="info"?"bg-blue-500":"bg-red-500"))
            . "'>" . htmlspecialchars($m['texto']) . "</div>";
        unset($_SESSION['msg']);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Gestión de Recursos - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root { --accent: #4f46e5; --muted: #6b7280; }
    body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .fade-in { animation: fadeIn 160ms ease-out; }
    @keyframes fadeIn { from { opacity: 0 } to { opacity: 1 } }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">