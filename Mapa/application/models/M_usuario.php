<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_usuario extends CI_Model {
    public function inserir($nome, $email, $usuario, $senha){

        try{
            //Verificar o status do usuário antes de fazer o insert
            $retornoUsuario = $this->validaUsuario($usuario);

            if($retornoUsuario['codigo'] == 4){
                //Query de inserção dos dados
                $this->db->query("insert into tbl_usuario (nome, email, usuario, senha)
                                  values ('$nome', '$email', '$usuario', md5('$senha'))");

                //Verificar se a inserção ocorreu com sucesso
                if($this->db->affected_rows() > 0){
                    $dados = array('codigo' => 1,
                                   'msg' => 'Usuário cadastrado corretamente.');
                }else{
                    $dados = array('codigo' => 6,
                                   'msg' => 'Houve algum problema na inserção na tabela de usuário.');
                }
            }else{
                $dados = array('codigo' => $retornoUsuario['codigo'],
                               'msg' => $retornoUsuario['msg']);
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 00,
                           'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' .
                                    $e->getMessage());
        }
                //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }

    public function consultar($nome, $email, $usuario){
        //---------------------------------------------
        //Função que servirá para três tipos de consulta:
        // * Para todos os usuários;
        // * Para um determinado usuário;
        // * Para nomes de usuários;
        //---------------------------------------------

        try{
            //Query para consultar dados de acordo com parâmetros passados
            $sql = "select id_usuario, nome, usuario, email
                    from tbl_usuario
                    where estatus != 'D'";

            if(trim($nome) != ''){
                $sql = $sql . " and nome like '%$nome%' ";
            }

            if(trim($email) != ''){
                $sql = $sql . " and email = '$email' ";
            }

            if(trim($usuario) != '') {
                $sql = $sql . " and usuario like '%$usuario%' ";
            }

            $retorno = $this->db->query($sql);

            //Verificar se a consulta ocorreu com sucesso
            if($retorno->num_rows() > 0){
                $dados = array('codigo' => 1,
                               'msg' => 'Consulta efetuada com sucesso.',
                               'dados' => $retorno->result());
            }else{
                $dados = array('codigo' => 6,
                               'msg' => 'Dados não encontrados.');
            }
        }catch (Exception $e){
            $dados = array('codigo' => 00,
                           'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' .
                                    $e->getMessage());
        }

        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }

    public function alterar($idUsuario, $nome, $email, $senha){
        try{
            //Verificar o status do usuário antes de fazer o update
            $retornoUsuario = $this->validaIdUsuario($idUsuario);

            if($retornoUsuario['codigo'] == 1){

                //Inicio a query para atualização
                //Inicio a query para atualização
                $query = "update tbl_usuario set ";

                //Vamos comparar os itens
                if($nome !== ''){
                    $query .= "nome = '$nome', ";
                }

                if($email !== ''){
                    $query .= "email = '$email', ";
                }

                if($senha !== ''){
                    $query .= "senha = md5('$senha'), ";
                }

                //Termino a concatenação da query
                $queryFinal = rtrim($query, ", ") . " where id_usuario = $idUsuario";

                //Executo a Query de atualização dos dados
                $this->db->query($queryFinal);

                //Verificar se a atualização ocorreu com sucesso
                if($this->db->affected_rows() > 0){
                    $dados = array('codigo' => 1,
                                    'msg' => 'Usuário atualizado corretamente');
                }else{
                    $dados = array('codigo' => 6,
                                    'msg' => 'Houve algum problema na atualização na 
                                                tabela de usuários');
                }
            }else{
                $dados = array('codigo' => $retornoUsuario['codigo'],
                                'msg' => $retornoUsuario['msg']);
            }
        }catch (Exception $e){
            $dados = array('codigo' => 00,
                            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' .
                                    $e->getMessage());
        }

        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }

    public function desativar($idUsuario){
        try {
            $retornoUsuario = $this->validaIdUsuario($idUsuario);
    
            if ($retornoUsuario['codigo'] == 1) {
                $this->db->query("UPDATE tbl_usuario SET estatus = 'D' WHERE id_usuario = $idUsuario");
    
                if ($this->db->affected_rows() > 0) {
                    return array('codigo' => 1, 'msg' => 'Usuário desativado com sucesso.');
                } else {
                    return array('codigo' => 6, 'msg' => 'Houve um problema ao desativar o usuário.');
                }
    
            } else {
                return $retornoUsuario; // Pode ser código 4 ou 5, retornado diretamente
            }
    
        } catch (Exception $e) {
            return array('codigo' => 0, 'msg' => 'Erro: ' . $e->getMessage());
        }
    }
    
    private function validaUsuario($usuario){
        try{
            //Atributo retorno recebe o resultado do SELECT
            //Sem status pois teremos que validar
            //Para verificar se está deletado virtualmente ou não.
            $retorno = $this->db->query("select * from tbl_usuario
                                         where usuario = '$usuario'");

            //Verifica se a quantidade de linhas trazidas na consulta é superior a 0
            //Vinculamos o resultado da query para tratarmos o resultado do status
            $linha = $retorno->row();

            if($retorno->num_rows() == 0){
                $dados = array('codigo' => 4,
                               'msg' => 'Usuário não existe na base de dados.');
            }else{
                if(trim($linha->estatus) == "D"){
                    $dados = array('codigo' => 5,
                                   'msg' => 'Usuário DESATIVADO NA BASE DE DADOS,
                                             não pode ser utilizado!');
                }else{
                    $dados = array('codigo' => 1,
                                   'msg' => 'Usuário correto');
                }
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 00,
                           'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' .
                                    $e->getMessage());
        }

        return $dados;
    }

    private function validaIdUsuario($idUsuario){
        try {
            $retorno = $this->db->query("SELECT * FROM tbl_usuario WHERE id_usuario = $idUsuario");
            $linha = $retorno->row();
    
            if ($retorno->num_rows() == 0) {
                return array(
                    'codigo' => 4,
                    'msg' => 'Usuário não existe na base de dados.'
                );
            } else {
                $estatus = strtolower(trim($linha->estatus));
                if ($estatus == "d") {
                    return array(
                        'codigo' => 5,
                        'msg' => 'Usuário JÁ DESATIVADO NA BASE DE DADOS!'
                    );
                } else {
                    return array(
                        'codigo' => 1,
                        'msg' => 'Usuário encontrado e ativo.'
                    );
                }
            }
        } catch (Exception $e) {
            return array(
                'codigo' => 0,
                'msg' => 'Erro: ' . $e->getMessage()
            );
        }
    }
    

        public function validaLogin($usuario, $senha){
        try{
        //Atributo retorno recebe o resultado do SELECT
        //realizado na tabela de usuários lembrando da função MD5()
        //por causa da criptografia, e sem status pois teremos que validar
        //para verificar se está deletado virtualmente ou não.
        $retorno = $this->db->query("select * from tbl_usuario
                            where usuario = '$usuario'
                            and senha = md5('$senha')");

        //Verifica se a quantidade de linhas trazidas na consulta é superior a 0,
        //Vinculamos o resultado da query para tratarmos o resultado do status
        $linha = $retorno->row();

        if($retorno->num_rows() == 0){
        $dados = array('codigo' => 4,
                'msg' => 'Usuário ou senha inválidos.');
        }else{
            if(trim($linha->estatus) == "D"){
            $dados = array('codigo' => 5,
                        'msg' => 'Usuário DESATIVADO NA BASE DE DADOS!');
            }else{
            $dados = array('codigo' => 1,
                        'msg' => 'Usuário correto');
            }
        }
        
        } catch (Exception $e) {
            $dados = array('codigo' => 00,
                        'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' .
                                    $e->getMessage());
        }

        return $dados;
    }
}    






