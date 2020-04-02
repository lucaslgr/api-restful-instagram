<?php

namespace Models;

use Core\Model;

class Photos extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //Retorna o número de fotos que o usuário do respectivo id possui
    public function getPhotosCount(int $id_user)
    {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM photos WHERE id_user = ?");
        $sql->execute(array($id_user));

        return $sql->fetch()['c'];
    } 

    //Deleta as fotos, os comentarios e os likes respectivos ao id do usuário passado
    public function deleteAll(int $id_user)
    {
        $sql = $this->pdo->prepare("DELETE FROM photos WHERE id_user = ?");
        $sql->execute(array($id_user));

        $sql = $this->pdo->prepare("DELETE FROM photos_comments WHERE id_user = ?");
        $sql->execute(array($id_user));

        $sql = $this->pdo->prepare("DELETE FROM photos_likes WHERE id_user = ?");
        $sql->execute(array($id_user));
    }

    //Retorna as fotos dos users dos respectivos id's enviados no array
    public function getFeedCollection(array $id_users = array(), int $offset, int $limit_page): array
    {
        $result = array();
        $users = new Users();

        $sql = $this->pdo->prepare(
            "SELECT * FROM photos 
            WHERE id_user IN (".implode(', ', $id_users).") 
            ORDER BY id DESC
            LIMIT ".$offset.", ".$limit_page);
        $sql->execute();
        
        if ($sql->rowCount() > 0) {
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($result as $key => $item) {
                //Em cada foto prenche com URL COMPLETA, NAME do proprietario, AVATAR, COMENTÁRIOS e LIKES da foto
                $user_info = $users->getInfo($item['id_user']);

                $result[$key]['url'] = \BASE_URL.'images/photos/'.$item['url'];
                $result[$key]['name'] = $user_info['name'];
                $result[$key]['avatar'] = $user_info['avatar'];
                $result[$key]['likes_count'] = $this->getCountLikes($item['id']);
                $result[$key]['comments'] = $this->getComments($item['id']);
            }
        }

        return $result;
    }

    //Retorna as informações da foto respectiva ao id informado
    public function getPhotoInfo(int $id_photo)
    {
        $result = '';

        $sql = $this->pdo->prepare("SELECT * FROM photos WHERE id = ?");
        $sql->execute(array($id_photo));
        if ($sql->rowCount() > 0){
            $result= $sql->fetch(\PDO::FETCH_ASSOC);

            $users = new Users();

            //Pegando todas informações do usuário que postou a foto
            $users_info = $users->getInfo($result['id_user']);

            //Pegando name, avatar e url da foto do usuário que postou a foto
            $result['name'] = $users_info['name'];
            $result['avatar'] = $users_info['avatar'];
            $result['url'] = BASE_URL.'images/photos/'.$result['url'];

            //Pegando os likes e os commentas na foto
            $result['likes'] = $this->getCountLikes($result['id']);
            $result['comments'] = $this->getComments($result['id']);
        }
        return $result;
    }

    //Retorna a quantidade de likes que tem na foto do respectivo id passado
    private function getCountLikes(int $id_photo): int
    {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM photos_likes WHERE id_photo = ?");
        $sql->execute(array($id_photo));
        return intval($sql->fetch()['c']);
    }

    //Deleta a foto do respectivo ID do proprietario da foto com o respectivo id informado no segundo parametro
    public function deletePhoto(int $id_photo, int $id_logged_user)
    {
        $sql = $this->pdo->prepare("SELECT id FROM photos WHERE id = ? AND id_user = ?");
        $sql->execute(array(
            $id_photo,
            $id_logged_user
        ));
        if ($sql->rowCount() > 0) {
            //Deletando todas informações referente a foto
            $sql = $this->pdo->prepare("DELETE FROM photos WHERE id = ?");
            $sql->execute(array($id_photo));

            $sql = $this->pdo->prepare("DELETE FROM photos_comments WHERE id_photo = ?");
            $sql->execute(array($id_photo));

            $sql = $this->pdo->prepare("DELETE FROM photos_likes WHERE id_photo = ?");
            $sql->execute(array($id_photo));

            return ''; //Foto e informações referentes a foto deletada com suceso
        } else
            return 'Ação negada, essa foto não existe ou não é sua';
    }

    //Retorna os comentários da photo respectiva ao id passado
    private function getComments(int $id_photo)
    {
        $comments = array();

        $sql = $this->pdo->prepare(
            "SELECT photos_comments.*, users.name FROM photos_comments
            LEFT JOIN users ON photos_comments.id_user = users.id
            WHERE photos_comments.id_photo = ?"
        );
        $sql->execute(array($id_photo));

        if ($sql->rowCount() > 0) {
            $comments = $sql->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $comments;
    }

    //Retorna as fotos do user respectivo ao id passado
    public function getPhotosFromUser(int $id_user, int $offset = 0, int $limit_page = 10)
    {
        $result = array();

        $sql = $this->pdo->prepare(
            "SELECT * FROM photos
            WHERE id_user = ? ORDER BY id DESC
            LIMIT ".$offset.', '.$limit_page
        );
        $sql->execute(array($id_user));
        if ($sql->rowCount() > 0){
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach ($result as $key => $item) {
                $result[$key]['url'] = BASE_URL.'images/photos/'.$item['url'];
                $result[$key]['likes_count'] = $this->getCountLikes($item['id']);
                $result[$key]['comments'] = $this->getComments($item['id']);
            }
        }
        return $result;
    }

    /*
    Retorna em ordem RANDOMICA fotos de TODOS users com o limite de páginas desejado e excluindo os fotos
    cujo os ID's forem enviados no array $excludes
    */
    public function getPhotosRandom(array $excludes = array(), int $limit_page = 10)
    {
        $result = array();

        //Tratando as informações recebidas no $excludes antes de fazer a query
        foreach($excludes as $key => $item)
            $excludes[$key] = addslashes(intval($item));

        //Se foi enviado algum id de foto para exclusão
        if (count($excludes) > 0) {
            /*
            Exemplo da query:
            SELECT * FROM photos WHERE id NOT IN (1, 2, 3, 4) ORDER BY RAND() LIMIT 1, 2
            */
            $sql = (
                "SELECT * FROM photos
                WHERE id NOT IN (".implode(', ', $excludes).")
                ORDER BY RAND()
                LIMIT ".addslashes($limit_page)
            );
        } else {
            $sql = (
                "SELECT * FROM photos ORDER BY RAND() LIMIT ".addslashes($limit_page)
            );
        }

        //Executando a query
        $sql = $this->pdo->query($sql);

        if ($sql->rowCount() > 0) { //Verificando se retornou algum resultado
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

            $users = new Users();
            
            foreach ($result as $key => $item) {
                $result[$key]['likes'] = $this->getCountLikes($item['id']);
                $result[$key]['comments'] = $this->getComments($item['id']);
                $result[$key]['url'] = BASE_URL.'images/photos/'.$item['url'];
            }
        }

        return $result;
    }

    //Adiciona um comentário na foto respectivo ao id passado e utilizando o Id do usuário passado
    public function addComment(int $id_user, int $id_photo, string $comment)
    {
        //Verificando se o comentário está preenchido
        if(empty($comment))
            return '';

        $sql = $this->pdo->prepare(
            "INSERT INTO photos_comments(id_user, id_photo, data_comment, txt) 
            VALUES(?, ?, NOW(), ?)"
        );

        $status_query = $sql->execute(
            array(
            $id_user,
            $id_photo,
            $comment
        ));

        return ($status_query)?(''):('ERRO na inserção do comentário no BD');
    }

    //Deleta o comentário do respectivo ID passado
    //OBS: só deleta se o comentário pertencer a quem está logado, ou se o usuário for o dono da foto
    public function deleteComment(int $id_comment, int $id_logged_user)
    {
        //Verificando se o usuário logado é o autor do comentário a ser deletado
        $sql = $this->pdo->prepare(
            "SELECT id FROM photos_comments
            WHERE id = ? AND id_user = ?"
        );
        $sql->execute(array(
            $id_comment,
            $id_logged_user
        ));
        if ($sql->rowCount() > 0) {
            $sql = $this->pdo->prepare("DELETE FROM photos_comments WHERE id = ?");
            $sql->execute(array($id_comment));
            return ''; //Comentário deletado com suceso

        } else { //Verificando se o usuário logado é o dono da foto onde o comentário se refere
            $sql = $this->pdo->prepare(
                "SELECT photos.id
                FROM photos
                JOIN photos_comments
                ON photos_comments.id = ? AND photos.id = photos_comments.id_photo
                AND photos.id_user = ?"
            );
            $sql->execute(
                array(
                    $id_comment,
                    $id_logged_user
                )
            );

            //Verifica se a photo onde o comentário pertence, pertence ao usuário logado
            if ($sql->rowCount() > 0) {
                $sql = $this->pdo->prepare("DELETE FROM photos_comments WHERE id = ?");
                $sql->execute(array($id_comment));
                return ''; //Comentário deletado com suceso
            } else {
                return 'Ação negada, a foto a qual o comentário se refere não pertence ao usuário logado';
            }
        }
            return 'Ação negada, esse comentário não existe ou não pertence ao usuário logado';
    }

    //Da um like na foto respectiva ao id passado, com o user respectivo ao id de user passado
    public function like(int $id_photo, int $id_user)
    {
        /*
        Antes de registrar um LIKE em uma foto, deve ser verificado se o usuário respectivo ao ID informado
        já não deu um LIKE nessa foto do respectivo id
        */
        $sql = $this->pdo->prepare(
            "SELECT id FROM photos_likes WHERE id_user = ? AND id_photo = ?"
        );
        $sql->execute(
            array(
                $id_user,
                $id_photo
            )
        );
        //Verifa se o resultado foi zero, ou seja, se o user não deu like nessa foto ainda
        if ($sql->rowCount() == 0) {
            $sql = $this->pdo->prepare(
                "INSERT INTO photos_likes( id_user, id_photo)
                VALUES(?, ?)"
            );
            $status_query = $sql->execute(
                array(
                    $id_user,
                    $id_photo
            ));
            return ($status_query)?(''):('Não foi possível inserir no BD');
        } else {
            return 'Você já deu like nessa foto';
        }
    }

    //Da um unlike na foto respectiva ao id passado, com o user respectivo ao id de user passado
    public function unlike(int $id_photo, int $id_user)
    {
        //Verifa se o resultado foi zero, ou seja, se o user não deu like nessa foto ainda
        $sql = $this->pdo->prepare(
            "DELETE FROM photos_likes WHERE id_user = ? AND id_photo = ?"
        );
        $status_query = $sql->execute(
            array(
                $id_user,
                $id_photo
            )
        );
        return ($status_query) ? ('') : ('Não foi possível deletar no BD');
    }
}