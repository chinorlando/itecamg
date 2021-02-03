<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {
    // a esta funcion estan llamando desde el scrip csv_import.js
    public function foo() {
        $this->send(array('foo' => 'bar'));
    }

    private function send($array) {

        if (!is_array($array)) return false;

        $send = array('token' => $this->security->get_csrf_hash()) + $array;
        // print_r($send);

        if (!headers_sent()) {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: ' . date('r'));
            header('Content-type: application/json');
        }

        exit(json_encode($send, JSON_FORCE_OBJECT));

    }

}