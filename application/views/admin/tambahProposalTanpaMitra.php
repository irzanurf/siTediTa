
 <div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Tambah Proposal
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="<?= base_url('admin/dashboard/');?>">Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-edit"></i> Tambah Proposal
                </li>
            </ol>
        </div>
    </div>

    <div class="grids">
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">PESAN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <center>
          <h3>PROSES OCR SEDANG BERLANGSUNG, MAAF MENUNGGU SEBENTAR</h3>
        </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">TUTUP</button>
      </div>
    </div>
  </div>
</div>
<form action="<?= base_url('admin/penelitian/addformProposal');?>" method="post" enctype="multipart/form-data"> 
<div class="card" style="max-width: 570px"><div class="card-body">
<div class="form-group"> Form ini memanfaatkan teknik OCR, silahkan input file proporsal terlebih dahulu :<br>
<label for="exampleInputFile"><strong>Upload Proposal</strong></label><label style="color:red; font-size:12px;">&nbsp; (*Wajib diisi)</label>
                                    <input type="file" accept="application/pdf" class="form-control" name="file_prop" id="file" ><br>
                                    <label style="color:red; font-size:12px;">.pdf maks 10mb</label>
                                </div> </div> </div><br>

<div class="row">
<div class="col-lg-6" style="float:none;margin:auto;">
                    <div class="form">
						<div class="forms">
							<div class="form-grids widget-shadow" data-example-id="basic-forms"> 
								<div class="form-title">
								</div>
								<div class="form-body">
									<form action="<?= base_url('admin/pengabdian/addformProposalTanpaMitra');?>" method="post" enctype="multipart/form-data"> 
                                    
                                    <div class="form-group">
                                <label>Ketua</label><label style="color:red; font-size:12px;"> (*Wajib diisi)</label>
                                <select class="form-control" name="nip" id="selectpicker0" data-live-search="true" required="">
                                        <option value="">Please Select</option>
                                        <?php
                                        foreach ($dosen as $ds) {
                                            ?>
                                            <option value="<?php echo $ds->nip; ?>"><?php echo $ds->nama; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    </div>

                                    <div class="form-group">
                                    <input type="hidden" class="form-control" name="id_jadwal" value=<?= $jadwal?>  >
                                </div>

                                <div class="form-group">
                                    <label>Jenis Pengabdian</label><label style="color:red; font-size:12px;"> (*Wajib diisi)</label>
                                    <select class="form-control" name="skema_pengabdian" id="skema_pengabdian" required="">
                                        <option value="">Please Select</option>
                                        <?php
                                        foreach ($skema as $sd) {
                                            ?>
                                            <option value="<?php echo $sd->id; ?>"><?php echo $sd->jenis_pengabdian; ?> - <?php echo $sd->tgl; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Judul Pengabdian</label><label style="color:red; font-size:12px;"> (*Wajib diisi)</label>
                                    <input class="form-control" name="judul" id="judul" required="">
                                    <span id="judul_result" style='color:red'></span>
                                </div>

                                <div class="form-group">
                                    <label>Abstrak</label><label style="color:red; font-size:12px;"> (*Wajib diisi)</label>
                                <textarea class="form-control" rows="3" id="input_abstrak" name="abstrak" required=""></textarea>
                                </div>
                                

                                <div class="form-group">
                                    <label>Lokasi</label>
                                    <input class="form-control" name="lokasi" id="input_lokasi" placeholder="Kelurahan, Kota, Provinsi">
                                </div>

                                <div class="form-group">
                                    <label>Lama Pelaksanaan</label><label style="color:red; font-size:12px;"> (*Wajib diisi)</label>
                                    <div class="form-group input-group">
                                    <input type="text" class="form-control" id="input_lama_bulan" name="bulan" required="">
                                    <span class="input-group-addon">bulan</span>
                                </div>
                                </div>

                                <div class="form-group">
                                    <label>Biaya</label><label style="color:red; font-size:12px;"> (*Wajib diisi)</label>
                                    <div class="form-group input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" class="form-control currency" id="input_biaya" name="biaya" required="">
                                    <span class="input-group-addon">,00</span>
                                </div>
                                </div>

                                <div class="form-group">
                                    <label>Pendanaan</label><label style="color:red; font-size:12px;"> (*Wajib diisi)</label>
                                    <select class="form-control" name="sumberdana" id="sumberdana">
                                        <option value="">Please Select</option>
                                        <?php
                                        foreach ($sumberdana as $sd) {
                                            ?>
                                            <option value="<?php echo $sd->id; ?>"><?php echo $sd->sumberdana; ?> - <?php echo $sd->tgl; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Luaran</label><label style="color:red; font-size:12px;"> (*Wajib diisi)</label>
                                    <div class="input-group control-group after-add-more2">
                                    <select class="form-control" id="selectpicker2" name="luaran[]"  data-live-search="true">
                                        <option value="">Please Select</option>
                                        <?php
                                        foreach ($luaran as $l) {
                                            ?>
                                            <option value="<?php echo $l->id; ?>"><?php echo $l->luaran; ?> - <?php echo $l->tgl; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="input-group-btn"> 
                                    <button class="btn btn-success add-more2" id="btnadd2" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                                </div></br>
                                
                                <div class="form-group">
                                <label>Anggota Dosen</label>
                                <div class="input-group control-group after-add-more">
                               <select class="form-control" id="selectpicker1" name="dosen[]" data-live-search="true">
                                    <option value="">Please Select</option>
                                    <?php
                                    foreach ($dosen as $ds) {
                                        ?>
                                        <option value ="<?php echo $ds->nip; ?>"><?php echo $ds->nama ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                 <div class="input-group-btn"> 
                                    <button class="btn btn-success add-more" id="btnadd" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                                </div>

                                <div class="form-group">
                                <label><br>Anggota Mahasiswa (NIM)</label>
                                <div class="input-group-btn"> 
                                    <button class="btn btn-success add-more1" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                                <div class="input-group control-group1 after-add-more1">
                                <input class="form-control" name="nim_mahasiswa[]" id="input_nim_mahasiswa" placeholder="NIM Mahasiswa" >
                                <input class="form-control" name="nama_mahasiswa[]" id="input_nama_mahasiswa" placeholder="Nama Mahasiswa" >
                                </div>
                            </div>

                            <div class="copy2 hide">
                                <div class="control-group input-group" style="margin-top:10px">
                                <input class="form-control id-luaran" name="luaran[]" hidden >
                                <input class="form-control nama-luaran"  readonly>
                                
                                    <div class="input-group-btn"> 
                                    <button class="btn btn-danger remove2" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                    </div>
                                </div>
                            </div>

                            <div class="copy hide">
                                <div class="control-group input-group" style="margin-top:10px">
                                <input class="form-control id-dosen" name="dosen[]" hidden >
                                <input class="form-control nama-dosen"  readonly>
                                
                                    <div class="input-group-btn"> 
                                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                    </div>
                                </div>
                            </div>
                        

                            <div class="copy1 hide">
                                <div class="control-group1 input-group" style="margin-top:10px">
                                <input class="form-control" name="nim_mahasiswa[]" placeholder="NIM Mahasiswa" >
                                <input class="form-control" name="nama_mahasiswa[]" placeholder="Nama Mahasiswa" >
                            
                                    <div class="input-group-btn"> 
                                    <button class="btn btn-danger remove1" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                    </div>
                                </div>
                            </div>

                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-primary">Save changes</button>
                                </div>
									</form> 
								</div>
							</div>
						</div>
                    </div>
                                </div>
</div>
</div>
Kecepatan : <input class="form-control" id="kecepatan" readonly></input> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
$(document).ready(function (e) {
 // $("#file").on('change',(function(e) {
  $('#jadwal').prop("disabled", true);
  $('#jenis').prop("disabled", true);
  $('#input_abstrak').prop("readonly", true);
  $('#selectpicker2').prop("disabled", true);
  $('#judul').prop("readonly", true);
  $('#input_lokasi').prop("readonly", true);
  $('#input_mitra').prop("readonly", true);
  $('#sumberdana').prop("disabled", true);
  $('#selectpicker1').prop("disabled", true);
  $('#input_biaya').prop("readonly", true);
  $('#input_lama_bulan').prop("readonly", true);
  $('#input_nim_mahasiswa').prop("readonly", true);
  $('#input_nama_mahasiswa').prop("readonly", true);

   $(document).on('input', '#file', function(e) {
   console.log("running");
  e.preventDefault();
  var fd = new FormData();
  var input   = document.getElementById("file");
  var myFile  = input.files[0];
  var type    = input.files[0].type;

  if (!(type== "application/pdf")) {
    alert("bukan pdf");
    input.value = "";
  }

  fd.append( 'file', myFile );
  $("#exampleModal").modal("show");
  $('#jadwal').prop("disabled", false);
  $('#jenis').prop("disabled", false);
  $('#selectpicker2').prop("disabled", false);

  $.ajax({
    url: '<?= base_url("ocr/Hasilpengabdian"); ?>',
    data: fd,
    processData: false,
    contentType: false,
    type: 'POST',
    success: function(data){
      r = JSON.parse(data);
      $('#judul').val(r.judul);
      $('#judul').prop("readonly", false);

      $('#input_abstrak').val(r.abstrak);
      $('#input_abstrak').prop("readonly", false);

      $('#input_lokasi').val(r.lokasi);
      $('#input_lokasi').prop("readonly", false);
      // $('#input_lama_1').val(r.)
      // $('#input_lama_2').val(r.)
      $('#input_biaya').val(r.biaya);
      $('#input_biaya').prop("readonly", false);
      // $('#input_sumberdana').val(r.sumber_dana);

      $('#input_lama_bulan').val(r.lama_bulan);
      $('#input_lama_bulan').prop("readonly", false);
      $('#selectpicker1').prop("disabled", false);
      $('#input_nim_mahasiswa').prop("readonly", false);
      $('#input_nama_mahasiswa').prop("readonly", false);
      $('#selectpicker2').prop("disabled", false);
      $('#sumberdana').prop("disabled", false);
      $('#jadwal').prop("disabled", false);
      $('#jenis').prop("disabled", false);
      $('#input_mitra').val(r.Mitra);
      $('#input_mitra').prop("readonly", false);
      $('#kecepatan').val(r.kecepatan);

      for (var i = 0; i < r.mahasiswa.length; i++) {
        html = '';
        html +='<div class="control-group1 input-group" style="margin-top:10px">';
        html += '<input class="form-control" name="nim_mahasiswa[]" value="'+r.nim_mhs[i]+'" placeholder="NIM Mahasiswa" >';
        html += '<input class="form-control" name="nama_mahasiswa[]" value="'+r.mahasiswa[i]+'" placeholder="Nama Mahasiswa" >';
        html += '<div class="input-group-btn"> <button class="btn btn-danger remove1" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button></div></div>';
        $(".after-add-more1").after(html);
      }


      for (var i = 0; i < r.dosen.length; i++) {
        html = '';
        html +='<div class="control-group1 input-group" style="margin-top:10px">';
        html += '<select class="form-control select_dosen" name="dosen[]">';
        html += '<option value="'+r.nip[i]+'" selected>'+r.dosen[i]+'</option></select>';
        html += '<div class="input-group-btn"> <button class="btn btn-danger remove1" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button></div></div>';
        $(".after-add-more").after(html);
      }

  
    //   $.each(r.mahasiswa, function( index, value ) {
    //     $(".after-add-more1").after(html);
    //     $(".control-group1:last").attr('id','id_mahasiswa'+index.toString())
    // });

      // console.log(xhtml)
      //       xhtml;
      //
      //     xhtml;
      // console.log(xhtml);
      // console.log(r);
    }
  });

 });
});
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#selectpicker1').selectpicker();
        $('#btnadd').prop('disabled', true);
        $('#selectpicker2').selectpicker();
        $('#btnadd2').prop('disabled', true);
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
                    // $('#selectpicker2').selectpicker();
    });
    </script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#selectpicker2').on('change', function(){
            $('#btnadd2').prop('disabled', false);
            
      $("body").on("click",".remove2",function(){ 
          $(this).parents(".control-group").remove();
      });
    });

    $("#btnadd2").on('click',function(){ 
                var temp = $(".copy2.hide").clone(true); 
                $('.nama-luaran', temp).val($('#selectpicker2 option:selected').text());
                $('.id-luaran', temp).val($('#selectpicker2').val());
                $(temp).removeClass("hide");
          $(".after-add-more2").after(temp);
          $('#selectpicker2').val("").selectpicker('refresh');


      });


        })
      
    </script>

<script type="text/javascript">
        $(document).ready(function() {
            $('#selectpicker0').selectpicker();
        });
    </script>

<script type="text/javascript"> 
        $(document).ready(function(){
            $('#submit').prop('disabled',true);
            $('#judul').change(function(){
            var judul = $('#judul').val();
            if(judul != ''){
                $.ajax({
                    url:"<?php echo base_url('admin/pengabdian/checkJudul');?>",
                    method:"post",
                    data:{judul:judul},
                    dataType: 'json',
                    success:function(data){
                        if(data=="Judul belum diajukan"){
                            $('#submit').prop('disabled',false);
                            $('#judul_result').hide();
                        }else{
                            $('#submit').prop('disabled', true);
                            $('#judul_result').show();
                            $('#judul_result').html(data);
                        }
                        //console.log(data);
                    }
                });
            }
            });
            });
    </script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#selectpicker1').on('change', function(){
            $('#btnadd').prop('disabled', false);
            
      $("body").on("click",".remove",function(){ 
          $(this).parents(".control-group").remove();
      });
    });

    $("#btnadd").on('click',function(){ 
                var temp = $(".copy.hide").clone(true); 
                $('.nama-dosen', temp).val($('#selectpicker1 option:selected').text());
                $('.id-dosen', temp).val($('#selectpicker1').val());
                $(temp).removeClass("hide");
          $(".after-add-more").after(temp);
          $('#selectpicker1').val("").selectpicker('refresh');


      });


        })
      
    </script>



    <script type="text/javascript">
    $(document).ready(function() {
      $(".add-more1").click(function(){ 
          var html = $(".copy1").html();
          $(".after-add-more1").after(html);
        //   $('.selectpicker2').selectpicker();
          
      });
      $("body").on("click",".remove1",function(){ 
          $(this).parents(".control-group1").remove();
      });
    });
    </script>


 

</body>

</html>
