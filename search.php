<?php

// 検索画面及び検索処理の実行



// 共通定義ファイル読み込み
require_once(dirname(__FILE__) . '/conf/conf.php');

// テンプレートに関するクラス読み込み
require_once(CLASS_TEMPLATE);

// POST/GETに関するクラス読み込み
require_once(CLASS_REQUEST);

// CSV file 操作に関するクラス読み込み
// ※CSV file の操作はこのクラスで実装する
require_once(CLASS_CSV_OPERATOR);


$r = new Request();
$csv = new CsvOperator(CSV_FILE_PATH);
$tmp = new Template(TEMPLATE_DIR . 'search.tpl');

$lists = [];

// 検索処理実行
$action = $r->post('action');
if ($action === ACTION_SEARCH) {
    // 検索条件の取得
    $data[$csv::TITLE_NUMBER] = $r->post($csv::TITLE_NUMBER);
    $data[$csv::TITLE_NAME] = $r->post($csv::TITLE_NAME);
    $data[$csv::TITLE_TEL] = $r->post($csv::TITLE_TEL);
    $data[$csv::TITLE_ADDRESS] = $r->post($csv::TITLE_ADDRESS);
    $data[$csv::TITLE_SECTION] = $r->post($csv::TITLE_SECTION);

    $lists = $csv->search($data);
}

$tmp->lists = $lists;
$tmp->show();

