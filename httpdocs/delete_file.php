<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileName = $_POST['fileName'] ?? '';
    $editBukkenCD = $_POST['editBukkenCD'];
    // ファイルパスを検証し、セキュリティリスクを管理する
#    $filePath = './kojifile/'.$editBukkenCD.'/' . basename($fileName); // ディレクトリトラバーサル攻撃を防ぐため
// if(strpos($fileName,'..')!==FALSE or strpos($fileName,'/')!==FALSE){
//     exit;
// }
if (preg_match('/\.\.|\/|\^/', $fileName)) {
    error_log("禁止された文字がファイル名に含まれています: " . $fileName);
    exit('不正なファイル名です。');
}
$filePath = './kojifile/'.$editBukkenCD.'/' . $fileName; // ディレクトリトラバーサル攻撃を防ぐため

// #調査ミサイル
// $fh = fopen("aaa.txt", "a");
// fwrite($fh,"\n fileName:". $fileName );
// fwrite($fh,"\n filePath:". $filePath );
// fclose($fh);
// #調査ミサイルEn


    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo "ファイルが正常に削除されました: " . htmlspecialchars($fileName);
        } else {
            echo "ファイルの削除中にエラーが発生しました";
        }
    } else {
        echo "ファイルが見つかりません: " . htmlspecialchars($fileName);
    }
} else {
    echo "不正なリクエストです。";
}
?>
