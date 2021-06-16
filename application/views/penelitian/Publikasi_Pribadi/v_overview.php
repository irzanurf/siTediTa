<br><?php foreach($tampilovr as $row) : ?>
<?php if ($row->Judul1!="none" &&  $row->Judul2!="none" && $row->Judul3!="none" && $row->Judul4!="none" && $row->Judul5!="none") {  ?>

<div class="row">
	<div class="col-sm-7">
		<div class="card">
    <div class="card-header">
    <hr width="100%">
  
  </div>
		  <div class="card-body">
   <div class="row ">
    <div class="col-md-3">
    <img class="img-responsive avatar-view" src="<?php print $row->image; ?>" width="230px"alt="Avatar" title="Change the avatar">
    </div>
    <div class="col-md-8">
      <h3><?php foreach($nama_user as $v) { ?>
								<?= $v->nama ?>
								<?php }?></h3>

      <ul class="list-unstyled user_data">
      <li><h4><?php print $row->Jurusan="Teknik Komputer"?"Sistem Komputer":$row->Jurusan; ?></h4>
      </li>
      <li><h6><?php print "NIDN : ".$row->Nidn; ?></h6>
      </li>
      <li><h6><?php print "SINTA ID : ".$row->Id_Sinta; ?></h6>
      </li>
      <li><h6><img src="<?php echo base_url(); ?>assets/bootstrap1/img/flagid.GIF"  width="20" height="10" ></img> <?php print $row->Bandeira; ?>
      </h6> </li>
      </ul>
    </div>
   </div>
   <h4><?php print $row->National_Rank; ?></h4><p style="line-height: 20%;">
                        <small><i>Rank in National</i></small></p><p style="line-height: 80%;">
                        <h4><?php print $row->Y3National_Rank; ?></h4></p><p style="line-height: 20%;">
                        <small><i>3 Years National Rank</i></small></p><p style="line-height: 80%;">
                        <h4><?php print $row->Affiliation_Rank; ?></h4></p><p style="line-height: 20%;">
                        <small><i>Rank in Affiliation</i></small></p><p style="line-height: 80%;">
                        <h4><?php print $row->Y3Affiliation_Rank; ?></h4></p><p style="line-height: 20%;">
                        <small><i>3 Years Affiliation Rank</i></small></p>
		  </div>
		</div>
	</div>

	<div class="col-sm-5">
	      <table class="table table-sm table-striped table-hover">
                                        <thead>
                        <tr>
                            <th class="uk-width-1-1"><i>Top 5 Papers By Citations</i> </th>
                            <th class="uk-text-center"><i>Citation</i></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    
                    <tr>
                              
                                <td>
                                    <!--<img class="journal-cover uk-align-left" src="img/cover-journal.png"/>-->
                                    <dl class="uk-description-list-line">
                                        <dt class="uk-text-primary">
                                           <a class="warlink2"  href="<?php echo $row->Link_Judul1; ?>"><?php echo $row->Judul1; ?></a>
                                        </dt>
                                       
                                        <dd><?php echo $row->Index_By1; ?></dd>
                                     
                                      </dl>
                                                                      </td>
                                <td ><dd><?php echo $row->Citation1; ?></dd></td>
                                                    </tr>
                                                    <tr>
                             
                                <td>
                                    <!--<img class="journal-cover uk-align-left" src="img/cover-journal.png"/>-->
                                    <dl class="uk-description-list-line">
                                        <dt class="uk-text-primary">
                                           <a class="warlink2" href="<?php echo $row->Link_Judul2; ?>" ><?php echo $row->Judul2; ?></a>
                                        </dt>
                                      
                                        <dd><?php echo $row->Index_By2; ?></dd>
                                     
                                      </dl>
                                                                      </td>
                                <td class="index-val uk-text-center"><dd><?php echo $row->Citation2; ?></dd></td>
                                                    </tr>
                                                    <tr>
                    
                                <td>
                                    <!--<img class="journal-cover uk-align-left" src="img/cover-journal.png"/>-->
                                    <dl class="uk-description-list-line">
                                        <dt class="uk-text-primary">
                                           <a class="warlink2" href="<?php echo $row->Link_Judul3; ?>" ><?php echo $row->Judul3; ?></a>
                                        </dt>
                                     
                                        <dd><?php echo $row->Index_By3; ?></dd>
                                     
                                      </dl>
                                                                      </td>
                                <td  class="index-val uk-text-center"><dd><?php echo $row->Citation3; ?></dd></td>
                                                    </tr>
                                                    <tr>
                       
                                <td>
                                    <!--<img class="journal-cover uk-align-left" src="img/cover-journal.png"/>-->
                                    <dl class="uk-description-list-line">
                                        <dt class="uk-text-primary">
                                           <a class="warlink2"  href="<?php echo $row->Link_Judul4; ?>" ><?php echo $row->Judul4; ?></a>
                                        </dt>
                                       
                                        <dd><?php echo $row->Index_By4; ?></dd>
                                     
                                      </dl>
                                                                      </td>
                                <td class="index-val uk-text-center"><dd><?php echo $row->Citation4; ?></dd></td>
                                                    </tr>
                                                    <tr>
                         
                                <td>
                                    <!--<img class="journal-cover uk-align-left" src="img/cover-journal.png"/>-->
                                    <dl class="uk-description-list-line">
                                        <dt class="uk-text-primary">
                                           <a class="warlink2" href="<?php echo $row->Link_Judul5; ?>" ><strong><?php echo $row->Judul5; ?></strong></a>
                                        </dt>
                                      
                                        <dd><?php echo $row->Index_By5; ?></dd>
                                     
                                      </dl>
                                                                      </td>
                                <td class="index-val uk-text-center"><dd><?php echo $row->Citation5; ?></dd></td>
                                                    </tr>

                                            </tbody>
                </table>
               
		  
	</div>
 <?php } else {?>

<br>
    <div class="row">
    <div class="col-sm-1">
    </div>
	  <div class="col-sm-8">
		<div class="card">
    <div class="card-header">
    <hr width="100%">
  
    </div>
		  <div class="card-body">
   <div class="row ">
    <div class="col-md-3">
    <img class="img-responsive avatar-view" src="<?php print $row->image; ?>" width="230px"alt="Avatar" title="Change the avatar">
    </div>
    <div class="col-md-8">
      <h3><?php foreach($nama_user as $v) { ?>
								<?= $v->nama ?>
								<?php }?></h3>

      <ul class="list-unstyled user_data">
      <li><h4><?php print $row->Jurusan="Teknik Komputer"?"Sistem Komputer":$row->Jurusan; ?></h4>
      </li>
      <li><h6><?php print "NIDN : ".$row->Nidn; ?></h6>
      </li>
      <li><h6><?php print "SINTA ID : ".$row->Id_Sinta; ?></h6>
      </li>
      <li><h6><img src="<?php echo base_url(); ?>assets/bootstrap1/img/flagid.GIF"  width="20" height="10" ></img> <?php print $row->Bandeira; ?>
      </h6> </li>
      </ul>
    </div>
   </div>
  <br> <br> <br> <br>
		  </div>
		</div>
	 </div>

   <div class="col-sm-2">
		 <div class="card border-secondary mb-3"> <hr width="80px">  
     <div class="card-body">   
     <h4><?php print $row->National_Rank; ?></h4><p style="line-height: 20%;">
                        <small><i>Rank in National</i></small></p><p style="line-height: 80%;">
                        <h4><?php print $row->Y3National_Rank; ?></h4></p><p style="line-height: 20%;">
                        <small><i>3 Years National Rank</i></small></p><p style="line-height: 80%;">
                        <h4><?php print $row->Affiliation_Rank; ?></h4></p><p style="line-height: 20%;">
                        <small><i>Rank in Affiliation</i></small></p><p style="line-height: 80%;">
                        <h4><?php print $row->Y3Affiliation_Rank; ?></h4></p><p style="line-height: 20%;">
                        <small><i>3 Years Affiliation Rank</i></small></p>
    	</div>
      </div>
      </div>
              
		  
	</div>
  

  <?php } ?>
  <?php endforeach; ?>
</div>    
               <style>
               .card{
                 margin : 0 auto;
                 float : none;
                 margin-bottom: 10px;
               }
               </style>