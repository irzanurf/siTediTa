<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengabdian extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('Admin');
        //cek session dan level user
        if($this->Admin->is_role() == "1" || $this->Admin->is_role()=='4'){
            redirect("login/");
        }
        $this->load->model('M_PropPengabdian');
        $this->load->model('M_Mitra');
        $this->load->model('M_User');
        $this->load->model('M_Profile');
        $this->load->model('M_SumberDana');
        $this->load->model('M_Dosen');
        $this->load->model('M_Mahasiswa');
        $this->load->model('M_SkemaPengabdian');
        $this->load->model('M_Admin');
        $this->load->model('M_LaporanAkhirPengabdian');
        $this->load->model('M_JadwalPengabdian');
        $this->load->model('M_Luaran');
        $this->load->model('M_KomponenNilaiPengabdian');
        $this->load->model('M_NilaiPropPengabdian');
        $this->load->model('M_Publikasi_Pribadi');
    }

    public function index()
    {
        $user = $this->session->userdata('user_name');
        $data['nama'] = $this->M_Dosen->getwhere_dosen(array('nip'=>$user))->result();
        $data['berita'] = $this->M_Admin->get_berita(array('id'=>2))->result();
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$user))->result();
        $data['view']= $this->M_PropPengabdian->get_viewpengajuan($user)->result();
        $data['anggota']= $this->M_PropPengabdian->get_viewanggota($user)->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header',$nama);
        $this->load->view("dosen/dashboardpengabdian",$data);
        $this->load->view("pengabdian/footer");
    }
    public function detail()
    {
        $id = $this->input->post('id',true);
        if($id==NULL){
            redirect("dosen/pengabdian/submitpermohonan");
        }
        // $id_jenis = $this->input->post('id_skema');
        $nip = $this->session->userdata('user_name');
        $proposal = $data['proposal'] = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        $id_jenis = $proposal->id_skema;
        
        $data['jenis'] = $this->M_KomponenNilaiPengabdian->getwhere_komponen(array('id_skema_pengabdian'=>$id_jenis))->result();
        $reviewer = $this->M_PropPengabdian->getwhere_rev(array('id_proposal'=>$id))->row()->reviewer;
        $reviewer2 = $this->M_PropPengabdian->getwhere_rev(array('id_proposal'=>$id))->row()->reviewer2;
        $data['komponen'] = $this->M_KomponenNilaiPengabdian->get_nilaikomponenProp($id, $reviewer)->result();
        $data['komponen2'] = $this->M_KomponenNilaiPengabdian->get_nilaikomponenProp($id,$reviewer2)->result();
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();

            $data['nilai'] = $this->M_NilaiPropPengabdian->getwhere_nilai(array('id_proposal'=>$id))->row()->nilai;
            $data['komentar'] = $this->M_NilaiPropPengabdian->getwhere_nilai(array('id_proposal'=>$id))->row()->komentar;

            $data['nilai2'] = $this->M_NilaiPropPengabdian->getwhere_nilai(array('id_proposal'=>$id))->row()->nilai2;
            $data['komentar2'] = $this->M_NilaiPropPengabdian->getwhere_nilai(array('id_proposal'=>$id))->row()->komentar2;
        
        $this->load->view('pengabdian/header', $nama);
        $this->load->view('dosen/detail', $data);
        $this->load->view("pengabdian/footer");
    }

    function checkUsername(){
        $userName = $this->input->post('username');
        $if_exists = $this->M_User->checkUserexist($userName);
        $if_exists_mitra = $this->M_Mitra->checkUserexist($userName);
        if ($if_exists > 0 || $if_exists_mitra > 0) {
          echo json_encode('Username tidak tersedia');
        } else {
          echo json_encode('Username tersedia');
        }
      }
      function checkJudul(){
        $judul = $this->input->post('judul');
        $if_exists = $this->M_PropPengabdian->checkJudulExist($judul);
        if ($if_exists > 0) {
          echo json_encode('Judul sudah diajukan');
        } else {
          echo json_encode('Judul belum diajukan');
        }
      }

      public function addformProposalTanpaMitra()
      {
          $this->form_validation->set_rules('judul','Judul Pengabdian', 'required');
          $this->form_validation->set_rules('abstrak','Abstrak', 'required');
        
          if($this->form_validation->run()==false){
              redirect("dosen/pengabdian/pengisianformtanpamitra");
          } else {
              $prop_file = $_FILES['file_prop'];
          if($prop_file=''){}else{
              $config['upload_path'] = './assets/prop_pengabdian';
              $config['allowed_types'] = 'pdf';
              $config['encrypt_name'] = TRUE;
  
              $this->load->library('upload',$config);
              if(!$this->upload->do_upload('file_prop')){
                  echo "Upload Gagal"; die();
              } else {
                  $prop_file=$this->upload->data('file_name');
              }
          }
              $nip = $this->session->userdata('user_name');
          
          
          
          $date = date('Y-m-d');
          $bulan = $this->input->post('bulan',true);
          $jadwal = $this->M_JadwalPengabdian->get_last_jadwal()->row()->id;
          $biaya = str_replace('.','',$this->input->post('biaya',true));
          
          $prop = [
              "nip"=>$nip,
              "judul"=>$this->input->post('judul',true),
              "abstrak"=>$this->input->post('abstrak',true),
              "tgl_upload"=>$date,
              "id_jadwal" => $jadwal,
              "lokasi"=>$this->input->post('lokasi',true),
              "lama_pelaksanaan"=>$bulan,
              "id_sumberdana"=>$this->input->post('sumberdana',true),
              "biaya"=>$biaya,
              "id_skema"=>$this->input->post('skema_pengabdian'),
              "file" => $prop_file
  
          ];
          $proposal=$this->M_PropPengabdian->insert_proposal($prop);
  
  
          /**
           * upload file proposal
           */
  
  
  
          $nip= $this->input->post('dosen[]');
          $data_dosen = array();
          for($i=0; $i<count($nip)-1; $i++)
          {
              if($nip[$i]==""||$nip[$i]==null||$nip[$i]==0){
  
              }
              else{
              $data_dosen[$i] = array(
                  'nip'  =>      $nip[$i],
                  "id_proposal"=>$proposal,
              );
          }
          }
          $this->M_PropPengabdian->dosen($data_dosen);
  
          $id_luaran= $this->input->post('luaran[]');
          $data_luaran = array();
          for($i=0; $i<count($id_luaran)-1; $i++)
          {
              if($id_luaran[$i]==""||$id_luaran[$i]==null||$id_luaran[$i]==0){
  
              }
              else{
              $data_luaran[$i] = array(
                  'id_luaran'  =>$id_luaran[$i],
                  "id_proposal"=>$proposal,
              );
          }
          }
          $this->M_PropPengabdian->luaran($data_luaran);
          
          $nim= $this->input->post('nim_mahasiswa[]');
          $nama_mhs = $this->input->post('nama_mahasiswa[]');
          $data_mahasiswa = array();
          for($i=0; $i<count($nim)-1; $i++)
          {
            if($nim[$i]==""||$nim[$i]==null||$nim[$i]==0){

            }
            else{
              $data_mahasiswa[$i] = array(
                  'nim'  => $nim[$i],
                  'nama' => $nama_mhs[$i],
                  "id_proposal"=>$proposal,
              );
          }}
          $this->M_PropPengabdian->mahasiswa($data_mahasiswa);
  
  
          $this->session->set_flashdata('pesan', '<p>Terimakasih Anda berhasil melakukan permohonan proposal <br> Proposal dapat dirubah selama jadwal pengumpulan belum berakhir <br> Pastikan Anda telah mengecek kembali proposal Anda sebelum jadwal pengumpulan berakhir </p>');
          $this->session->set_flashdata('button', 'dosen/pengabdian/submitpermohonan');
          redirect("dosen/pengabdian/success");
          }
      }

    public function addformProposal()
    {
        $this->form_validation->set_rules('judul','Judul Pengabdian', 'required');
        $this->form_validation->set_rules('abstrak','Abstrak', 'required');
        $this->form_validation->set_rules('instansi','Nama Instansi', 'required');
        $this->form_validation->set_rules('username','Username', 'required');
        $this->form_validation->set_rules('password','Password', 'required');
        if($this->form_validation->run()==false){
            redirect("dosen/pengabdian/pengisianform");
        } else {
            $prop_file = $_FILES['file_prop'];
        if($prop_file=''){}else{
            $config['upload_path'] = './assets/prop_pengabdian';
            $config['allowed_types'] = 'pdf';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload',$config);
            if(!$this->upload->do_upload('file_prop')){
                echo "Upload Gagal"; die();
            } else {
                $prop_file=$this->upload->data('file_name');
            }
        }
            $nip = $this->session->userdata('user_name');
        $data = [
            "nama_instansi"=> $this->input->post('instansi',true),
            "penanggung_jwb"=>$this->input->post('pj',true),
            "no_telp"=> $this->input->post('no_telp',true),
            "alamat"=>$this->input->post('alamat',true),
            "email"=>$this->input->post('email',true),
            "username"=>$this->input->post('username',true),
            "status"=>"0"

        ];
        $data_check = [
            "nama_instansi"=> $this->input->post('instansi',true),
            "username"=>$this->input->post('username',true),
            "status"=>"0"
        ];
        $checkMitra = $this->M_Mitra->getwhere_mitra($data_check);
        if($checkMitra->num_rows() > 0){
            $mitra = $checkMitra->row()->id;
        } else{
            $mitra=$this->M_Mitra->insert_mitra($data);
        }
        
        $date = date('Y-m-d');
        $bulan = $this->input->post('bulan',true);
        $jadwal = $this->M_JadwalPengabdian->get_last_jadwal()->row()->id;
        $biaya = str_replace('.','',$this->input->post('biaya',true));
        
        $prop = [
            "id_mitra"=>$mitra,
            "nip"=>$nip,
            "judul"=>$this->input->post('judul',true),
            "abstrak"=>$this->input->post('abstrak',true),
            "tgl_upload"=>$date,
            "id_jadwal" => $jadwal,
            "lokasi"=>$this->input->post('lokasi',true),
            "lama_pelaksanaan"=>$bulan,
            "id_sumberdana"=>$this->input->post('sumberdana',true),
            "biaya"=>$biaya,
            "id_skema"=>$this->input->post('skema_pengabdian')

        ];
        $proposal=$this->M_PropPengabdian->insert_proposal($prop);


        /**
         * upload file proposal
         */
        
        $data_file = array('file'=>$prop_file);
        $this->M_PropPengabdian->update_prop($proposal,$data_file);



        $nip= $this->input->post('dosen[]');
        $data_dosen = array();
        for($i=0; $i<count($nip)-1; $i++)
        {
            if($nip[$i]==""||$nip[$i]==null||$nip[$i]==0){

            }
            else{
            $data_dosen[$i] = array(
                'nip'  =>      $nip[$i],
                "id_proposal"=>$proposal,
            );
        }
        }
        $this->M_PropPengabdian->dosen($data_dosen);

        $id_luaran= $this->input->post('luaran[]');
        $data_luaran = array();
        for($i=0; $i<count($id_luaran)-1; $i++)
        {
            if($id_luaran[$i]==""||$id_luaran[$i]==null||$id_luaran[$i]==0){

            }
            else{
            $data_luaran[$i] = array(
                'id_luaran'  =>$id_luaran[$i],
                "id_proposal"=>$proposal,
            );
        }
        }
        $this->M_PropPengabdian->luaran($data_luaran);
        
        $nim= $this->input->post('nim_mahasiswa[]');
        $nama_mhs = $this->input->post('nama_mahasiswa[]');
        $data_mahasiswa = array();
        for($i=0; $i<count($nim)-1; $i++)
        {
            $data_mahasiswa[$i] = array(
                'nim'  => $nim[$i],
                'nama' => $nama_mhs[$i],
                "id_proposal"=>$proposal,
            );
        }
        $this->M_PropPengabdian->mahasiswa($data_mahasiswa);


        $role_mitra='4';
        $password= md5($this->input->post('password',true));
        $user_mitra = [
            "username"=>$this->input->post('username',true),
            "password"=>$password,
            "role"=>$role_mitra
        ];
        $this->M_User->insert_user($user_mitra);
        $this->session->set_flashdata('pesan', '<p>Terimakasih Anda berhasil melakukan permohonan proposal <br> Proposal dapat diedit selama Anda belum melakukan "Submit" di menu selanjutnya<br> Pastikan Anda telah mengecek kembali proposal Anda sebelum melakukan finalisasi <br> Proposal akan otomatis terfinalisasi apabila batas pengumpulan telah berakhir</p>');
        $this->session->set_flashdata('button', 'dosen/pengabdian/submitpermohonan');
        redirect("dosen/pengabdian/success");
        }
    }

    public function success(){
        $this->load->view("pengabdian/header");
        $this->load->view("dosen/success");
        $this->load->view("pengabdian/footer");
    }


    public function PengisianForm()
    {
        $data['sumberdana']= $this->M_SumberDana->get_sumberdana()->result();
        $nip = $this->session->userdata('user_name');
        $data['dosen']= $this->M_Dosen->get_dosen()->result();
        $data['mahasiswa']= $this->M_Mahasiswa->get_mahasiswa()->result();
        $data['skema'] = $this->M_SkemaPengabdian->get_skemapengabdian()->result();
        $data['luaran']= $this->M_Luaran->get_luaran_pengabdian()->result();
        $data['jadwal'] = $this->M_JadwalPengabdian->get_last_jadwal()->row();
        $now = date('Y-m-d', strtotime(date('Y-m-d')));
        $awal = date('Y-m-d', strtotime($data['jadwal']->tgl_mulai));
        $akhir = date('Y-m-d', strtotime($data['jadwal']->tgl_selesai));
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();

        
        $this->load->view('pengabdian/header',$nama);
        if(($now >= $awal) && ($now<=$akhir)){
            $this->load->view('dosen/form_permohonan_pengabdian_mitra',$data);
        } else {
            $this->load->view('dosen/closed_form', $data);
        }
        $this->load->view('pengabdian/footer');
    }

    public function formTambahMitra($id)
    {

        $data['prop'] = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        $id_mitra = $data['prop']->id_mitra;
        $nip = $data['prop']->nip;
        $data['dosen'] = $this->M_Dosen->getwhere_dosen(array('nip'=>$nip))->row();

    
        $nip = $this->session->userdata('user_name');
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        
        $this->load->view('pengabdian/header',$nama);
        $this->load->view('dosen/form_tambah_mitra',$data);
        $this->load->view('pengabdian/footer');
    }

    public function addFormMitra()
    {
        $data = [
            "nama_instansi"=> $this->input->post('instansi',true),
            "penanggung_jwb"=>$this->input->post('pj',true),
            "no_telp"=> $this->input->post('no_telp',true),
            "alamat"=>$this->input->post('alamat',true),
            "email"=>$this->input->post('email',true),
            "username"=>$this->input->post('username',true),
            "status"=>"0"

        ];
        $data_check = [
            "nama_instansi"=> $this->input->post('instansi',true),
            "username"=>$this->input->post('username',true),
            "status"=>"0"
        ];
        $checkMitra = $this->M_Mitra->getwhere_mitra($data_check);
        if($checkMitra->num_rows() > 0){
            $mitra = $checkMitra->row()->id;
        } else{
            $mitra=$this->M_Mitra->insert_mitra($data);
        }

        $id_prop = $this->input->post('id');
        $data_prop = [
            'id_mitra' => $mitra,
        ];

        $role_mitra='4';
        $password= md5($this->input->post('password',true));
        $user_mitra = [
            "username"=>$this->input->post('username',true),
            "password"=>$password,
            "role"=>$role_mitra
        ];
        $this->M_User->insert_user($user_mitra);

        $this->M_PropPengabdian->update_prop($id_prop,$data_prop);
        $this->session->set_flashdata('pesan', '<p>Terimakasih Anda berhasil melakukan penambahan mitra <br> Detail mitra dapat diedit selama Mitra belum menyetujui kerjasama<br> Pastikan Anda telah mengecek kembali detail mitra pengabdian Anda<br></p>');
        $this->session->set_flashdata('button', 'dosen/pengabdian/submitpermohonan');
        redirect("dosen/pengabdian/success");


    }

    public function PengisianFormTanpaMitra()
    {
        $data['sumberdana']= $this->M_SumberDana->get_sumberdana()->result();
        $nip = $this->session->userdata('user_name');
        $data['dosen']= $this->M_Dosen->get_dosen()->result();
        $data['mahasiswa']= $this->M_Mahasiswa->get_mahasiswa()->result();
        $data['skema'] = $this->M_SkemaPengabdian->get_skemapengabdian()->result();
        $data['luaran']= $this->M_Luaran->get_luaran_pengabdian()->result();
        $data['jadwal'] = $this->M_JadwalPengabdian->get_last_jadwal()->row();
        $now = date('Y-m-d', strtotime(date('Y-m-d')));
        $awal = date('Y-m-d', strtotime($data['jadwal']->tgl_mulai));
        $akhir = date('Y-m-d', strtotime($data['jadwal']->tgl_selesai));
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        
        $this->load->view('pengabdian/header',$nama);
        if(($now >= $awal) && ($now<=$akhir)){
            $this->load->view('dosen/form_permohonan_pengabdian',$data);
        } else {
            $this->load->view('dosen/closed_form', $data);
        }
        $this->load->view('pengabdian/footer');
    }
        
    public function UploadSuratMitra()
    {
        $nip = $this->session->userdata('user_name');
        $data['view']= $this->M_PropPengabdian->get_viewpengajuan($nip)->result();
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header',$nama);
        $this->load->view('dosen/upload_surat_mitra',$data);
        $this->load->view('pengabdian/footer');

    }



    public function hapusProposal($id)
    {
        $data = array('id' => $id);
        $mitra_id = $this->M_PropPengabdian->getwhere_proposal($data)->row();
        $data_mitra = array('id' => $mitra_id->id_mitra);
        $mitra = $this->M_Mitra->getwhere_mitra($data_mitra)->row();
        $data_user = array(
            'username' => $mitra->username
        );
        $this->M_User->delete_user($data_user);
        $data_foreign = array('id_proposal'=>$id);
        $this->M_Mitra->delete_mitra($data_mitra);
        $this->M_PropPengabdian->delete_proposal($data);
        $this->M_Dosen->delete_dosenpengabdian($data_foreign);
        $this->M_Mahasiswa->delete_mahasiswapengabdian($data_foreign);
        redirect('dosen/pengabdian/submitpermohonan');



    }
    public function hapusProposalTanpaMitra($id)
    {
        $data = array('id' => $id);
        $data_foreign = array('id_proposal'=>$id);
        $this->M_PropPengabdian->delete_proposal($data);
        $this->M_Dosen->delete_dosenpengabdian($data_foreign);
        $this->M_Mahasiswa->delete_mahasiswapengabdian($data_foreign);
        $this->M_Luaran->delete_luaranpengabdian($data);
        redirect('dosen/pengabdian/submitpermohonan');

    }

    public function SubmitPermohonan()
    {
        $nip = $this->session->userdata('user_name');
        $username = $this->session->userdata('user_name');
        $data['view']= $this->M_PropPengabdian->get_viewpengajuan($username)->result();
        $data['anggota']= $this->M_PropPengabdian->get_viewanggota($username)->result();
        $data['sumberdana']= $this->M_SumberDana->get_sumberdana()->result();
        $data['dosen']= $this->M_Dosen->get_dosen()->result();
        $data['mahasiswa']= $this->M_Mahasiswa->get_mahasiswa()->result();
        // $data = array(
        //     'sumberdana' => $this->M_SumberDana->get_sumberdana(),
        //     'sumberdana_selected' => '',
        // );
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header',$nama);
        $this->load->view('dosen/formpengabdian', $data);
        $this->load->view('pengabdian/footer');

    }

    public function mitra()
    {
        $nip = $this->session->userdata('user_name');
        $username = $this->session->userdata('user_name');
        $data['view']= $this->M_PropPengabdian->get_viewpengajuan($username)->result();
        $data['anggota']= $this->M_PropPengabdian->get_viewanggota($username)->result();
        $data['sumberdana']= $this->M_SumberDana->get_sumberdana()->result();
        $data['dosen']= $this->M_Dosen->get_dosen()->result();
        $data['mahasiswa']= $this->M_Mahasiswa->get_mahasiswa()->result();
        // $data = array(
        //     'sumberdana' => $this->M_SumberDana->get_sumberdana(),
        //     'sumberdana_selected' => '',
        // );
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header',$nama);
        $this->load->view('dosen/penambahanmitra', $data);
        $this->load->view('pengabdian/footer');

    }

    public function updateSurat()
    {
        $surat = $_FILES['file_persetujuan'];
        if(!empty($surat['name'])){
            $config['upload_path'] = './assets/suratmitra';
            $config['allowed_types'] = 'pdf';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload',$config);
            if(!$this->upload->do_upload('file_persetujuan')){
                echo "Upload Gagal"; die();
            } else {
                $surat=$this->upload->data('file_name');
            }
        }
        $id = $this->input->post('id');
        $data = array('file_persetujuan'=>$surat);
        $this->M_Mitra->update_mitra($id,$data);
        redirect('dosen/pengabdian/submitpermohonan');
    }

    public function uploadLaporanAkhir()
    {
        $surat = $_FILES['laporan_akhir'];
        if($surat=''){}else{
            $config['upload_path'] = './assets/laporan_akhir';
            $config['allowed_types'] = 'pdf';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload',$config);
            if(!$this->upload->do_upload('laporan_akhir')){
                echo "Upload Gagal"; die();
            } else {
                $surat=$this->upload->data('file_name');
            }
        }
        $id = $this->input->post('id');
        $data = array('laporan_akhir'=>$surat);
        $this->M_LaporanAkhirPengabdian->update_laporan($id,$data);
        redirect('dosen/pengabdian/laporanakhir');
    }

    public function uploadLogbook()
    {
        $surat = $_FILES['logbook'];
        if($surat=''){}else{
            $config['upload_path'] = './assets/logbook';
            $config['allowed_types'] = 'pdf';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload',$config);
            if(!$this->upload->do_upload('logbook')){
                echo "Upload Gagal"; die();
            } else {
                $surat=$this->upload->data('file_name');
            }
        }
        $id = $this->input->post('id');
        $data = array('logbook'=>$surat);
        $this->M_LaporanAkhirPengabdian->update_laporan($id,$data);
        redirect('dosen/pengabdian/laporanakhir');
    }

    public function uploadBelanja()
    {
        $surat = $_FILES['belanja'];
        if($surat=''){}else{
            $config['upload_path'] = './assets/belanja';
            $config['allowed_types'] = 'pdf';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload',$config);
            if(!$this->upload->do_upload('belanja')){
                echo "Upload Gagal"; die();
            } else {
                $surat=$this->upload->data('file_name');
            }
        }
        $id = $this->input->post('id');
        $data = array('belanja'=>$surat);
        $this->M_LaporanAkhirPengabdian->update_laporan($id,$data);
        redirect('dosen/pengabdian/laporanakhir');
    }

    public function uploadLuaran()
    {
        $surat = $_FILES['luaran'];
        if($surat=''){}else{
            $config['upload_path'] = './assets/luaran';
            $config['allowed_types'] = 'pdf';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload',$config);
            if(!$this->upload->do_upload('luaran')){
                echo "Upload Gagal"; die();
            } else {
                $surat=$this->upload->data('file_name');
            }
        }
        $id = $this->input->post('id');
        $data = array('luaran'=>$surat);
        $this->M_LaporanAkhirPengabdian->update_laporan($id,$data);
        redirect('dosen/pengabdian/laporanakhir');
    }

    public function editMitra($id){
        $data['prop'] = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        $id_mitra = $data['prop']->id_mitra;
        $nip = $data['prop']->nip;
        $data['dosen'] = $this->M_Dosen->getwhere_dosen(array('nip'=>$nip))->row();


        $id_mitra = $data['prop']->id_mitra;
        $data['luaran']= $this->M_Luaran->get_luaran_pengabdian()->result();
        $data['mitra'] = $this->M_Mitra->getwhere_mitra(array('id'=>$id_mitra))->row();
        $nip = $this->session->userdata('user_name');
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header',$nama);
        $this->load->view('dosen/edit_mitra',$data);
        $this->load->view('pengabdian/footer');
    }

    

    public function editProposal($id){
        $data['sumberdana']= $this->M_SumberDana->get_sumberdana()->result();
        $data['proposal'] = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        $id_mitra = $data['proposal']->id_mitra;
        $data['dosen']= $this->M_Dosen->get_dosen()->result();
        $data['luaran']= $this->M_Luaran->get_luaran_pengabdian()->result();
        $data['mahasiswa']= $this->M_Mahasiswa->get_mahasiswa()->result();
        $data['skema'] = $this->M_SkemaPengabdian->get_skemapengabdian()->result();
        $data['anggota_dosen'] = $this->M_PropPengabdian->dosen_update_prop($id)->result();
        $data['anggota_mhs'] = $this->M_PropPengabdian->mhs_update_prop($id)->result();
        $data['nilai_luaran'] = $this->M_PropPengabdian->luaran_update_prop($id)->result();
        $data['mitra'] = $this->M_Mitra->getwhere_mitra(array('id'=>$id_mitra))->row();
        $nip = $this->session->userdata('user_name');
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header',$nama);
        $this->load->view('dosen/upproppengabdian_mitra',$data);
        $this->load->view('pengabdian/footer');
    }

    public function editProposalTanpaMitra($id){
        $data['sumberdana']= $this->M_SumberDana->get_sumberdana()->result();
        $data['proposal'] = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        
        $data['dosen']= $this->M_Dosen->get_dosen()->result();
        $data['luaran']= $this->M_Luaran->get_luaran_pengabdian()->result();
        $data['mahasiswa']= $this->M_Mahasiswa->get_mahasiswa()->result();
        $data['skema'] = $this->M_SkemaPengabdian->get_skemapengabdian()->result();
        $data['anggota_dosen'] = $this->M_PropPengabdian->dosen_update_prop($id)->result();
        $data['anggota_mhs'] = $this->M_PropPengabdian->mhs_update_prop($id)->result();
        $data['nilai_luaran'] = $this->M_PropPengabdian->luaran_update_prop($id)->result();
        $nip = $this->session->userdata('user_name');
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header',$nama);
        $this->load->view('dosen/upproppengabdian',$data);
        $this->load->view('pengabdian/footer');
    }

    public function updateProposalTanpaMitra(){
        $this->form_validation->set_rules('judul','Judul Pengabdian', 'required');
        $this->form_validation->set_rules('abstrak','Abstrak', 'required');
        if($this->form_validation->run()==false){
            redirect("dosen/pengabdian/editproposaltanpamitra/".$this->input->post('id'));
        } else {
        $id = $this->input->post('id');
        $nip = $this->session->userdata('user_name');
        $data_proposal = $this->M_PropPengabdian->getwhere_proposal(array('id'=> $id))->row();
        
        $date = date('Y-m-d');
        $old_username_mitra = $this->M_Mitra->getwhere_mitra(array('id'=>$data_proposal->id_mitra))->row()->username;
        $biaya = str_replace('.','',$this->input->post('biaya',true));
        $prop = array (
            "judul"=>$this->input->post('judul',true),
            "abstrak"=>$this->input->post('abstrak',true),
            "tgl_upload"=>$date,
            "lokasi"=>$this->input->post('lokasi',true),
            "biaya"=>$biaya,
    );
        $proposal=$this->M_PropPengabdian->update_prop($id,$prop);
        /**
         * upload file proposal
         */
        
            $prop_file = $_FILES['file_prop'];
            if(!empty($prop_file['name'])){
                $config['upload_path'] = './assets/prop_pengabdian';
                $config['allowed_types'] = 'pdf';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload',$config);
                if(!$this->upload->do_upload('file_prop')){
                    echo "Upload Gagal"; die();
                } else {
                    $prop_file=$this->upload->data('file_name');
                }
                $data_file = array('file'=>$prop_file);
            $this->M_PropPengabdian->update_prop($id,$data_file);
            }
            /* update anggota dosen */
            $dsn_update = $this->input->post('dosen[]');
            $id_dsn_anggota = $this->input->post('id_dsn_anggota[]');
            $dsn_new = $this->input->post('dosen_new[]');
            $data_dsn_anggota = $this->M_PropPengabdian->dosen_update_prop($id)->result();
            $luaran_update = $this->input->post('luaran[]');
            $id_nilai_luaran = $this->input->post('id_nilai_luaran[]');
            $luaran_new = $this->input->post('luaran_new[]');
            $data_nilai_luaran = $this->M_PropPengabdian->luaran_update_prop($id)->result();
            // print_r($dsn_update);
            if(!empty($dsn_update)){
                foreach($data_dsn_anggota as $k){
                    for($i=0;$i<count($dsn_update);$i++){
                        if($k->id == $id_dsn_anggota[$i]){
                            
                            $dsn=$dsn_update[$i];
                            $data_dosen =[
                                'nip' => $dsn,
                            ];
                            $this->M_PropPengabdian->update_dosen_anggota($data_dosen, $id_dsn_anggota[$i]);
                            continue 2;
                        }
                    } 
                    $this->M_PropPengabdian->hapus_dosen_anggota(array('id'=>$k->id));
                }
                if(!empty($dsn_new)){
                    for($j=0; $j<count($dsn_new)-1;$j++)
                        {
                            if($dsn_new[$j]==""||$dsn_new[$j]==null){

                            }
                            else{
                            $dosen_new=$dsn_new[$j];
                            $data_dosen_new =[
                                'nip' => $dosen_new,
                                'id_proposal' => $id
                            ];
                            $this->M_PropPengabdian->insert_dsn_anggota($data_dosen_new);
                        }
                        }
                    }
            }
            if(!empty($luaran_update)){
                foreach($data_nilai_luaran as $k){
                    for($i=0;$i<count($luaran_update);$i++){
                        if($k->id == $id_nilai_luaran[$i]){
                            
                            $luaran=$luaran_update[$i];
                            $data_luaran =[
                                'id_luaran' => $luaran,
                            ];
                            $this->M_PropPengabdian->update_nilai_luaran($data_luaran, $id_nilai_luaran[$i]);
                            continue 2;
                        }
                    } 
                    $this->M_PropPengabdian->hapus_nilai_luaran(array('id'=>$k->id));
                }
                if(!empty($luaran_new)){
                    for($j=0; $j<count($luaran_new)-1;$j++)
                    {
                        if($luaran_new[$j]==""||$luaran_new[$j]==null||$luaran_new[$j]==0){

                        }
                        else{
                       $l_new=$luaran_new[$j];
                        $data_luaran_new =[
                            'id_luaran' => $l_new,
                            'id_proposal' => $id
                        ];
                        $this->M_PropPengabdian->insert_nilai_luaran($data_luaran_new);
                    }
                    }
                }
            }
            if(empty($dsn_update)){
                $this->M_PropPengabdian->hapus_dosen_anggota(array('id_proposal'=>$id));
                for($j=0; $j<count($dsn_new)-1;$j++)
                    {
                        if($dsn_new[$j]==""||$dsn_new[$j]==null||$dsn_new[$j]==0){

                        }
                        else{
                        
                        $dosen_new=$dsn_new[$j];
                        $data_dosen_new =[
                            'nip' => $dosen_new,
                            'id_proposal' => $id
                        ];
                        $this->M_PropPengabdian->insert_dsn_anggota($data_dosen_new);
                    }
                    }
                }
                
                
                if(empty($luaran_update)){
                    $this->M_PropPengabdian->hapus_nilai_luaran(array('id_proposal'=>$id));
                    for($j=0; $j<count($luaran_new)-1;$j++)
                    {
                        if($luaran_new[$j]==""||$luaran_new[$j]==null||$luaran_new[$j]==0){

                        }
                        else{
                        $l_new=$luaran_new[$j];
                        $data_luaran_new =[
                            'id_luaran' => $l_new,
                            'id_proposal' => $id
                        ];
                        $this->M_PropPengabdian->insert_nilai_luaran($data_luaran_new);
                    }
                    }
                }

        /* update anggota mahasiswa */
        $mhs_update = $this->input->post('nim_mahasiswa[]');
        $mhs_nama_update = $this->input->post('nama_mahasiswa[]');
        $id_mhs_anggota = $this->input->post('id_mhs_anggota[]');
        $mhs_new = $this->input->post('nim_mahasiswa_new[]');
        $mhs_nama_new = $this->input->post('nama_mahasiswa_new[]');
        $data_mhs_anggota = $this->M_PropPengabdian->mhs_update_prop($id)->result();

        foreach($data_mhs_anggota as $k){
            for($i=0;$i<count($mhs_update);$i++){
                if($k->id == $id_mhs_anggota[$i]){
                    $mhs=$mhs_update[$i];
                    $data_mhs =[
                        'nim' => $mhs,
                        'nama'=> $mhs_nama_update[$i],
                    ];
                    $this->M_PropPengabdian->update_mhs_anggota($data_mhs, $id_mhs_anggota[$i]);
                    continue 2;
                }
            } 
            $this->M_PropPengabdian->hapus_mhs_anggota(array('id'=>$k->id));
        }
        for($j=0; $j<count($mhs_new)-1;$j++)
            {
                $mahasiswa_new=$mhs_new[$j];
                $data_mhs_new =[
                    'nim' => $mahasiswa_new,
                    'nama' => $mhs_nama_new[$j],
                    'id_proposal' => $id
                ];
                $this->M_PropPengabdian->insert_mhs_anggota($data_mhs_new);
            }
            $this->session->set_flashdata('pesan', '<p>Terimakasih Anda berhasil melakukan perubahan proposal <br> Proposal dapat dirubah selama jadwal pengumpulan belum berakhir <br> Pastikan Anda telah mengecek kembali proposal Anda sebelum jadwal pengumpulan berakhir </p>');
            $this->session->set_flashdata('button', 'dosen/pengabdian/submitpermohonan');
            redirect("dosen/pengabdian/success");
        }

    }

    public function updateProposal(){
        $this->form_validation->set_rules('judul','Judul Pengabdian', 'required');
        $this->form_validation->set_rules('abstrak','Abstrak', 'required');
        if($this->form_validation->run()==false){
            redirect("dosen/pengabdian/editproposal/".$this->input->post('id'));
        } else {
        
        $id = $this->input->post('id');
        $nip = $this->session->userdata('user_name');
        $data_proposal = $this->M_PropPengabdian->getwhere_proposal(array('id'=> $id))->row();
        
        $date = date('Y-m-d');
        $old_username_mitra = $this->M_Mitra->getwhere_mitra(array('id'=>$data_proposal->id_mitra))->row()->username;
        $biaya = str_replace('.','',$this->input->post('biaya',true));
        $prop = array (
            "judul"=>$this->input->post('judul',true),
            "abstrak"=>$this->input->post('abstrak',true),
            "tgl_upload"=>$date,
            "lokasi"=>$this->input->post('lokasi',true),
            "biaya"=>$biaya,
    );
        $proposal=$this->M_PropPengabdian->update_prop($id,$prop);
        /**
         * upload file proposal
         */
        
            $prop_file = $_FILES['file_prop'];
            if(!empty($prop_file['name'])){
                $config['upload_path'] = './assets/prop_pengabdian';
                $config['allowed_types'] = 'pdf';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload',$config);
                if(!$this->upload->do_upload('file_prop')){
                    echo "Upload Gagal"; die();
                } else {
                    $prop_file=$this->upload->data('file_name');
                }
                $data_file = array('file'=>$prop_file);
            $this->M_PropPengabdian->update_prop($id,$data_file);
            }
            /* update anggota dosen */
            $dsn_update = $this->input->post('dosen[]');
            $id_dsn_anggota = $this->input->post('id_dsn_anggota[]');
            $dsn_new = $this->input->post('dosen_new[]');
            $data_dsn_anggota = $this->M_PropPengabdian->dosen_update_prop($id)->result();
            $luaran_update = $this->input->post('luaran[]');
            $id_nilai_luaran = $this->input->post('id_nilai_luaran[]');
            $luaran_new = $this->input->post('luaran_new[]');
            $data_nilai_luaran = $this->M_PropPengabdian->luaran_update_prop($id)->result();
            // print_r($dsn_update);
            if(!empty($dsn_update)){
                foreach($data_dsn_anggota as $k){
                    for($i=0;$i<count($dsn_update);$i++){
                        if($k->id == $id_dsn_anggota[$i]){
                            
                            $dsn=$dsn_update[$i];
                            $data_dosen =[
                                'nip' => $dsn,
                            ];
                            $this->M_PropPengabdian->update_dosen_anggota($data_dosen, $id_dsn_anggota[$i]);
                            continue 2;
                        }
                    } 
                    $this->M_PropPengabdian->hapus_dosen_anggota(array('id'=>$k->id));
                }
                if(!empty($dsn_new)){
                    for($j=0; $j<count($dsn_new)-1;$j++)
                        {
                            if($dsn_new[$j]==""||$dsn_new[$j]==null||$dsn_new[$j]==0){

                            }
                            else{
                            $dosen_new=$dsn_new[$j];
                            $data_dosen_new =[
                                'nip' => $dosen_new,
                                'id_proposal' => $id
                            ];
                            $this->M_PropPengabdian->insert_dsn_anggota($data_dosen_new);
                        }
                        }
                    }
            }
            if(!empty($luaran_update)){
                foreach($data_nilai_luaran as $k){
                    for($i=0;$i<count($luaran_update);$i++){
                        if($k->id == $id_nilai_luaran[$i]){
                            
                            $luaran=$luaran_update[$i];
                            $data_luaran =[
                                'id_luaran' => $luaran,
                            ];
                            $this->M_PropPengabdian->update_nilai_luaran($data_luaran, $id_nilai_luaran[$i]);
                            continue 2;
                        }
                    } 
                    $this->M_PropPengabdian->hapus_nilai_luaran(array('id'=>$k->id));
                }
                if(!empty($luaran_new)){
                    for($j=0; $j<count($luaran_new)-1;$j++)
                    {
                        if($luaran_new[$j]==""||$luaran_new[$j]==null||$luaran_new[$j]==0){

                        }
                        else{
                       $l_new=$luaran_new[$j];
                        $data_luaran_new =[
                            'id_luaran' => $l_new,
                            'id_proposal' => $id
                        ];
                        $this->M_PropPengabdian->insert_nilai_luaran($data_luaran_new);
                    }
                    }
                }
            }
            if(empty($dsn_update)){
                $this->M_PropPengabdian->hapus_dosen_anggota(array('id_proposal'=>$id));
                for($j=0; $j<count($dsn_new)-1;$j++)
                    {
                        if($dsn_new[$j]==""||$dsn_new[$j]==null||$dsn_new[$j]==0){

                        }
                        else{
                        
                        $dosen_new=$dsn_new[$j];
                        $data_dosen_new =[
                            'nip' => $dosen_new,
                            'id_proposal' => $id
                        ];
                        $this->M_PropPengabdian->insert_dsn_anggota($data_dosen_new);
                    }
                    }
                }
                
                
                if(empty($luaran_update)){
                    $this->M_PropPengabdian->hapus_nilai_luaran(array('id_proposal'=>$id));
                    for($j=0; $j<count($luaran_new)-1;$j++)
                    {
                        if($luaran_new[$j]==""||$luaran_new[$j]==null||$luaran_new[$j]==0){

                        }
                        else{
                        $l_new=$luaran_new[$j];
                        $data_luaran_new =[
                            'id_luaran' => $l_new,
                            'id_proposal' => $id
                        ];
                        $this->M_PropPengabdian->insert_nilai_luaran($data_luaran_new);
                    }
                    }
                }

        /* update anggota mahasiswa */
        $mhs_update = $this->input->post('nim_mahasiswa[]');
        $mhs_nama_update = $this->input->post('nama_mahasiswa[]');
        $id_mhs_anggota = $this->input->post('id_mhs_anggota[]');
        $mhs_new = $this->input->post('nim_mahasiswa_new[]');
        $mhs_nama_new = $this->input->post('nama_mahasiswa_new[]');
        $data_mhs_anggota = $this->M_PropPengabdian->mhs_update_prop($id)->result();

        foreach($data_mhs_anggota as $k){
            for($i=0;$i<count($mhs_update);$i++){
                if($k->id == $id_mhs_anggota[$i]){
                    $mhs=$mhs_update[$i];
                    $data_mhs =[
                        'nim' => $mhs,
                        'nama'=> $mhs_nama_update[$i],
                    ];
                    $this->M_PropPengabdian->update_mhs_anggota($data_mhs, $id_mhs_anggota[$i]);
                    continue 2;
                }
            } 
            $this->M_PropPengabdian->hapus_mhs_anggota(array('id'=>$k->id));
        }

        for($j=0; $j<count($mhs_new)-1;$j++)
            {
                $mahasiswa_new=$mhs_new[$j];
                $data_mhs_new =[
                    'nim' => $mahasiswa_new,
                    'nama' => $mhs_nama_new[$j],
                    'id_proposal' => $id
                ];
                $this->M_PropPengabdian->insert_mhs_anggota($data_mhs_new);
            }

        /* edit mitra  */

        $id_mitra = $this->input->post('id_mitra');
        $data_mitra = [
            "nama_instansi"=> $this->input->post('instansi',true),
            "penanggung_jwb"=>$this->input->post('pj',true),
            "no_telp"=> $this->input->post('no_telp',true),
            "alamat"=>$this->input->post('alamat',true),
            "email"=>$this->input->post('email',true),
            "username"=>$this->input->post('username',true),
        ];

        $this->M_Mitra->update_mitra($id_mitra, $data_mitra);

        $pass = $this->input->post('password');
        $username_mitra = $this->input->post('username');
        if($pass != null){
            $if_exists = $this->M_User->checkUserexist($username_mitra);
            if($if_exists>0){
                $data_user_mitra = [
                    'username' =>$username_mitra,
                    'password' =>md5($pass)
                ];
                $this->M_User->update_user($old_username_mitra, $data_user_mitra);
            } else {
                $data_user_mitra = [
                    "username"=>$username_mitra,
                    "password"=>md5($pass),
                    "role"=>4
                ];
                $this->M_User->insert_user($data_user_mitra);
            }
        } else{
            $data_user_mitra = [
                'username' => $this->input->post('username')
            ];
            $this->M_User->update_user($old_username_mitra, $data_user_mitra);
        }

        
        
        $this->session->set_flashdata('pesan', '<p>Terimakasih Anda berhasil melakukan perubahan proposal <br> Proposal dapat dirubah selama jadwal pengumpulan belum berakhir <br> Pastikan Anda telah mengecek kembali proposal Anda sebelum jadwal pengumpulan berakhir </p>');
            $this->session->set_flashdata('button', 'dosen/pengabdian/submitpermohonan');
            redirect("dosen/pengabdian/success");
        }

    }

    public function finalSubmitProp($id){
        $data = array('status'=>'SUBMITTED');
        $this->M_PropPengabdian->update_prop($id,$data);
        redirect('dosen/pengabdian/submitpermohonan');

    }

    public function DaftarPermohonan()
    {
        $nip = $this->session->userdata('user_name');
        $data['view']= $this->M_PropPengabdian->get_viewpengajuan($nip)->result();
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header',$nama);
        $this->load->view('dosen/daftar_permohonan_pengabdian', $data);
        $this->load->view('pengabdian/footer');

    }

    public function updateMitra()
    {
        $id = $this->input->post('id');
        $data_proposal = $this->M_PropPengabdian->getwhere_proposal(array('id'=> $id))->row();
        
        $old_username_mitra = $this->M_Mitra->getwhere_mitra(array('id'=>$data_proposal->id_mitra))->row()->username;
        $id_mitra = $this->input->post('id_mitra');
        $data_mitra = [
            "nama_instansi"=> $this->input->post('instansi',true),
            "penanggung_jwb"=>$this->input->post('pj',true),
            "no_telp"=> $this->input->post('no_telp',true),
            "alamat"=>$this->input->post('alamat',true),
            "email"=>$this->input->post('email',true),
            "username"=>$this->input->post('username',true),
        ];

        $this->M_Mitra->update_mitra($id_mitra, $data_mitra);

        $pass = $this->input->post('password');
        $username_mitra = $this->input->post('username');
        if($pass != null){
                $data_user_mitra = [
                    'username' =>$username_mitra,
                    'password' =>md5($pass)
                ];
                $this->M_User->update_user($old_username_mitra, $data_user_mitra);
          
        } else{
            $data_user_mitra = [
                'username' => $this->input->post('username')
            ];
            $this->M_User->update_user($old_username_mitra, $data_user_mitra);
        }

        $this->session->set_flashdata('pesan', '<p>Terimakasih Anda berhasil melakukan pengubahan data mitra <br> Data mitra dapat diedit selama mitra belum menyetujui kerjasama <br> Pastikan Anda telah mengecek kembali data mitra Anda<br></p>');
        $this->session->set_flashdata('button', 'dosen/pengabdian/submitpermohonan');
        redirect("dosen/pengabdian/success");


    }

    public function deleteMitra($id)
    {
        $data_proposal = $this->M_PropPengabdian->getwhere_proposal(array('id'=> $id))->row();
        $id_mitra = $data_proposal->id_mitra;
        $data = [
            'id_mitra' => 0,
        ];
        $this->M_PropPengabdian->update_prop($id, $data);
        $this->M_Mitra->delete_mitra($id_mitra);
        redirect('dosen/pengabdian/submitpermohonan');

    }

    public function luaran(){
        $id = $this->input->post('id');
        $data['proposal'] = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        if($id==NULL){
            redirect("dosen/pengabdian/laporanAkhir");
        }
        $nip = $this->session->userdata('user_name');
        $data['luaran'] = $this->M_PropPengabdian->get_luaran(array('id_proposal'=>$id))->result();
        $nama['nama']= $this->M_Profile->getwhere_profile(array('nip'=>$nip))->result();
        $nama['cek']= $this->M_Profile->cekRevPenelitian(array('nip'=>$nip))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header', $nama);
        $this->load->view('dosen/uploadluaran',$data);
        $this->load->view("pengabdian/footer");
    }

    public function upLuaran(){
        $id=$this->input->post('id');
        $cekLuaran = $this->M_PropPengabdian->get_luaran(array('id_proposal'=>$id))->result();
        $nip = $this->session->userdata('user_name');

            for($i=0, $count = count($cekLuaran);$i<$count;$i++) {
                $id_luaran = $this->input->post("id_luaran$i");
                $jenis_luaran = $this->input->post("jenis$i");
                $judul = $this->input->post("judul$i");
                $nama = $this->input->post("nama$i");
                $author = $this->input->post("author$i");
                $tahun = $this->input->post("tahun$i");
                $link = $this->input->post("link$i");
                $file_luaran = $_FILES["file_luaran$i"];

                if(empty($file_luaran['name'])){
                    $datafile = [
                        "pengusul"=>$nip,
                        "judul"=>$judul,
                        "jenis_luaran"=>$jenis_luaran,
                        "nama"=>$nama,
                        "author"=>$author,
                        "tahun"=>$tahun,
                        "link"=>$link,];
                        $proposal=$this->M_PropPengabdian->update_luaran($datafile,$id,$id_luaran);
                }
                else{
                    $config['upload_path'] = './assets/luaran';
                    $config['allowed_types'] = 'pdf';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload',$config);
                    if(!$this->upload->do_upload("file_luaran$i")){
                        echo "Upload Gagal"; die();
                    } else {
                        $file_luaran=$this->upload->data('file_name');
                    }
                    $datafile = [
                        "pengusul"=>$nip,
                        "judul"=>$judul,
                        "jenis_luaran"=>$jenis_luaran,
                        "nama"=>$nama,
                        "author"=>$author,
                        "tahun"=>$tahun,
                        "link"=>$link,
                        "file"=>$file_luaran,];
                        $this->M_PropPengabdian->update_luaran($datafile,$id,$id_luaran);
                    }
                
            };


        $data = [
            "luaran"=>"done",
        ];
        $this->M_LaporanAkhirPengabdian->update_laporan($id,$data);
        $this->session->set_flashdata('pesan', '<p>Terimakasih Anda berhasil melakukan pengumpulan luaran laporan akhir <br> Laporan akhir dapat dirubah selama jadwal pengumpulan belum berakhir <br> Pastikan Anda telah mengecek kembali laporan akhir Anda sebelum jadwal pengumpulan berakhir </p>');
        $this->session->set_flashdata('button', 'dosen/pengabdian/laporanAkhir');
        redirect("dosen/pengabdian/success"); 
    }

    public function laporanAkhir()
    {
        $id = $this->input->post('id');
        $data['view']= $this->M_PropPengabdian->get_viewlaporanakhir()->result();
        $nip = $this->session->userdata('user_name');
        $nama['cek']= $this->M_Profile->cekRevPengabdian(array('nip'=>$nip))->result();
        $data['luaran'] = $this->M_PropPengabdian->get_luaran(array('id_proposal'=>$id))->result();
        $nama['hitungovr'] = $this->M_Publikasi_Pribadi->hitungovrview();
        $this->load->view('pengabdian/header',$nama);
        $cekjad=$data['jadwal'] = $this->M_JadwalPengabdian->get_last_jadwal()->row();
        if (empty($cekjad)){
            $this->load->view('dosen/closed_form_akhir', $data);
        }
        else{
        $now = date('Y-m-d', strtotime(date('Y-m-d')));
        $awal = date('Y-m-d', strtotime($data['jadwal']->tgl_mulai));
        $akhir = date('Y-m-d', strtotime($data['jadwal']->tgl_akhir));
        if(($now>= $awal) && ($now<=$akhir)){
            $this->load->view('dosen/laporan_akhir_pengabdian', $data);

        } 
     else {
        $this->load->view('dosen/closed_form_akhir', $data);
    }
}
$this->load->view('pengabdian/footer');
        
    }


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}