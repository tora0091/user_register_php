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

$isError = false;
$csv = new CsvOperator(CSV_FILE_PATH);
$tmp = new Template(TEMPLATE_DIR . 'update.tpl');
$r = new Request();

$action = $r->post('action');
$number = $r->post($csv::TITLE_NUMBER);
$tmp->number = $number;

// 更新する社員番号が存在しない場合も処理を中断
if (strlen($number) <= 0) {
    var_dump("[Error] Update number is not found." . __CLASS__ . ':' . __LINE__);
    exit;
}

// update 処理
if ($action === ACTION_UPDATE) {
    try {
        $data[$csv::TITLE_NUMBER] = $number;
        $data[$csv::TITLE_NAME] = $r->post($csv::TITLE_NAME);
        $data[$csv::TITLE_TEL] = $r->post($csv::TITLE_TEL);
        $data[$csv::TITLE_ADDRESS] = $r->post($csv::TITLE_ADDRESS);
        $data[$csv::TITLE_SECTION] = $r->post($csv::TITLE_SECTION);

        // 必須チェック
        if ($csv->primaryKeyCheck($r->post()) === false) {
            $tmp->errorMsg = '必須項目が入力されていません';
            $tmp->input = $data;
            $isError = true;
        }

        // エラーが発生していない場合のみ更新処理
        if ($isError === false) {
            $csv->update($data);

            // 登録画面へリダイレクト
            header('Location: ./register.php');
            exit;
        }
    } catch (Exception $ex) {
        var_dump($ex->getMessage());
        exit;
    }
}

// 入力エラーが発生した場合は、入力値を画面に表示させる
// 入力エラーが発生していない場合、
// 更新ボタンを押したときの遷移なので、csvからデータを取得する
if (!$isError) {
    // 更新する社員のレコードを取得
    $record = $csv->record($number);
    $tmp->input = $record;
}
$tmp->show();
