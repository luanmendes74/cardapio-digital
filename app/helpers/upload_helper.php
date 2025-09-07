<?php
// Helper para upload de imagens

function uploadImagem($file, $pasta = 'uploads', $prefixo = '')
{
    // Verifica se o arquivo foi enviado
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['sucesso' => false, 'erro' => 'Nenhum arquivo foi enviado ou ocorreu um erro no upload.'];
    }

    // Verifica se é uma imagem
    $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $tipoArquivo = mime_content_type($file['tmp_name']);
    
    if (!in_array($tipoArquivo, $tiposPermitidos)) {
        return ['sucesso' => false, 'erro' => 'Tipo de arquivo não permitido. Use apenas JPG, PNG, GIF ou WebP.'];
    }

    // Verifica o tamanho do arquivo (máximo 5MB)
    $tamanhoMaximo = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $tamanhoMaximo) {
        return ['sucesso' => false, 'erro' => 'Arquivo muito grande. Tamanho máximo permitido: 5MB.'];
    }

    // Cria a pasta se não existir
    if (!file_exists($pasta)) {
        mkdir($pasta, 0755, true);
    }

    // Gera nome único para o arquivo
    $extensao = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nomeArquivo = $prefixo . uniqid() . '.' . $extensao;
    $caminhoCompleto = $pasta . '/' . $nomeArquivo;

    // Move o arquivo para a pasta de destino
    if (move_uploaded_file($file['tmp_name'], $caminhoCompleto)) {
        return ['sucesso' => true, 'nome_arquivo' => $nomeArquivo, 'caminho' => $caminhoCompleto];
    } else {
        return ['sucesso' => false, 'erro' => 'Erro ao mover o arquivo para a pasta de destino.'];
    }
}

function redimensionarImagem($caminhoOrigem, $caminhoDestino, $larguraMax = 800, $alturaMax = 600, $qualidade = 85)
{
    // Obtém informações da imagem
    $info = getimagesize($caminhoOrigem);
    if (!$info) {
        return false;
    }

    $larguraOrig = $info[0];
    $alturaOrig = $info[1];
    $tipo = $info[2];

    // Calcula as novas dimensões mantendo a proporção
    $proporcao = min($larguraMax / $larguraOrig, $alturaMax / $alturaOrig);
    $novaLargura = intval($larguraOrig * $proporcao);
    $novaAltura = intval($alturaOrig * $proporcao);

    // Cria a imagem de origem baseada no tipo
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $imagemOrig = imagecreatefromjpeg($caminhoOrigem);
            break;
        case IMAGETYPE_PNG:
            $imagemOrig = imagecreatefrompng($caminhoOrigem);
            break;
        case IMAGETYPE_GIF:
            $imagemOrig = imagecreatefromgif($caminhoOrigem);
            break;
        case IMAGETYPE_WEBP:
            $imagemOrig = imagecreatefromwebp($caminhoOrigem);
            break;
        default:
            return false;
    }

    if (!$imagemOrig) {
        return false;
    }

    // Cria a nova imagem redimensionada
    $imagemNova = imagecreatetruecolor($novaLargura, $novaAltura);
    
    // Preserva a transparência para PNG e GIF
    if ($tipo == IMAGETYPE_PNG || $tipo == IMAGETYPE_GIF) {
        imagealphablending($imagemNova, false);
        imagesavealpha($imagemNova, true);
        $transparente = imagecolorallocatealpha($imagemNova, 255, 255, 255, 127);
        imagefilledrectangle($imagemNova, 0, 0, $novaLargura, $novaAltura, $transparente);
    }

    // Redimensiona a imagem
    imagecopyresampled($imagemNova, $imagemOrig, 0, 0, 0, 0, $novaLargura, $novaAltura, $larguraOrig, $alturaOrig);

    // Salva a nova imagem
    $resultado = false;
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $resultado = imagejpeg($imagemNova, $caminhoDestino, $qualidade);
            break;
        case IMAGETYPE_PNG:
            $resultado = imagepng($imagemNova, $caminhoDestino, 9);
            break;
        case IMAGETYPE_GIF:
            $resultado = imagegif($imagemNova, $caminhoDestino);
            break;
        case IMAGETYPE_WEBP:
            $resultado = imagewebp($imagemNova, $caminhoDestino, $qualidade);
            break;
    }

    // Libera a memória
    imagedestroy($imagemOrig);
    imagedestroy($imagemNova);

    return $resultado;
}

function deletarImagem($caminho)
{
    if (file_exists($caminho)) {
        return unlink($caminho);
    }
    return true; // Se o arquivo não existe, considera como sucesso
}

function obterUrlImagem($nomeArquivo, $pasta = 'uploads')
{
    if (empty($nomeArquivo)) {
        return null;
    }
    return URL_BASE . '/' . $pasta . '/' . $nomeArquivo;
}

