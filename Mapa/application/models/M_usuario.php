<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_usuario extends CI_Model{
    public function inserir($nome, $email, $usuario, $senha){
        try{
            //verificar status antes do insert
            $retornoUsuario = $this->validaUsuario($usuario);
            if($retornoUsuario['codigo'] == 4){
                //query com inserção de dados
               $this->db->query("INSERT INTO tbl_usuario (nome, email, usuario, senha) 
                VALUES ('$nome', '$email', '$usuario', MD5('$senha'))");
                //verificar se a inserçao ocorreu com sucesso
                if($this->db->affected_rows() > 0){
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Usuário cadastrado com sucesso!'
                    );
                }else{
                    $dados = array(
                        'codigo' => 6,
                        'msg' => 'Houve um problema na inserção na tabela de usuario'
                    );
                } 
            }else{
                $dados = array('codigo' => $retornoUsuario['codigo'],
                    'msg' => $retornoUsuario['msg']);
               
            }
        }
        catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //enviao array de com as infos tratadas acima pela etsrutura de decisão if else
        return $dados;
    }
       public function consultar($nome, $email, $usuario, ){
        try{
            $sql = "select id_usuario, nome,usuario, email
            from tbl_usuario
            where estatus != 'D'";

            if(trim($nome) != ''){
                $sql = $sql . " and nome like '%$nome%'";
            }

            if(trim($email) != ''){
                $sql = $sql . " and email = '$email'";
            }
            if(trim($usuario) != ''){
                $sql = $sql . " and usuario like '%$usuario%'";
            }

            $retorno = $this->db->query($sql);

            //verificar se a consulta ocorreu com sucesso
            if($retorno->num_rows() > 0){
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta realizada com sucesso!',
                    'dados' => $retorno->result()
                );}else{
                    $dados = array(
                        'codigo' => 6,
                        'msg' => 'Dados não encontrados.'
                    );
                }
        }
        catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //enviao array de com as infos tratadas acima pela etsrutura de decisão if else
        return $dados;
    }

  public function alterar($idUsuario, $nome, $email, $senha) {
    try {
        $retornoUsuario = $this->validaIdUsuario($idUsuario);

        if ($retornoUsuario['codigo'] == 1) {
            $query = "UPDATE tbl_usuario SET ";
            $fieldsAdded = false; // Rastreia se campos foram adicionados

            if ($nome !== '') {
                $query .= "nome = '$nome', ";
                $fieldsAdded = true;
            }
            if ($email !== '') {
                $query .= "email = '$email', ";
                $fieldsAdded = true;
            }
            if ($senha !== '') {
                $query .= "senha = MD5('$senha'), ";
                $fieldsAdded = true;
            }

            // Verifica se há campos para atualizar
            if (!$fieldsAdded) {
                $dados = array(
                    'codigo' => 7,
                    'msg' => 'Nenhum campo válido fornecido para atualização.'
                );
                return $dados;
            }

            $queryFinal = rtrim($query, ", ") . " WHERE id_usuario = $idUsuario";
            $this->db->query($queryFinal);

            if ($this->db->affected_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Usuário atualizado com sucesso!'
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Houve um problema na atualização na tabela de usuario'
                );
            }
        } else {
            $dados = array(
                'codigo' => $retornoUsuario['codigo'],
                'msg' => $retornoUsuario['msg']
            );
        }
    } catch (Exception $e) {
        $dados = array(
            'codigo' => 00,
            "msg" => 'ATENÇÃO: O seguinte erro aconteceu: ' . $e->getMessage()
        );
    }
    return $dados;
}
    public function desativar ($idUsuario){
try{
    //verificar o status antes do update
    $retornoUsuario = $this->validaUsuario($idUsuario);

    if($retornoUsuario['codigo'] == 1){
        //query de atualização dos dados
        $this->db->query("update tbl_usuario set estatus ='D'
        where id_usuario = $idUsuario");

        //verificar se a atualização ocorreu com sucesso
        if($this->db->affected_rows() > 0 ){
            $dados = array(
                'codigo' => 1,
                'msg' => 'Usuário desativado com sucesso!'
            );
        }else{
            $dados = array(
                'codigo' => 6,
                'msg' => 'Houve um problema na atualização na tabela de usuario'
            );
        }
    }else{
        $dados = array(
            'codigo' => $retornoUsuario['codigo'],
            'msg' => $retornoUsuario['msg']
        );
    }
}
catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //enviao array de com as infos tratadas acima pela etsrutura de decisão if else
        return $dados;
    }

    private function validaUsuario($usuario){
        try{
        //verificar se o usuario existe
        $retorno = $this->db->query("select * from tbl_usuario
        where usuario = '$usuario'");

        //verifica se qtd de linhas trazidas na consulta é superior 0
        //vinculamos o resultado da query para tratarmos p resultado
        $linha = $retorno->row();
        if($retorno->num_rows() == 0){
            $dados = array(
                'codigo' => 4,
                'msg' => 'Usuário não existe na base de dados!'
            );
        }else{
           if(trim($linha->estatus == "D")){
               $dados = array(
                'codigo' => 5,
                'msg' => 'Usuário DESATIVADO na base de dados, não pode ser utilizado!'
            );

           }
         else{
            $dados = array(
                'codigo' => 1,
                'msg' => 'Usuário correto!'
            );
         }
        }}
  catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //enviao array de com as infos tratadas acima pela etsrutura de decisão if else
        return $dados;
    }

private function validaIdUsuario($idUsuario){
try{
    $retorno = $this->db->query("select * from tbl_usuario
    where id_usuario = $idUsuario");

    //verifica se qtd de linhas trazidas na consulta é superior 0
    //vinculamos o resultado da query para tratarmos p resultado
    $linha = $retorno->row();
    if($retorno->num_rows() == 0){
        $dados = array(
            'codigo' => 4,
            'msg' => 'Usuário não existe na base de dados!'
        );
    }else{

        if(trim($linha->estatus) == "D"){
            $dados = array(
                'codigo' => 5,
                'msg' => 'Usuário DESATIVADO na base de dados, não pode ser utilizado!'
            );
        }
        else{
            $dados = array(
                'codigo' => 1,
                'msg' => 'Usuário correto!'
            );
        }}
}

 catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //enviao array de com as infos tratadas acima pela etsrutura de decisão if else
        return $dados;
    }

public function validaLogin($usuario, $senha){
    try{
        //verificar se o usuario existe
     
        $retorno = $this->db->query("SELECT * FROM tbl_usuario 
                           WHERE usuario = '$usuario' 
                           AND senha = MD5('$senha')");
  //verifica se qtd de linhas trazidas na consulta é superior 0
        //vinculamos o resultado da query para tratarmos p resultado

        $linha = $retorno->row();
      
        if($retorno->num_rows() == 0){
            $dados = array(
                'codigo' => 4,
                'msg' => 'Usuário ou senha inválidos!'
            );
        }else{
           if(trim($linha->estatus == "D")){
               $dados = array(
                'codigo' => 5,
                'msg' => 'Usuário DESATIVADO na base de dados, não pode ser utilizado!'
            );

           }else{
             $dados = array(
                'codigo' => 1,
                'msg' => 'Usuário correto!'
            );
           }
        }}
catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                "msg" => 'ATENÇÃO: o segiunte erro aconteceu: ' . $e->getMessage()
            );
        }
        //enviao array de com as infos tratadas acima pela etsrutura de decisão if else
        return $dados;
    }
} 