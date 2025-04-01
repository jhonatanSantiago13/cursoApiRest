<?php
/*
! ESTE ES UN SCRIPT DONDE CREAREMOS CON LOS SERVICIOS  CON LOS VERBOS GET, POST, PUT Y DELETE
*/



// if(!isset($_SERVER['PHP_AUTH_USER'])){
//     header('WWW-Authenticate: Basic realm="Mi dominio"');
//     header('HTTP/1.0 401 Unauthorized'); //401 Unauthorized
//     echo 'Texto a enviar si el usuario pulsa el botón Cancelar'; //Mensaje que se muestra si el usuario cancela la autentificación
//     exit;        
// }else{
//     if($_SERVER['PHP_AUTH_USER'] == 'admin' && $_SERVER['PHP_AUTH_PW'] == '1234'){
//         echo 'Bienvenido';
//     }else{
//         header('HTTP/1.0 403 Forbidden'); //403 Forbidden
//         echo 'Usuario o contraseña incorrectos';
//         exit;    
//     }
// }

/* Autentificación via HTTP */

/* $user = array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : '';
$pwd = array_key_exists('PHP_AUTH_PW', $_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '';   */  

/* 

? if (array_key_exists('PHP_AUTH_USER', $_SERVER)) {
    ? $user = $_SERVER['PHP_AUTH_USER'];
? } else {
    ? $user = '';
? }

? if (array_key_exists('PHP_AUTH_PW', $_SERVER)) {
    ? $pwd = $_SERVER['PHP_AUTH_PW'];
? } else {
    ? $pwd = '';
? }

? if ($user != 'admin' || $pwd != '1234') {
    ? die;
? } 

*/

/* AUTENTIFICACIÓN POR HMAC 

*/

// verificar que la información enviada por el cliente es correcta
/* 

if(!array_key_exists('HTTP_X_HASH', $_SERVER) || 
   !array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) || 
   !array_key_exists('HTTP_X_UID', $_SERVER)){
    die;
}

list($hash, $uid, $timestamp) = [
    $_SERVER['HTTP_X_HASH'],
    $_SERVER['HTTP_X_UID'],
    $_SERVER['HTTP_X_TIMESTAMP'],
];

//llave secreta
$secret = 'esto es un secreto';
//generar hash
$newHash = sha1($uid . $timestamp . $secret);


//comparar los hash del usuario y del servidor
if($newHash !== $hash){

    echo "acceso denegado";
    die;
}
 */

/* AUTENTIFICACIÓN POR ACCESS TOKEN */

// verificar que el servidor haya recibido un token del cliente
 if(!array_key_exists('HTTP_X_TOKEN', $_SERVER)){
    die;
}

// servidor de autentificación
$url = 'http://localhost:8080/cursoApiRest/auth_server.php';

//inicializar cURL para hacer una petición a un servidor
$ch = curl_init($url);

//configurar la petición cURL
curl_setopt( $ch, CURLOPT_HTTPHEADER, [
	"X-Token: {$_SERVER['HTTP_X_TOKEN']}",
]);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$ret = curl_exec( $ch );

/* if ( curl_errno($ch) != 0 ) {
	die ( curl_error($ch) );
} */

if ( $ret !== 'true' ) {
	http_response_code( 403 );

	echo json_encode(
        [
            'error' => 'Bad token',
        ]
    );

    die;
}

//avisarle al cliente que vamos a devolver un JSON
header('Content-Type: application/json');

/* definir los tipos de recursos que seran consultables*/
$allowedResourceTypes = [
    'books',
    'authors',
    'genres',
];

/* validar si el tipo de recurso que esta solitando esta permitido dentro de
la lista de recurso permitidos*/

$resourceType = $_GET['resource_type'];

// la función in_array() busca un valor en un array
// si lo encuentra devuelve true, si no lo encuentra devuelve false 
if ( !in_array( $resourceType, $allowedResourceTypes ) ) {
	http_response_code( 400 );
	echo json_encode(
		[
			'error' => "$resourceType is un unkown",
		]
	);

	die;
}

// definir los recursos
$books = [

    1 => [
        'titulo' => 'Lo que el viento se llevo',
        'id_autor' => 2,
        'id_genero' => 2,
    ],
    2 => [
        'titulo' => 'La Iliada',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
    3 => [
        'titulo' => 'La Odisea',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
 

];



//Levantar el id del recurso buscado
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';

switch(strtoupper($_SERVER['REQUEST_METHOD'])){
    case 'GET':

        /* if(empty($resourceId)){

            echo json_encode($books);

        }else{
            if(array_key_exists($resourceId, $books)){
                echo json_encode($books[$resourceId]);
            }else{
                echo json_encode('El recurso solicitado no existe');
            }
        } */

        if ( "books" !== $resourceType ) {
			http_response_code( 404 );

			echo json_encode(
				[
					'error' => $resourceType.' not yet implemented :(',
				]
			);

			die;
		}

        if ( !empty( $resourceId ) ) {
			if ( array_key_exists( $resourceId, $books ) ) {
				echo json_encode(
					$books[ $resourceId ]
				);
			} else {
				http_response_code( 404 );

				echo json_encode(
					[
						'error' => 'Book '.$resourceId.' not found :(',
					]
				);
			}
		} else {
			echo json_encode(
				$books
			);
		}

		die;

        
        break;
    case 'POST':

        //Tomar la entrada POST cruda
        $json = file_get_contents('php://input');

        //Procesar el JSON y convertirlo en un array
        /* ejecutar el codigo en curl
        ? curl -X "POST" http://localhost:8080/cursoApiRest/router.php/books -d "{ \"titulo\":\"Nuevo Libro\",\"id_autor\": 1,\"id_genero\": 2}"    
        */
        $books[] = json_decode($json, true);

        // echo array_keys($books)[count($books)-1];
        echo json_encode($books);
        
        break;
    case 'PUT':

        /* 
        ? curl -X "PUT" http://localhost:8080/cursoApiRest/router.php/books/1 -d "{ \"titulo\":\"Nuevo Libro\",\"id_autor\": 1,\"id_genero\": 2}"

        */

        //Validar que el recurso exista
        if(!empty($resourceId) && array_key_exists($resourceId, $books)){
            //Tomar la entrada cruda
            $json = file_get_contents('php://input');

            //Procesar el JSON 
            //reemplazando la informacion del recurso
            $books[$resourceId] = json_decode($json, true);

            //Retornar la coleccion modificada en formato JSON
            echo json_encode($books);
        } 
        
        break;
    case 'DELETE':

        /* 
        ? curl -X "DELETE" http://localhost:8080/cursoApiRest/router.php/books/1
        
        */

        //validar que el recurso exista
        if(!empty($resourceId) && array_key_exists($resourceId, $books)){
            //Eliminar el recurso
            unset($books[$resourceId]);
        }
        
        echo json_encode($books);

        break;
   
}

?>