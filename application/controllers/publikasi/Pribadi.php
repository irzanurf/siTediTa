<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pribadi extends CI_Controller {

function __construct(){
parent::__construct();
		
$this->load->model('M_Publikasi_Pribadi');
$this->load->model('M_CariPublikasiPribadi');
$this->load->model('M_Profile');
$this->load->helper('html');
$this->load->library('table'); 

$this->nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
$this->bar['hitungscp'] = $this->M_Publikasi_Pribadi->hitungscp();
$this->bar['hitunggs'] = $this->M_Publikasi_Pribadi->hitunggs();
$this->bar['hitungipr'] = $this->M_Publikasi_Pribadi->hitungipr();
$this->bar['hitungrsc'] = $this->M_Publikasi_Pribadi->hitungrsc();
$this->bar['hitungbook'] = $this->M_Publikasi_Pribadi->hitungbook();
$this->data['nama_user'] = $this->M_Profile->getwhere_profile(array('nip'=>$this->session->userdata('user_name')))->result();
$this->data['tampilovr'] = $this->M_Publikasi_Pribadi->tampilovrview();
$this->data['tampilscp'] = $this->M_Publikasi_Pribadi->tampilscp();
$this->data['tampilgs'] = $this->M_Publikasi_Pribadi->tampilgs();
$this->data['tampilipr'] = $this->M_Publikasi_Pribadi->tampilipr();
$this->data['tampilrsc'] = $this->M_Publikasi_Pribadi->tampilrsc();
$this->data['tampilbook'] = $this->M_Publikasi_Pribadi->tampilbook();
$nip = $this->session->userdata('user_name');
$this->nama['nama']= $this->M_Profile->getwhere_profile(array('nip'=>$nip))->result();
$this->nama['cek']= $this->M_Profile->cekRevPenelitian(array('nip'=>$nip))->result();

}

//-----------------------------------------------------------------------------------------OVERVIEW
function overview_penelitian() {
$user = $this->session->userdata('user_name');
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_overview',$this->data);
$this->load->view('penelitian/footer'); 
}

function overview_pengabdian() {
$user = $this->session->userdata('user_name');
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_overview',$this->data);
$this->load->view('pengabdian/footer'); 
}

//----------------------------------------------------------------------------------------------SCOPUS
function scopus_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_scopus',$this->data);
$this->load->view('penelitian/footer');
}
	   
function cariScopus_penelitian() {
$cari['tampilscp']=$this->M_CariPublikasiPribadi->cariScopus();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_scopus',$cari);
$this->load->view('penelitian/footer');
}

function scopus_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_scopus',$this->data);
$this->load->view('pengabdian/footer');
}
	   
function cariScopus_pengabdian() {
$cari['tampilscp']=$this->M_CariPublikasiPribadi->cariScopus();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_scopus',$cari);
$this->load->view('pengabdian/footer');
}

//-----------------------------------------------------------------------------------------GOOGLE SCHOLAR
function gscholar_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_gscholar',$this->data);
$this->load->view('penelitian/footer');
 }

function cariGscholar_penelitian() {
$cari['tampilgs']=$this->M_CariPublikasiPribadi->cariGS();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_gscholar',$cari);
$this->load->view('penelitian/footer');
}

function gscholar_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_gscholar',$this->data);
$this->load->view('pengabdian/footer');
 }

function cariGscholar_pengabdian() {
$cari['tampilgs']=$this->M_CariPublikasiPribadi->cariGS();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_gscholar',$cari);
$this->load->view('pengabdian/footer');
}

//----------------------------------------------------------------------------------------------IPR(SHINTA)
function IPR_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_ipr',$this->data);
$this->load->view('penelitian/footer');
}

function cariIPR_penelitian() {
$cari['tampilipr']=$this->M_CariPublikasiPribadi->cariPaten();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_ipr',$cari);
$this->load->view('penelitian/footer');
}

function IPR_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_ipr',$this->data);
$this->load->view('pengabdian/footer');
}

function cariIPR_pengabdian() {
$cari['tampilipr']=$this->M_CariPublikasiPribadi->cariPaten();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_ipr',$cari);
$this->load->view('pengabdian/footer');
}

//-----------------------------------------------------------------------------------------------RESEARCH
function research_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_research',$this->data);
$this->load->view('penelitian/footer');
}

function cariResearch_penelitian() {
$cari['tampilrsc']=$this->M_CariPublikasiPribadi->cariResearch();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('penelitian/penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_research',$cari);
$this->load->view('pengabdian/footer');
}

function research_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_research',$this->data);
$this->load->view('pengabdian/footer');
}

function cariResearch_pengabdian() {
$cari['tampilrsc']=$this->M_CariPublikasiPribadi->cariResearch();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_research',$cari);
$this->load->view('pengabdian/footer');
}

//-----------------------------------------------------------------------------------------------------BOOKS
function book_penelitian(){
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_book',$this->data);
$this->load->view('penelitian/footer');
}

function cariBook_penelitian() {
$cari['tampilbook']=$this->M_CariPublikasiPribadi->cariBook();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Pribadi/topbar_pribadi_penelitian',$this->bar);
$this->load->view('penelitian/Publikasi_Pribadi/v_book',$cari);
$this->load->view('penelitian/footer');
}

function book_pengabdian(){
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_book',$this->data);
$this->load->view('pengabdian/footer');
}

function cariBook_pengabdian() {
$cari['tampilbook']=$this->M_CariPublikasiPribadi->cariBook();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Pribadi/topbar_pribadi_pengabdian',$this->bar);
$this->load->view('pengabdian/Publikasi_Pribadi/v_book',$cari);
$this->load->view('pengabdian/footer');
}
	}