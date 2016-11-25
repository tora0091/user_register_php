#社員管理システム

新入社員用サンプルプログラム

○ディレクトリ構成

public
    class
        CsvOperator.php         csvファイル処理クラス
        Request.php             post get リクエスト処理クラス
        Template.php            テンプレート処理クラス
    conf
        conf.php                共通定義ファイル
    data
        staff.csv               社員管理CSVファイル、存在しない場合は登録時新規に作成する
    template
        register.tpl            登録画面、削除画面
        search.tpl              検索画面
        update.tpl              更新画面
    index.php                   トップページ
    register.php                登録処理、画面表示
    search.php                  検索処理、画面表示
    update.php                  更新処理、画面表示


○できたこと
・基本的な操作(登録/更新/削除/検索)
・簡単な入力チェック
・機能によりファイルを分割
・MVCっぽい実装

○やらなかったこと
・入力データのサニタイジング処理
・あいまい検索
・ファイルロック
・エラー処理
などなどいろいろ

以上です