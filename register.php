<?php

// 共通定義ファイル読み込み
require_once(dirname(__FILE__) . '/conf/conf.php');

// テンプレートに関するクラス読み込み
require_once(CLASS_TEMPLATE);

// POST/GETに関するクラス読み込み
require_once(CLASS_REQUEST);

// CSV file 操作に関するクラス読み込み
// ※CSV file の操作はこのクラスで実装する
require_once(CLASS_CSV_OPERATOR);


$lists = [];
$isError = false;

// テンプレートファイル読み込み
$tmp = new Template(TEMPLATE_DIR . 'register.tpl');

try {
    // Request データ取得
    $r = new Request();

    // csv操作オブジェクト生成
    $csv = new CsvOperator(CSV_FILE_PATH);

    // action の値によって処理を切り替える
    // register ... 登録
    // delete ... 削除
    $action = $r->post('action');
    if ($action === ACTION_REGISTER) {
        $data[$csv::TITLE_NUMBER] = $r->post($csv::TITLE_NUMBER);
        $data[$csv::TITLE_NAME] = $r->post($csv::TITLE_NAME);
        $data[$csv::TITLE_TEL] = $r->post($csv::TITLE_TEL);
        $data[$csv::TITLE_ADDRESS] = $r->post($csv::TITLE_ADDRESS);
        $data[$csv::TITLE_SECTION] = $r->post($csv::TITLE_SECTION);

        // 必須チェック
        if ($isError === false && $csv->primaryKeyCheck($r->post()) === false) {
            $tmp->errorMsg = '必須項目が入力されていません';
            $tmp->input = $data;
            $isError = true;
        }
        
        // 重複チェック
        if ($isError === false && $csv->duplicateKeyCheck($r->post($csv::TITLE_NUMBER)) === false) {
            $tmp->errorMsg = '[社員番号]はすでに利用されています';
            $tmp->input = $data;
            $isError = true;
        }
        
        // エラーが発生していない場合のみ登録処理を実施
        if ($isError === false) {
            $csv->register($data);
        }
    } elseif ($action === ACTION_DELETE) {
        $csv->delete($r->post('number'));
    }

    // csv ファイルの読み込み
    $lists = $csv->lists();
} catch (Exception $ex) {
    // Exception が発生したら処理中断
    var_dump($ex->getMessage());
    exit;
}

$tmp->lists = $lists;
$tmp->show();
