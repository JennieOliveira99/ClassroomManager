<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_relatorio extends CI_Model
{
   public function buscarReservasPorData($dataMapa)
   {
      try {
         $sql = "SELECT
    DATE_FORMAT(m.datareserva, '%d-%m-%Y') AS datareserva,
    s.descricao AS desc_sala,
    s.codigo AS desc_codigo,
    h.descricao AS desc_periodo,
    DATE_FORMAT(h.hora_ini, '%H:%i') AS hora_ini,
    DATE_FORMAT(h.hora_fim, '%H:%i') AS hora_fim,
    t.descricao AS desc_turma,
    p.nome AS nome_professor
FROM 
    tbl_mapa m
JOIN
    tbl_professor p ON m.codigo_professor = p.codigo
JOIN
    tbl_horario h ON m.codigo_horario = h.codigo
JOIN 
    tbl_turma t ON m.codigo_turma = t.codigo
JOIN
    tbl_sala s ON m.sala = s.codigo
WHERE
    m.datareserva = ?
    AND m.estatus = ''
ORDER BY FIELD(h.descricao, 'MANHÃ', 'TARDE', 'NOITE'), m.sala";

         $retornoMapa = $this->db->query($sql, [$dataMapa]);


         //  $retornoMapa = $this->db->query($sql);

         // verificar se a consulta ocorreu com sucesso
         if ($retornoMapa->num_rows() > 0) {
            $dados = $retornoMapa->result();
         } else {
            $dados = 0;
         }
      } catch (Exception $e) {
         // corrigir a concatenação da mensagem de erro
         $dados = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
         );
      }
      // Envia o array $dados com as informações tratadas acima pela 
      // estrutura de decisão if
      return $dados;
   }
}
