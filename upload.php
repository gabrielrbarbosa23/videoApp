<?php
if(isset($_FILES['videos'])){
    $file_names = $_FILES['videos']['name'];
    $file_tmps = $_FILES['videos']['tmp_name'];

    // Itera sobre todos os arquivos enviados
    foreach ($file_tmps as $key => $file_tmp) {
        $file_name = $file_names[$key];
        move_uploaded_file($file_tmp, "uploads/" . $file_name);
    }

    // Redireciona de volta para a página principal após o envio
    header("Location: index.php");
    exit();
}
?>
