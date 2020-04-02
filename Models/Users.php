<?php

namespace Models;

use Core\Model;
use Models\Jwt;
use Models\Photos;

class Users extends Model
{
    private $id_user;

    public function __construct()
    {
        parent::__construct();
    }

    public function checkCredentials(string $email, string $password): bool
    {
        $sql = "SELECT id, pass FROM users WHERE email = :email";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':email', $email);
        $sql->execute(); 

        if ($sql->rowCount() > 0) {
            $sql = $sql->fetch();

            if (password_verify($password, $sql['pass'])) {
                $this->id_user = $sql['id']; //Salvando a senha
                return true; //Fez login com sucesso
            } else {
                return false; //Errou a senha
            }
        } else {
            return false; //Não existe esse user
        }
    }

    //Retorna o id do usuário logado
    public function getId()
    {
        return $this->id_user;
    }

    //Retorna as informações do usuário respectivo ao ID
    public function getInfo(int $id)
    {
        $user_info = array();

        $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $sql->execute(array($id));
        
        if ($sql->rowCount() > 0){ //Se encontrou um usuario com esse ID
            $user_info = $sql->fetch(\PDO::FETCH_ASSOC);

            if (!empty($user_info['avatar'])) {
                $user_info['avatar'] = BASE_URL.'images/avatar/'.$user_info['avatar'];
            } else {
                $user_info['avatar'] = BASE_URL.'images/avatar/default.png';
            }

            $photos = new Photos;

            $user_info['follwing'] = $this->getFollowingCount($id);
            $user_info['followers'] = $this->getFollowers($id);
            $user_info['photos_count'] = $photos->getPhotosCount($id);
        }

        return $user_info;
    }

    //Edita as informações do respectivo user
    public function editInfo(int $id_user, array $data = array())
    {
        /**
         * O usuário só pode altear informações dele mesmo, logo, verificando se o ID do usuário enviado na requisição é igual
         * a do usuário que está logado (id que veio no playload do TOKEN).
         */
        if ($id_user != $this->id_user)
            return 'Não é permitido alterar outro usuário';
        
        //Array que armazenará as informações enviadas à serem alteradas
        $toChange = array();

        if (!empty($data['name']))
            $toChange['name'] = $data['name'];

        if (!empty($data['email'])) {
            if (filter_var($data['email'],FILTER_VALIDATE_EMAIL)) {
                if (!$this->checkEmailExists($data['email']))
                    $toChange['email'] = $data['email'];
                else
                    return 'Email já existente e/ou email inválido!';
            } else {
                return 'Email inválido!';
            }
        }

        if (!empty($data['pass']))
            $toChange['pass'] = password_hash($data['pass'], PASSWORD_BCRYPT);

        //Verificando se foi enviado algum dado para ser alterado, se foi, aplica as alterações no BD
        if (count($toChange) > 0){
            $fieldsToChange = array_keys($toChange);
            $valuesOfFields = array_values($toChange);
            //Adicionando o id_user no valuesOfFields
            $valuesOfFields[] = $id_user;

            foreach ($fieldsToChange as $key => $field)
                $fieldsToChange[$key] = $field.=' = ?';
            
            $sql = $this->pdo->prepare("UPDATE users SET ".implode(',', $fieldsToChange)." WHERE id = ?;");
            $status_query = $sql->execute($valuesOfFields);
            if (!$status_query)
                return 'ERRO ao inserir no BD';
        } else
            return 'Nenhum dado foi preenchido';
    } 

    //Deleta o usuário do respectivo id
    public function delete(int $id_user)
    {
        /*Verifica se o id do usuário passado é o mesmo do usuário que esta logado, pois só o proprio usuário pode se
        * deletar.
        */
        if ($id_user != $this->id_user)
            return 'Não é permitido excluir outro usuário';
        /*
            DELETAR EM MASSA:
            Fotos
            Comentários nas fotos
            Likes nas Fotos
            Seguidores e seguidos
            Usuário
        */
        $p = new Photos();
        $p->deleteAll($id_user);
        
        $sql = $this->pdo->prepare("DELETE FROM users_following WHERE id_user_passive = ? OR id_user_active = ?");
        $sql->execute(array(
            $id_user,
            $id_user
        ));

        $sql = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $status_query = $sql->execute(array($id_user));

        if (!$status_query)
            return 'ERRO ao deletar no BD';
    }

    //Retorna o número de pessoas que o usuário com respectivo id está seguindo
    private function getFollowingCount(int $id_user): int
    {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM users_following WHERE id_user_active = ?");
        $sql->execute(array($id_user));

        return intval($sql->fetch()['c']);
    }

    //Retorna o número de seguidores do usuário com respectivo id
    private function getFollowers(int $id_user): int
    {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM users_following WHERE id_user_passive = ?");
        $sql->execute(array($id_user));

        return intval($sql->fetch()['c']);
    }

    //Adiciona um novo user
    public function create(string $email, string $pass, string $name='', string $avatar=''): bool
    {
        if (!$this->checkEmailExists($email)) {
            $sql = $this->pdo->prepare("INSERT INTO users(name, email, pass, avatar)
                                        VALUES(?, ?, ?, ?)");
            $status_query = $sql->execute(
                array(
                    $name,
                    $email,
                    password_hash($pass,PASSWORD_BCRYPT),
                    $avatar
            ));

            if ($status_query) {
                $this->id_user = $this->pdo->lastInsertId(); //Pegando o id do usuário inserido nesse momento
                return true;
            }
                
        }else { //Email já existente
            return false;
        }
    }

    //Retorna o feed de acordo com o offset e o limit por página
    public function getFeed(int $offset=0, int $limit_page=0)
    {
        //Pegando os seguidores do usuário logado
        $followingUsers = $this->getFollowing($this->getId());

        $p = new Photos();

        return $p->getFeedCollection($followingUsers, $offset, $limit_page);
    }

    //Retorna os id's dos seguidores do usuário referente ao id informado
    private function getFollowing(int $id_user)
    {
        $result = array();

        //Pegando os id's dos seguidores do usuário logado
        $sql = $this->pdo->prepare("SELECT id_user_passive FROM users_following WHERE id_user_active = ?");
        $sql->execute(array(
            $id_user
        ));
        if ($sql->rowCount() > 0) {
            $result = $sql->fetchAll();
            foreach($result as $key => $value)
            {
                $result[$key] = $value['id_user_passive'];
            }
        }

        return $result;
    }

    //Faz um following do user logado no user do respectivo ID
    public function follow(int $id_user): bool
    {
        //Verifica se o usuário já segue o user alvo
        $sql = $this->pdo->prepare("SELECT * FROM users_following WHERE id_user_active = ? AND id_user_passive = ?");
        $sql->execute(
            array(
            $this->id_user, //Id do usuário logado
            $id_user //Id do usuário desejado
            )
        );
        if ($sql->rowCount() > 0)//Usuário desejado já é seguido
            return false;
        
        //Segue o usuário desejado
        $sql = $this->pdo->prepare("INSERT INTO users_following(id_user_active, id_user_passive) VALUES(?, ?)");
        $status_query = $sql->execute(
            array(
            $this->id_user,//Id do usuário logado
            $id_user //Id do usuário desejado
            )
        );
        
        return ($status_query===true)?(true):(false); //Testa se a query foi executada com sucesso
    }

    //Faz um unfollow no user respectivo ao id passado
    public function unfollow(int $id_user): bool
    {
        $sql = $this->pdo->prepare("DELETE FROM users_following WHERE id_user_active = ? AND id_user_passive = ?");
        $status_query = $sql->execute(
            array(
            $this->id_user, //Id do usuário logado
            $id_user //Id do usuário desejado
            )
        );
        return ($status_query===true)?(true):(false); //Testa se a query foi executada com sucesso
    }

    //Checa se o email existe
    private function checkEmailExists(string $email):bool
    {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM users WHERE email = ?");
        $sql->execute(array(
            $email
        ));

        return ($sql->fetch()['c'] > 0)?(true):(false);
    }

    //Cria e retorna um JWT
    public function createJwt()
    {
        $jwt = new Jwt();
        return $jwt->create(
            array(
                'id_user' => $this->id_user
            )
        );
    }

    //Valida um JWT
    public function validateJwt(string $token): bool
    {
        $jwt = new Jwt();
        $info = $jwt->validate($token);

        if (isset($info->id_user)) {
            $this->id_user = $info->id_user;
            return true;
        } else {
            return false; //Token não validado
        }
    }   
}