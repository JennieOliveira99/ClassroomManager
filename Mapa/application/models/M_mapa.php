<?php

defined('BASEPATH') or exit('No direct script access allowed');

//incluir classe que precisamos instanciar
include_once("M_sala.php");
include_once("M_horario.php");
include_once("M_Turma.php");
include_once("M_professor.php");

class M_mapa extends CI_Model
{
    public function inserir($dataReserva, $codSala, $codHorario, $codTurma, $codProfessor)
    {
        try {

            //verifico se professor já esta cadastrado
           

            $retornoConsulta = $this->consultaReservaTotal($dataReserva, $codSala, $codHorario, $codTurma, $codProfessor);


            if ($retornoConsulta['codigo'] == 6) {
                //chamando o objeto sala para validação
                $salaObj = new M_sala();

                //chamar metodo de verificação
                $retornoConsultaSala = $salaObj->consultar($codSala, '', '', '');

                if ($retornoConsultaSala['codigo'] == 1) {
                    //chamo oobjeto sala para validação

                    $horarioObj = new M_horario();

                    //chamar metodo de verificação
                    //$retornoConsultaHorario = $horarioObj->consultaHorarioCod($codHorario);
                    $retornoConsultaHorario = $horarioObj->consultar($codHorario, '', '', '');
                    if ($retornoConsultaHorario['codigo'] == 1) {

                        //chamo o obj para a validaçã
                        $turmaObj = new M_turma();
                        //chamar o metodo de verificação
                        $retornoConsultaTurma = $turmaObj->consultaTurmaCod($codTurma);
                        if ($retornoConsultaTurma['codigo'] == 1) {

                            //chamo o obj para a validação
                            $professorObj = new M_professor();

                            //chamar o metodo de verificação
                            $retornoConsultaProfessor = $professorObj->consultaProfessorCod($codProfessor);

                            if ($retornoConsultaProfessor['codigo'] == 1) {

                                //query de inserção de dados
                                $this->db->query("insert into tbl_mapa (data_reserva, sala, codigo_horario, codigo_turma, codigo_professor)
                                values ('" . $this->db->escape_str($dataReserva) . "', 
                                       " . $this->db->escape($codSala) . ", 
                                       " . $this->db->escape($codHorario) . ", 
                                       " . $this->db->escape($codTurma) . ", 
                                       " . $this->db->escape($codProfessor) . ")");
                                //verifico se a inserção foi realizada com sucesso
                                if ($this->db->affected_rows() > 0) {
                                    return array(
                                        'codigo' => 1,
                                        'mensagem' => 'Reserva cadastrada corretamente'
                                    );
                                } else {
                                    return array(
                                        'codigo' => 8,
                                        "msg" => 'Houve um problema na inserção da tabela de agendamento'
                                    );
                                }
                            } else {
                                $dados = array(
                                    'codigo' => $retornoConsultaProfessor['codigo'],
                                    'msg' => $retornoConsultaProfessor['msg']
                                );
                            }
                        } else {
                            $dados = array(
                                'codigo' => $retornoConsultaTurma['codigo'],
                                'msg' => $retornoConsultaTurma['msg']
                            );
                        }
                    } else {
                        $dados = array(
                            'codigo' => $retornoConsultaHorario['codigo'],
                            'msg' => $retornoConsultaHorario['msg']
                        );
                    }
                } else {
                    $dados = array(
                        'codigo' => $retornoConsultaSala['codigo'],
                        'msg' => $retornoConsultaSala['msg']
                    );
                }
            } 
            else {
                return $retornoConsulta;
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //enviao array de com as infos tratadas acima pela etsrutura de decisão if else
        return $dados;
    }

    private function consultaReservaTotal($dataReserva, $codSala, $codHorario, $codTurma, $codProfessor)
    {
        try {
            // Verificação de agendamento exato
            $sql = "SELECT * FROM tbl_mapa 
                    WHERE data_reserva = '" . $this->db->escape_str($dataReserva) . "'
                    AND sala = " . $this->db->escape($codSala) . "
                    AND codigo_horario = " . $this->db->escape($codHorario) . "
                    AND codigo_turma = " . $this->db->escape($codTurma) . "
                    AND codigo_professor = " . $this->db->escape($codProfessor);
            
            $retornoExato = $this->db->query($sql);
            
            if ($retornoExato->num_rows() > 0) {
                $linha = $retornoExato->row();
                return array(
                    'codigo' => (isset($linha->estatus) && $linha->estatus == "D") ? 7 : 1,
                    'msg' => (isset($linha->estatus) && $linha->estatus == "D") ? 
                        'Agendamento desativado no sistema.' : 
                        'Agendamento já cadastrado no sistema'
                );
            }
    
            // Verificação de conflito de horário
            $sql = "SELECT h.hora_ini, h.hora_fim 
                    FROM tbl_horario h 
                    WHERE h.codigo = " . $this->db->escape($codHorario);
            
            $retornoHorario = $this->db->query($sql);
            
            if ($retornoHorario->num_rows() > 0) {
                $linhaHr = $retornoHorario->row();
                $horaInicial = $linhaHr->hora_ini;
                $horaFinal = $linhaHr->hora_fim;
    
                $sql = "SELECT m.* FROM tbl_mapa m
                        JOIN tbl_horario h ON m.codigo_horario = h.codigo
                        WHERE m.data_reserva = '" . $this->db->escape_str($dataReserva) . "'
                        AND m.sala = " . $this->db->escape($codSala) . "
                        AND h.hora_fim > '" . $horaInicial . "'
                        AND h.hora_ini < '" . $horaFinal . "'
                        AND (m.estatus IS NULL OR m.estatus != 'D')";
                
                $retornoConflito = $this->db->query($sql);
                
                if ($retornoConflito->num_rows() > 0) {
                    return array(
                        'codigo' => 1,
                        'msg' => 'Já existe um agendamento conflitante para esta sala e horário'
                    );
                }
            }
    
            return array(
                'codigo' => 6,
                'msg' => 'Reserva não encontrada'
            );
    
        } catch (Exception $e) {
            return array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o seguinte erro aconteceu: ' . $e->getMessage()
            );
        }
    }
    public function consultar($codigo, $dataReserva, $codSala, $codHorario, $codTurma, $codProfessor)
    {
        try {
            //query para consultar dados de acordo com parametros passados
         $sql = "select m.codigo, date_format(m.data_reserva,'%d-%m-%Y') datareservabra, data_reserva,
        m. sala, s.descricao descsala, m.codigo_horario,
        h.descricao deshorario, m.codigo_turma, t.descricao descturma, m.codigo_professor,
        p.nome nome_professor
        from tbl_mapa m, tbl_professor p, tbl_horario h, tbl_turma t, tbl_sala s
        where m.estatus = ''
        and m.codigo_professor = p.codigo
        and m.codigo_horario = h.codigo
        and m.codigo_turma = t.codigo
        and m.sala = s.codigo";
        if (trim($codigo) != '') {
            $sql .= " AND m.codigo = " . $this->db->escape($codigo);
        }
        if (trim($dataReserva) != '') {
            $sql .= " AND m.data_reserva = '" . $this->db->escape_str($dataReserva) . "'";
        }
        if (trim($codSala) != '') {
            $sql .= " AND m.sala = " . $this->db->escape($codSala);
        }
        if (trim($codHorario) != '') {
            $sql .= " AND m.codigo_horario = " . $this->db->escape($codHorario);
        }
        if (trim($codTurma) != '') {
            $sql .= " AND m.codigo_turma = " . $this->db->escape($codTurma);
        }
        if (trim($codProfessor) != '') {
            $sql .= " AND m.codigo_professor = " . $this->db->escape($codProfessor);
        }
            //$sql = $sql . "order by m.datareserva, h.hora_ini, m.codigo_horario, m_sala ";
         
            $sql .= " ORDER BY m.data_reserva, h.hora_ini, m.codigo_horario, m.sala";
            $retorno = $this->db->query($sql);

            //verifica se consulta ocorreu com sucesso
            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    "msg" => 'Consulta efetuada com sucesso',
                    "dados" => $retorno->result()
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    "msg" => 'Agedamentos não encontrados'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o seguinte erro aconteceu: ' . $e->getMessage(),
                "\n"
            );
        }
        //envia array de dados com as infos tratadas    
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
            // Primeiro verifica se o agendamento existe
            $consulta = $this->consultar($codigo, '', '', '', '', '');
            if ($consulta['codigo'] != 1) {
                return array(
                    'codigo' => 6,
                    'msg' => 'Agendamento não cadastrado'
                );
            }
    
            // Query de desativação (DELETE ou UPDATE conforme sua necessidade)
            // Opção 1: DELETE físico
            $this->db->query("DELETE FROM tbl_mapa WHERE codigo = " . $this->db->escape($codigo));
            
            // Opção 2: UPDATE lógico (se você mantém histórico)
            // $this->db->query("UPDATE tbl_mapa SET estatus = 'D' WHERE codigo = " . $this->db->escape($codigo));
    
            if ($this->db->affected_rows() > 0) {
                return array(
                    'codigo' => 1,
                    'msg' => 'Agendamento desativado corretamente'
                );
            } else {
                return array(
                    'codigo' => 5,
                    'msg' => 'Houve um problema na desativação do agendamento'
                );
            }
        } catch (Exception $e) {
            return array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: o seguinte erro aconteceu: ' . $e->getMessage()
            );
        }
    }
}
