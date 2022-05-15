# Ecommerce CRUD - Symfony 6

Teste para a vaga de Back-End utilizando Symfony e PHP 8.0 para criar um Ecommerce básico

## Documentação

- Para visualizar a documentação da API basta acessar https://diminua.me/e/3cH

## Requisitos
 - A aplicação requer o PHP 8+ para que o Symfony 6 seja suportado, o banco de dados utilizado foi o MariaDB 10.4.24, porém, a última versão do MySQL também é suportada.

## Instalação

 - Clone o repositório do GIT

```sh
git clone https://github.com/giovanipatrick/ecommerce_symfony.git
cd ecommerce_symfony
composer update
```

- Subir Database Limpa
```sh
cd ecommerce_symfony
php bin/console doctrine:migrations:migrate
```
- Subir Database com dados
```sh
Importe ecommerce_symfony.sql
```

### Iniciar Projeto
- Local
```sh
symfony server:start -port 8080
```

- Apache
```sh
Aponte ecommerce_symfony/public no VirtualHost
```