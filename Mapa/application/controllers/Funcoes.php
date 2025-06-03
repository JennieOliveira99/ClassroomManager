<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Funcoes extends CI_Controller{

    public function index(){
        $this->load->view('Login');
    }
public function indexPagina() {
    $this->load->view('index'); 
}
    public function encerraSistema(){
       header('Location: '. base_url());
    }
    public function abreSala() {
    $this->load->view('sala');
}

}