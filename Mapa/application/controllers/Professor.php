<?php

defined ('BASEPATH') or exit ('No direct script access allowed');



class Professor extends CI_Controller{

    private $codigo;

    private $nome;

    private $cpf;

    private $tipo;

    private $estatus;



    public function getCodigo(){

        return $this->codigo;

    }

    public function getNome(){

        return $this->nome;

    }

    public function getCpf(){

        return $this->cpf;

    }

    public function getTipo(){

        return $this->tipo;

    }

    public function getEstatus(){

        return $this->estatus;

    }



    public function setCodigo($codigoFront){

        $this->codigo = $codigoFront;

    }

    

    public function setNome($nomeFront){

        $this->nome = $nomeFront;

    }



    public function setCpf($cpfFront){

        $this->cpf = $cpfFront;

    }



    public function setTipo($tipoFront){

        $this->tipo = $tipoFront;

    }



    public function setEstatus($estatusFront){

        $this->estatus = $estatusFront;

    }



    public function inserir(){

        try{

            $json = file_get_contents('php://input');

            $resultado = json_decode($json);



            $lista = array(

                'nome' => '0',

                'cpf' => '0',

                'tipo' => '0'

            );



            if(verificarParam($resultado, $lista) == 1){

                $this->setNome($resultado->nome);

                $this->setCpf($resultado->cpf);

                $this->setTipo($resultado->tipo);



                if(trim($this->getNome()) == ''){

                    $retorno = array('codigo' => 2,

                                    'msg' => 'Nome não informado.');

                }elseif(trim($this->getCpf()) == ''){

                    $retorno = array('codigo' => 3,

                    'msg' => 'CPF não informado.');

                }elseif(trim($this->getTipo()) == ''){

                    $retorno = array('codigo' => 4,

                    'msg' => 'Tipo não informado.');

                }else{

                    $this->load->model('M_professor');



                    $retorno = $this->M_professor->inserir($this->getNome(),

                                                           $this->getTipo(),

                                                           $this->getCpf());

                }

            }else{

                $retorno = array(

                    'codigo' => 99,

                    'msg' => 'Os campos vindos do Front End não representam o método de inserção. Verifique.'

                );

            }

        }catch(Exception $e){

            $retorno = array(

                'codigo' => 0,

                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

                $e->getMessage()

            );

        }



        echo json_encode($retorno);

    }



    public function consultar(){

        try{

            $json = file_get_contents('php://input');

            $resultado = json_decode($json);



            $this->setCodigo($resultado->codigo);

            $this->setNome($resultado->nome);

            $this->setCpf($resultado->cpf);

            $this->setTipo($resultado->tipo);



            $lista = array(

                'codigo' => '0',

                'nome' => '0',

                'cpf' => '0',

                'tipo' => '0'

            );



            if(verificarParam($resultado, $lista) == 1){

                $this->load->model('M_professor');



                    $retorno = $this->M_professor->consultar($this->getCodigo(),

                                                           $this->getNome(),

                                                           $this->getCpf(),

                                                           $this->getTipo());

            }else{

                $retorno = array(

                    'codigo' => 99,

                    'msg' => 'Os campos vindos do Front End não representam o método de consulta. Verifique.'

                );

            }

        }catch(Exception $e){

            $retorno = array(

                'codigo' => 0,

                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

                $e->getMessage()

            );

        }



        echo json_encode($retorno);

    }



    public function alterar(){

        try{

            $json = file_get_contents('php://input');

            $resultado = json_decode($json);



            $lista = array(

                'codigo' => '0',

                'nome' => '0',

                'cpf' => '0',

                'tipo' => '0'

            );



            if(verificarParam($resultado, $lista) == 1){

                $this->setCodigo($resultado->codigo);

                $this->setNome($resultado->nome);

                $this->setCpf($resultado->cpf);

                $this->setTipo($resultado->tipo);



                if(trim($this->getCodigo() == '')){

                    $retorno = array(

                        'codigo' => 2,

                        'Código não informado.'

                    );

                }elseif(trim($this->getNome() == '') && trim($this->getTipo()) == '' && trim($this->getCpf() == '')){

                    $retorno = array(

                        'codigo' => 3,

                        'msg' => 'Pelo menos um dos parâmetros precisa ser passado para atualização.'

                    );

                }else{

                    $this->load->model('M_professor');



                    $retorno = $this->M_professor->alterar($this->getCodigo(),

                                                           $this->getNome(),

                                                           $this->getCpf(),

                                                           $this->getTipo());

                }

            }else{

                $retorno = array(

                    'codigo' => 99,

                    'msg' => 'Os campos vindos do Front End não representam o método de atualização. Verifique.'

                );

            }

        }catch(Exception $e){

            $retorno = array(

                'codigo' => 0,

                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

                $e->getMessage()

            );

        }



        echo json_encode($retorno);

    }



    public function desativar(){

        try{

            $json = file_get_contents('php://input');

            $resultado = json_decode($json);



            $lista = array(

                'codigo' => '0'

            );



            if(verificarParam($resultado, $lista) == 1){

                $json = file_get_contents('php://input');

                $resultado = json_decode($json);



                $this->setCodigo($resultado->codigo);



                if(trim($this->getCodigo() == '')){

                    $retorno = array(

                        'codigo' => 2,

                        'msg' => 'Código não informado'

                    );

                }else{

                    $this->load->model('M_professor');



                    $retorno = $this->M_professor->desativar($this->getCodigo());

                }

            }else{

                $retorno = array(

                    'codigo' => 99,

                    'msg' => 'Os campos vindos do Front End não representam o método de desativação. Verifique.'

                );

            }

        }catch(Exception $e){

            $retorno = array(

                'codigo' => 0,

                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

                $e->getMessage()

            );

        }



        echo json_encode($retorno);

    }

          public function listar(){
        // função para listar os horários no front
        // sem necessidade de parâmetros
        try{
            // carrega modela
            $this->load->model('M_professor');

            // chama o método para buscar todas as turmas
            $retorno = $this->M_professor->listarTodos();

        } catch (Exception $e) {
            $retorno = array('codigo' => 0,
                               'msg' => 'Erro ao listar os professores'. $e->getMessage());
        }

        // retorna as salas em formato JSON
        echo json_encode($retorno);
    }



}

?>