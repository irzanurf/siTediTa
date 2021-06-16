<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umum extends CI_Controller {

function __construct(){
parent::__construct();
		
$this->load->model('M_Publikasi_Umum');
$this->load->model('M_Publikasi_Pribadi');
$this->load->model('M_CariPublikasiUmum');
$this->load->model('M_Profile');
$this->load->helper('html');
$this->load->library('table'); 
$this->nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
$this->data['tampilscp'] = $this->M_Publikasi_Umum->tampilscp();
$this->data['tampilgs'] = $this->M_Publikasi_Umum->tampilgs();
$this->data['tampilemer'] = $this->M_Publikasi_Umum->tampilemer();
$this->data['tampilscd'] = $this->M_Publikasi_Umum->tampilscd();
$this->data['tampilgar'] = $this->M_Publikasi_Umum->tampilgar();
$this->data['tampilipr'] = $this->M_Publikasi_Umum->tampilipr();
$this->data['tampilrsc'] = $this->M_Publikasi_Umum->tampilrsc();
$this->data['tampilbook'] = $this->M_Publikasi_Umum->tampilbook();
$nip = $this->session->userdata('user_name');
$this->nama['nama']= $this->M_Profile->getwhere_profile(array('nip'=>$nip))->result();
$this->nama['cek']= $this->M_Profile->cekRevPenelitian(array('nip'=>$nip))->result();
}

//------------------------------------------------------DASHBOARD-------------------------//	  
function dashboard_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/dashboard');
$this->load->view('penelitian/footer'); 
}

function dashboard_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/dashboard');
$this->load->view('pengabdian/footer'); 
}

//---------------------------------------------------------SCOPUS------------------------//	
function scopus_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_scopus',$this->data);
$this->load->view('penelitian/footer');
}

function cariScopus_penelitian() {
$cari['tampilscp']=$this->M_CariPublikasiUmum->cariScopus();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_scopus',$cari);
$this->load->view('penelitian/footer');
}

function scopus_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_scopus',$this->data);
$this->load->view('pengabdian/footer');
}

function cariScopus_pengabdian() {
$cari['tampilscp']=$this->M_CariPublikasiUmum->cariScopus();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_scopus',$cari);
$this->load->view('pengabdian/footer');
}

//---------------------------------------------------------------GOOGLE SCHOLAR------------------------//
function gscholar_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_gscholar',$this->data);
$this->load->view('penelitian/footer');
}

function cariGscholar_penelitian() {
$cari['tampilgs']=$this->M_CariPublikasiUmum->cariGscholar();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_gscholar',$cari);
$this->load->view('penelitian/footer');
}

function gscholar_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_gscholar',$this->data);
$this->load->view('pengabdian/footer');
}

function cariGscholar_pengabdian() {
$cari['tampilgs']=$this->M_CariPublikasiUmum->cariGscholar();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_gscholar',$cari);
$this->load->view('pengabdian/footer');
}

//------------------------------------------------------------------EMERALD------------------------//
function emerald_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_emerald',$this->data);
$this->load->view('penelitian/footer');
}

function cariEmerald_penelitian() {
$cari['tampilemer']=$this->M_CariPublikasiUmum->cariEmerald();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_emerald',$cari);
$this->load->view('penelitian/footer');
}

function emerald_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_emerald',$this->data);
$this->load->view('pengabdian/footer');
}

function cariEmerald_pengabdian() {
$cari['tampilemer']=$this->M_CariPublikasiUmum->cariEmerald();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_emerald',$cari);
$this->load->view('pengabdian/footer');
}

//-------------------------------------------------------------------GARUDA------------------------//
function garuda_penelitian(){
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_garuda',$this->data);
$this->load->view('penelitian/footer');
}

function cariGaruda_penelitian() {
$cari['tampilgar']=$this->M_CariPublikasiUmum->cariGaruda();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_garuda',$cari);
$this->load->view('penelitian/footer');
}

function garuda_pengabdian(){
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_garuda',$this->data);
$this->load->view('pengabdian/footer');
}

function cariGaruda_pengabdian() {
$cari['tampilgar']=$this->M_CariPublikasiUmum->cariGaruda();
$this->load->view('pengabdian/header', $this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_garuda',$cari);
$this->load->view('pengabdian/footer');
}

//------------------------------------------------------------------SCIENCEDIRECT------------------------//
function scd_penelitian(){
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_scd',$this->data);
$this->load->view('penelitian/footer');
}

function cariScd_penelitian() {
$cari['tampilscd']=$this->M_CariPublikasiUmum->cariScd();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_scd',$cari);
$this->load->view('penelitian/footer');
}

function scd_pengabdian(){
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_scd',$this->data);
$this->load->view('pengabdian/footer');
}

function cariScd_pengabdian() {
$cari['tampilscd']=$this->M_CariPublikasiUmum->cariScd();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_scd',$cari);
$this->load->view('pengabdian/footer');
}

	
//----------------------------------------------------------------------------------------------IPR(SHINTA)
function IPR_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_ipr',$this->data);
$this->load->view('penelitian/footer');
}

function cariIPR_penelitian() {
$cari['tampilipr']=$this->M_CariPublikasiPribadi->cariPaten();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_ipr',$cari);
$this->load->view('penelitian/footer');
}

function IPR_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_ipr',$this->data);
$this->load->view('pengabdian/footer');
}

function cariIPR_pengabdian() {
$cari['tampilipr']=$this->M_CariPublikasiPribadi->cariPaten();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_ipr',$cari);
$this->load->view('pengabdian/footer');
}

//-----------------------------------------------------------------------------------------------RESEARCH
function research_penelitian() {
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_research',$this->data);
$this->load->view('penelitian/footer');
}

function cariResearch_penelitian() {
$cari['tampilrsc']=$this->M_CariPublikasiPribadi->cariResearch();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_research',$cari);
$this->load->view('pengabdian/footer');
}

function research_pengabdian() {
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_research',$this->data);
$this->load->view('pengabdian/footer');
}

function cariResearch_pengabdian() {
$cari['tampilrsc']=$this->M_CariPublikasiPribadi->cariResearch();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_research',$cari);
$this->load->view('pengabdian/footer');
}

//-----------------------------------------------------------------------------------------------------BOOKS
function book_penelitian(){
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_book',$this->data);
$this->load->view('penelitian/footer');
}

function cariBook_penelitian() {
$cari['tampilbook']=$this->M_CariPublikasiPribadi->cariBook();
$this->load->view('penelitian/header',$this->nama);
$this->load->view('penelitian/Publikasi_Umum/topbar_umum_penelitian');
$this->load->view('penelitian/Publikasi_Umum/v_book',$cari);
$this->load->view('penelitian/footer');
}

function book_pengabdian(){
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_book',$this->data);
$this->load->view('pengabdian/footer');
}

function cariBook_pengabdian() {
$cari['tampilbook']=$this->M_CariPublikasiPribadi->cariBook();
$this->load->view('pengabdian/header',$this->nama);
$this->load->view('pengabdian/Publikasi_Umum/topbar_umum_pengabdian');
$this->load->view('pengabdian/Publikasi_Umum/v_book',$cari);
$this->load->view('pengabdian/footer');
}
}