<?php
defined('BASEPATH') OR exit('No direct script acess allowed');

class Sala extends CI_Controller {
    //Atributos privados da Classe
    private $codigo;
    private $descricao;
    private $andar;
    private $capacidade;
    private $estatus;

    //Getters dos atributos
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getAndar()
    {
        return $this->andar;
    }

    public function getCapacidade()
    {
        return $this->capacidade;
    }

    public function getEstatus()
    {
        return $this->estatus;
    }

    //Setters dos atributos
    public function setCodigo($codigoFront)
    {
        $this->codigo = $codigoFront;
    }

    public function setDescricao($descricaoFront)
    {
        $this->descricao = $descricaoFront;
    }

    public function setAndar($andarFront)
    {
        $this->andar = $andarFront;
    }

    public function setCapacidade($capacidadeFront)
    {
        $this->capacidade = $capacidadeFront;
    }

    public function setEstatus($estatusFront)
    {
        $this->estatus = $estatusFront;
    }


    public function inserir(){
        //Codigo, Descrição e Andar
        // Recebidos via JSON e colocados em variáveis
        // Retornos possíveis:
        // 1 - Sala cadastrados corretamente (Banco)
        // 2 - Faltou informar o código da sala (FrontEnd)
        // 3 - Faltou informar a descrição (FrontEnd)
        // 4 - Faltou informar o andar (FrontEnd)
        // 5 - Sala já cadastrada no sistema
        // 6 - Houve algum problema no insert da tabela (Banco)
        // 7 - Sala já cadastrada no Sistema
       try{
        // Dados recebidos via JSON e colocados em atributos
        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        // Array com os dados que deverão vir do Front
        $lista = array(
            "codigo" => '0',
            "descricao" => '0',
            "andar" => '0',
            "capacidade" => '0'
        );

        if (verificarParam($resultado, $lista) == 1) {
            // Fazendo os setters
            $this->setCodigo($resultado->codigo);
            $this->setDescricao($resultado->descricao);
            $this->setAndar($resultado->andar);
            $this->setCapacidade($resultado->capacidade);
            
            // Farenos uma validação para sabermos se todos os dados foram enviados
            if (trim($this->getCodigo()) == '' || $this->getCodigo() == 0) {
                $retorno = array('codigo' => 2,
                                 'msg' => 'Código não informado.');
            }elseif(trim($this->getDescricao()) == ''){
                $retorno = array('codigo' => 3,
                                'msg' => 'Descrição não informada.');
            }elseif(trim($this->getAndar()) == '' || trim($this->getAndar()) == 0){
                $retorno = array('codigo' => 4,
                                'msg' => 'Andar não informado.'); 
            }elseif(trim($this->getCapacidade()) == '' || trim($this->getCapacidade()) == 0){
                $retorno = array('codigo' => 5,
                                'msg' => 'Capacidade não informada.');
            }else{
                // Realizo a instância da Model
                $this->load->model('M_sala');

                // Atributo $retorno recebe array com informações da validação do acesso
                $retorno = $this->M_sala->inserir($this->getCodigo(), $this->getDescricao(),
                                                  $this->getAndar(), $this->getCapacidade());
            }
        }else {
           $retorno = array(
            'codigo' => 99,
            'msg' => 'Os campos vindos do FrontEnd não representam
                      o método de Inserção. Verifique.'
            );
        }

     }catch (Exception $e) {  
        $retorno = array('codigo' => 0,
                        'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                             $e->getMessage());
     }

      // Retorno no formato JSON
      echo json_encode($retorno);
    }

    public function consultar(){
         //Codigo, Descrição e Andar
        // Recebidos via JSON e colocados em variáveis
        // Retornos possíveis:
        // 1 - Dados consultados corretamente (Banco)
        // 6 - Dados não encontrados (Banco)
        try{
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            // Array com os dados que deverão vir do Front
            $lista = array(
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                # Fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setAndar($resultado->andar);
                $this->setCapacidade($resultado->capacidade);

                // Realizo a instância do Model
                $this->load->model('M_sala');

                // Atributo $retorno recebe array com informações da consulta dos dados
                $retorno = $this->M_sala->consultar($this->getCodigo(),
                                                    $this->getDescricao(),
                                                    $this->getAndar(),
                                                    $this->getCapacidade());
            }else{
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam
                              o método de Consulta. Verifique.'
                );
            }
        }catch (Exception $e) {
            $retorno = array('codigo' => 0,
                             'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                       $e->getMessage());
        }
        // Retorno no formato JSON
        echo json_encode($retorno);
    }

    public function alterar(){
        //Codigo, Descrição e Andar
        // Recebidos via JSON e colocados em variáveis
        // Retornos possíveis:
        // 1 - Dado(s) alterados(s) corretamente (Banco)
        // 2 - Código da sala não informado ou Zerado
        // 3 - Descriçãp não informada
                     
        try{
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
    
            // Array com os dados que deverão vir do Front
            $lista = array(
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0',
            );
            
            if (verificarParam($resultado, $lista) == 1) {
                # Fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setAndar($resultado->andar);
                $this->setCapacidade($resultado->capacidade);

                if (trim($this->getCodigo() == '')) {
                    $retorno = array('codigo' => 2,
                                     'msg' => 'Código não informado');
                // Descrição, Andar ou Capacidade, pelo menos 1 deles precisa ser informado                     
                }elseif(trim($this->getDescricao()) == '' && trim($this->getAndar()) == '' &&
                        trim($this->getCapacidade()) == ''){
                    $retorno = array('codigo' => 3,
                                     'msg' => 'Pelo menos um parâmetro precisa ser passado para atualização');        
                }else{
                    // Realizo a instância da Model
                    $this->load->model('M_sala');

                    // Atributo $retorno recebe o array com informações da alteração dos dados
                    $retorno = $this->M_sala->alterar($this->getCodigo(),
                                                      $this->getDescricao(),  
                                                      $this->getAndar(),  
                                                      $this->getCapacidade());                                               
                }
            }else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam
                              o método de Alteração. Verifique'
                );
            }
        }catch (Exception $e) {
            $retorno = array('codigo' => 0,
                             'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                             $e->getMessage());
        }
        // Retorno no formato JSON
        echo json_encode($retorno);
    }

    public function desativar(){
        //Codigo, Descrição e Andar
        // Recebidos via JSON e colocados em variáveis
        // Retornos possíveis:
        // 1 - Sala desativada corretamente (Banco)
        // 2 - Código da sala não informado
        // 5 - Houve algum problema na desativação da sala
        // 6 - Dados não encontradas

        try{
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
    
            // Array com os dados que deverão vir do Front
            $lista = array(
                "codigo" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                
                $json = file_get_contents('php://input');
                $resultado = json_decode($json);

                //Fazendo os setters
                $this->setCodigo($resultado->codigo);

                // Validação para do usuário que não deverá ser branco
                if (trim($this->getCodigo() == '')) {
                    $retorno = array('codigo' => 2,
                                     'msg' => 'Código não informado');
                }else{
                    // Realizo a instância da Model
                    $this->load->model('M_sala');

                    // Atributo $retorno recebe array com informações
                    $retorno = $this->M_sala->desativar($this->getCodigo());
                }
            }else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'os campos vindos do FrontEmd não representam
                              o método de login. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array('codigo' => 0,
                             'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                       $e->getMessage());
        }

        // Retorno no formato JSON
        echo json_encode($retorno);
    }
}

?>