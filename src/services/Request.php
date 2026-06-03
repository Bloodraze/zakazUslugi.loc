<?php

namespace src\services;

class Request{
    public bool $isPost = false;
    public bool $isGet = false;

    public function __construct(){
        if(($_SERVER['REQUEST_METHOD'] ?? '') === 'GET'){
            $this->isGet = true;
        }elseif(($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'){
            $this->isPost = true;
        }
    }

    public function clearParam(string $param): string{
        return trim(strip_tags($param));
    }
    public function clearArray(array $array): array{
        $result = [];
        foreach($array as $key => $value){
            if(gettype($value) == 'array'){
                $result[$key] = $this->clearArray($value);
                continue;
            }
            $result[$key] = $this->clearParam($value);
        }
        return $result;
    }
    public function post($param = null){
        if($param){
            return $this->clearParam($_POST[$param]);
        }else{
            return $this->clearArray($_POST);
        }
    }
    public function get($param = null){
        if($param){
            return $this->clearParam($_GET[$param]);
        }else{
            return $this->clearArray($_GET);
        }
    }
}