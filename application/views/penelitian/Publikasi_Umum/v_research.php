
  
<hr>
<hr>
<div class="card"><div class="card-body">
              <div class="row">
              <div class="col-sm-3">
          <form action="<?= base_url('publikasi/Umum/cariResearch_pengabdian');?>" method="post">
   <input id="de" type="text" name="cari" class="form-control form-control-lg"
                autocomplete="off" autofocus></div>
    <div class="col-lg-1" >
    <button  type="submit" value="cari"  name="submit" class="btn btn-secondary" type="button">
    <i class="fa fa-search"></i></button>
              </div>
</tr></form>  
</form>
</div>               


                    <table id="datatable-buttons" class="table table-sm table-striped table-hover">
                      <thead>
                      <tr>
                      <th class="uk-text-center">No</th>
                            <th class="uk-width-8-10">Research Title</th>
                            <th class="uk-width-1-1">Finding Sponsor</th>
                            
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
$no = 1;

foreach($tampilrsc as $i) : 

?>
<tr>
                                <td class="index-val uk-text-center"><?=$no++?></td>
                                <td>
                                    <!--<img class="journal-cover uk-align-left" src="img/cover-journal.png"/>-->
                                    <dl align="left"  class="uk-description-list-line">
                                        <dt class="uk-text-primary">
                                           <a href="" ><?php echo $i->A; ?></a>
                                        </dt>
                                        <dd><?php echo $i->B; ?></dd>
                                        <dd><?php echo $i->C; ?></dd>
                                        <dd><?php echo $i->D; ?></dd>
                                        <dd><?php echo $i->F; ?></dd>
                                      </dl>
                                                                      </td>
                                <td class="index-val uk-text-center"><dd><?php echo $i->E; ?></dd></td>
                              
                            </tr>
                      
<?php endforeach; ?>
                                            </tbody>
</table>
                  </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
        </div>
        </div>