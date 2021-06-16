<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasilpenelitian extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->library('upload');
        // //load model admin
        // $this->load->model('admin');
        // //cek session dan level user
        // if($this->admin->is_role() != "1"){
        //     redirect("login/");
        // }
    }



    public function index()
    {
      $new_filename = substr(md5(mt_rand()), 0, 7);
      $config['upload_path'] = 'uploads';
      $config['allowed_types'] = 'pdf';
      $config['max_size'] = 9999000;
      $config['file_name'] = $new_filename;
      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      $error= [];
  		if($this->upload->do_upload('file')){
  			$uploadData = $this->upload->data();
  		}else{
        $error = array('error' => $this->upload->display_errors());
      }

      $cmd = "C:\Users\irza\AppData\Local\Programs\Python\Python39\python.exe ".FCPATH."python\ocr\ocr_teliti.py ".FCPATH."uploads";
      $cmd .= str_replace(" ","","\ ").$new_filename.".pdf";
      // print($cmd);exit;
      exec($cmd,$o,$e);

      $hasilnya  = file_get_contents(FCPATH."python\ocr\hasil_teliti.json");
      print_r($hasilnya);exit;








      if (!$this->upload->do_upload('file'))
      {
          $error = array('error' => $this->upload->display_errors());
          print($error);exit;

          // $this->load->view('imageupload_form', $error);
      }
      else
      {
          $data = array('image_metadata' => $this->upload->data());
          print("sukses");exit;

          // $this->load->view('imageupload_success', $data);
      }

    }

    // public function logout()
    // {
    //     $this->session->sess_destroy();
    //     redirect('login');
    // }

}
