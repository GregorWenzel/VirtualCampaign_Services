<?php

class Represent {
    protected $data = false;
    protected $err = false;

    public function __construct($data, $err) {
        $this->data = $data;
        $this->err = $err;
    }

    public function getJson() {
        ob_clean();
        ob_start();
        header('Content-Type: application/json');
        if ($this->err) {
            http_response_code(400);
            trigger_error("User error: " . $this->err, E_USER_ERROR);

            return json_encode(['Error' => $this->err]);
        }

        return json_encode($this->data);
    }
}