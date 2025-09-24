<script type="text/javascript" src="<?php echo $this->webroot ?>js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/fancybox/jquery.fancybox-1.3.4.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script  language="javascript" type="text/javascript">
      function open_lightbox(id)
       {
	jQuery.fancybox({
			'autoScale': true,
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'href'				:"<?php echo $this->webroot; ?>Jrns/print_view/"+id,
			'hideOnOverlayClick' : false,
			'overlayShow'   :   false
			
		});
       }
    
    
    function change_dirction(tabval){
          switch(tabval){
            case'main':
            document.location="<?php echo $this->webroot; ?>Jrns/add_jn_report_main/<?php echo $this->params['pass'][0]; ?>";    
            break;
            case'personnel':
            document.location="<?php echo $this->webroot; ?>Jrns/report_jn_perssonel_list/<?php echo $this->params['pass'][0]; ?>"; 
            break;
	    case'checklist':
            document.location="<?php echo $this->webroot; ?>Jrns/add_jn_checklist/<?php echo $this->params['pass'][0]; ?>"; 
            break;
            case'attachments':
            document.location="<?php echo $this->webroot; ?>Jrns/report_jn_attachment_list/<?php echo $this->params['pass'][0]; ?>";
            break;
            case'link':
            document.location="<?php echo $this->webroot; ?>Jrns/report_jn_link_list/<?php echo $this->params['pass'][0]; ?>/<?php echo base64_encode('all'); ?>";
            break;
            case'view':
            document.location="<?php echo $this->webroot; ?>Jrns/add_jrnreport_view/<?php echo $this->params['pass'][0]; ?>";  
            break;
         }
    }
    
</script>
    <div class="tabspanel">
    <ul>
     <?php if(($_SESSION['report_create']==$_SESSION['adminData']['AdminMaster']['id'])|| ($_SESSION['adminData']['RoleMaster']['id']==1)){ ?>  	 
    <li><a href="javascript:void(0);" id="main" class="selectedtab"  onclick="change_dirction('main');">Main</a></li>
    <li><a href="javascript:void(0);" id="personnel" onclick="change_dirction('personnel');" >Personnel</a></li>
    <li><a href="javascript:void(0);" id="checklist" onclick="change_dirction('checklist');">Checklist</a></li>
    <li><a href="javascript:void(0);" id="attachments" onclick="change_dirction('attachments');">Attachments</a></li>
    <li><a href="javascript:void(0);" id="link" onclick="change_dirction('link');">Link</a></li>
    <?php } ?>
    <li><a href="javascript:void(0);" id="view" onclick="change_dirction('view');">View</a></li>
    <li><a href="javascript:void(0);" id="print" onclick="open_lightbox('<?php echo $this->params['pass'][0]; ?>');">Print Preview</a></li>
    <div class="clear"></div>
    </ul>
</div> 
