<a href="./LICENSE">![GitHub](https://img.shields.io/badge/license-MIT-green)</a>

# PROJETO - API-DEVSINSTAGRAM[EM DESENVOLVIMENTO]

## :rocket: Tecnologias utilizadas

<li>PHP 7.4</li>

## :loudspeaker: Apresentação

**API-DEVSINSTAGRAM** é um projeto de um WebService RESTful inpirado no instagram.

## ⚙ Features

- [x] API no padrão RESTful 

- [x] Não utiliza frameworks, apenas PHP puro.

- [x] Autenticação via JWT(JSON Web Token).

- [x] Arquitetura MVC.

## :clipboard: Instruções para rodar o projeto

### Pré-requisitos

- Antes de começar, você vai precisar ter instalado em sua máquina as seguintes ferramentas:

<li>![Git](https://git-scm.com)</li>
<li>![Apache](https://www.apachefriends.org/pt_br/index.html)</li>
<li>![MySQL](https://www.apachefriends.org/pt_br/index.html)</li>
<li>Caso não tenha, instle um editor, eu indico o <b>[VSCode](https://code.visualstudio.com/)</li>

### Instalando e rodando:

- 1º: Clone o repositório
  
  ```bash
  # Clone este repositório
  $ git clone https://github.com/lucaslgr/api-restful-instagram
  ```

- 2º: Inicie o Apache e o MySQL via XAMPP ou via terminal

## :globe_with_meridians: Endpoints:

  ```php
  users/login         (POST)      = logar usuário
  users/new           (POST)      = adicionar um novo usuário
  users/{id}          (GET)       = informações do usuário {id}
  users/{id}          (PUT)       = editar usuário {id}
  users/{id}          (DELETE)    = excluir usuárip {id}
  users/{id}/feed     (GET)       = feed de fotos do usuário {id}
  users/{id}/photos   (GET)       = fotos do usuário {id}
  users/{id}/follow   (POST)      = seguir usuário {id}
  users/{id}/follow   (DELETE)      = deseguir usuário {id}
  
  photos/random       (GET)       = fotos aleatórias
  photos/new          (POST)      = inserir nova foto
  photos/{id}         (GET)       = informações sobre a foto{id}
  photos/{id}         (DELETE)    = deleta a foto{id}
  photos/{id}/comment (POST)      = inserir novo comentário na foto{id}
  photos/{id}/comment (DELETE)    = deletar o comentário da foto{id}
  photos/{id}/like    (POST)      = curtir a foto{id}
  photos/{id}/like    (DELETE)    = descurtir a foto{id}
  ```

## :man_technologist: Autoria

Lucas Guimarães

https://lucaslgr.github.io/

https://www.linkedin.com/in/lucas-guimar%C3%A3es-rocha-a30282132/

## :male_detective: Referências

https://www.hostgator.com.br/blog/api-restful/
https://www.php.net/