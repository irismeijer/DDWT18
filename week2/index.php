<?php
/**
 * Controller
 * User: reinardvandalen
 * Date: 05-11-18
 * Time: 15:25
 */

include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'ddwt18_week2', 'ddwt18','ddwt18');

/* global variables */
$nbr_series = count_series($db);
$nbr_users = count_users($db);
$right_column = use_template('cards');
$template = Array(
    1 => Array('name' => 'Home','url' => '/DDWT18/week2/'),
    2 => Array('name' => 'Overview','url' => '/DDWT18/week2/overview/'),
    3 => Array('name' => 'Add Series', 'url' => '/DDWT18/week2/add/'),
    4 => Array('name' => 'My Account',  'url' => '/DDWT18/week2/myaccount/'),
    5 => Array('name' => 'Register','url' => '/DDWT18/week2/register/'),
    6 => Array('name' => 'Logout', 'url' => '/DDWT18/week2/logout/')
);


/* Landing page */
if (new_route('/DDWT18/week2/', 'get')) {

    if (isset($_GET['error_msg'])){
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Page info */
    $active_id = 1;
    $page_title = 'Home';
    $breadcrumbs = get_breadcrumbs([
        'DDWT18' => na('/DDWT18/', False),
        'Week 2' => na('/DDWT18/week2/', False),
        'Home' => na('/DDWT18/week2/', True)
    ]);
    $navigation = get_navigation($template, $active_id);

    /* Page content */
    $page_subtitle = 'The online platform to list your favorite series';
    $page_content = 'On Series Overview you can list your favorite series. You can see the favorite series of all Series Overview users. By sharing your favorite series, you can get inspired by others and explore new series.';

    /* Choose Template */
    include use_template('main');
}

/* Overview page */
elseif (new_route('/DDWT18/week2/overview/', 'get')) {
    /* Get error message from remove POST route */
    if (isset($_GET['error_msg'])){
        $error_msg = get_error($_GET['error_msg']);
    }
    /* Get Number of Series */
    $active_id = 2;

    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'DDWT18' => na('/DDWT18/', False),
        'Week 2' => na('/DDWT18/week2/', False),
        'Overview' => na('/DDWT18/week2/overview', True)
    ]);
    $navigation = get_navigation($template, $active_id);

    /* Page content */

    $page_subtitle = 'The overview of all series';
    $page_content = 'Here you find all series listed on Series Overview.';
    $left_content = get_serie_table($db, get_series($db));

    /* Choose Template */
    include use_template('main');
}

/* Single Serie */
elseif (new_route('/DDWT18/week2/serie/', 'get')) {
    /* Get series from db */
    $serie_id = $_GET['serie_id'];
    $serie_info = get_serieinfo($db, $serie_id);
    $added_by = get_name($db,$serie_info['user']);

    $display_buttons = False;
    if (check_login()){
        if ($_SESSION['user_id'] == $serie_info['user']){
            $display_buttons = True;
        }
    }

    /* Page info */
    $active_id = 2;
    $page_title = $serie_info['name'];
    $breadcrumbs = get_breadcrumbs([
        'DDWT18' => na('/DDWT18/', False),
        'Week 2' => na('/DDWT18/week2/', False),
        'Overview' => na('/DDWT18/week2/overview/', False),
        $serie_info['name'] => na('/DDWT18/week2/serie/?serie_id='.$serie_id, True)
    ]);
    $navigation = get_navigation($template, $active_id);

    /* Page content */
    $page_subtitle = sprintf("Information about %s", $serie_info['name']);
    $page_content = $serie_info['abstract'];
    $nbr_seasons = $serie_info['seasons'];
    $creators = $serie_info['creator'];

    /* Choose Template */
    include use_template('serie');
}

/* Add serie GET */
elseif (new_route('/DDWT18/week2/add/', 'get')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/DDWT18/week2/login/');
    }

    /* Get error message from add POST route */
    if(isset($_GET['error_msg'])){
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Page info */
    $active_id = 3;
    $page_title = 'Add Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT18' => na('/DDWT18/', False),
        'Week 2' => na('/DDWT18/week2/', False),
        'Add Series' => na('/DDWT18/week2/new/', True)
    ]);
    $navigation = get_navigation($template, $active_id);

    /* Page content */
    $page_subtitle = 'Add your favorite series';
    $page_content = 'Fill in the details of you favorite series.';
    $submit_btn = "Add Series";
    $form_action = '/DDWT18/week2/add/';

    /* Choose Template */
    include use_template('new');
}

/* Add serie POST */
elseif (new_route('/DDWT18/week2/add/', 'post')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/DDWT18/week2/login/');
    }
    $feedback = add_serie($db, $_POST);
    /* Redirect to serie GET route */
    redirect(sprintf('/DDWT18/week2/add/?error_msg=%s', json_encode($feedback)));
}

/* Edit serie GET */
elseif (new_route('/DDWT18/week2/edit/', 'get')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/DDWT18/week2/login/');
    }
    /* Get error message from edit POST route */
    if (isset($_GET['error_msg'])){
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Get serie info from db */
    $serie_id = $_GET['serie_id'];
    $serie_info = get_serieinfo($db, $serie_id);

    /* Page info */
    $active_id = 0;
    $page_title = 'Edit Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT18' => na('/DDWT18/', False),
        'Week 2' => na('/DDWT18/week2/', False),
        sprintf("Edit Series %s", $serie_info['name']) => na('/DDWT18/week2/new/', True)
    ]);
    $navigation = get_navigation($template, $active_id);
    /* Page content */
    $page_subtitle = sprintf("Edit %s", $serie_info['name']);
    $page_content = 'Edit the series below.';
    $submit_btn = "Edit Series";
    $form_action = '/DDWT18/week2/edit/';

    /* Choose Template */
    include use_template('new');
}

/* Edit serie POST */
elseif (new_route('/DDWT18/week2/edit/', 'post')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/DDWT18/week2/login/');
    }
    $feedback = update_serie($db, $_POST);
    /* Redirect to serie GET route */
    $serie_id = $_POST['serie_id'];
    $error_msg = json_encode($feedback);
    redirect(sprintf('/DDWT18/week2/edit/?serie_id='.$serie_id.'/?error_msg=%s', $error_msg));
}

/* Remove serie */
elseif (new_route('/DDWT18/week2/remove/', 'post')) {
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/DDWT18/week2/login/');
    }

    $feedback = remove_serie($db, $_POST['serie_id']);
    /* Redirect to serie GET route */
    redirect(sprintf('/DDWT18/week2/overview/?error_msg=%s', json_encode($feedback)));
}
/* My account GET */
elseif (new_route('/DDWT18/week2/myaccount/', 'get')){
    /* Check if logged in */
    if ( !check_login() ) {
        redirect('/DDWT18/week2/login/');
    }

    /* Get error message from POST route */
    if (isset($_GET['error_msg'])){
        $error_msg = get_error($_GET['error_msg']);
    }
    $user = get_name($db, $_SESSION['user_id']);

    /* Page info */
    $active_id = 4;
    $page_title = 'My Account';
    $breadcrumbs = get_breadcrumbs([
        'DDWT18' => na('/DDWT18/', False),
        'Week 2' => na('/DDWT18/week2/', False),
        'My Account' => na('/DDWT18/week2/myaccount/', True)
    ]);
    $navigation = get_navigation($template, $active_id);
    /* Page content */
    $page_subtitle = sprintf('User: '.$user.'.');
    $page_content = 'Below the user information';


    /* Choose Template */
    include use_template('account');

}

/* Register GET */
elseif (new_route('/DDWT18/week2/register/', 'get')){
    /* Get error message from edit POST route */
    if (isset($_GET['error_msg'])){
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Page info */
    $active_id = 5;
    $page_title = 'Register';
    $breadcrumbs = get_breadcrumbs([
        'DDWT18' => na('/DDWT18/', False),
        'Week 2' => na('/DDWT18/week2/', False),
        'Register' => na('/DDWT18/week2/register/', True)
    ]);
    $navigation = get_navigation($template, $active_id);
    /* Page content */
    $page_subtitle = sprintf('Register as a new user. Fill in the form below.');

    /* Choose Template */
    include use_template('register');
}

/* Register POST */
elseif (new_route('/DDWT18/week2/register/', 'post')) {
    $error_msg = register_user($db, $_POST);
    /* Redirect to homepage */
    redirect(sprintf('/DDWT18/week2/register/?error_msg=%s', json_encode($error_msg)));
}

/* Login GET */
elseif (new_route('/DDWT18/week2/login/', 'get')){
    /* Get error message from edit POST route */
    if (isset($_GET['error_msg'])){
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Page info */
    $active_id = 0;
    $page_title = 'Login';
    $breadcrumbs = get_breadcrumbs([
        'DDWT18' => na('/DDWT18/', False),
        'Week 2' => na('/DDWT18/week2/', False),
        'Login' => na('/DDWT18/week2/login/', True)
    ]);
    $navigation = get_navigation($template, $active_id);
    /* Page content */
    $page_subtitle = sprintf('Fill in the form below.');

    /* Choose Template */
    include use_template('login');
}

/* Login POST */
elseif (new_route('/DDWT18/week2/login/', 'post')) {
    $error_msg = login_user($db, $_POST);
    redirect(sprintf('/DDWT18/week2/login/?error_msg=%s', json_encode($error_msg)));
}

/* Logout GET */
elseif (new_route('/DDWT18/week2/logout/', 'get')){
    $error_msg = logout_user();
    redirect(sprintf('/DDWT18/week2/?error_msg=%s', json_encode($error_msg)));
}

else {
    http_response_code(404);
}