<?php

// テンプレートに関するクラス
class Template
{
    private $templatePath;

    // コンストラクタ
    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    // 画面表示
    // ※単にファイルを読み込んでいるだけ
    public function show()
    {
        $view = $this;
        require $this->templatePath;
    }
}