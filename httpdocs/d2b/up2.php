<?php
echo "<html>"; 
echo '<head><meta http-equiv="X-UA-Compatible" content="IE=8" />';
echo '<meta http-equiv="Content-Type" content="text/html; charset=euc-jp"></head>';
echo "0:".$_FILES['userfile']['name'];
$uploaddir = './uploads/';
#$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
$uploadfile = $uploaddir .$_FILES['userfile']['name'];
echo "<br>1:".$uploadfile;

#$uploadfile = mb_convert_encoding($uploadfile, "UTF-8", "euc-jp"); #chrome is OK
#$uploadfile = mb_convert_encoding($uploadfile, "SJIS", "euc-jp");
#$uploadfile = mb_convert_encoding($uploadfile, "SJIS", "UTF-8");
#$uploadfile = mb_convert_encoding($uploadfile, "UTF-8", "SJIS");
# up2.html change euc . then up2.php is euc OK
$uploadfile = mb_convert_encoding($uploadfile, "UTF-8", "euc-jp");#ie11 OK

echo "<br>2:".$uploadfile;

echo "<br>3:".$_FILES['userfile']['tmp_name'];
#$uploadfile = mb_convert_encoding($uploadfile, "JIS", "SJIS");

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo " file upload NG\n";
}

#echo 'Here is some more debugging info:';
#print_r($_FILES);

print "</pre>";

#####一覧表示
$i = 0;
   $dir = "./uploads";                    //一覧表示するディレクトリ

        $opendir = opendir($dir);
        while($file = readdir($opendir)) {
            if($file === "." or $file === "..") {
                continue;
            }
            $file = $dir . "/" . $file;
            if(is_dir($file)) {

                continue;
            }

                $filename = mb_convert_encoding(preg_replace("/^[\.\/]+/", "", $file),"euc-jp","UTF-8");

		$filename = str_replace("uploads/", "", $filename);
		$fileList[$i] = $filename;
echo "<br>".$fileList[$i] ;
#拡張子

		$i = $i + 1;
        }
        closedir($opendir);



$fileLoop = $i ;

echo "</body>"; 
echo "</html>"; 
?>
