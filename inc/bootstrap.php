<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

const PROJECT_ROOT_PATH = __DIR__ . "/../";
const MODULE_NAME = 1;

// include main configuration file
require_once PROJECT_ROOT_PATH . "/inc/config.php";

// include the use model file
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
require_once PROJECT_ROOT_PATH . "/Model/UserModel.php";

// include validation file
require_once PROJECT_ROOT_PATH . "/Validations/UserValidation.php";

// include the base controller file
require_once PROJECT_ROOT_PATH . "/Controller/Api/BaseController.php";
require_once PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
