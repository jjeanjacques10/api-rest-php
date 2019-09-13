# API REST EM PHP - Calculadora automação de profissões

Api Rest para pesquisa de vagas automatizadas em um banco de dados MySQL, a aplicaço foi desenvolvida em PHP apenas.

## Configuração da URL amigável no NGINX:

Para poder acessar a URL no formato REST é necessário inserir o seguinte código no NGINX:

```
location / {
    # First attempt to serve request as file, then
    # as directory, then fall back to displaying a 404.
    #try_files $uri $uri/ =404;
    #try_files $uri $uri/ /index.php?$query_string;
    try_files $uri $uri/ /index.php?$args;  
      
}

location /api/v1/ {
        rewrite /api/v1/(.*)$ /api/v1/index.php?args=$1 last;
}
```

## Para o XAMPP já temos o arquivo .htaccess

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

# Estrutura da API

## GET - /api/v1/profissao/

Seleciona as 50 ocupações mais vistas na plataforma.


## GET - /api/v1/profissao?nome=<ocupacao>

Busca pelo nome da ocupação, passando como parâmetro GET nome mostrando o resutaldo em ordem alfabética.


#### Params
* nome   

## GET - /api/v1/profissao/?CBO2002=<cbo2002>

Faz o cadastro de uma nova visualização da ocupação, quando a ocupação já foi visualiza anteriormente pelo usuário não será cadastrado outra vez um view. Funciona exclusivamente via GET enviando o CBO2002 da ocupação.

#### Params
* cbo2002   

Fonte dos dados - http://www.mtecbo.gov.br/cbosite/pages/downloads.jsf
Projeto base da API - https://www.youtube.com/watch?v=pa6QwLWG12Q

