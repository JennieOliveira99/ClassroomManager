<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Horario extends CI_Controller
{

    //atributos privados da classe
    //Atributos privados da Classe
    private $codigo;
    private $descricao;
    private $horaInicial;
    private $horaFinal;
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

    public function getHoraInicial()
    {
        return $this->horaInicial;
    }

    public function getHoraFinal()
    {
        return $this->horaFinal;
    }

    public function getEstatus()
    {
        return $this->estatus;
    }

    //Setters dos atributos

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @param $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @param $horaInicial
     */
    public function setHoraInicial($horaInicial)
    {
        $this->horaInicial = $horaInicial;
    }

    /**
     * @param $horaFinal
     */
    public function setHoraFinal($horaFinal)
    {
        $this->horaFinal = $horaFinal;
    }

    /**
     * @param $estatus
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;
    }

    public function inserir()
    {
        /*
    horario inicial e final recebidos via JSON e colocados em variaveis:
    1- horario cadastrado corretamente no banco
    2- faltou informar descricao(forntednd)
    3- faltou horario inicial
    4- faltou horario final
    5- horario ja cadastrado no sistema
    6- hiuve algum problema no insert da tabel (BD)

    */

        try {
            //recebendop dados via  json  e atribuind à variaveis
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            //array com dados que deverao vir do front
            $lista = array(
                "descricao" => '0',
                "horaInicial" => '0',
                "horaFinal" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //fazendo setters
                $this->setDescricao($resultado->descricao);
                $this->setHoraInicial($resultado->horaInicial);
                $this->setHoraFinal($resultado->horaFinal);

                //fazendo validacao para saber se todos os dados foram enviados
                if (trim($this->getDescricao()) == '') {
                    $retorno = array(
                        'codigo' => '2',
                        'msg' => 'Descricao nao informada.'

                    );
                } elseif (trim($this->getHoraInicial()) == '') {
                    $retorno = array(
                        'codigo' => '3',
                        'msg' => 'Hora Inicial nao informada.'

                    );
                } elseif (trim($this->getHoraFinal()) == '') {
                    $retorno = array(
                        'codigo' => '4',
                        'msg' => 'Hora final nao informada.');
                } else {
                    //realizo a instrancia da model
                    $this->load->model('M_horario');
                    //atributo retorno recebe array com informações da validacao do acesso
                    $retorno = $this->M_horario->inserir(
                        $this->getDescricao(),
                        $this->getHoraInicial(),
                        $this->getHoraFinal(),

                    );
                }
            } else {
                $retorno = array(
                    'codigo' => '99',
                    'msg' => 'Os campos vindos do frontEnd nao representam o método de login.Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => '0',
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ', $e->getMessage());
        }

        //retorno formato json
        echo json_encode($retorno);
    }
    public function consultar()
{
    try {
        // Recebendo dados via JSON
        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        // Verificando se os dados foram recebidos corretamente
        if ($resultado === null) {
            throw new Exception("Dados JSON inválidos ou não fornecidos.");
        }

        // Extraindo parâmetros do JSON
        $codigo = isset($resultado->codigo) ? trim($resultado->codigo) : '';
        $descricao = isset($resultado->descricao) ? trim($resultado->descricao) : '';
        $horaInicial = isset($resultado->horaInicial) ? trim($resultado->horaInicial) : '';
        $horaFinal = isset($resultado->horaFinal) ? trim($resultado->horaFinal) : '';

        // Carregando a model
        $this->load->model('M_horario');

        // Chamando o método de consulta da model
        $retorno = $this->M_horario->consultar($codigo, $descricao, $horaInicial, $horaFinal);

    } catch (Exception $e) {
        $retorno = array(
            'codigo' => '0',
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
        );
    }

    // Retorno no formato JSON
    echo json_encode($retorno);
}
public function alterar()
{
    try {
        // Recebendo dados via JSON
        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        // Verificando se os dados foram recebidos corretamente
        if ($resultado === null) {
            throw new Exception("Dados JSON inválidos ou não fornecidos.");
        }

        // Extraindo parâmetros do JSON
        $codigo = isset($resultado->codigo) ? trim($resultado->codigo) : '';
        $descricao = isset($resultado->descricao) ? trim($resultado->descricao) : '';
        $horaInicial = isset($resultado->horaInicial) ? trim($resultado->horaInicial) : '';
        $horaFinal = isset($resultado->horaFinal) ? trim($resultado->horaFinal) : '';

        // Verificando se o código foi informado
        if (empty($codigo)) {
            $retorno = array(
                'codigo' => 2,
                'msg' => 'Código não informado.'
            );
        } 
        // Verificando se pelo menos um dos parâmetros (descricao, horaInicial, horaFinal) foi informado
        elseif (empty($descricao) && empty($horaInicial) && empty($horaFinal)) {
            $retorno = array(
                'codigo' => 3,
                'msg' => 'Pelo menos 1 parâmetro deve ser informado para a atualização.'
            );
        } else {
            // Carregando a model
            $this->load->model('M_horario');

            // Chamando o método de alteração da model
            $retorno = $this->M_horario->alterar($codigo, $descricao, $horaInicial, $horaFinal);
        }
    } catch (Exception $e) {
        $retorno = array(
            'codigo' => '0',
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
        );
    }

    // Retorno no formato JSON
    echo json_encode($retorno);
}

    public function desativar()
    {
        /*
        Código recebido via JSON e colocado em variável.
        Retornos possíveis:
        1 - Horário desativado corretamente (Banco)
        2 - Código do horário não informado ou zerado
        5 - Houve algum problema ao desativar o horário (Banco)
        */
        try {
            // Recebendo dados via JSON e atribuindo à variável
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
    
            // Array com dados que devem vir do front
            $lista = array(
                "codigo" => '0'
            );
    
            // Verificando se os parâmetros recebidos são válidos
            if (verificarParam($resultado, $lista) == 1) {
                $json = file_get_contents('php://input');
                $resultado = json_decode($json);

                // Fazendo o setter do código
                $this->setCodigo($resultado->codigo);
    
                // código obrigatório
                if (trim($this->getCodigo()) == '') {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Código não informado.'
                    );
                } else {
                    // Realizando a instância da model
                    $this->load->model('M_horario');
    
                    // Atributo $retorno recebe array com informações da desativação
                    $retorno = $this->M_horario->desativar($this->getCodigo());
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do frontEnd não representam o método de desativação. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => '0',
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
            );
        }
    
        // Retorno no formato JSON
        echo json_encode($retorno);
    }
}