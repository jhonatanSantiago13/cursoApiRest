<?php
// Definir la URL del endpoint de tu API
$url = "http://localhost:8080/cursoApiRest/router.php/books/1";

// Inicializar la sesión de cURL
$curl = curl_init($url); // Se establece la URL para la solicitud

// Configurar los encabezados de la petición
$headers = [
    "X-Token: d2d957cfdbd61dd965ec9e727ace9e2c35361b4" // Token necesario para autenticación
];

// Configurar las opciones de cURL
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); // Se añaden los encabezados HTTP
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Indica que la respuesta debe ser devuelta como cadena de texto

// Ejecutar la solicitud y obtener la respuesta del servidor
$response = curl_exec($curl); // Se envía la solicitud al servidor

// Verificar si ocurrió algún error durante la ejecución de cURL
if (curl_errno($curl)) {
    // Imprimir el error si ocurre
    echo "Error en cURL: " . curl_error($curl);
} else {
    // Decodificar la respuesta JSON
    $jsonData = json_decode($response, true); // Convierte el JSON en un array asociativo

    // Verificar si la decodificación fue exitosa
    if ($jsonData) {
        // Imprimir el contenido del JSON de forma estructurada
        print_r($jsonData);
    } else {
        // Mostrar un mensaje de error si el JSON no pudo ser decodificado
        echo "Error al decodificar el JSON: " . $response;
    }
}

// Cerrar la sesión de cURL para liberar recursos
curl_close($curl);
?>