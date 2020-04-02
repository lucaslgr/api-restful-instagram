<?php

namespace Controllers;

use Core\Controller;
use Models\Users;
use Models\Photos;

class UsersController extends Controller
{
    public function index(){}

    //ACTION login 
    public function login()
    {
        $response = array(
            'error' => ''
        );

        $method = $this->getMethod();
        $data = $this->getRequestData();

        if ($method != 'POST')
            $response['error'] = 'Método de requisição incompatível';
        else {
            if (!empty($data['email']) && !empty($data['pass'])) {
                $users = new Users();

                //Chamando o model para checar se o usuário existe e se as credenciais batem
                if ($users->checkCredentials($data['email'], $data['pass'])) {
                    //Usuário autenticado, gerar o JWT
                    $response['jwt'] = $users->createJwt();
                } else {
                    $response['error'] = 'Acesso negado';
                }
            } else {
                $response['error'] = 'Email e/ou senha não preenchido';
            }
        }
            

        $this->returnJson($response);
    }

    //ACTION add novo usuário
    public function add_new()
    {
        $response = array(
            'error' => ''
        );

        if ($this->getMethod() != 'POST')
            $response['error'] = 'Método de requisição incompatível';
        else {
            $data = $this->getRequestData();

            if (!empty($data['email']) && !empty($data['pass']) && !empty($data['name'])) {
                
                if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) { //Validando o email
                    $users = new Users();

                    //Chamando o model para cadastrar o usuário
                    if($users->create(
                        $data['email'],
                        $data['pass'],
                        $data['name'],
                        (!empty($data['avatar']) ? ($data['avatar']) : (''))
                    )){
                        $response['jwt'] = $users->createJwt();
                    } else {
                        $response['error'] = 'Email já existente';
                    }

                } else {
                    $response['error'] = 'E-mail inválido';
                }
            } else {
                $response['error'] = 'Email e/ou senha e/ou nome não preenchido';
            }
        }

        $this->returnJson($response);
    }

    //ACTION pega informações do usuário
    public function view($id)
    {
        $response = array(
            'error'=>'',
            'logged'=>false
        );

        //Pegando o método da requisição
        $method = $this->getMethod();
        //Pegando os dados da requisição
        $data = $this->getRequestData();

        $users = new Users();

        //Validando o JWT
        if (!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
            $response['logged'] = true;

            /**
             * Verificando se o ID da url é igual ao ID do Token, ou seja, se o usuário que eu quero vez as
             * informações(id enviado no token) é o mesmo usuário que está logado (id obtido do token validado)
             */
            $response['id_me'] = ($id == $users->getId())?(true):(false);

            //Identificando o método de envio
            switch($method){
                case('GET'): //Pegar informações do user
                    $response['data_user'] = $users->getInfo($id);
                    if (count($response['data_user']) === 0) {
                        $response['error'] = 'Usuário não existe';
                    }
                    break;
                case('PUT'): //Alterar os dados do user
                    $response['error'] = $users->editInfo($id, $data);
                    break;
                case('DELETE'):
                    $response['error'] = $users->delete($id);
                    break;
                default:
                    $response['error'] = 'Método '.$method.' incompatível';
                    break;
            }
            
        } else {
            $response['error'] = 'Acesso negado';
        }

        $this->returnJson($response);
    }

    //ACTION feed retorna as atualizações do usuário logado
    public function feed()
    {
        $response = array(
            'error' => ''
        );

        //Pegando o método da requisição
        $method = $this->getMethod();
        //Pegando os dados da requisição
        $data = $this->getRequestData();

        $users = new Users();

        //Validação do token
        if (!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
            $response['logged'] = true;

            if ($method == 'GET') {
                $offset = 0;

                if (!empty($data['offset']))
                    $offset = intval($data['offset']);
                
                $limit_page = 10;
                if (!empty($data['limit_page']))
                    $limit_page = intval($data['limit_page']);

                $response['data'] = $users->getFeed($offset, $limit_page);
            } else {
                $response['error'] = 'Método '.$method.' incompatível';
            }
        } else { //Se TOKEN não é válido
            $response['error'] = 'Acesso negado';
        }

        $this->returnJson($response);
    }

    //ACTON photos onde o recebe as fotos do usuário do respectivo ID
    public function photos(int $id_user)
    {
        $response = array(
            'error' => '',
            'logged' => 'false'
        );

        //Pegando o método
        $method = $this->getMethod();
        //Pegando os dados enviados
        $data = $this->getRequestData();

        $users = new Users();
        $p = new Photos();

        //Verificando se o JWT foi enviado e se é válidado
        if (!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
            $response['logged'] = true;
            
            //Verificando se são as fotos do próprio user
            $response['is_me'] = false;
            if ($id_user == $users->getId())
                $response['is_me'] = true;

            //Verificando se o método foi GET
            if ($method=='GET') {
                $offset = 0;

                if (!empty($data['offset']))
                    $offset = intval($data['offset']);
                
                $limit_page = 10; 
                if (!empty($data['limit_page']))
                    $limit_page = intval($data['limit_page']);

                $response['data'] = $p->getPhotosFromUser($id_user, $offset, $limit_page);
            } else {
                $response['error'] = 'Método '.$method.' incompatível';
            }
        } else {
            $response['error'] = 'Acesso negado!';
        }

        $this->returnJson($response);
    }

    //ACTION follow, POST->seguir, GET->deseguir o user do respectivo id
    public function follow(int $id_user)
    {
        $response = array(
            'error' => '',
            'logged' => 'false'
        );

        //Pegando o método
        $method = $this->getMethod();
        //Pegando os dados enviados
        $data = $this->getRequestData();

        $users = new Users();

        //Verificando se o JWT foi enviado e se é válidado
        if (!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
            $response['logged'] = true;
            
            //Verificando se são as fotos do próprio user
            $response['is_me'] = false;
            if ($id_user == $users->getId())
                $response['is_me'] = true;

            //Verificando se o método
            switch($method){
                case 'POST':
                    if (!$users->follow($id_user))
                        $response['error'] = 'Usuário já é seguido pelo usuário logado ou usuário desejado é inexistente!';
                break;

                case 'DELETE':
                    if (!$users->unfollow($id_user))
                        $response['error'] = 'Usuário não é seguido pelo usuário logado ou usuário desejado é inexistente!';
                break;

                default:
                    $response['error'] = 'Método '.$method.' incompatível';
                break;
            }
            
        } else {
            $response['error'] = 'Acesso negado!';
        }

        $this->returnJson($response);
    }
}
