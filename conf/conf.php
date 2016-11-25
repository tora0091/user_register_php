<?php

// プログラムの基準となるディレクトリを指定
const BASE_DIR = './';

// CSVファイルに対する設定
// ファイル名称
const CSV_FILE_NAME = 'staff.csv';

// ファイルディレクトリ
const CSV_FILE_DIR = BASE_DIR . 'data/';

// ファイルパス
const CSV_FILE_PATH = CSV_FILE_DIR . CSV_FILE_NAME;


// テンプレートにする対する設定
const TEMPLATE_DIR = BASE_DIR . 'template/';


// クラスに対する設定
const CLASS_DIR = BASE_DIR . 'class/';

// クラスの定義
const CLASS_TEMPLATE = CLASS_DIR . 'Template.php';
const CLASS_REQUEST = CLASS_DIR . 'Request.php';
const CLASS_CSV_OPERATOR = CLASS_DIR . 'CsvOperator.php';

// Action 定義
const ACTION_REGISTER = 'register';
const ACTION_UPDATE = 'update';
const ACTION_DELETE = 'delete';
const ACTION_SEARCH = 'search';


