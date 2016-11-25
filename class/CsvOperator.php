<?php

class CsvOperator
{
    // ファイルセパレータ
    const SEPALATOR = ',';

    // カラム名称
    const TITLE_NUMBER = 'number';
    const TITLE_NAME = 'name';
    const TITLE_TEL = 'tel';
    const TITLE_ADDRESS = 'address';
    const TITLE_SECTION = 'section';

    // ファイルオープンモード
    const MODE_APPEND = 'a';
    const MODE_WRITE = 'w';

    // 検索タイプ
    const SEARCH_TYPE_EQUAL = 1;    // 完全一致
    const SEARCH_TYPE_LIKE = 2;     // あいまい検索

    private $csv = null;

    public function __construct($csv = null)
    {
        if ($csv === null) {
            throw new Exception('[Error] Argument error.' . __CLASS__ . ':' . __LINE__);
        }
        $this->csv = $csv;
    }

    // csvファイルに値を登録する
    public function register(array $data = null)
    {
        if ($data === null) {
            throw new Exception('[Error] Argument error.' . __CLASS__ . ':' . __LINE__);
        }

        // ファイルを追記でオープン
        // ファイルが存在しない場合、新規に作成する
        $fp = fopen($this->csv, self::MODE_APPEND);
        if ($fp === false) {
            throw new Exception('[Error] File open error.' . __CLASS__ . ':' . __LINE__);            
        }

        // 出力する値を作成
        $text = sprintf("%s,%s,%s,%s,%s\n",
            $data[self::TITLE_NUMBER],
            $data[self::TITLE_NAME],
            $data[self::TITLE_TEL],
            $data[self::TITLE_ADDRESS],
            $data[self::TITLE_SECTION]);

        if (fwrite($fp, $text) === false) {
            throw new Exception('[Error] File write error.' . __CLASS__ . ':' . __LINE__);            
        }
        fclose($fp);
    }

    // csvファイルに登録されている値を削除する
    public function delete($number = null)
    {
        if ($number === null) {
            return;
        }

        // ファイルが存在しない場合、特に何もしない
        if (file_exists($this->csv) === false) {
            return;
        }

        $newLists = [];
        $lists = file($this->csv, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        foreach ($lists as $line) {
            $cols = explode(self::SEPALATOR, $line);
            if ($number !== $cols[0]) {
                // 配列の再構築、削除対象number以外を配列に格納
                $newLists[] = $line;
            }
        }

        // 新規作成、新規書き込みでオープン
        $fp = fopen($this->csv, self::MODE_WRITE);
        if ($fp === false) {
            throw new Exception('[Error] File open error.' . __CLASS__ . ':' . __LINE__);            
        }

        foreach ($newLists as $line) {
            if (fwrite($fp, $line . "\n") === false) {
                throw new Exception('[Error] File write error.' . __CLASS__ . ':' . __LINE__);            
            }
        }
        fclose($fp);
    }

    // csvファイルに登録されている値を配列として返す
    public function lists()
    {
        $hashes = [];

        // ファイルが存在し読み込み可能であればファイルの内容を配列に格納し返す
        if (is_readable($this->csv)) {
            // FILE_IGNORE_NEW_LINES: 配列の各要素の改行を追加しない
            // FILE_SKIP_EMPTY_LINES: 空行を読み飛ばす
            $lists = file($this->csv, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);

            foreach ($lists as $line) {
                $cols = explode(self::SEPALATOR, $line);
                $hashes[] = [
                    self::TITLE_NUMBER => $cols[0],
                    self::TITLE_NAME => $cols[1],
                    self::TITLE_TEL => $cols[2],
                    self::TITLE_ADDRESS => $cols[3],
                    self::TITLE_SECTION => $cols[4],
                ];
            }
        } 
        return $hashes;
    }

    // 社員番号が利用されているかどうかのチェック
    // true: 問題なし
    // false: すでに利用されており再度入力を促す
    public function duplicateKeyCheck($number)
    {
        // ファイルが存在しない場合、trueを返す
        if (file_exists($this->csv) === false) {
            return true;
        }

        $lists = file($this->csv, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        foreach ($lists as $line) {
            $cols = explode(self::SEPALATOR, $line);
            // 同じ社員番号がすでに登録されていた場合、falseを返す
            if ($number === $cols[0]) {
                return false;
            }
        }
        return true;
    }

    // 必須チェック
    // true: 問題なし
    // false: 必須項目が入力されていない
    public function primaryKeyCheck(array $data = null)
    {
        if ($data === null) {
            return false;
        }

        // 社員番号
        if (strlen($data[self::TITLE_NUMBER]) <= 0) {
            return false;
        }

        // 社員名
        if (strlen($data[self::TITLE_NAME]) <= 0) {
            return false;
        }

        return true;
    }

    // csvリストから特定の社員番号レコードを取得
    public function record($number = null)
    {
        $record = [];

        if ($number === null) {
            return $record;
        }

        // csv ファイルの一覧配列取得
        $lists = $this->lists();
        foreach ($lists as $line) {
            // 同じ社員番号が存在した場合
            if ($number === $line[self::TITLE_NUMBER]) {
                $record = $line;
                break;
            }
        }
        return $record;
    }

    // 特定のレコードの値を更新
    // update のロジックとして、対象レコードを発見、データ書き換えた配列作成、配列をファイルに書き込み
    // ※問題はデータが多くなってきた場合、処理が重くなること
    public function update(array $data = null)
    {
        if ($data === null) {
            return;
        }

        $newLists = [];
        $number = $data[self::TITLE_NUMBER];

        // csv ファイルの一覧配列取得
        $lists = $this->lists();
        foreach ($lists as $line) {
            // 同じ社員番号が存在した場合
            if ($number === $line[self::TITLE_NUMBER]) {
                $line = $data;
            }
            $newLists[] = $line;
        }

        // 新規作成、新規書き込みでオープン
        $fp = fopen($this->csv, self::MODE_WRITE);
        if ($fp === false) {
            throw new Exception('[Error] File open error.' . __CLASS__ . ':' . __LINE__);            
        }

        foreach ($newLists as $line) {
            $text = sprintf("%s,%s,%s,%s,%s\n", 
                $line[self::TITLE_NUMBER],
                $line[self::TITLE_NAME],
                $line[self::TITLE_TEL],
                $line[self::TITLE_ADDRESS],
                $line[self::TITLE_SECTION]);
            if (fwrite($fp, $text) === false) {
                throw new Exception('[Error] File write error.' . __CLASS__ . ':' . __LINE__);            
            }
        }
        fclose($fp);
    }

    // 検索処理
    // ・検索条件が設定されていない場合、全件を表示する
    public function search(array $data = null)
    {
        // csvファイルに登録されている一覧取得
        $lists = $this->lists();
        if ($data === null) {
            return $lists;
        }

        // 検索処理実行、先頭の社員番号から配列を絞り込んでゆく
        $lists = $this->searchLists($lists, self::TITLE_NUMBER, $data[self::TITLE_NUMBER], self::SEARCH_TYPE_EQUAL); 
        $lists = $this->searchLists($lists, self::TITLE_NAME, $data[self::TITLE_NAME], self::SEARCH_TYPE_EQUAL); 
        $lists = $this->searchLists($lists, self::TITLE_TEL, $data[self::TITLE_TEL], self::SEARCH_TYPE_EQUAL); 
        $lists = $this->searchLists($lists, self::TITLE_ADDRESS, $data[self::TITLE_ADDRESS], self::SEARCH_TYPE_EQUAL); 
        $lists = $this->searchLists($lists, self::TITLE_SECTION, $data[self::TITLE_SECTION], self::SEARCH_TYPE_EQUAL); 
        return $lists;
    }

    // 検索処理のロジック
    // 検索対象の配列を対象カラムで検索し、どんどん絞り込んでいく
    private function searchLists(array $lists, $colsName, $searchValue, $searchType = 1)
    {
        // 検索対象の文字列が空の場合、取得した検索対象配列をそのまま返す
        if (strlen($searchValue) <= 0) {
            return $lists;
        }

        $newLists = [];
        foreach($lists as $line) {
            if ($searchType === self::SEARCH_TYPE_EQUAL) {
                // 完全一致検索
                if ($line[$colsName] === $searchValue) {
                    $newLists[] = $line;
                }
            } else {
                // あいまい検索
                // todo: preg_match かなんか使えば実装できるはず
            }
        }
        return $newLists;
    }
}