<?php

namespace App;


use App\Exceptions\ConfigNotFoundException;

class Container {
    private $results = [];
    private $params = [];

    /**
     * @param $id
     * @return mixed
     */
    public function get($id) {
        if (array_key_exists($id, $this->results)) {
            return $this->results[$id];
        }
        if (!array_key_exists($id, $this->params)) {
            throw new ConfigNotFoundException("Undefined parameters '{$id}'");
        }

        $param = $this->params[$id];
        if ($param instanceof \Closure) {
            $this->results[$id] = $param($this);
        } else {
            $this->results[$id] = $param;
        }

        return $this->results[$id];
    }

    /**
     * @param        $id
     * @param        $value
     */
    public function set($id, $value): void {
        if (array_key_exists($id, $this->results)) {
            unset($this->results[$id]);
        }
        $this->params[$id] = $value;
    }

//    private function __construct($file) {
//        $this->file = $file;
//        $this->init();
//    }

//    private function init() {
//        $this->config = include $this->file;
//    }

//    public static function getInstance(string $file) {
//        if (empty(self::$instance)) {
//            self::$instance = new self($file);
//        }
//        return self::$instance;
//    }

}