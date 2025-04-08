<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Professor extends CI_Controller
{

    //atributos privados da classe
    private $codigo;
    private $nome;
    private $cpf;
    private $tipo;
    private $estatus;

    //getters dos atributos
    public function getCodigo()
    {
        return $this->codigo;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function getCpf()
    {
        return $this->cpf;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getEstatus()
    {
        return $this->estatus;
    }

    // setters dos atributos
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;
    }

    public function inserir()
    {
        //horario inicial e horario final
        //recebidos via JSON e colocados em variaveis
        //retornos possiveis:
        //1 - professor cadastrado com sucesso
        //2 - faltou informar o nome
        //3 - faltou informar o cpf
        //4 - faltou informar o tipo
        //5 - professor ja cadastrado no sistema
        //6 - houve algum problema no insert da tabela

        try {
            //dados frecebidos via JSON
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //array com os dados que deverao vir do fornt
            $lista = array(
                "nome" => '0',
                "cpf" => '0',
                "tipo" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //fazendo os setters
                $this->setNome($resultado->nome);
                $this->setCpf($resultado->cpf);
                $this->setTipo($resultado->tipo);

                //faremos a validacao para sabermos se todos os dados foram enviados
                if (trim($this->getNome()) == '') {
                    $retorno = array('codigo' => 2, 'msg' => 'Faltou informar o nome ');
                } else if (trim($this->getCpf()) == '') {
                    $retorno = array('codigo' => 3, 'msg' => 'Faltou informar o cpf ');
                } else if (trim($this->getTipo()) == '') {
                    $retorno = array('codigo' => 4, 'msg' => 'Faltou informar o tipo ');
                } else {
                    //realiza a instancia da model
                    $this->load->model('M_professor');

                    //atributo $retorno recebe array com informações da validacao do acesso
                    $retorno = $this->M_professor->inserir(
                        $this->getNome(),
                        $this->getCpf(),
                        $this->getTipo()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do frontEnd nao reprentam o método de inserção. VERIFIQUE.'
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


    public function consultar()
{
    //codigo,nome,cp, tipo recebidos via JSON colocados em variaveis
    //retornos possiveis:
    //1 - dados consultados corretamente
    //6 - dados nao encontrados

    try {
        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        //array com os dados que deverao vir do front
        $lista = array(
            "codigo" => '0',
            "nome" => '0',
            "cpf" => '0',
            "tipo" => '0'
        );
        
        if (verificarParam($resultado, $lista) == 1) {
            //fazendo os setters APÓS verificar os parâmetros
            $this->setCodigo($resultado->codigo);
            $this->setNome($resultado->nome);
            $this->setCpf($resultado->cpf);
            $this->setTipo($resultado->tipo);

            //realiza a instancia da model
            $this->load->model('M_professor');

            //atributo $retorno recebe array com informações da validacao do acesso
            $retorno = $this->M_professor->consultar(
                $this->getCodigo(),
                $this->getNome(),
                $this->getCpf(),
                $this->getTipo()
            );
        } else {
            $retorno = array(
                'codigo' => 99,
                'msg' => 'Os campos vindos do frontEnd nao representam o método de consulta. VERIFIQUE.'
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
    public function alterar()
    {
        //codigo, nome, cpf, tipo rcebidos via JSON colocados em variaveis
        //retornos possiveis:
        //1 - dados alterados corretamente
        //2 - faltou informar o codigo ou codigo zerado
        //3 - pelo menos 1 parametro deve ser infrmado
        //5 - dados nao encontrados

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //array com os dados que deverao vir do fornt
            $lista = array(
                "codigo" => '0',
                "nome" => '0',
                "cpf" => '0',
                "tipo" => '0'
            );
            if (verificarParam($resultado, $lista) == 1) {

                //fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setNome($resultado->nome);
                $this->setCpf($resultado->cpf);
                $this->setTipo($resultado->tipo);

                //validações para a passagem de atributo ou campo VAZIO
                if (trim($this->getCodigo()) == '') {
                    $retorno = array('codigo' => 2, 'msg' => 'Faltou informar o codigo ');
                } else if (trim($this->getNome() == '' && trim($this->getTipo()) == '' && trim($this->getCpf()) == '')) {
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'Pelo menos 1 parametro deve ser informado 
                para atualização.'
                    );
                } else {
                    //realiza a instancia da model
                    $this->load->model('M_professor');

                    //atributo $retorno recebe array com informações da validacao do acesso
                    $retorno = $this->M_professor->alterar(
                        $this->getCodigo(),
                        $this->getNome(),
                        $this->getCpf(),
                        $this->getTipo()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do frontEnd nao reprentam o método de alteracao. VERIFIQUE.'
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
        //usuario recebido via JSON  colocado em variavel
        //retornos possiveis
        //1 - usuario desativado corretamente
        // 2 - faltou informar o codigo ou codigo zerado
        // 5- houve algum problema no delete da tabela
        //6 - dados nao encontrados

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //array com os dados vindos do front
            $lista = array(
                "codigo" => '0'
            );

            if(verificarParam($resultado, $lista) == 1){
                $json = file_get_contents('php://input');
                $resultado = json_decode($json);

                //fazendo os setters 
                $this->setCodigo($resultado->codigo);
                //validações para a passagem de atributo ou campo VAZIO
                if (trim($this->getCodigo()) == '') {
                    $retorno = array('codigo' => 2, 
                    'msg' => 'Faltou informar o codigo ');
                } else {
                    //realiza a instancia da model
                    $this->load->model('M_professor');

                    //atributo $retorno recebe array com informações da validacao do acesso
                    $retorno = $this->M_professor->desativar(
                        $this->getCodigo()
                    );
                }
            }else{
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do frontEnd nao reprentam o método de desativacao. VERIFIQUE.'
                );
            }
        } 
        catch (Exception $e) {
            //se houver erro, retorna o erro
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
            );
        }
        //retorno no formato JSON
        echo json_encode($retorno);
    }
}
