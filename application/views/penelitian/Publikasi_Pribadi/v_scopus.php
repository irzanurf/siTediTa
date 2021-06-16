
<hr>
<hr>
<div class="card"><div class="card-body">
              <div class="row">
              <div class="col-sm-3">
          <form action="<?= base_url('publikasi/Pribadi/cariScopus_penelitian');?>" method="post">
   <input id="de" type="text" name="cari" class="form-control form-control-lg"
                autocomplete="off" autofocus></div>
    <div class="col-lg-1" >
    <button  type="submit" value="cari"  name="submit" class="btn btn-secondary" type="button">
    <i class="fa fa-search"></i></button>
              </div>
</tr></form>  
</form>
</div>
        
                    <table id="datatable-buttons" class="table table-sm table-striped table-hover data">
                      <thead>
                      <tr>
                            <th class="uk-text-center">No</th>
                            <th class="uk-width-1-1">Publications</th>
                            
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
$no = 1;

foreach($tampilscp as $i) : 

?>

                                                    <tr>
                                <td ><?=$no++?></td>
                                <td>
                                    <!--<img class="journal-cover uk-align-left" src="img/cover-journal.png"/>-->
                                    <dl align="left">
                                        <dt class="uk-text-primary" >
                                           <a href="<?php echo $i->Index_By; ?>" > <?php echo $i->Judul; ?></a>
                                        </dt>
                                         <dd > <?php echo $i->Link_Judul; ?></dd>
                                      </dl>
                                      
                                </td>
                              
                            </tr>
                      
<?php endforeach; ?>
                                            </tbody><script type="text/javascript">
	$(document).ready(function(){
		$('.data').DataTable();
	});
</script>
</table>
                  </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div> </div></div>
    
