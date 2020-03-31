<?php
class BaseApp {
    public function __construct($app, $connection) {
        $this->app = $app;
        $this->connection = $connection;
    }
}