<?php
  $uploaddir = '';
  $uploadfile = $uploaddir.basename($_FILES['uploadfile']['name']);

  if (copy($_FILES['uploadfile']['tmp_name'], $uploadfile)){
    //echo "<h3>Файл успешно загружен на сервер</h3>";
    $filename = $_FILES['uploadfile']['tmp_name'];
    require_once('report.php');
  }else{
    echo "<h3>Ошибка! Не удалось загрузить файл на сервер!</h3>"; exit;
  }
?>