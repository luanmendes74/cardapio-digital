# Cardápio Digital SaaS - Projeto

Este é o repositório do projeto Cardápio Digital SaaS, uma plataforma multi-tenant construída com PHP puro, seguindo uma arquitetura MVC.

## 🚀 Estrutura de Diretórios

A estrutura do projeto foi desenhada para separar as responsabilidades e facilitar a manutenção.

`´´´`
/cardapio-saas/
|
|-- 📁 app/                     # O coração da aplicação
|   |-- 📁 controllers/         # Controla o fluxo entre Models e Views
|   |-- 📁 core/                # Classes base (Roteador, Controller, Database)
|   |-- 📁 models/              # Lida com a lógica e acesso ao banco de dados
|   |-- 📁 views/               # Contém o HTML que é exibido para o usuário
|   |-- bootstrap.php           # Arquivo de inicialização e autoload
|
|-- 📁 config/                 # Arquivos de configuração
|   |-- database.php            # Configurações do banco de dados
|
|-- 📁 public/                 # Única pasta acessível publicamente
|   |-- .htaccess               # Redireciona todas as requisições para o index.php
|   |-- index.php               # Ponto de entrada único da aplicação (Front Controller)
|   |-- css/                    # (Futuro) Arquivos CSS
|   |-- js/                     # (Futuro) Arquivos JavaScript
|   |-- assets/                 # (Futuro) Imagens, fontes, etc.
|
|-- README.md                   # Este arquivo
|-- database.sql                # Script de criação do banco de dados
|-- configuracao_xampp.md       # Guia para configurar o ambiente local
`´´´`

## ⚙️ Configuração do Ambiente (XAMPP)

Para rodar o projeto localmente, siga as instruções detalhadas no arquivo `configuracao_xampp.md`. Os passos principais são:

1.  **Importar o Banco de Dados:** Use o arquivo `database.sql` no phpMyAdmin.
2.  **Configurar `hosts`:** Adicionar os domínios de teste no arquivo `hosts` do seu sistema operacional.
3.  **Configurar o `Virtual Host` do Apache:** Apontar o servidor para a pasta `/public` do projeto.
4.  **Reiniciar o Apache.**

## ✅ Próximos Passos (Fase 2)

-   [ ] Criar sistema de autenticação para os estabelecimentos.
-   [ ] Construir o painel de administração (dashboard).
-   [ ] Desenvolver o CRUD (Create, Read, Update, Delete) de Produtos.
-   [ ] Desenvolver o CRUD de Categorias.
-   [ ] Implementar o upload de imagens para produtos e logo do estabelecimento.
