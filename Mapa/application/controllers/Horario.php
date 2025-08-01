<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Horario extends CI_Controller {
    // Atributos privados da classe
    private $codigo;
    private $descricao;
    private $horaInicial;
    private $horaFinal;
    private $estatus;

    // Getters dos atributos
    public function getCodigo() 
    {
        return $this->codigo;
    }
    
    public function getHoraInicial() 
    {
        return $this->horaInicial;
    }
    
    public function getHoraFinal() 
    {
        return $this->horaFinal;
    }
    public function getDescricao() 
    {
        return $this->descricao;
    }

    
    public function getEstatus() 
    { 
        return $this->estatus;
    }

    // Setters dos atributos
    public function setCodigo($codigoFront) 
    {
        $this->codigo = $codigoFront;
    }
    public function setDescricao($descricaoFront) 
    {
        $this->descricao = $descricaoFront;
    }

    public function setHoraInicial($horaInicialFront) 
    {
        $this->horaInicial = $horaInicialFront;
    }

    public function setHoraFinal($horaFinalFront) 
    {
        $this->horaFinal = $horaFinalFront;
    }

    public function setEstatus($estatusFront) 
    { 
        $this->estatus = $estatusFront;
    }

    public function inserir(){
        //Horário Inicial e Horário Final
        //recebidos via JSON e colocados em variáveis
        //Retornos possíveis:
        //1 - Horário cadastrado corretamente (Banco)
        //2 - Faltou informar a Descricao (FrontEnd)
        //3 - Faltou informar o Horário Inicial (FrontEnd)
        //4 - Faltou informar o Horário Final (FrontEnd)
        //5 - Horário já cadastrado no sistema
        //6 - Houve algum problema no insert da tabela (Banco)

        try{
            //Dados recebidos via JSON
            //e colocados em atributos
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            //Array com os dados que deverão vir do Front
            $lista = array(
                "descricao" => '0',
                "horaInicial" => '0',
                "horaFinal" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setDescricao($resultado->descricao);
                $this->setHoraInicial($resultado->horaInicial);
                $this->setHoraFinal($resultado->horaFinal);

                //Faremos uma validação para sabermos se todos os dados
                //foram enviados
                if (trim($this->getDescricao()) == ''){
                    $retorno = array('codigo' => 2, 
                                    'msg' => 'Descrição não informada.');
                }elseif (trim($this->getHoraInicial()) == ''){
                    $retorno = array('codigo' => 3, 
                                     'msg' => 'Hora inicial não informada.');
                }elseif (trim($this->getHoraFinal()) == ''){
                    $retorno = array('codigo' => 4, 
                                      'msg' => 'Hora final não informada.');
                }else{
                    //Realizo a instância da Model
                    $this->load->model('M_horario');

                    //Atributo $retorno recebe array com informações
                    //da validação do acesso
                    $retorno = $this->M_horario->inserir($this->getDescricao(),
                                                         $this->getHoraInicial(),
                                                         $this->getHoraFinal());
                }
            }else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindo do FrontEnd não representam 
                              o método de login. Verifique.'
                    );
                }

        }catch (Exception $e) {
                $retorno = array('codigo'=> 0,
                                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ', 
                                        $e->getMessage());
        }    

        //Retorno no formato JSON
        echo json_encode($retorno);
    }
    
    public function consultar(){

        //Código
        //recebido via JSON e colocados
        //em variáveis
        //Retornos possíveis:
        //1 - Dados consultados corretamente (Banco)
        //6 - Dados não encontrados (Banco)
    
        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
    
            //Array com os dados que deverão vir do Front
            $lista = array(
                'codigo' => '0',
                "descricao" => '0',
                "horaInicial" => '0',
                "horaFinal" => '0'
            );
            
            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setHoraInicial($resultado->horaInicial);
                $this->setHoraFinal($resultado->horaFinal);
            
                //Realizo a instância da Model
                $this->load->model('M_horario');
            
                //Atributo $retorno recebe array com informações
                //da consulta dos dados
                $retorno = $this->M_horario->consultar($this->getCodigo(),
                $this->getDescricao(),
                $this->getHoraInicial(),
                $this->getHoraFinal());
            }else{
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam o método de login. Verifique.'
                );
            }
            
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' , $e->getMessage());
        }
            
            //Retorno no formato JSON
            echo json_encode($retorno);
    }

    public function alterar(){
        //Código, horário inicial e horário final
        //recebidos via JSON e colocados
        //em variáveis
        //Retornos possíveis:
        //1 - Dado(s) alterado(s) corretamente (Banco)
        //2 - Código da sala não informado ou Zerado
        //3 - Pelo menos um parâmetro precisa ser informado
        //    (descrição, hora inicial ou hora final)
        //4 - Horário não cadastrado no sistema
        //5 - Houve algum problema no salvamento dos dados
    
        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
    
            //Array com os dados que deverão vir do Front
            $lista = array(
                "codigo" => '0',
                "descricao" => '0',
                "horaInicial" => '0',
                "horaFinal" => '0'
            );
    
            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setHoraInicial($resultado->horaInicial);
                $this->setHoraFinal($resultado->horaFinal);
                //Código é obrigatório
                if (trim($this->getCodigo() == '')) {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Código não informado.'
                    );
                //Descrição, Hora Inicial e Hora Final,
                //Pelo menos 1 deles precisa ser informado.
                }elseif(trim($this->getDescricao() == '' && $this->getHoraInicial() == '' 
                        && $this->getHoraFinal() == '')){
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'Pelo menos um parâmetro precisa ser 
                        passado para atualização.');
                }else{
                    //Realizo a instância da Model
                    $this->load->model('M_horario');

                    //Atributo $retorno recebe array com informações
                    //da alteração dos dados
                    $retorno = $this->M_horario->alterar($this->getCodigo(),
                                                         $this->getDescricao(),
                                                         $this->getHoraInicial(),
                                                         $this->getHoraFinal());
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam
                              o método de login. Verifique.'
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
        //Usuário recebido via JSON e colocado em variável
        //Retornos possíveis:
        //1 - Horário desativado corretamente (Banco)
        //2 - Código do horário não informado
        //3 - Horário não cadastrado no sistema
        //4 - Houve algum problema na desativação do horário
    
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
            
                //Código é obrigatório
                if (trim($this->getCodigo() == '')){
                    $retorno = array('codigo' => 2,
                                      'msg' => 'Código não informado.');
                }else{
                    //Realizo a instância da Model
                    $this->load->model("M_horario");

                    $retorno = $this->M_horario->desativar($this->getCodigo());                
                }
            }else {
            $retorno = array(
                'codigo' => 99,
                'msg' => 'Os campos vindos do FrontEnd não representam
                          o método de login. Verifique.'
                );
            }

            } catch (Exception $e) {
                $retorno = array('codigo' => 0,
                                 'msg' => 'ATENCÃO: O seguinte erro aconteceu -> ',
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
            $this->load->model('M_horario');

            // chama o método para buscar todas as turmas
            $retorno = $this->M_horario->listarTodos();

        } catch (Exception $e) {
            $retorno = array('codigo' => 0,
                               'msg' => 'Erro ao listar os horarios'. $e->getMessage());
        }

        // retorna as salas em formato JSON
        echo json_encode($retorno);
    }
}

// localhost/FatecSRDSII202501/Horario/inserir

//  {
//     "codigo" : "",
// 	"descricao" : "Manhã",
// 	"horaInicial" : "08:00",
//  	"horaFinal" : "12:00"
//  }
