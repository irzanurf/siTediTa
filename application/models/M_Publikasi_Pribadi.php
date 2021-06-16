
<?php
class M_Publikasi_Pribadi extends CI_Model{
 function __construct(){
  parent::__construct();
 }
function tampilbook(){ //menampilkan tabel pbp_book
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);
$query = $this->db->get('pbp_book');
return $query->result(); 
 }

function hitungbook(){ //menghitung jumlah isi tabel pbp_book
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);
$query = $this->db->get('pbp_book');
return $query->num_rows(); 
 }

function hitungovrview(){ //menghitung jumlah isi tabel pbp_overview
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);
$query = $this->db->get('pbp_overview');       
return $query->num_rows(); 
}

function tampilovrview(){//menampilkan tabel pbp_overview
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);
$query = $this->db->get('pbp_overview');       
return $query->result(); 
}

function tampilgs(){//menampilkan tabel pbp_gscholar
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);
$query = $this->db->get('pbpu_gscholar');
return $query->result(); 
 }

function hitunggs(){ //menghitung jumlah isi tabel pbp_gscholar
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);
$query = $this->db->get('pbpu_gscholar');
return $query->num_rows(); 
 }

function tampilipr(){//menampilkan tabel pbp_ipr
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);   
$query = $this->db->get('pbp_ipr');
return $query->result(); 
}

function hitungipr(){ //menghitung jumlah isi tabel pbp_ipr
    $nidn=$this->session->userdata("user_name");
    $this->db->like('Nidn', $nidn);
$query = $this->db->get('pbp_ipr');
return $query->num_rows(); 
 }

function tampilrsc(){//menampilkan tabel pbp_research
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);                 
$query = $this->db->get('pbp_research');
return $query->result(); 
 }

 function hitungrsc(){ //menghitung jumlah isi tabel pbp_research
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);
$query = $this->db->get('pbp_research');
return $query->num_rows(); 
 }

function tampilscp(){//menampilkan tabel pbp_scopus
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);                         
$query = $this->db->get('pbpu_scopus');
return $query->result(); 
}

function hitungscp(){ //menghitung jumlah isi tabel pbp_scopus
$nidn=$this->session->userdata("user_name");
$this->db->like('Nidn', $nidn);
$query = $this->db->get('pbpu_scopus');
return $query->num_rows(); 
 }


 }