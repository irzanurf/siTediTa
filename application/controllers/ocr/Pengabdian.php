<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once APPPATH.'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;

class Pengabdian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('admin');
        //cek session dan level user
        if($this->admin->is_role() != "1"){
            redirect("login/");
        }
        $this->load->config('email');
        $this->load->library('email');
        $this->load->model('M_PropPengabdian');
        $this->load->model('M_Mitra');
        $this->load->model('M_User');
        $this->load->model('M_SumberDana');
        $this->load->model('M_Dosen');
        $this->load->model('M_NilaiPropPengabdian');
        $this->load->model('M_Admin');
        $this->load->model('M_ReviewerPengabdian');
        $this->load->model('M_AssignProposalPengabdian');
        $this->load->model('M_KomponenNilaiPengabdian');
        $this->load->model('M_Mahasiswa');
        $this->load->model('M_LaporanAkhirPengabdian');
    }

    public function index()
    {
        $user = $this->session->userdata('user_name');
        $data['user'] = $this->M_Admin->getwhere_admin(array('nip'=>$user))->row();
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar_dosen_pengabdian');
        $this->load->view('admin/dashboardpengabdian',$data);
    }

    public function approval()
    {
        $data['view'] = $this->M_PropPengabdian->get_viewApproval()->result();
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar_dosen_pengabdian');
        $this->load->view('admin/approval_prop_pengabdian',$data);


    }

    public function daftarPengabdian()
    {
        $data['view'] = $this->M_PropPengabdian->get_viewPengabdian()->result();
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar_dosen_pengabdian');
        $this->load->view('admin/daftar_prop_pengabdian',$data);

    }

    public function assignProposal()
    {
        $data['view'] = $this->M_PropPengabdian->get_viewAssign()->result();
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar_dosen_pengabdian');
        $this->load->view('admin/assign_pengabdian',$data);

    }

    public function assignReviewerProposal($id)
    {
        $data['prop'] = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        $id_mitra = $data['prop']->id_mitra;
        $data['mitra'] = $this->M_Mitra->getwhere_mitra(array('id'=>$id_mitra))->row();
        $nip = $data['prop']->nip;
        $data['dosen'] = $this->M_Dosen->getwhere_dosen(array('nip'=>$nip))->row();
        $data['reviewer'] = $this->M_ReviewerPengabdian->get_reviewer()->result();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar_dosen_pengabdian');
        $this->load->view('admin/assign_reviewer_pengabdian',$data);


    }

    public function submitAssignReviewer()
    {
        $idProp = $this->input->post('id');
        $reviewer = $this->input->post('reviewer');
        $data = [
            'id_proposal' => $idProp,
            'reviewer' => $reviewer
        ];
        $status = [
            'status' => 'ASSIGNED'
        ];

        $this->M_PropPengabdian->update_prop($idProp,$status);
        $this->M_AssignProposalPengabdian->insert_assignment($data);
        redirect('admin/pengabdian/assignproposal');

    }

    public function detailProposal($id)
    {
        $data['prop'] = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        $id_mitra = $data['prop']->id_mitra;
        $data['mitra'] = $this->M_Mitra->getwhere_mitra(array('id'=>$id_mitra))->row();
        $nip = $data['prop']->nip;
        $data['dosen'] = $this->M_Dosen->getwhere_dosen(array('nip'=>$nip))->row();
        $data['komponen'] = $this->M_KomponenNilaiPengabdian->get_nilaikomponen(array('id_proposal'=>$id))->result();
        $data['nilai'] = $this->M_NilaiPropPengabdian->getwhere_nilai(array('id_proposal'=>$id))->row();

        // $this->load->view('layout/header');
        // $this->load->view('layout/sidebar_dosen_pengabdian');
        // $this->load->view('reviewer/editnilai_prop_pengabdian',$data);

        // $data['prop'] = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        // $id_mitra = $data['prop']->id_mitra;
        // $data['mitra'] = $this->M_Mitra->getwhere_mitra(array('id'=>$id_mitra))->row();
        // $nip = $data['prop']->nip;
        // $data['dosen'] = $this->M_Dosen->getwhere_dosen(array('nip'=>$nip))->row();
        // $data['nilai'] = $this->M_PropPengabdian->getNilaiProp(array('id_proposal'=>$id))->row();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar_dosen_pengabdian');
        $this->load->view('admin/detail_proposal',$data);


    }

    public function skemaPengabdian()
    {
        
    }

    public function acceptProposal($id)
    {
        $status = [
            'status' => 'ACCEPTED'
        ];

        $data = [
            'id_proposal' => $id,
            'nip' => $this->session->userdata('user_name'),
        ];

        $this->M_PropPengabdian->update_prop($id,$status);
        $prop = $this->M_PropPengabdian->getwhere_proposal(array('id'=>$id))->row();
        $lap = $this->M_LaporanAkhirPengabdian->getwhere_laporan(array('id_proposal'=>$id))->row();
        if( $lap == NULL){
            $this->M_LaporanAkhirPengabdian->insert_laporan($data);
        }

        $from = $this->config->item('smtp_user');
        $to = $this->M_Dosen->getwhere_dosen(array('nip'=>$prop->nip))->row()->email;
        $subject = 'APPROVAL PROPOSAL PENGABDIAN';
        $message = 'Proposal pengabdian anda yang berjudul '.$prop->judul.' berstatus approved';

        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            echo 'Your Email has successfully been sent.';
        } else {
            show_error($this->email->print_debugger());
        }
        
        redirect('admin/pengabdian/approval');
    }

    public function rejectProposal($id)
    {
        $status = [
            'status' => 'REJECTED'
        ];

        $this->M_PropPengabdian->update_prop($id,$status);
        redirect('admin/pengabdian/approval');

    }

    public function laporanAkhir()
    {
        $data['view']= $this->M_PropPengabdian->get_viewlaporanakhir()->result();
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar_dosen_pengabdian');
        $this->load->view('admin/laporan_akhir_pengabdian', $data);

    
    }

    public function testword()
    {
        // $phpWord = new PhpWord();
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
		$section = $phpWord->addSection();
        
		
		$writer = new Word2007($phpWord);
		
        $filename = 'simple';
        
        $cellRowSpan = array('vMerge' => 'restart', 'borderTopSize'=>1 ,'borderTopColor' =>'black','borderLeftSize'=>1,'borderLeftColor' =>'black','borderRightSize'=>1,'borderRightColor'=>'black','borderBottomSize' =>1,'borderBottomColor'=>'black');
        $cellRowContinue = array('vMerge' => 'continue',  'borderTopSize'=>1 ,'borderTopColor' =>'black','borderLeftSize'=>1,'borderLeftColor' =>'black','borderRightSize'=>1,'borderRightColor'=>'black','borderBottomSize' =>1,'borderBottomColor'=>'black');
        $cellColSpan = array('gridSpan' => 2, 'borderTopSize'=>1 ,'borderTopColor' =>'black','borderLeftSize'=>1,'borderLeftColor' =>'black','borderRightSize'=>1,'borderRightColor'=>'black','borderBottomSize' =>1,'borderBottomColor'=>'black');
        $styleCell = array('borderTopSize'=>1 ,'borderTopColor' =>'black','borderLeftSize'=>1,'borderLeftColor' =>'black','borderRightSize'=>1,'borderRightColor'=>'black','borderBottomSize' =>1,'borderBottomColor'=>'black' );
        $table = $section->addTable('myOwnTableStyle',array('borderSize' => 1, 'borderColor' => '999999', 'afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0  ));


        $prop = $this->M_PropPengabdian->get_viewAnnouncement()->result();


        $table->addRow();
        $table->addCell(2000, $cellRowSpan)->addText("No");
        $table->addCell(2000, $cellRowSpan)->addText("Judul Pengabdian");
        $table->addCell(2000, $cellRowSpan)->addText("Ketua Pengabdian");
        $table->addCell(4000, $cellColSpan)->addText("Anggota Pengabdian");
        $table->addCell(2000, $cellRowSpan)->addText("Jumlah Dana per Judul (Rp)");

        $table->addRow();
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(2000,$styleCell)->addText("Dosen");
        $table->addCell(2000,$styleCell)->addText("Mahasiswa");
        $table->addCell(null, $cellRowContinue);
        $no = 1;
        foreach($prop as $p){
            $noDsn= 1;
            $noMhs = 1;
            $table->addRow();
            $table->addCell(2000,$styleCell)->addText($no++);
            $table->addCell(2000,$styleCell)->addText($p->judul);
            $table->addCell(2000,$styleCell)->addText($p->nama);
            $dosen = $this->M_Dosen->getwhere_dosenpengabdian(array('id_proposal'=>$p->id))->result();
            $celldsn = $table->addCell(2000,$styleCell);
            foreach( $dosen as $dsn){
                $celldsn->addText($noDsn++.'. '.$this->M_Dosen->getwhere_dosen(array('nip'=>$dsn->nip))->row()->nama);
            }

            // foreach($dosen as $dsn){
            //     $table->addCell(2000)->addText($noDsn++.''.$this->M_Dosen->getwhere_dosen(array('nip'=>$dsn->nip))->row()->nama);
            // }
            $cellmhs = $table->addCell(2000,$styleCell);
            foreach($this->M_Mahasiswa->getwhere_mahasiswapengabdian(array('id_proposal'=>$p->id))->result() as $mhs){
                $cellmhs->addText($noMhs++.'. '.$this->M_Mahasiswa->getwhere_mahasiswa(array('nim'=>$mhs->nim))->row()->nama);
            }
            $table->addCell(2000,$styleCell)->addText(number_format($p->biaya,0,',','.'));

        }
        
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter( $phpWord, "Word2007" );
		header( "Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document" );
        header('Content-Disposition: attachment;filename="'. $filename .'.docx"'); 
        header('Cache-Control: max-age=0');
        
        
        $objWriter->save( "php://output" );
		
        // $writer->save('php://output');
        // $phpword->save('Perfomance_Appraisal.docx', 'Word2007', true);
    }
}