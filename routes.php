<?php
/*
users/login         (POST)      = logar usuário
users/new           (POST)      = adicionar um novo usuário
users/{id}          (GET)       = informações do usuário {id}
users/{id}          (PUT)       = editar usuário {id}
users/{id}          (DELETE)    = excluir usuárip {id}
users/feed          (GET)       = feed de fotos do usuário {id}
users/{id}/photos   (GET)       = fotos do usuário {id}
users/{id}/follow   (POST)      = seguir usuário {id}
users/{id}/follow   (DELETE)    = deseguir usuário {id}

photos/random       (GET)       = fotos aleatórias
photos/new          (POST)      = inserir nova foto
photos/{id}         (GET)       = informações sobre a foto{id}
photos/{id}         (DELETE)    = deleta a foto{id}
photos/{id}/comment (POST)      = inserir novo comentário na foto{id}
photos/comment/{id} (DELETE)    = deletar o comentário da foto{id}
photos/{id}/like    (POST)      = curtir a foto{id}
photos/{id}/like    (DELETE)    = descurtir a foto{id}
*/

global $routes;
$routes = array();

//Cofiguração das rotas
$routes['/users/login']         =    '/users/login';
$routes['/users/new']           =    '/users/add_new';
$routes['/users/feed']          =    '/users/feed';
$routes['/users/{id}']          =    '/users/view/:id';
$routes['/users/{id}/photos']   =    '/users/photos/:id';
$routes['/users/{id}/follow']   =    '/users/follow/:id';

$routes['/photos/random']       =    '/photos/random';
$routes['/photos/new']          =    '/photos/new_record';
$routes['/photos/{id}']         =    '/photos/view/:id';
$routes['/photos/{id}/comment'] =    '/photos/comment/:id';
$routes['/comment/{id}']        =    '/photos/delete_comment/:id';
$routes['/photos/{id}/like']    =    '/photos/like/:id';
?>