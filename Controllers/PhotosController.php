<?php
namespace Controllers;

use Core\Controller;
use Models\Users;
use Models\Photos;

class PhotosController extends Controller
{
    public function __construct(){} 

    /*
    ACTION random que retorna um número determinado de photos randomicamente e excluindo as respectivas dos ID's informados na query
    */
    public function random()
    {
        $response = array(
            'error' => '',
            'logged' => false
        );

        //Pegando o método da requisição
        $method = $this->getMethod();
        //Pegando os dados enviados
        $data = $this->getRequestData();

        $users = new Users();
        $p = new Photos();

        //Verificando se o JWT foi enviado e se é válidado
        if (!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
            $response['logged'] = true;

            if ($method == 'GET') {
                
                //array que armazena os ID's das fotos já enviadas
                $excludes = (!empty($data['excludes'])?(explode(',',$data['excludes'])):(array()));

                //Limite de fotos para serem exibidas por vêz
                $limit_page = (!empty($data['limit_page']))?($data['limit_page']):(array());

                $response['data'] = $p->getPhotosRandom($excludes, $limit_page);
            } else {
                $response['error'] = 'Método '.$method.' incompatível';
            }
        } else {
            $response['error'] = 'Acesso negado!';
        }

        $this->returnJson($response);
    }

    /*
    ACTION que,
    quando GET-> retorna todas informações da photo respectiva ao id informado
    quando DELETE-> deleta todas informações da photo respectiva ao id informado
    */
    public function view(int $id_photo)
    {
        $response = array(
            'error' => '',
            'logged' => false
        );

        //Pegando o método da requisição
        $method = $this->getMethod();
        //Pegando os dados enviados
        $data = $this->getRequestData();

        $users = new Users();
        //Verificando se o JWT foi enviado e se é válidado
        if (!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
            $response['logged'] = true;
            
            $p = new Photos();

            switch($method){
                case 'GET':
                    $response['data'] = $p->getPhotoInfo($id_photo);
                    break;
                case 'DELETE':
                    $response['error'] = $p->deletePhoto($id_photo, $users->getId());
                    break;
                default:
                    $response['error'] = 'Método '.$method.' incompatível';
                    break;
            }
        } else
            $response['error'] = 'Acesso negado!';

        $this->returnJson($response);
    }

    /*
    ACTON que,
    POST -> Faz um comentário em uma foto respectiva ao id enviado no corpo da requisição
    */
    public function comment(int $id_photo)
    {
        $response = array(
            'error' => '',
            'logged' => false,
        );

        //Pegando o método da requisição
        $method = $this->getMethod();
        //Pegando os dados enviados
        $data = $this->getRequestData();

        $users = new Users();

        //Verificando se o JWT foi enviado e se é válidado
        if (!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
            $response['logged'] = true;

            //Verificação do método
            switch($method){
                case 'POST':
                    if (empty($data['txt'])) { //Se o comentário não tiver sido enviado
                        $response['error'] = 'Comentário vazio';
                    } else{
                        $p = new Photos();
                        $response['error'] = $p->addComment($users->getId(), $id_photo, $data['txt']);
                    }
                    break;
                default:
                    $response['error'] = 'Método '.$method.' incompatível';
                    break;
            }

        } else
            $response['error'] = 'Acesso negado!';

        $this->returnJson($response);
    }

    /*
    ACTION
    DELETE -> Deleta o comentário específico de uma foto 
    */
    public function delete_comment(int $id_comment)
    {
        $response = array(
            'error' => '',
            'logged' => false,
        );

        //Pegando o método da requisição
        $method = $this->getMethod();
        //Pegando os dados enviados
        $data = $this->getRequestData();

        $users = new Users();

        //Verificando se o JWT foi enviado e se é válidado
        if (!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
            $response['logged'] = true;

            //Verificação do método
            switch($method){
                case 'DELETE':
                    $p = new Photos();
                    $response['error'] = $p->deleteComment($id_comment, $users->getId());
                    break;
                default:
                    $response['error'] = 'Método '.$method.' incompatível';
                    break;
            }

        } else
            $response['error'] = 'Acesso negado!';

        $this->returnJson($response);
    }

    /*
    ACTION
    POST -> Da um like na foto respectiva ao id informado
    DELETE -> Da um deslike respectivo na foto do id informado
    */
    public function like(int $id_photo)
    {
        $response = array(
            'error' => '',
            'logged' => false
        );

        //Pegando o método da requisição
        $method = $this->getMethod();
        //Pegando os dados enviados
        $data = $this->getRequestData();

        $users = new Users();
        //Verificando se o JWT foi enviado e se é válidado
        if (!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
            $response['logged'] = true;
            
            $p = new Photos();

            switch($method){
                case 'POST':
                    $p = new Photos();
                    $response['data'] = $p->like($id_photo, $users->getId());
                    break;
                case 'DELETE':
                    $response['error'] = $p->unlike($id_photo, $users->getId());
                    break;
                default:
                    $response['error'] = 'Método '.$method.' incompatível';
                    break;
            }
        } else
            $response['error'] = 'Acesso negado!';

        $this->returnJson($response);
    }
}