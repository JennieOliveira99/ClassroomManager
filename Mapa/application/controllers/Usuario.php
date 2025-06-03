<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends CI_Controller
{
    //atributos privados da classe

    private $idUsuario;
    private $nome;
    private $email;
    private $usuario;
    private $senha;

    //getters dos atributos

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getUsuario()
    {
        return $this->usuario;
    }
    public function getSenha()
    {
        return $this->senha;
    }

    //setters dos atributos
    public function setIdUsuario($idUsuarioFront)
    {
        $this->idUsuario = $idUsuarioFront;
    }
    public function setNome($nomeFront)
    {
        $this->nome = $nomeFront;
    }
    public function setEmail($emailFront)
    {
        $this->email = $emailFront;
    }
    public function setUsuario($usuarioFront)
    {
        $this->usuario = $usuarioFront;
    }
    public function setSenha($senhaFront)
    {
        $this->senha = $senhaFront;
    }

    public function inserir()
    {
        //nome, user e senha
        //recebidos via JSON
        //retornos possiveis:
        //1- usuario cadastrado corretamente (banco)
        //2- faltou informar nome (Front)
        //3- faltou informar email (Front)
        //4- faltou informar user (Front)
        //5- faltou informar senha (Front)
        try {
            //usuario e senha colocados em atributos
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            // array com dados que devem vir do front
            $lista = array(
                'nome' => '0',
                'email' => '0',
                'usuario' => '0',
                'senha' => '0'
            );

            if (verificarParam($resultado, $lista)) {
                $this->setNome($resultado->nome);
                $this->setEmail($resultado->email);
                $this->setUsuario($resultado->usuario);
                $this->setSenha($resultado->senha);

                //faremos uma validação para saber se todos os dados foram enviados
                if (trim($this->getNome()) == '') {
                    $retorno = array(
                        'codigo' => 2,
                        'mensagem' => 'Nome não informado'
                    );
                } else if (trim($this->getEmail()) == '') {
                    $retorno = array(
                        'codigo' => 3,
                        'mensagem' => 'Email não informado'
                    );
                } elseif (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)) {
                    $retorno = array(
                        'codigo' => 6,
                        'mensagem' => 'Email em formato inválido'
                    );
                } else if (trim($this->getUsuario()) == '') {
                    $retorno = array(
                        'codigo' => 4,
                        'mensagem' => 'Usuario não informado'
                    );
                } else if (trim($this->getSenha()) == '') {
                    $retorno = array(
                        'codigo' => 5,
                        'mensagem' => 'Senha não informada'
                    );
                } else {
                    //realizo instancia da model
                    $this->load->model('M_usuario');

                    //atributo $retorno recebe array com infos
                    $retorno = $this->M_usuario->inserir(
                        $this->getNome(),
                        $this->getEmail(),
                        $this->getUsuario(),
                        $this->getSenha()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do frontEnd nao representam o método de inserção. VERIFIQUE.'
                );
            }
        } catch (Exception $e) {
            //se houver erro, retorna o erro
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
            );
        }
        //retorno no formato JSON
        echo json_encode($retorno);
    }

   public function consultar() {
    try {
        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        // Verifica se o JSON foi decodificado corretamente
        if($resultado === null) {
            throw new Exception("JSON inválido ou vazio");
        }

        $lista = array(
            'nome' => '0',
            'email' => '0',
            'usuario' => '0',
        );

        // Verifica os parâmetros
        if (verificarParam($resultado, $lista)) {
            $this->setNome(isset($resultado->nome) ? $resultado->nome : '');
            $this->setEmail(isset($resultado->email) ? $resultado->email : '');
            $this->setUsuario(isset($resultado->usuario) ? $resultado->usuario : '');

            $this->load->model('M_usuario');
            
            $retorno = $this->M_usuario->consultar(
                $this->getNome(),
                $this->getEmail(),
                $this->getUsuario()
            );
        } else {
            $retorno = array(
                'codigo' => 99,
                'msg' => 'Os campos vindos do frontEnd não representam o método de consulta. VERIFIQUE.'
            );
        }
    } catch (Exception $e) {
        $retorno = array(
            'codigo' => 0,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
        );
    }
    echo json_encode($retorno);
}

    public function alterar()
    {
        //idUsuario, nome email e senha  recebidos e colocados em variaveis
        //retornos possiveis:
        //1 - dados consulatdos corretamnete no banco
        //2 - idUsuaio em branco ou zerado
        // 3 - nenhum parametro de alteracao informado
        //65- dados nao encontrados no banco

        try {
            //usuario e senha colocados em atributos
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            // array com dados que devem vir do front
            $lista = array(
                'idUsuario' => '0',
                'nome' => '0',
                'email' => '0',
                'senha' => '0',
            );

            if (verificarParam($resultado, $lista) == 1) {
                $this->setIdUsuario($resultado->idUsuario);
                $this->setNome($resultado->nome);
                $this->setEmail($resultado->email);
                $this->setSenha($resultado->senha);

                if (trim($this->getIdUsuario() == '')) {
                    $retorno = array(
                        'codigo' => 2,
                        'mensagem' => 'Id do Usuario não informado'
                    );
                } //nome,senha ou email pelo menos 1 precisa ser informado
                else if (
                    trim($this->getNome()) == '' &&
                    trim($this->getEmail()) == '' &&
                    trim($this->getSenha()) == ''
                ) {
                    $retorno = array(
                        'codigo' => 3,
                        'mensagem' => 'Pelo menos 1 parametro de alteracao deve ser informado'
                    );
                    //verificaçao se é valido
                } else if (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)) {
                    $retorno = array(
                        'codigo' => 6,
                        'mensagem' => 'Email em formato inválido'
                    );
                } else {
                    //realizo instancia da model
                    $this->load->model('M_usuario');

                    //atributo $retorno recebe array com infos
                    $retorno = $this->M_usuario->alterar(
                        $this->getIdUsuario(),
                        $this->getNome(),
                        $this->getEmail(),
                        $this->getSenha()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do frontEnd nao representam o método de inserção. VERIFIQUE.'
                );
            }
        } catch (Exception $e) {
            //se houver erro, retorna o erro
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
            );
        }
        //retorno no formato JSON
        echo json_encode($retorno);
    }


    public function desativar()
    {
        //nome comum e usuario recebidos e colocados em variaveis
        //retornos possiveis:
        //1 - dados desativado corretamnete no banco
        //2- usuario nao encontrado
        //4 - usuario ja desativado

        try {
            //usuario e senha colocados em atributos
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            // array com dados que devem vir do front
            $lista = array(
                'idUsuario' => '0'

            );

            if (verificarParam($resultado, $lista) == 1) {
                $json = file_get_contents('php://input');
                $resultado = json_decode($json);

                //fazendo os setters
                $this->setIdUsuario($resultado->idUsuario);

                //validacao para que o user nao seja em branco
                if (trim($this->getIdUsuario()) == '') {
                    $retorno = array(
                        'codigo' => 2,
                        'mensagem' => 'Id do Usuario não informado'
                    );
                } else {
                    //realizo instancia da model
                    $this->load->model('M_usuario');
                    //atributo $retorno recebe array com infos
                    $retorno = $this->M_usuario->desativar(
                        $this->getIdUsuario()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do frontEnd nao representam o método de inserção. VERIFIQUE.'
                );
            }
        } catch (Exception $e) {
            //se houver erro, retorna o erro
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
            );
        }
        //retorno no formato JSON
        echo json_encode($retorno);
    }

    public function logar() {
    try {
        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        // array com dados que devem vir do front
        $lista = array(
            'usuario' => '0',
            'senha' => '0'
        );

        if (verificarParam($resultado, $lista) == 1) {
            //setters
            $this->setUsuario($resultado->usuario);
            $this->setSenha($resultado->senha);

            //validacao para que o user nao seja em branco
            if (trim($this->getUsuario()) == '') {
                $retorno = array(
                    'codigo' => 2,
                    'mensagem' => 'Usuario não informado'
                );
            } elseif (trim($this->getSenha()) == '') {
                $retorno = array(
                    'codigo' => 3,
                    'mensagem' => 'Senha não informada'
                );
            } else {
                //realizo instancia da model
                $this->load->model('M_usuario');
                
                // CORREÇÃO: Chamar validaLogin() em vez de logar()
                $retorno = $this->M_usuario->validaLogin(
                    $this->getUsuario(),
                    $this->getSenha()
                );
            }
        } else {
            $retorno = array(
                'codigo' => 99,
                'msg' => 'Os campos vindos do frontEnd não representam o método de login. VERIFIQUE.'
            );
        }
    } catch (Exception $e) {
        $retorno = array(
            'codigo' => 0,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
        );
    }
    
    // Adicionando o retorno JSON que estava faltando
    echo json_encode($retorno);
}
}