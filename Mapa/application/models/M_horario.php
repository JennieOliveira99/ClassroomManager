<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_horario extends CI_Model
{
    public function inserir($descricao, $horaInicial, $horaFinal)
    {
        try {
            // Verificando se o horário já está cadastrado
            $retornoConsulta = $this->consultaHorario($descricao, $horaInicial, $horaFinal);

            if ($retornoConsulta['codigo'] != 1 && $retornoConsulta['codigo'] != 7) {
                // Query de inserção dos dados
                $this->db->query("INSERT INTO tbl_horario (descricao, hora_ini, hora_fim)
                                  VALUES ('$descricao', '$horaInicial', '$horaFinal')");

                // Verificar se a inserção ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Horário cadastrado corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 6,
                        'msg' => 'Houve algum problema na inserção na tabela de horários.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 5,
                    'msg' => 'Horário já cadastrado no sistema.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }

        // Retorna o array $dados com as informações tratadas
        return $dados;
    }

    private function consultaHorario($descricao, $horaInicial, $horaFinal)
    {
        try {
            // Query para consultar dados de acordo com parâmetros passados
            $sql = "SELECT * FROM tbl_horario 
                    WHERE descricao = '$descricao'
                    AND hora_ini = '$horaInicial' 
                    AND hora_fim = '$horaFinal'
                    AND estatus = ''";

            $retornoHorario = $this->db->query($sql);

            // Verificar se a consulta ocorreu com sucesso
            if ($retornoHorario->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.'
                );
            } else {
                $dados = array(
                    'codigo' => 4,
                    'msg' => 'Horário não encontrado no sistema.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }

        // Retorna o array $dados com as informações tratadas
        return $dados;
    }

    public function consultarHorarioCod($codigo)
    {
        try {
            // Query para consultar dados de acordo com o código
            $sql = "SELECT * FROM tbl_horario WHERE codigo = $codigo AND estatus = ''";
            $retornoHorario = $this->db->query($sql);

            // Verificar se a consulta ocorreu com sucesso
            if ($retornoHorario->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso'
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Horário não encontrado'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }

        // Retorna o array $dados com as informações tratadas
        return $dados;
    }
    public function consultar($codigo, $descricao, $horaInicial, $horaFinal)
    {
        try {
            // Query para consultar dados de acordo com parâmetros passados
            $sql = "SELECT * FROM tbl_horario WHERE estatus != 'D' ";
    
            if (trim($codigo) != '') {
                $sql .= " AND codigo = $codigo ";
            }
    
            if (trim($horaInicial) != '') {
                $sql .= " AND hora_ini = '$horaInicial' ";
            }
    
            if (trim($descricao) != '') {
                $sql .= " AND descricao LIKE '%$descricao%' ";
            }
    
            if (trim($horaFinal) != '') {
                $sql .= " AND hora_fim = '$horaFinal' ";
            }
    
            // Executando a consulta
            $retorno = $this->db->query($sql);
    
            // Verificar se a consulta ocorreu com sucesso
            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.',
                    'dados' => $retorno->result() // Retorna todos os resultados
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Nenhum horário encontrado.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
    
        // Retorna o array $dados com as informações tratadas
        return $dados;
    }
    public function alterar($codigo, $descricao, $horaInicial, $horaFinal)
{
    try {
        // Verifico se o horário já está cadastrado
        $retornoConsulta = $this->consultarHorarioCod($codigo);

        if ($retornoConsulta['codigo'] == 1) {
            // Inicio a query para atualização
            $query = "UPDATE tbl_horario SET ";

            // Vamos comparar os itens
            if ($descricao !== '') {
                $query .= "descricao = '$descricao', ";
            }

            if ($horaInicial !== '') {
                $query .= "hora_ini = '$horaInicial', ";
            }

            if ($horaFinal !== '') {
                $query .= "hora_fim = '$horaFinal', ";
            }

            // Termino a concatenação da query
            $queryFinal = rtrim($query, ", ") . " WHERE codigo = $codigo";

            // Executo a Query de atualização dos dados
            $this->db->query($queryFinal);

            // Verificar se a atualização ocorreu com sucesso
            if ($this->db->affected_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Horário atualizado corretamente.'
                );
            } else {
                $dados = array(
                    'codigo' => 5,
                    'msg' => 'Houve algum problema na atualização na tabela de horários.'
                );
            }
        } else {
            $dados = array(
                'codigo' => 4,
                'msg' => 'Horário não cadastrado no sistema.'
            );
        }
    } catch (Exception $e) {
        $dados = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
        );
    }

    // Retorna o array $dados com as informações tratadas
    return $dados;
}
    public function desativar($codigo)
    {
        try {
            // Verifico se o horário já está cadastrado
            $retornoConsulta = $this->consultarHorarioCod($codigo);

            if ($retornoConsulta['codigo'] == 1) {
                // Query de atualização de dados
                $this->db->query("UPDATE tbl_horario SET estatus = 'D' WHERE codigo = $codigo");

                // Verificar se a atualização ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Horário DESATIVADO corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 4,
                        'msg' => 'Houve algum problema na DESATIVAÇÃO do horário.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 3,
                    'msg' => 'Horário não cadastrado no sistema, não pode desativar.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }

        // Retorna o array $dados com as informações tratadas
        return $dados;
    }
}