<?php
// Gera a URL correta para um estabelecimento com base no modo de roteamento definido em config.php
function gerarUrlEstabelecimento($subdominio) {
    if (MODO_ROTEAMENTO === 'subdominio') {
        // Remove 'http://' ou 'https://' da URL_BASE para construir o link do subdomínio
        $domain_parts = parse_url(URL_BASE);
        return 'http://' . $subdominio . '.' . $domain_parts['host'];
    } else {
        // Modo 'caminho'
        return URL_BASE . '/' . $subdominio;
    }
}
