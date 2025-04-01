<?php

// Eliminar la parte de la ruta que no es relevante
// Aquí usamos str_replace para eliminar "/cursoApiRest/router.php" de la URL completa.
// Esto nos permite trabajar únicamente con la parte de la URL que contiene los datos importantes (como "/books" o "/books/1").
$requestUri = str_replace('/cursoApiRest/router.php', '', $_SERVER["REQUEST_URI"]);

// ------------------------------------------
// Primera condición: Rutas del tipo "/recurso/id"
// ------------------------------------------
// Usamos una expresión regular para buscar coincidencias en la URL
// que sigan el formato "/recurso/id" (por ejemplo, "/books/1").
if (preg_match('/\/([^\/]+)\/([^\/]+)/', $requestUri, $matches)) {
    // Capturamos el tipo de recurso (ejemplo: "books") y lo asignamos a $_GET['resource_type']
    $_GET['resource_type'] = $matches[1];

    // Capturamos el ID del recurso (ejemplo: "1") y lo asignamos a $_GET['resource_id']
    $_GET['resource_id'] = $matches[2];

    // Incluimos el archivo server.php, que se encargará de procesar esta solicitud
    require 'server.php';

// ------------------------------------------
// Segunda condición: Rutas del tipo "/recurso"
// ------------------------------------------
// Si no hay un ID en la URL, buscamos un formato que tenga solo "/recurso" (por ejemplo, "/books").
} elseif (preg_match('/\/([^\/]+)\/?/', $requestUri, $matches)) {
    // Capturamos el tipo de recurso (ejemplo: "books") y lo asignamos a $_GET['resource_type']
    $_GET['resource_type'] = $matches[1];

    // Incluimos el archivo server.php para procesar la solicitud
    require 'server.php';

// ------------------------------------------
// Si ninguna condición anterior coincide
// ------------------------------------------
// Esto ocurre si la URL no tiene un formato válido.
} else {
    // Registramos un mensaje en el log indicando que no hubo coincidencias
    error_log('No matches');

    // Devolvemos un código HTTP 404 indicando "Recurso no encontrado"
    http_response_code(404);
}


?>