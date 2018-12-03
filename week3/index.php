<?php
/**
 * Controller
 * User: reinardvandalen
 * Date: 05-11-18
 * Time: 15:25
 */

/* Require composer autoloader */
require __DIR__ . '/vendor/autoload.php';

/* Include model.php */
include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'ddwt18_week3', 'ddwt18', 'ddwt18');
// Set credentials for authentication
$cred = set_cred('ddwt18', 'ddwt18');

/* Create Router instance */
$router = new \Bramus\Router\Router();

// Add routes here

// Checks the authentication
$router->before('GET|POST|PUT|DELETE', '/api/.*', function() use($cred) {
    if (!check_cred($cred)){
        $feedback = [
            'type' => 'danger',
            'message' => 'Authentication failed. Please check the credentials.'
        ];
        echo json_encode($feedback);
        exit();
    }
});

$router->mount('/api', function() use ($router, $db) {
    http_content_type('application/json');

    /* GET for reading all series */
    $router->get('/series', function() use($db) {
        $series = get_series($db);
        // converts array series to json
        echo json_encode($series);
    });

    /* GET for reading individual series */
    $router->get('/series/(\d+)', function($id) use($db) {
        $serie = get_serieinfo($db, $id);
        echo json_encode($serie);
    });

    /* DELETE a individual serie */
    $router->delete('/series/(\d+)', function($id) use($db) {
        $feedback = remove_serie($db, $id);
        echo json_encode($feedback);
    });

    /* POST for adding series */
    $router->post('/series', function() use($db) {
        $feedback = add_series($db, $_POST);
        echo json_encode($feedback);
    });

    /* PUT for updating a serie */
    $router->put('/series/(\d+)', function($id) use($db) {
        $_PUT = array();
        parse_str(file_get_contents('php://input'), $_PUT);

        $serie_info = $_PUT + ["serie_id" => $id];
        $feedback = update_serie($db, $serie_info);
        echo json_encode($feedback);
    });

});


$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo 'This page does not exists.';

});



/* Run the router */
$router->run();
