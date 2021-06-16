
<?php
class M_Publikasi_Umum extends CI_Model{
 function __construct(){
  parent::__construct();
 }

function tampilgar(){//menampilkan tabel pb_garuda
$query = $this->db->get('pbu_garuda');
return $query->result(); 
 }

function tampilgs(){//menampilkan tabel pbu_gscholar
$query = $this->db->get('pbpu_gscholar');
return $query->result(); 
 }

function tampilscd(){//menampilkan tabel pb_sciencedirect
$query = $this->db->get('pbu_sciencedirect');
return $query->result(); 
 }

function tampilscp(){//menampilkan tabel pbu_scopus
$query = $this->db->get('pbpu_scopus');
return $query->result(); 
}

function tampilemer(){//menampilkan tabel pb_emerald
$query = $this->db->get('pbu_emerald');
return $query->result(); 
}

function tampilbook(){ //menampilkan tabel pbp_book
$query = $this->db->get('pbp_book');
return $query->result(); 
}

function tampilrsc(){ //menampilkan tabel pbp_book
$query = $this->db->get('pbp_research');
return $query->result(); 
}

function tampilipr(){ //menampilkan tabel pbp_book
$query = $this->db->get('pbp_ipr');
return $query->result(); 
}
 }