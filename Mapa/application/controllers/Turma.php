<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Turma extends CI_Controller
{

    //atributos privados da classe
    private $codigo;
    private $descricao;
    private $capacidade;
    private $dataInicio;
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

    public function getCapacidade()
    {
        return $this->capacidade;
    }

    public function getDataInicio()
    {
        return $this->dataInicio;
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

    public function setCapacidade($capacidadeFront)
    {
        $this->capacidade = $capacidadeFront;
    }

    public function setDataInicio($dataInicioFront)
    {
        $this->dataInicio = $dataInicioFront;
    }

    public function setEstatus($estatusFront)
    {
        $this->estatus = $estatusFront;
    }

    public function inserir()
    {
        //descricao e capacidade recebidos via JSON  e colocados  em variaveis
        //retornos possiveis:
        //1 - turma cadastrada corretamente
        //2 - faltou informar descricao  
        //3 - faltou informar capacidade
        //4- faltou informar data de inicio da turma
        //5 - turma ja cadastrada no sistema
        //6 - houve um problema no inserir da turma

        try {
            //dados recebidos via JSON
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //array com os dados que deverao vir do front
            $lista = array(
                'descricao' => '0',
                'capacidade' => '0',
                'dataInicio' => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {

                //fazendo os setters
                $this->setDescricao($resultado->descricao);
                $this->setCapacidade($resultado->capacidade);
                $this->setDataInicio($resultado->dataInicio);

                //faremos com os dados que deverão vir do front
                if (trim($this->getDescricao()) == '') {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'ATENÇÃO: Faltou informar a descrição da turma.'
                    );
                } else if (trim($this->getCapacidade()) == '') {
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'ATENÇÃO: Faltou informar a capacidade da turma.'
                    );
                } else if (trim($this->getDataInicio()) == '') {
                    $retorno = array(
                        'codigo' => 4,
                        'msg' => 'ATENÇÃO: Faltou informar a data de início da turma.'
                    );
                } else {
                    //realizo a instancia da model
                    $this->load->model('M_turma');
                    //atributo $retorno recebe array com infos da validação do acesso
                    $retorno = $this->M_turma->inserir(
                        $this->getDescricao(),
                        $this->getCapacidade(),
                        $this->getDataInicio()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'ATENÇÃO: Os campos do FrontEnd não reprentaam o método de inserção. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        //retorno formato JSON
        echo json_encode($retorno);
    }

    public function consultar()
    {
        //codigo, descricao e capacidade recebidos via JSON  e colocados  em variaveis
        //retornos possiveis:
        //1 - turma cadastrada corretamente
        //6 -dados nao encontrados

        try {
            //array com dados que deverao vir do front
            $lista = array(
                'codigo' => '0',
                'descricao' => '0',
                'capacidade' => '0',
                'dataInicio' => '0'
            );

            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            if (verificarParam($resultado, $lista) == 1) {
                //fazendo setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setCapacidade($resultado->capacidade);
                $this->setDataInicio($resultado->dataInicio);

                //realizo a instrancia da model
                $this->load->model('M_turma');
                //atributo retorno recebe array com informações da validacao do acesso
                $retorno = $this->M_turma->consultar(
                    $this->getCodigo(),
                    $this->getDescricao(),
                    $this->getCapacidade(),
                    $this->getDataInicio()
                );
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do frontEnd nao representam o método de consulta. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        //retorno formato json
        echo json_encode($retorno);
    }
/*
    public function alterar(){
        //codigo, descricao e capacidade recebidos via JSON  e colocados  em variaveis
        //retornos possiveis:
        //1 - dados alterados corretamente
        //2 - faltou informar codigo ou codigo zerado
        //3 - pelo menos 1 parametro deve ser passado
        //5 - dados nao encontrados

        try{
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //array com dados que deverao vir do front
            $lista = array(
                'codigo' => '0',
                'descricao' => '0',
                'capacidade' => '0',
                'dataInicio' => '0'
            );
            if(verificarParam($resultado, $lista) == 1) {
                //fazendo setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setCapacidade($resultado->capacidade);
                $this->setDataInicio($resultado->dataInicio);

               //validacoes para a passagem de atributo ou campo vazio
               if(
                    trim($this->getCodigo()) == '' ){
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'ATENÇÃO: Código da turma não.'
                    );
                } else if(trim($this->getDescricao()) == '' && trim($this->getCapacidade()) == '' && trim($this->getDataInicio()) == ''){
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'ATENÇÃO: Pelo menos um parâmetro deve ser passado para alteração.'
                    );
                } else {
                    //realizo a instrancia da model
                    $this->load->model('M_turma');

                    //atributo retorno recebe array com informações da validacao do acesso
                    $retorno = $this->M_turma->alterar(
                        $this->getCodigo(),
                        $this->getDescricao(),
                        $this->getCapacidade(),
                        $this->getDataInicio()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do frontEnd nao representam o método de alteração. Verifique.'
                );
            }

        }
        catch (Exception $e) {
            $retorno = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
    }
*/

    public function alterar()
{
    try {
        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        // Array com dados que devem vir do front
        $lista = array(
            'codigo' => '0',
            'descricao' => '0',
            'capacidade' => '0',
            'dataInicio' => '0'
        );

        if (verificarParam($resultado, $lista) == 1) {
            // Fazendo setters
            $this->setCodigo($resultado->codigo);
            $this->setDescricao($resultado->descricao);
            $this->setCapacidade($resultado->capacidade);
            $this->setDataInicio($resultado->dataInicio);

            // Validações para a passagem de atributo ou campo vazio
            if (trim($this->getCodigo()) == '') {
                $retorno = array(
                    'codigo' => 2,
                    'msg' => 'ATENÇÃO: Código da turma não informado.'
                );
            } else if (trim($this->getDescricao()) == '' && trim($this->getCapacidade()) == '' && trim($this->getDataInicio()) == '') {
                $retorno = array(
                    'codigo' => 3,
                    'msg' => 'ATENÇÃO: Pelo menos um parâmetro deve ser passado para alteração.'
                );
            } else {
                // Realizo a instância da model
                $this->load->model('M_turma');

                // Atributo retorno recebe array com informações da validação do acesso
                $retorno = $this->M_turma->alterar(
                    $this->getCodigo(),
                    $this->getDescricao(),
                    $this->getCapacidade(),
                    $this->getDataInicio()
                );
            }
        } else {
            $retorno = array(
                'codigo' => 99,
                'msg' => 'Os campos vindos do frontEnd não representam o método de alteração. Verifique.'
            );
        }
    } catch (Exception $e) {
        $retorno = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
        );
    }

    // Retorno no formato JSON
    echo json_encode($retorno);
}


        public function desativar(){
            //codigo, descricao e capacidade recebidos via JSON  e colocados  em variaveis
            //retornos possiveis:   
            //1 - dados desativados corretamente
            //2 - faltou informar codigo 
            //5 - houve algum problema na destivacao da turma
            //6 - dados nao encontrados

            try{
                $json = file_get_contents('php://input');
                $resultado = json_decode($json);

                //array com dados que deverao vir do front
                $lista = array(
                    'codigo' => '0'
                  
                );
                
                if(verificarParam($resultado, $lista) == 1) {
                    $json = file_get_contents('php://input');
                    $resultado = json_decode($json);

                    //fazendo setters
                    $this->setCodigo($resultado->codigo);
                   

                   //validacoes para a passagem de atributo ou campo vazio
                   if(
                        trim($this->getCodigo()) == '' ){
                        $retorno = array(
                            'codigo' => 2,
                            'msg' => 'ATENÇÃO: Código da turma não.'
                        );
                    }  else {
                        //realizo a instrancia da model
                        $this->load->model('M_turma');

                        //atributo retorno recebe array com informações da validacao do acesso
                        $retorno = $this->M_turma->desativar(
                            $this->getCodigo(),
                           
                        );
                    }
                } else {
                    $retorno = array(
                        'codigo' => 99,
                        'msg' => 'Os campos vindos do frontEnd nao representam o método de alteração. Verifique.'
                    );
                }

            }
            catch (Exception 
                $e) {
                $retorno = array(
                    'codigo' => 00,
                    'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
                );
            }
            //retorno formato json
            echo json_encode($retorno);
        }
    }
