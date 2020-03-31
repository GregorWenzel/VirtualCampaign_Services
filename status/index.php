<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/includes/config.php';
require_once __DIR__ . '/app/settings.php';
require_once __DIR__ . '/app/rendermanager.php';

session_start();

$mysql = new Simplon\Mysql\Mysql(
    $db_arr['host'],
    $db_arr['username'],// user
    $db_arr['passwd'],  // password
    $db_arr['dbname']  // database
);

$app = new \Slim\Slim();

$heckAccess = function() {
    if (isset($_GET['hash']) && $_GET['hash'] == HASH) {
        return;
    }
    if (isset($_POST['hash']) && $_POST['hash'] == HASH) {
        return;
    }

    $app = \Slim\Slim::getInstance();
    $app->redirect('404');
};


$app->get('/settings', $heckAccess, function () use ($app, $mysql) {
    $settings = new SettingsApp($app, $mysql);
    return $settings->getSettings();
});

$app->get('/rendermanager', $heckAccess, function () use ($app, $mysql) {
    $manager = new RenderManagerApp($app, $mysql);
    return $manager->getList();
});
$app->post('/rendermanager', $heckAccess, function () use ($app, $mysql) {
    $action = isset($_POST['action']) ? $_POST['action'] : false;

    $manager = new RenderManagerApp($app, $mysql);
    switch ($action) {
        case 'Save':
            $manager->saveItem($_POST);
            return $app->redirect("./rendermanager?hash={$_POST['hash']}");
        case 'Activate':
            $manager->forceActivate($_POST);
            return $app->redirect("./rendermanager?hash={$_POST['hash']}");
        default:
            return $app->redirect('404');
    }
});

$app->post('/update', $heckAccess, function () use ($app, $mysql) {
    $settings = new SettingsApp($app, $mysql);
    return $settings->updateSettings($_POST);
});

$app->get('/', $heckAccess, function () use ($app, $mysql) {
    $settings = new SettingsApp($app, $mysql);
    return $settings->getStat();
});

$app->run();
