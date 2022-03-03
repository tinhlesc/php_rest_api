<?php

    require __DIR__ . "/inc/bootstrap.php";

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri);

    if (!isset($uri[MODULE_NAME])) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    $requestMethod = $_SERVER["REQUEST_METHOD"];
    switch ($requestMethod) {
        case 'POST':
            $action = 'create';
            break;
        case 'PUT':
        case 'PATCH':
            $action = 'update';
            break;
        case 'DELETE':
            $action = 'delete';
            break;
        default:
            $action = 'get';
    }

    $objFeedController = new UserController(new UserModel());
    $strMethodName = $action. 'Action';
    $objFeedController->{$strMethodName}();
