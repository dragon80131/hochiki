<?php

  if( isset( $_FILES["xfiles"] ) )
  {
    foreach( $_FILES["xfiles"]["error"] as $key => $error )
    {
      if( $error == UPLOAD_ERR_OK )
      {
        $tmp_name = $_FILES["xfiles"]["tmp_name"][ $key ];
        $name     = $_FILES["xfiles"]["name"][ $key ];

        move_uploaded_file( $tmp_name, "./$name" );
      }
    }
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>example</title>
</head>
<body>
<form action="./index.php" method="post" enctype="multipart/form-data">
  <div>
    <input type="file" name="xfiles[]" /><br />
    <input type="file" name="xfiles[]" /><br />
    <input type="file" name="xfiles[]" />
  </div>
  <div>
    <input type="submit" />
  </div>
</form>
</body>
</html>
