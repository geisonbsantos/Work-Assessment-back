## StarterPack - DMA

<p align="center"><img src="http://www.saude.ba.gov.br/wp-content/themes/sesab2017/assets/images/logo-sesab.gif"></p>

<p align="center">
<img src="https://img.shields.io/badge/PHP-8.3.0-8892BF.svg" alt="Versão PHP">
<img src="https://img.shields.io/badge/Laravel-11.20.0-ff2d20.svg" alt="Versão">

</p>

## Sobre

<p align="center">Este projeto tem como objetivo, servir de base para futuros projetos da SESAB/DMA. Provendo um padrão de desenvolvimento, servindo recursos base, organizando assim, o fluxo para criação dos novos projetos e unificando a arquitetura das aplicações.</p>

## Documentação da API

<p align="center"> Documentação da api, feita no postman.
Link: https://documenter.getpostman.com/view/21375199/Uze4u34B Atualizando...
</p>

## Status

<h4 align="center"> 
	🚧  Em atualização...  🚧
</h4>

### Features

-   [x] Autenticação de usuário
-   [x] Cadastro de usuário
-   [x] Reset de senha do usuário por email
-   [x] Cadastro de perfil de usuário
-   [x] Cadastro de habilidades do sistema
-   [x] Help para relato de problemas
-   [x] Funcionalidade da FAQ - Perguntas Frequentes.

### Packages

-   [x] Sanctum Link: https://laravel.com/docs/11.x/sanctum
-   [x] Pint Link: https://laravel.com/docs/11.x/pint
-   [x] Pest Link: https://pestphp.com/

### Pré-requisitos laravel 11 <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="Versão PHP">

Antes de começar, você vai precisar ter instalado em sua máquina as seguintes ferramentas:
[Git](https://git-scm.com), [Composer](https://getcomposer.org/download/),
[Php 8.3.10 Thread Safe](https://windows.php.net/downloads/releases/php-8.3.10-Win32-vs16-x64.zip), [InstantClient 19](https://download.oracle.com/otn_software/nt/instantclient/1918000/instantclient-basic-windows.x64-19.18.0.0.0dbru.zip), [Oci8 - Versão 3.0.1](https://pecl.php.net/package/oci8)
Além disto é bom ter um editor para trabalhar com o código como [VSCode](https://code.visualstudio.com/)

### Configurando o InstantClient Oracle (Windows)

Esse é um projeto que tem conexão com banco de dados oracle, então, para que seu php consiga se conectar com o oracle sem problemas, será necessário configurar o instantClient.

1° Após baixar o instantClient e o Oci8, extraia a pasta do download para o seu disco local, renomeie para "instantclient_suaversão" Exemplo: "instantclient_19_18".

2° instantclient_suaversão: Dentro da pasta que foi descompactada, copie todas as dll's, em seguida cole todas na pasta do seu php. Faça outra cópia para a pasta "ext",que também fica dentro da sua pasta do php.

3° Oci8: Dentro da pasta que foi descompactada, copie todas as dll's, em seguida cole todas na pasta "ext", que também fica dentro da sua pasta do php.

4° No painel de controle do seu sistema, procure por: "variáveis de ambiente". Vá em "Editar as variáveis de ambiente do sistema" -> "Variáveis de ambiente". Acessando as variáveis, crie uma nova pasta com o nome Oracle e coloque no path, o caminho da pasta que está descompactada. Exemplo: "C:/instantclient_19_18".

5° Edite o seu php.ini, Recomendações, descomentando a extensões "extension=curl, extension=fileinfo, extension=gd, extension=bz2, extension=ldap, extension=mbstring, extension=oci8_19, extension=odbc, extension=openssl, extension=pdo_oci", extension_dir = "ext". Dentro do seu php.ini, que fica dentro da sua pasta do php, aperte a tecla F3 para localizar a extensão, e retire o ";" que fica antes. Feito isso, salve o aquivo, e seu instantClient estará configurado.

### 🎲 Rodando o Back End (servidor)

```bash
# Clone este repositório
$ git clone <http://gitlab.saude.ba.gov.br/dma/starterpack-laravel.git>

# Acesse a pasta do projeto no terminal/cmd
$ cd starterpack-laravel

# 1 -Instale as dependências
$ composer install

# Certifique de configurar o .env
Copie o .env-example, e cole renomeando o arquivo para .env

# Volte ao terminal e defina uma chave de segurança para seu .env
$ php artisan key:generate

# Execute a aplicação em modo local
$ php artisan serve

# O servidor inciará na porta:8000 - acesse <http://localhost:8000>
```

### 🛠 Tecnologias

As seguintes ferramentas foram usadas na construção do projeto:

-   [Laravel](https://laravel.com/)

### Banco de Dados

Oracle 19c

```bash
# Desenvolvimento
-  Host 	=> 10.17.3.201
-  Porta	=> 1521
-  Sid		=> orcl
-  Database => orcl
-  Usuário  => STARTERPACK_DESENV
-  Senha 	=> kCoK82qQ
```

## Uso do docker

#### Na raiz do projeto rode os comandos.

```bash
1- docker-compose build (executar o build do projeto)

depois que o build finalizar sem erro, execute o proximo comando.

2- docker-compose up -d (Inicializar o container no docker )

3- Acesar localhost na porta 9090 e verificar se a aplicação esta rodando
```

## Configuração do xdebug

```bash
1- Baixa a extensão do vscode do php xdebug
2- Cria o arquivo de launch.json do xdebug
3- Dentro do arquivo launch.json procure por esse trecho e perceba que não vau ter o campo "pathMappings".
    
    Exemplo de como vai estar
    {
        "name": "Listen for Xdebug",
        "type": "php",
        "request": "launch",
        "port": 9003,
    },


    Como deve ficar com o novo campo "pathMappings":
    {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/": "${workspaceFolder}"
            }
    }
```
