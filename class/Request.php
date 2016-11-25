<?php

class Request
{
    private $posts = null;
    private $gets = null;

    public function __construct()
    {
        $this->posts = $_POST;
        $this->gets = $_GET;
    }

    // Post リクエストに対する処理
    public function post($name = null)
    {
        return $this->request($this->posts, $name);
    }

    // Get リクエストに対する処理
    public function get($name = null)
    {
        return $this->request($this->gets, $name);
    }

    // リクエストに対する共通処理
    private function request($request, $name = null)
    {
        // $name が指定されていなければ リクエスト配列を返す
        if ($name === null) {
            return $request;
        }

        // $name が指定されており、かつリクエスト配列に存在すれば、その値を返す
        if (isset($request[$name])) {
            return $request[$name];
        }

        // $name がリクエスト配列に存在しない場合は空文字を返す
        return ''; 
    }
}