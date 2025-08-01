<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Turma extends CI_Controller {
    // Atributos privados da classe
    private $codigo;
    private $descricao;
    private $capacidade;
    private $dataInicio;
    private $estatus;

    // Getters dos atributos
    public function getCodigo() {
        return $this->codigo;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getCapacidade() {
        return $this->capacidade;
    }

    public function getEstatus() {
        return $this->estatus;
    }

        // Setters dos atributos
    public function setCodigo($codigoFront)
    {
        $this->codigo = $codigoFront;
    }

    public function setDataInicio($dataInicioFront)
    {
        $this->dataInicio = $dataInicioFront;
    }

    public function setDescricao($descricaoFront)
    {
        $this->descricao = $descricaoFront;
    }

    public function setCapacidade($capacidadeFront)
    {
        $this->capacidade = $capacidadeFront;
    }

    public function setEstatus($estatusFront)
    {
        $this->estatus = $estatusFront;
    }

    public function inserir()    {
        // Descrição, e capacidade
        // recebidos via JSON e colocados em variáveis
        // Retornos possíveis:
        //1 - Turma cadastrada corretamente (Banco)
        //2 - Faltou informar a Descricao (FrontEnd)
        //3 - Faltou informar a capacidade (FrontEnd)
        //4 - Faltou informar a data de início da turma (FrontEnd)
        //5 - Turma já cadastrada no sistema
        //6 - Houve algum problema no insert da tabela (Banco)

        try{

            //Dados recebidos via JSON
            //e colocados em atributos
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //Array com os dados que deverão vir do Front
            $lista = array(
                "descricao" => '0',
                "capacidade" => '0',
                "dataInicio" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setDescricao($resultado->descricao);
                $this->setCapacidade($resultado->capacidade);
                $this->setDataInicio($resultado->dataInicio);

                //Faremos uma validação para sabermos se todos os dados
                //foram enviados
                if (trim($this->getDescricao()) == ''){
                    $retorno = array('codigo' => 2,
                                    'msg' => 'Descrição não informada.');
                }elseif (trim($this->getCapacidade()) == ''){
                    $retorno = array('codigo' => 3,
                                    'msg' => 'Capacidade não informada.');
                }elseif (trim($this->getDataInicio()) == ''){
                    $retorno = array('codigo' => 4,
                                    'msg' => 'Data de início não informada.');
                }else{
                    //Realizo a instância da Model
                    $this->load->model('M_turma');

                    //Atributo $retorno recebe array com informações
                    //da validação do acesso
                    $retorno = $this->M_turma->inserir($this->getDescricao(),
                                                       $this->getCapacidade(),
                                                       $this->getDataInicio());
                }    
            }else {
                $retorno = array(
                'codigo' => 99,
                'msg' => 'Os campos vindos do FrontEnd não representam
                            o método de inserção. Verifique.'
                );
            }

        }catch (Exception $e) {
                $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                        $e->getMessage());
        }

        //Retorno no formato JSON
        echo json_encode($retorno);
    }    
 public function consultar(){
            //Código, Descrição e Capacidade
            //recebidos via JSON e colocados
            //em variáveis
            //Retornos possíveis:
            //1 - Dados consultados corretamente (Banco)
            //6 - Dados não encontrados (Banco)

        try{
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //Array com os dados que deverão vir do Front
            $lista = array(
                "codigo"     => '0',
                "descricao"  => '0',
                "capacidade" => '0',
                "dataInicio" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setCapacidade($resultado->capacidade);
                $this->setDataInicio($resultado->dataInicio);

                //Realizo a instância da Model
                $this->load->model('M_turma');

                //Atributo $retorno recebe array com informações
                //da consulta dos dados
                $retorno = $this->M_turma->consultar($this->getCodigo(),
                                                    $this->getDescricao(),
                                                    $this->getCapacidade(),
                                                    $this->getDataInicio());
            }else{
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam
                            o método de consulta. Verifique.'
                );
            }
        }catch (Exception $e) {
            $retorno = array('codigo' => 0,
                    'msg' => 'ATENÇÃO: O seguinte erro aconteceu -',
                              $e->getMessage());
        }
        //Retorno no formato JSON
        echo json_encode($retorno);
    }        
    public function alterar(){
        //Código, Descrição e Capacidade
        //recebidos via JSON e colocados
        //em variáveis
        //Retornos possíveis:
        //1 - Dado(s) alterado(s) corretamente (Banco)
        //2 - Código não informado ou Zerado
        //3 - Pelo menos um parâmetro deve ser passado
        //5 - Dados não encontrados (Banco)
    
        try{
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            
            //Array com os dados que deverão vir do Front
            $lista = array(
                "codigo" => '0',
                "descricao" => '0',
                "capacidade" => '0',
                "dataInicio" => '0'
            );
            
            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setCapacidade($resultado->capacidade);
                $this->setDataInicio($resultado->dataInicio);
            
                //Validações para passagem de atributo ou campo VAZIO
                if (trim($this->getCodigo() == '')){
                    $retorno = array('codigo' => 2,
                                      'msg' => 'Código não informado');
                //Nome, Senha ou Tipo de Usuário, pelo menos 1 deles precisa ser informado.
                }elseif(trim($this->getDescricao() == '') && trim($this->getCapacidade() == '')
                        && trim($this->getDataInicio() == '')){
                    $retorno = array('codigo' => 3,
                                      'msg' => 'Pelo menos um parâmetro precisa ser passado para atualização');
                }else{
                //Realizo a instância da Model
                $this->load->model('M_turma');
            
                //Atributo $retorno recebe array com informações
                //da alteração dos dados
                $retorno = $this->M_turma->alterar($this->getCodigo(),
                                                    $this->getDescricao(),
                                                    $this->getCapacidade(),
                                                    $this->getDataInicio());
                }
            }else{
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam o método de alteração. Verifique.'
                );
            }
            
        }catch (Exception $e) {
            $retorno = array('codigo' => 0,
                            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                    $e->getMessage());
        }
        //Retorno no formato JSON
        echo json_encode($retorno);
    }
    
    public function desativar(){
        //Código da turma recebido via JSON e colocado em variável
        //Retornos possíveis:
        //1 - Turma desativada corretamente (Banco)
        //2 - Código da turma não informado
        //5 - Houve algum problema na desativação da turma
        //6 - Dados não encontrados (Banco)
    
        try{
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
        
            //Array com os dados que deverão vir do Front
            $lista = array(
            "codigo" => '0'
            );
                
            if (verificarParam($resultado, $lista) == 1) {
                $json = file_get_contents('php://input');
                $resultado = json_decode($json);

                //Fazendo os setters
                $this->setCodigo($resultado->codigo);

                //Validação para do usuário que não deverá ser branco
                if (trim($this->getCodigo() == '')){
                    $retorno = array('codigo' => 2,
                                    'msg' => 'Código não informado');
                }else{
                    //Realizo a instância da Model
                    $this->load->model('M_turma');
                
                    //Atributo $retorno recebe array com informações
                    $retorno = $this->M_turma->desativar($this->getCodigo());
                }
                
            }else {
                $retorno = array(
                'codigo' => 99,
                'msg' => 'Os campos vindos do FrontEnd não representam
                o método de desativação. Verifique.'
                );
            }
        } catch (Exception $e) {
             $retorno = array('codigo' => 0,
                              'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                        $e->getMessage());
        }
                
        //Retorno no formato JSON
        echo json_encode($retorno);
    } 
    public function listar(){
        // função para listar os horários no front
        // sem necessidade de parâmetros
        try{
            // carrega modela
            $this->load->model('M_turma');

            // chama o método para buscar todas as turmas
            $retorno = $this->M_turma->listarTodas();

        } catch (Exception $e) {
            $retorno = array('codigo' => 0,
                               'msg' => 'Erro ao listar as turmas'. $e->getMessage());
        }

        // retorna as salas em formato JSON
        echo json_encode($retorno);
    }
}       


                        
                    





