<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_professor extends CI_Model
{
   
    public function inserir($nome, $cpf, $tipo)
    {
        try {
            //verifico se o prof ja esta cadastrado 
            $retornoConsulta = $this->consultaProfessorCpf($cpf);
            if ($retornoConsulta['codigo'] != 1) {
                //query de inserção de dados
                $this->db->query("insert into tbl_professor (nome, tipo, cpf) values ('$nome', '$tipo', '$cpf')");

                //verifico se a inserção foi realizada com sucesso
                if ($this->db->affected_rows() > 0) {
                    return array(
                        'codigo' => 1,
                        'mensagem' => 'Professor cadastrado corretamente'
                    );
                } else {
                    $dados = array(
                        'codigo' => 6,
                        "msg" => 'Houve um problema na inserção da tabela'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 5,
                    "msg" => 'Professor já cadastrado no sistema'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //envia array de dados com as infos tratadas 
        return $dados;
    }

    private function consultaProfessorCpf($cpf)
    {
        try {
            //query para consultar dados de acordo com parametros passados
            $sql = "select * from tbl_professor where cpf = '$cpf'";
            $retornoSala = $this->db->query($sql);

            //verifica se a consulta ocorreu com sucesso
            if ($retornoSala->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    "msg" => 'Consulta efetuada com sucesso',
                    
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    "msg" => 'Professor não encontrado'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //envia array de dados com as infos tratadas    
        return $dados;
    }

    public function consultaprofessorCod($codigo)
    {
        try {
            //query para consultar dados de acordo com parametros passados
            $sql = "select * from tbl_professor where codigo = '$codigo' and estatus = ''";
            $retornoSala = $this->db->query($sql);

            //verifica se a consulta ocorreu com sucesso
            if ($retornoSala->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    "msg" => 'Consulta efetuada com sucesso',
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    "msg" => 'Professor não encontrado'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //query para consultar dados de acordo com parametros passados
        return $dados;
    }
    public function consultar($codigo, $nome, $cpf, $tipo)
    {
        try {
            //query para consultar dados de acordo com parametros passados
            $sql = "select * from tbl_professor where estatus = ''";
            if (trim($codigo) != '') {
                $sql .= " and codigo = '$codigo'";
            }
            if (trim($cpf) != '') {
                $sql .= " and cpf = '$cpf'";
            }
            if (trim($nome) != '') {
                $sql .= " and nome like '%$nome%'";
            }

            if (trim($tipo) != '') {
                $sql .= " and tipo = '$tipo'";
            }
            $sql .= " order by nome ";
            $retorno = $this->db->query($sql);

            //verifica se a consulta ocorreu com sucesso
            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    "msg" => 'Consulta efetuada com sucesso',
                    'dados' => $retorno->result()
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    "msg" => 'Professor não encontrado'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //query para consultar dados de acordo com parametros passados
        return $dados;
    }

    public function alterar($codigo, $dataReserva, $codSala, $codHorario, $codTurma, $codProfessor)
    {
        try {
            // Primeiro verifica se o agendamento existe
            $consulta = $this->consultar($codigo, '', '', '', '', '');
            if ($consulta['codigo'] != 1) {
                return array(
                    'codigo' => 8,
                    'msg' => 'Agendamento não encontrado no sistema'
                );
            }
    
            // Inicia a construção da query
            $query = "UPDATE tbl_mapa SET ";
            $updates = array();
    
            // Adiciona apenas os campos que foram informados
            if (!empty($dataReserva)) {
                $updates[] = "data_reserva = '" . $this->db->escape_str($dataReserva) . "'";
            }
            if (!empty($codSala)) {
                // Valida a sala
                $salaObj = new M_sala();
                if ($salaObj->consultar($codSala, '', '', '')['codigo'] != 1) {
                    return array(
                        'codigo' => 4,
                        'msg' => 'Sala inválida'
                    );
                }
                $updates[] = "sala = " . $this->db->escape($codSala);
            }
            if (!empty($codHorario)) {
                // Valida o horário
                $horarioObj = new M_horario();
                if ($horarioObj->consultar($codHorario, '', '', '')['codigo'] != 1) {
                    return array(
                        'codigo' => 5,
                        'msg' => 'Horário inválido'
                    );
                }
                $updates[] = "codigo_horario = " . $this->db->escape($codHorario);
            }
            if (!empty($codTurma)) {
                // Valida a turma
                $turmaObj = new M_turma();
                if ($turmaObj->consultaTurmaCod($codTurma)['codigo'] != 1) {
                    return array(
                        'codigo' => 6,
                        'msg' => 'Turma inválida'
                    );
                }
                $updates[] = "codigo_turma = " . $this->db->escape($codTurma);
            }
            if (!empty($codProfessor)) {
                // Valida o professor
                $professorObj = new M_professor();
                if ($professorObj->consultaProfessorCod($codProfessor)['codigo'] != 1) {
                    return array(
                        'codigo' => 7,
                        'msg' => 'Professor inválido'
                    );
                }
                $updates[] = "codigo_professor = " . $this->db->escape($codProfessor);
            }
    
            // Se não há campos para atualizar
            if (empty($updates)) {
                return array(
                    'codigo' => 9,
                    'msg' => 'Nenhum campo válido para atualização'
                );
            }
    
            // Monta a query final
            $query .= implode(", ", $updates) . " WHERE codigo = " . $this->db->escape($codigo);
    
            // Executa a atualização
            $this->db->query($query);
    
            if ($this->db->affected_rows() > 0) {
                return array(
                    'codigo' => 1,
                    'msg' => 'Agendamento alterado com sucesso'
                );
            } else {
                return array(
                    'codigo' => 9,
                    'msg' => 'Nenhuma alteração realizada ou dados idênticos aos existentes'
                );
            }
    
        } catch (Exception $e) {
            return array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: o seguinte erro aconteceu: ' . $e->getMessage()
            );
        }
    }

    public function desativar($codigo)
    {

        try {
            //verifico se o professor ja esta cadatsrado
            $retornoConsulta = $this->consultaProfessorCod($codigo);
            if ($retornoConsulta['codigo'] == 1) {
                //query de desativação do professor
                $this->db->query("update tbl_professor set estatus = 'D'
            where codigo = $codigo");

                //vericar se atualização ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Professor desativado corretamente'
                    );
                } else {
                    $dados = array(
                        'codigo' => 5,
                        "msg" => 'Houve um problema na desativação do produto'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 6,
                    "msg" => 'Professor não cadastrado'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //envia array de dados com as infos tratadas
        return $dados;
    }
}
