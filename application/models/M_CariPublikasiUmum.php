
<?php
class M_CariPublikasiUmum extends CI_Model{
 function __construct(){
  parent::__construct();
 }

 function cariScopus(){
    $d = $this->input->POST('cari');
    $this->db->from("pbu_scopus");
    $this->db->like('Title', $d);
    $this->db->or_like('Author', $d);
    $this->db->or_like('Source', $d);
    $query=$this->db->get()->result();
    return $query; 
 }
 
 function cariGscholar(){
    $d = $this->input->POST('cari');
    $this->db->from("pbu_gscholar");
    $this->db->like('Title', $d);
    $this->db->or_like('Author', $d);
    $this->db->or_like('Date_Released', $d);
    $query=$this->db->get()->result();
    return $query; 
 }

 function cariScd(){
    $d = $this->input->POST('cari');
    $this->db->from("pbu_sciencedirect");
    $this->db->like('Title', $d);
    $this->db->or_like('Author', $d);
    $this->db->or_like('Date_Released', $d);
    $query=$this->db->get()->result();
    return $query; 
 }

 function cariGaruda(){
    $d = $this->input->POST('cari');
    $this->db->from("pbu_garuda");
    $this->db->like('Title', $d);
    $this->db->or_like('Author', $d);
    $this->db->or_like('Date_Released', $d);
    $query=$this->db->get()->result();
    return $query; 
 }

 function cariBook(){
   $d = $this->input->POST('cari');
   $this->db->from("pbp_book");
   $this->db->like('Judul', $d);
   $this->db->or_like('Isbn', $d);
   $this->db->or_like('Author', $d);
   $this->db->or_like('Publisher', $d);
   $this->db->or_like('pld', $d);
   $query=$this->db->get()->result();
   return $query; 
}

function cariResearch(){
   $d = $this->input->POST('cari');
   $this->db->from("pbp_research");
   $this->db->like('A', $d);
   $this->db->or_like('B', $d);
   $this->db->or_like('C', $d);
   $this->db->or_like('D', $d);
   $this->db->or_like('E', $d);
   $this->db->or_like('F', $d);
   $query=$this->db->get()->result();
   return $query; 
}

function cariPaten(){
   $d = $this->input->POST('cari');
   $this->db->from("pbp_paten");
   $this->db->like('Judul', $d);
   $this->db->or_like('ID', $d);
   $this->db->or_like('Tahun_Permohonan', $d);
   $this->db->or_like('Kategori', $d);
   $this->db->or_like('Patent_Holder', $d);
   $this->db->or_like('Inventor', $d);
   $query=$this->db->get()->result();
   return $query; 
}

 function cariEmerald(){
   $d = $this->input->POST('cari');
   $this->db->from("pbu_emerald");
   $this->db->like('Title', $d);
    $this->db->or_like('Author', $d);
    $this->db->or_like('Date_Released', $d);
   $query=$this->db->get()->result();
   return $query; 
}
 }