<?php
$ds          = DIRECTORY_SEPARATOR;  //1
 
$storeFolder = '../upfile/kojiPicfile';   //2
#echo $_POST["ID"};
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          //3             
      
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

#    $targetFile =  $targetPath. $_FILES['file']['name'];  //5
    $targetFile =  $targetPath. "/temp.jpg" ;  //5
 
    move_uploaded_file($tempFile,$targetFile); //6
     
}
?> 
