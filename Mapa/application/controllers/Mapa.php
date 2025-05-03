<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mapa extends CI_Controller
{

    private $codigo;
    private $dataReserva;
    private $codigo_sala;
    private $codigo_horario;
    private $codigo_turma;
    private $codigo_professor;
    private $estatus;

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
    public function getCodigoProfessor()
    {
        return $this->codigo_professor;
    }
    public function getEstatus()
    {
        return $this->estatus;
    }

    //setters dos atributos
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
    public function setCodigoProfessor($codigo_professorFront)
    {
        $this->codigo_professor = $codigo_professorFront;
    }
    public function setEstatus($estatusFront)
    {
        $this->estatus = $estatusFront;
    }

    public function inserir()
    {
        try {
            // Recebe os dados JSON
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
    
            // Verifica se todos os campos foram recebidos
            if (!isset($resultado->dataReserva) || !isset($resultado->codSala) || 
                !isset($resultado->codHorario) || !isset($resultado->codTurma) || 
                !isset($resultado->codProfessor)) {
                echo json_encode(array(
                    'codigo' => 99,
                    'msg' => 'Todos os campos são obrigatórios'
                ));
                return;
            }
    
            // Seta os valores
            $this->setDataReserva($resultado->dataReserva);
            $this->setCodigoSala($resultado->codSala);
            $this->setCodigoHorario($resultado->codHorario);
            $this->setCodigoTurma($resultado->codTurma);
            $this->setCodigoProfessor($resultado->codProfessor);
    
            // Valida campos vazios
            if (empty($this->getDataReserva())) {
                echo json_encode(array('codigo' => 2, 'msg' => 'Data de reserva não informada'));
                return;
            }
            if (empty($this->getCodigoSala())) {
                echo json_encode(array('codigo' => 3, 'msg' => 'Sala não informada'));
                return;
            }
            if (empty($this->getCodigoHorario())) {
                echo json_encode(array('codigo' => 4, 'msg' => 'Horário não informado'));
                return;
            }
            if (empty($this->getCodigoTurma())) {
                echo json_encode(array('codigo' => 5, 'msg' => 'Turma não informada'));
                return;
            }
            if (empty($this->getCodigoProfessor())) {
                echo json_encode(array('codigo' => 6, 'msg' => 'Professor não informado'));
                return;
            }
    
            // Chama o modelo para inserir
            $this->load->model('M_mapa');
            $retorno = $this->M_mapa->inserir(
                $this->getDataReserva(),
                $this->getCodigoSala(),
                $this->getCodigoHorario(),
                $this->getCodigoTurma(),
                $this->getCodigoProfessor()
            );
    
            echo json_encode($retorno);
    
        } catch (Exception $e) {
            echo json_encode(array(
                'codigo' => 00,
                'msg' => 'Erro: ' . $e->getMessage()
            ));
        }
    }

    public function consultar()
    {
        try {
            // Recebe os dados JSON
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
    
            // Inicializa os setters com valores vazios
            $this->setCodigo(isset($resultado->codigo) ? $resultado->codigo : '');
            $this->setDataReserva(isset($resultado->dataReserva) ? $resultado->dataReserva : '');
            $this->setCodigoSala(isset($resultado->codSala) ? $resultado->codSala : '');
            $this->setCodigoHorario(isset($resultado->codHorario) ? $resultado->codHorario : '');
            $this->setCodigoTurma(isset($resultado->codTurma) ? $resultado->codTurma : '');
            $this->setCodigoProfessor(isset($resultado->codProfessor) ? $resultado->codProfessor : '');
    
            // Carrega o modelo
            $this->load->model('M_mapa');
            
            // Chama a consulta no modelo
            $retorno = $this->M_mapa->consultar(
                $this->getCodigo(),
                $this->getDataReserva(),
                $this->getCodigoSala(),
                $this->getCodigoHorario(),
                $this->getCodigoTurma(),
                $this->getCodigoProfessor()
            );
    
            echo json_encode($retorno);
    
        } catch (Exception $e) {
            echo json_encode(array(
                'codigo' => 00,
                'msg' => 'Erro: ' . $e->getMessage()
            ));
        }
    }
    public function alterar()
    {
        try {
            // Recebe os dados JSON
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
    
            // Verifica se o código foi informado
            if (!isset($resultado->codigo) || empty(trim($resultado->codigo))) {
                echo json_encode(array(
                    'codigo' => 2,
                    'msg' => 'Código de reserva não informado'
                ));
                return;
            }
    
            // Seta os valores recebidos
            $this->setCodigo($resultado->codigo);
            $this->setDataReserva(isset($resultado->dataReserva) ? $resultado->dataReserva : null);
            $this->setCodigoSala(isset($resultado->codSala) ? $resultado->codSala : null);
            $this->setCodigoHorario(isset($resultado->codHorario) ? $resultado->codHorario : null);
            $this->setCodigoTurma(isset($resultado->codTurma) ? $resultado->codTurma : null);
            $this->setCodigoProfessor(isset($resultado->codProfessor) ? $resultado->codProfessor : null);
    
            // Carrega o modelo
            $this->load->model('M_mapa');
            
            // Chama o método de alteração
            $retorno = $this->M_mapa->alterar(
                $this->getCodigo(),
                $this->getDataReserva(),
                $this->getCodigoSala(),
                $this->getCodigoHorario(),
                $this->getCodigoTurma(),
                $this->getCodigoProfessor()
            );
    
            echo json_encode($retorno);
    
        } catch (Exception $e) {
            echo json_encode(array(
                'codigo' => 00,
                'msg' => 'Erro: ' . $e->getMessage()
            ));
        }
    }
    public function desativar()
    {
        try {
            // Recebe os dados JSON
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
    
            // Verifica se o código foi informado
            if (!isset($resultado->codigo) || empty($resultado->codigo)) {
                echo json_encode(array(
                    'codigo' => 2,
                    'msg' => 'Código de agendamento não informado'
                ));
                return;
            }
    
            // Seta o código
            $this->setCodigo($resultado->codigo);
    
            // Carrega o modelo
            $this->load->model('M_mapa');
            
            // Chama o método de desativação
            $retorno = $this->M_mapa->desativar($this->getCodigo());
    
            echo json_encode($retorno);
    
        } catch (Exception $e) {
            echo json_encode(array(
                'codigo' => 00,
                'msg' => 'Erro: ' . $e->getMessage()
            ));
        }
    }}
