
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">
                            Edit Mitra Pengabdian
                        </h1>
                        <ol class="breadcrumb">
                            
                            <li class="active">
                                <i class="fa fa-edit"></i> Pengajuan Mitra
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-6" style="float:none;margin:auto;">
                    <div class='form-group row'>
                        <label class="col-lg-4 col-form-label">Judul Pengabdian</label>
                        <div class="col-lg-8">
                            <p><?=$prop->judul;?></p>
                        </div>
                    </div>
                    <label>Ketua Pengabdian</label>
                    <div class='form-group row'>
                        <p class="col-lg-4">Nama Lengkap</p>
                        <div class="col-lg-8">
                            <p><?=$dosen->nama;?></p>
                        </div><br>
                        <p class="col-lg-4">NIP/NIDN</p>
                        <div class="col-lg-8">
                            <p><?=$dosen->nip;?></p>
                        </div><br>
                        <p class="col-lg-4 ">Program Studi</p>
                        <div class="col-lg-8">
                            <p><?=$dosen->program_studi;?></p>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class="col-lg-4 col-form-label">Lama Pengabdian</label>
                        <div class="col-lg-8">
                            <p><?=$prop->lama_pelaksanaan;?></p>
                        </div>
                    </div> 
                    <div class='form-group row'>
                        <label class="col-lg-4 col-form-label">Biaya Pengabdian</label>
                        <div class="col-lg-8">
                            <p>Rp <?=  number_format($prop->biaya,2,',','.');?></p>
                        </div>
                    </div> 
                    <?= form_open_multipart('admin/pengabdian/updateMitra');?>

                    
                                <div class="form-group">
                                    <label>Nama Instansi</label><label style="color:red; font-size:12px;"> (*Wajib diisi)</label>
                                    <input class="form-control" name="instansi" required="" <?php echo "value=\"" . $mitra->nama_instansi . "\""; ?> >
                                    <input class='form-control hidden' type="text" name="id_mitra" value=<?=$mitra->id?> hidden>
                                    <input class='form-control hidden' type="text" name="id" value=<?=$prop->id?> hidden>
                                </div>
                                <div class="form-group">
                                    <label>Penanggung Jawab</label>
                                    <input class="form-control" name="pj"<?php echo "value=\"" . $mitra->penanggung_jwb . "\""; ?> >
                                </div>
                                <div class="form-group">
                                    <label>Nomor Telepon</label>
                                    <input class="form-control" name="no_telp" value=<?= $mitra->no_telp?> >
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" name="email" value=<?= $mitra->email?> >
                                </div>
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input class="form-control" name="alamat" <?php echo "value=\"" . $mitra->alamat . "\""; ?> >
                                </div>
                                <div class="form-group">
                                    <label>Username</label><label style="color:red; font-size:12px;"> (*Maks 20 Karakter)</label>
                                    <input class="form-control" id="username" name="username" required="" value=<?=$mitra->username?> placeholder="Masukkan username untuk mitra">
                                    <span id="username_result" style='color:red'></span>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Apabila tidak ingin mengganti password, cukup kosongi field ini" >
                                </div>

                

                                <h3>File Proposal</h3>
                                <div>
                                    <iframe src="<?= base_url('assets/prop_pengabdian');?>/<?=$proposal->file?>" width="93%" height="400px" >
                                    </iframe>
                                </div>
                                <button type="submit" id='submit' class="btn btn-primary">Submit</button>
                                
                            <?= form_close(); ?>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="<?= base_url('assets/template/js/jquery-1.11.0.js');?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?= base_url('assets/template/js/bootstrap.min.js');?>"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        $('#selectpicker1').selectpicker();
        $('#btnadd').prop('disabled', true);
        // $('#selectpicker2').selectpicker();
        $( '.currency' ).keyup(function(event) {
            // skip for arrow keys
            if(event.which >= 37 && event.which <= 40) return;

            // format number
            $(this).val(function(index, value) {
            return value
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            ;
            });
            });

    });
    </script>

   
   <script type="text/javascript"> 
        $(document).ready(function(){
            //$('#submit').prop('disabled',true);
            $('#username').change(function(){
            var username = $('#username').val();
            if(username != ''){
                $.ajax({
                    url:"<?php echo base_url('dosen/Pengabdian/checkUsername');?>",
                    method:"post",
                    data:{username:username},
                    dataType: 'json',
                    success:function(data){
                        var userVal = $('#username').val();
                        if(data=="Username tersedia" && userVal.length < 21){
                            $('#submit').prop('disabled',false);
                            $('#username_result').hide();
                        }else if(data!="Username tersedia" && userVal.length < 21){
                            $('#submit').prop('disabled', true);
                            $('#username_result').show();
                            $('#username_result').html(data);
                        } else if(data=="Username tersedia" && userVal.length > 20){
                            $('#submit').prop('disabled', true);
                            $('#username_result').show();
                            $('#username_result').html("Karakter username tidak boleh lebih dari 20");
                        } else{
                            $('#submit').prop('disabled', true);
                            $('#username_result').show();
                            $('#username_result').html("Username tidak tersedia dan karakter username melebihi 20 karakter");
                        }
                        //console.log(data);
                    }
                });
            }
			else
			{
				            $('#submit').prop('disabled',true);

			}
            });
            });
    </script>

</body>

</html>
