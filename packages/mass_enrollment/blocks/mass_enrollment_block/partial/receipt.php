<div class="text-center">
<h3><?php translate('ThankYouforthePayment', $bLanguage); ?></h3>
<a onclick="window.print()" class="btn btn-theme">
    <?php translate('PrintReceipt', $bLanguage); ?>
</a>
</div>
<div class="clearfix"></div>
<hr class="mar-tp-btm"/>
<div class="printables">
<div class="col-lg-8 col-lg-push-2 " style="border: 1px solid #ccc; background: #fff">
    <div style="padding: 20px; text-align:center" id="printOnly">
        <img alt="logo.png" src="http://divinewordgifts.informaticsinc.com/download_file/view_inline/28" style="border-radius: 0"/>
        <hr class="mar-tp-btm" style="margin-top:10px;"/>
    </div>
    <div style="padding: 10px">
        <?php echo str_replace('<p>&nbsp;</p>', '<br/>', $html); ?>
    </div>
</div>
</div>
<style>
#printOnly {
   display : none;
}
@media print {
    #printOnly {
       display : block;
    }
    html, body {
    height:100%; 
    margin: 0 !important; 
    padding: 0 !important;
    overflow: hidden;
  }
body * {
      visibility: hidden;
      margin:0; padding:0;
   }
   .printables * { 
      visibility: visible;
      
   }
   .printables {
   position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;

    background-color: #ffffff;
   }
}
</style>