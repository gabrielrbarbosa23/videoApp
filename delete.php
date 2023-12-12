<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados enviados por POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica se o nome do arquivo foi enviado corretamente
    if (isset($data['filename'])) {
        $file_name = $data['filename'];
        $file_path = "uploads/" . $file_name;

        // Verifica se o arquivo existe no diretório de uploads
        if (file_exists($file_path)) {
            // Tenta excluir o arquivo do servidor
            if (unlink($file_path)) {
                http_response_code(200); // Indica que a exclusão foi bem-sucedida
            } else {
                http_response_code(500); // Indica falha ao excluir o arquivo
            }
        } else {
            http_response_code(404); // Indica que o arquivo não foi encontrado
        }
    } else {
        http_response_code(400); // Indica requisição inválida
    }
} else {
    http_response_code(405); // Indica método não permitido
}
?>