<?php 

// recibe le método de la petición
$method = $_SERVER['REQUEST_METHOD'];

/* 
? este es un token que estara alamacenado en la base de datos y que se le asignara a un usuario
? para que pueda acceder a la API
*/
$token = sha1('esto es un secreto');

// verifica que la petición sea de tipo POST
if($method === 'POST'){

    // VERIFICAR QUE LAS CREDIENCIALES SEAN VALIADAS
    if ( !array_key_exists( 'HTTP_X_CLIENT_ID', $_SERVER ) || !array_key_exists( 'HTTP_X_SECRET', $_SERVER ) ) {
        http_response_code( 400 );

        die( 'Faltan parametros' );
    }

    $clientId = $_SERVER['HTTP_X_CLIENT_ID'];
    $secret = $_SERVER['HTTP_X_SECRET'];

    if ( $clientId !== '1' || $secret !== 'SuperSecreto!' ) {
        http_response_code( 403 );

        die( 'No autorizado' );

    }

    echo "$token";

}elseif($method === 'GET'){

    if ( !array_key_exists( 'HTTP_X_TOKEN', $_SERVER ) ) {
        http_response_code( 400 );

        die ( 'Faltan parametros' );
    }

    if ( $_SERVER['HTTP_X_TOKEN'] == $token ) {
        echo 'true';
    } else {
        echo 'false';
    }

}else{
    echo 'false';
}

?>