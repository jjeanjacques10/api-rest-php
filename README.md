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

Fonte dos dados - http://www.mtecbo.gov.br/cbosite/pages/downloads.jsf
Projeto base da API - https://www.youtube.com/watch?v=pa6QwLWG12Q
