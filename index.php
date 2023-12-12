<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>App Video</title>
    <link rel="stylesheet" type="text/css" href="indexx.css" />
</head>
<body>

    <div class="title">
        <h2>App Video</h2>
    </div>

<!--------------------------------------- Botão para mostrar a div -------------------------------------------------->
    <button id="admButton">Mostrar Envio de Vídeos</button>

    <div id="admContent">
        <h1>Enviar vídeos</h1>
        
        <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
            Selecione um ou mais vídeos para enviar:
            <label for="fileUpload" class="uploadButton">Escolher Vídeo</label>
            <span class="fileSelected" id="fileSelected"></span>
            <input type="file" id="fileUpload" name="videos[]" accept="video/*" multiple style="display: none;">
            <input type="submit" value="Enviar">
        </form>
        
        <hr>
    </div>
    
<!--------------------------------------- div dos vídeos -------------------------------------------------->
    <div class="videos">
        <?php
            $dir = "uploads/";
            $files = array_diff(scandir($dir), array('..', '.'));

            if (!empty($files)) {
                $filesInfo = [];

                foreach ($files as $file) {
                    $filePath = $dir . $file;
                    if (is_file($filePath)) {
                        $filesInfo[$file] = filemtime($filePath); // Obtém o timestamp da última modificação do arquivo
                    }
                }

                // Ordena os arquivos com base no timestamp (do último vídeo enviado para o mais antigo)
                arsort($filesInfo);

                $counter = 0;
                foreach ($filesInfo as $file => $timestamp) {
                    $counter++;
                    $videoClass = ($counter === 1) ? 'top-video' : ''; // Adiciona a classe 'top-video' ao primeiro vídeo da lista

                    echo '<div class="video-container ' . $videoClass . '">
                            <video controls>
                                <source src="' . $dir . $file . '" type="video/mp4">
                                Seu navegador não suporta a exibição deste vídeo.
                            </video>

                            <button class="deleteButton" style="display: none;" onclick="showPrompt(\'delete\', \'' . $file . '\')">Excluir</button>
                        </div>';
                }
            } else {
                echo "<p>Nenhum vídeo enviado ainda.</p>";
            }
        ?>
    </div>

    <footer>
        <p>Este website é um teste criado para fazer upload e delete de vídeos através de uma determinada senha</p>
    </footer>



<!--------------------------------------- JavaScript -------------------------------------------------->

<!--------------------------------------- Botão de Adm upload e delete -------------------------------------------------->
    <script>
        const senhaFixa = '123';

        // Obtém referências dos elementos
        const admButton = document.getElementById('admButton');
        const admContent = document.getElementById('admContent');
        const deleteButtons = document.getElementsByClassName('deleteButton');

        // Adiciona um evento de clique ao botão fixo
            admButton.addEventListener('click', function() {
        const password = prompt("Por favor, insira a senha:");

        // Verifica se a senha inserida corresponde à senha fixa
        if (password !== null && password === senhaFixa) {
            // Verifica o estilo de exibição atual
            const isHidden = window.getComputedStyle(admContent).getPropertyValue('display') === 'none';

            // Alterna a exibição do conteúdo de envio de vídeos baseado no estilo atual
            if (isHidden) {
                admContent.style.display = 'block';

                // Exibe os botões de exclusão
                Array.from(deleteButtons).forEach(button => {
                    button.style.display = 'block';
                });
            } else {
                admContent.style.display = 'none';
            }
        } else {
            alert("Senha incorreta. Ação cancelada.");
        }
    });

        function showPrompt(action, filename) {
            if (action === 'delete') {
                const confirmDelete = confirm('Tem certeza de que deseja excluir este vídeo?');
                if (confirmDelete) {
                    // Aqui você pode fazer uma requisição para o delete.php para excluir o vídeo
                    // Vou usar o fetch para fazer uma requisição DELETE para o delete.php
                    fetch('delete.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ filename: filename })
                    })
                    .then(response => {
                        if (response.ok) {
                            // Se a exclusão foi bem-sucedida, recarregue a página para atualizar a lista de vídeos
                            window.location.reload();
                        } else {
                            alert('Falha ao excluir o vídeo.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao excluir o vídeo:', error);
                    });
                }
            }
        }
    </script>

    <script>
        // Adicionando um evento para capturar o nome do arquivo selecionado
        document.getElementById('fileUpload').addEventListener('change', function () {
            const fileInput = document.getElementById('fileUpload');
            const fileText = document.getElementById('fileSelected');

            if (fileInput.files.length > 0) {
                fileText.textContent = fileInput.files.length > 1 ? fileInput.files.length + ' arquivos selecionados' : fileInput.files[0].name;
            } else {
                fileText.textContent = '';
            }
        });
    </script>
</body>
</html>