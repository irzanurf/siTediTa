
       <style>
         .verticalLine {
  border-left:  4px grey;
  border-left-style: double;
}    
.warlink{
  color:black;
}.warlink2{
  color:grey;
}
.uk-width-1-1{
  text-align:left;
}
</style>
          	<div class="col-sm-12">
          <div class="row">
          <div class="col-sm-2 "> 
            <button class="btn btn-outline-info"> <span class="count_top"><i class="fa fa-user"></i><a class="warlink" href="<?= base_url('publikasi/Pribadi/overview_pengabdian');?>"> Overview</a></span></button>
              
              </div>
            <div class="col-sm-2 verticalLine">
            <button class="btn btn-outline-secondary"> <span class="count_top"><i class="fa fa-file-text nav_icon"></i><a class="warlink"  href="<?= base_url('publikasi/Pribadi/book_pengabdian');?>"> Books</a></span></button>
              <div><span class="count_bottom"><i class="green"><?= $hitungbook;?> </i> Records</span></div>
              </div>
            <div class="col-sm-2 verticalLine">
            <button class="btn btn-outline-secondary"><span class="count_top"><i class="fa fa-file-text nav_icon"></i><a class="warlink" href="<?= base_url('publikasi/Pribadi/IPR_pengabdian');?>"> IPR</a></span></button>
            <div><span class="count_bottom"><i class="green"><?= $hitungipr;?> </i> Records</span></div>
            </div>
            <div class="col-sm-2 verticalLine">
            <button class="btn btn-outline-secondary"> <span class="count_top"><i class="fa fa-file-text nav_icon"></i><a class="warlink"  href="<?= base_url('publikasi/Pribadi/research_pengabdian');?>"> Research</a></span></button>
            <div><span class="count_bottom"><i class="green"><?= $hitungrsc;?> </i> Records</span></div>
             </div>
            <div class="col-sm-2 verticalLine">
            <button class="btn btn-outline-secondary"> <span class="count_top"><i class="fa fa-file-text nav_icon"></i><a class="warlink"  href="<?= base_url('publikasi/Pribadi/scopus_pengabdian');?>"> Scopus</a></span></button>
            <div><span class="count_bottom"><i class="green"><?= $hitungscp;?> </i> Records</span></div>
            </div>
            <div class="col-sm-2 verticalLine">
            <button class="btn btn-outline-secondary">  <span class="count_top"><i class="fa fa-file-text nav_icon"></i><a class="warlink"  href="<?= base_url('publikasi/Pribadi/gscholar_pengabdian');?>"> Google Scholar</a></span></button>
            <div><span class="count_bottom"><i class="green"><?= $hitunggs;?> </i> Records</span></div>
            </div>
            </div>
           
          </div>

   