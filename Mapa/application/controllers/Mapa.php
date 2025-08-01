<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mapa extends CI_Controller
{
    //Atributos privados da classe
    private $codigo;
    private $dataReserva;
    private $codigo_sala;
    private $codigo_horario;
    private $codigo_turma;
    private $codigo_professor;
    private $estatus;
    private $dataInicio;
    private $dataFim;
    private $diaSemana;

    //Getters dos atributos
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDataReserva()
    {
        return $this->dataReserva;
    }

    public function getCodigoSala()
    {
        return $this->codigo_sala;
    }

    public function getCodigoHorario()
    {
        return $this->codigo_horario;
    }

    public function getCodigoTurma()
    {
        return $this->codigo_turma;
    }

    public function getProfessor()
    {
        return $this->codigo_professor;
    }

    public function getStatus()
    {
        return $this->estatus;
    }
    // getters adicionais
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    public function getDataFim()
    {
        return $this->dataFim;
    }

        public function getDiaSemana()
    {
        return $this->diaSemana;
    }

    //Setters dos atributos
    public function setCodigo($codigoFront)
    {
        $this->codigo = $codigoFront;
    }

    public function setDataReserva($dataReservaFront)
    {
        $this->dataReserva = $dataReservaFront;
    }

    public function setCodigoSala($codigo_salaFront)
    {
        $this->codigo_sala = $codigo_salaFront;
    }

    public function setCodigoHorario($codigo_horarioFront)
    {
        $this->codigo_horario = $codigo_horarioFront;
    }

    public function setCodigoTurma($codigo_turmaFront)
    {
        $this->codigo_turma = $codigo_turmaFront;
    }

    public function setProfessor($professorFront)
    {
        $this->codigo_professor = $professorFront;
    }

    public function setStatus($estatusFront)
    {
        $this->estatus = $estatusFront;
    }
       public function setDataInicio($dataInicioFront)
    {
        $this->dataInicio = $dataInicioFront;
    }

    public function setDataFim($dataFimFront)
    {
        $this->dataFim = $dataFimFront;
    }

    public function setDiaSemana($diaSemanaFront)
    {
        $this->diaSemana = $diaSemanaFront;
    }




    public function inserir()
    {
        //Data de reserva, codigo da sala, codigo do horário codigo da turma
        //Recebidos via JSON e colocados em variáveis
        //Retornos possíveis:
        // 1 - Reserva cadastrada corretamente (Banco)
        // 2 - Faltou informar a Data (FrontEnd)
        // 3 - Faltou informar a Sala (FrontEnd)
        // 4 - Faltou informar o Horário (FrontEnd)
                //5 - Faltou informar a Turma (FrontEnd)
        //6 - Faltou informar o professor (FrontEnd)
        //7 - Agendamento já cadastrado no sistema
        //8 - Agendamento desativado no sistema
        //9 - Houve algum problema no insert da tabela (Banco)

        try {
            //Dados recebidos via JSON
            //e colocados em atributos
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //Array com os dados que deverão vir do Front
            $lista = array(
                "dataReserva" => '0',
                "codSala" => '0',
                "codHorario" => '0',
                "codTurma" => '0',
                "codProfessor" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setDataReserva($resultado->dataReserva);
                $this->setCodigoSala($resultado->codSala);
                $this->setCodigoHorario($resultado->codHorario);
                $this->setCodigoTurma($resultado->codTurma);
                $this->setProfessor($resultado->codProfessor);


                //Faremos uma validação para sabermos se todos os dados
                //foram enviados
                if (trim($this->getDataReserva()) == '') {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Data não informada.'
                    );
                } elseif (trim($this->getCodigoSala()) == '') {
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'Sala não informada.'
                    );
                } elseif (trim($this->getCodigoHorario()) == '') {
                    $retorno = array(
                        'codigo' => 4,
                        'msg' => 'Horário não informado.'
                    );
                } elseif (trim($this->getCodigoTurma()) == '') {
                    $retorno = array(
                        'codigo' => 5,
                        'msg' => 'Turma não informada.'
                    );
                } elseif (trim($this->getProfessor()) == '') {
                    $retorno = array(
                        'codigo' => 6,
                        'msg' => 'Professor não informado.'
                    );
                } else {
                    //Realizo a instancia da Model
                    $this->load->model('M_mapa');

                    //atributo $retorno recebe array com informações
                    //da validação de acesso
                    $retorno = $this->M_mapa->inserir(
                        $this->getDataReserva(),
                        $this->getCodigoSala(),
                        $this->getCodigoHorario(),
                        $this->getCodigoTurma(),
                        $this->getProfessor()
                    );
                } 
            }else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam
                            o método de login. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                $e->getMessage()
            );
        }

        //Retorno no formato JSON
        echo json_encode($retorno);
    }
        
    public function consultar()
    {
        //Código, Data de reserva, codigo da sala, codigo do horário codigo da turma
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
                "codigo" => '0',
                "dataReserva" => '0',
                "codSala" => '0',
                "codHorario" => '0',
                "codTurma" => '0',
                "codProfessor" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDataReserva($resultado->dataReserva);
                $this->setCodigoSala($resultado->codSala);
                $this->setCodigoHorario($resultado->codHorario);
                $this->setCodigoTurma($resultado->codTurma);
                $this->setProfessor($resultado->codProfessor);
                
                //Realizo a instancia da Model
                $this->load->model('M_mapa');

                //Atributo $retorno recebe array com informações
                //da consulta dos dados
                $retorno = $this->M_mapa->consultar(
                    $this->getCodigo(),
                    $this->getDataReserva(),
                    $this->getCodigoSala(),
                    $this->getCodigoHorario(),
                    $this->getCodigoTurma(),
                    $this->getProfessor()
                );
            } else {
            $retorno = array(
                'codigo' => 99,
                'msg' => 'Os campos vindos do FrontEnd não representam
                        o método de login. Verifique.'
            );
        }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                $e->getMessage()
            );
        }

        //Retorno no formato JSON
        echo json_encode($retorno);
    }

    public function alterar()
    {
        //Código, Data de reserva, codigo da sala, codigo do horário codigo da turma
        //recebidos via JSON e colocados em variáveis
        //Retornos possíveis:
        //1 - Reserva alterada corretamente (Banco)
        //2 - Faltou informar o código da reserva (FrontEnd)
        //3 - Faltou informar a Data (FrontEnd)
        //4 - Faltou informar a Sala (FrontEnd)
        //5 - Faltou informar o Horário (FrontEnd)
        //6 - Faltou informar a Turma (FrontEnd)
        //7 - Faltou informar o professor (FrontEnd)
        //8 - Agendamento não cadastrado no sistema
        //9 - Houve algum problema no insert da tabela (Banco)

        try {
            //Dados recebidos via JSON
            //e colocados em atributos
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //Array com os dados que deverão vir do Front
            $lista = array(
                "codigo" => '0',
                "dataReserva" => '0',
                "codSala" => '0',
                "codHorario" => '0',
                "codTurma" => '0',
                "codProfessor" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDataReserva($resultado->dataReserva);
                $this->setCodigoSala($resultado->codSala);
                $this->setCodigoHorario($resultado->codHorario);
                $this->setCodigoTurma($resultado->codTurma);
                $this->setProfessor($resultado->codProfessor);
    
                //Faremos uma validação para sabermos se todos os dados
                //foram enviados
                if (trim($this->getCodigo()) == '') {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Código não informado.'
                    );
                } elseif (trim($this->getDataReserva()) == '') {
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'Data não informada.'
                    );
                } elseif (trim($this->getCodigoSala()) == '') {
                    $retorno = array(
                        'codigo' => 4,
                        'msg' => 'Sala não informada.'
                    );
                } elseif (trim($this->getCodigoHorario()) == '') {
                    $retorno = array(
                        'codigo' => 5,
                        'msg' => 'Horário não informado.'
                    );
                } elseif (trim($this->getCodigoTurma()) == '') {
                    $retorno = array(
                        'codigo' => 6,
                        'msg' => 'Turma não informada.'
                    );
                } elseif (trim($this->getProfessor()) == '') {
                    $retorno = array(
                        'codigo' => 7,
                        'msg' => 'Professor não informado.'
                    );
                } else {
                    //Realizo a instância da Model
                    $this->load->model('M_mapa');
    
                    //Atributo $retorno recebe array com informações
                    //da validação do acesso
                    $retorno = $this->M_mapa->alterar(
                        $this->getCodigo(),
                        $this->getDataReserva(),
                        $this->getCodigoSala(),
                        $this->getCodigoHorario(),
                        $this->getCodigoTurma(),
                        $this->getProfessor()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam
                            o método de login. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                $e->getMessage()
            );
        }

        //Retorno no formato JSON
        echo json_encode($retorno);
    }

    public function desativar()
    {
        //Usuário recebido via JSON e colocado em variável
        //Retornos possíveis:
        //1 - Agendamento desativado corretamente (Banco)
        //2 - Código do curso não informado
        //3 - Houve algum problema na desativação do horário
        //6 - Dados não encontrados (Banco)
        try {
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
                if (trim($this->getCodigo() == '')) {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Código do agendamento não informado'
                    );
                } else {
                    //Realizo a instância da Model
                    $this->load->model("M_mapa");

                    //Atributo $retorno recebe array com informações
                    $retorno = $this->M_mapa->desativar($this->getCodigo());
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam
                            o método de login. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                $e->getMessage()
            );
        }

        //Retorno no formato JSON
        echo json_encode($retorno); 
    }  
//Esse código é da pagina 87 a pagina 94 - Notas de aula do dia 22/04/2025
 public function inserirNovo()
    {
        //Data de reserva, codigo da sala, codigo do horário codigo da turma
        //Recebidos via JSON e colocados em variáveis
        //Retornos possíveis:
        // 1 - Reserva cadastrada corretamente (Banco)
        // 2 - Faltou informar a Data (FrontEnd)
        // 3 - Faltou informar a Sala (FrontEnd)
        // 4 - Faltou informar o Horário (FrontEnd)
                //5 - Faltou informar a Turma (FrontEnd)
        //6 - Faltou informar o professor (FrontEnd)
        //7 - Agendamento já cadastrado no sistema
        //8 - Agendamento desativado no sistema
        //9 - Houve algum problema no insert da tabela (Banco)

        try {
            //Dados recebidos via JSON
            //e colocados em atributos
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //Array com os dados que deverão vir do Front
            $lista = array(
                "codSala" => '0',
                "codHorario" => '0',
                "codTurma" => '0',
                "codProfessor" => '0',
                "dataInicio" =>'0',
                "dataFim" =>'0',
                "diaSemana" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                //Fazendo os setters
                $this->setCodigoSala($resultado->codSala);
                $this->setCodigoHorario($resultado->codHorario);
                $this->setCodigoTurma($resultado->codTurma);
                $this->setProfessor($resultado->codProfessor);
                $this->setDataInicio($resultado->dataInicio);
                $this->setDataFim($resultado->dataFim);
                $this->setDiaSemana($resultado->diaSemana);

                //Faremos uma validação para sabermos se todos os dados
                //foram enviados
                if (trim($this->getCodigoSala()) == '') {
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'Sala não informada.'
                    );
                } elseif (trim($this->getCodigoHorario()) == '') {
                    $retorno = array(
                        'codigo' => 4,
                        'msg' => 'Horário não informado.'
                    );
                } elseif (trim($this->getCodigoTurma()) == '') {
                    $retorno = array(
                        'codigo' => 5,
                        'msg' => 'Turma não informada.'
                    );
                } elseif (trim($this->getProfessor()) == '') {
                    $retorno = array(
                        'codigo' => 6,
                        'msg' => 'Professor não informado.'
                    );
                } else {
                    //Realizo a instancia da Model
                    $this->load->model('M_mapa');

                    //atributo $retorno recebe array com informações
                    //da validação de acesso

                    $retorno = $this->M_mapa->inserirNovo(
                        $this->getCodigoSala(),
                        $this->getCodigoHorario(),
                        $this->getCodigoTurma(),
                        $this->getProfessor(),
                        $this->getDataInicio(),
                        $this->getDataFim(),
                        $this->getDiaSemana()
                    );
                } 
            }else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam
                            o método de login. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' ,
                 $e->getMessage()
            );
        }

        //Retorno no formato JSON
        echo json_encode($retorno);
    }

        public function desativarMultiplos()
    {
        try {
            // recebe codigosvia Json
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //Verificaq se os codigos foram recebidos e se sao validos
            if (!isset($resultado->codigos) || empty($resultado->codigos) || !is_array($resultado->codigos)){
                $retorno = array(
                    'codigo'=> 2,
                    'msg' => 'Nenhum código de agendamento informado.'
                );
            }else{
                $codigos = $resultado->codigos; // array de códigos

                // realizo a instância da model
                $this->load->model('M_mapa');

                $sucesso = true;
                foreach ($codigos as $codigo){
                    $retorno = $this->M_mapa->desativar($codigo); // chama a model para cada codigo
                    if ($retorno['codigo'] !== 1){
                        $sucesso = false;
                        break;
                    }
                }

                if ($sucesso){
                    $retorno = array(
                        'codigo' => 1,
                        'msg' =>'Mapeamentos desativados corretamente'
                    );
                }else{
                    $retorno = array(
                        'codigo' => 5,
                        'msg' =>'Houve um erro ao desativar os mapeamentos'
                    );
                }
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'Erro: ' . $e->getMessage()
            );
        }

        //Retorno no formato JSON
        echo json_encode($retorno); 
        
    }  
}
