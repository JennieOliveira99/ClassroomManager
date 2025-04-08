<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_professor extends CI_Model
{
    public function inserir($nome, $tipo, $cpf)
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
                    "msg" => 'Professor já cadastrado'
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

    public function alterar($codigo, $nome, $tipo, $cpf)
    {
        try {
            //verifico se o prof ja esta cadastrado 
            $retornoConsulta = $this->consultaProfessorCod($codigo);
            if ($retornoConsulta['codigo'] == 1) {
                $query = "update tbl_professor set";
                $updates = array();
                
                if ($nome != '') {
                    $updates[] = " nome = '$nome'";
                }
                if ($cpf != '') {
                    $updates[] = " cpf = '$cpf'";
                }
                if ($tipo != '') {
                    $updates[] = " tipo = '$tipo'";
                }
                
                // Se não há campos para atualizar
                if (empty($updates)) {
                    return array(
                        'codigo' => 3,
                        'msg' => 'Pelo menos 1 parametro deve ser informado para atualização'
                    );
                }
                
                // Junta todos os campos a atualizar
                $query .= implode(",", $updates);
                $query .= " where codigo = $codigo";
                
                //executo a query
                $this->db->query($query);
                
                //verifico se a alteração foi realizada com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'mensagem' => 'Professor alterado corretamente'
                    );
                } else {
                    $dados = array(
                        'codigo' => 6,
                        "msg" => 'Nenhuma alteração foi necessária ou houve um problema na alteração da tabela'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 5,
                    "msg" => 'Professor não encontrado'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o seguinte erro aconteceu: ' . $e->getMessage()
            );
        }
        return $dados;
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
