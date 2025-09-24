<script type="text/javascript" src="<?php echo $this->webroot ?>js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/fancybox/jquery.fancybox-1.3.4.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script  language="javascript" type="text/javascript">
      function open_lightbox()
       {
	jQuery.fancybox({
			'autoScale': true,
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'href'				:"<?php echo $this->webroot; ?>Certifications/print_view/",
			'hideOnOverlayClick' : false,
			'overlayShow'   :   false
			
		});
       }
    
    
    function change_dirction(tabval){
          switch(tabval){
            case'main':
            document.location="<?php echo $this->webroot; ?>Certifications/certification_main_list";    
            break;
            case'attachment':
            document.location="<?php echo $this->webroot; ?>Certifications/certification_attach_list"; 
            break;
            case'rel':
            document.location="<?php echo $this->webroot; ?>Certifications/cerficate_email_list"; 
            break;
            case'view':
            document.location="<?php echo $this->webroot; ?>Certifications/add_certificate_view";  
            break;
           }
    }
    
</script>
    <div class="tabspanel">
    <ul>
    <?php if($_SESSION['adminData']['RoleMaster']['id']==1){ ?>  
    <li><a href="javascript:void(0);" id="main" class="selectedtab"  onclick="change_dirction('main');">Main</a></li>
    <li><a href="javascript:void(0);" id="attachments" onclick="change_dirction('attachment');">Attachment</a></li>
    <li><a href="javascript:void(0);" id="attachments" onclick="change_dirction('rel');">Reminder Email List</a></li>
    <?php } ?>
    <li><a href="javascript:void(0);" id="view" onclick="change_dirction('view');">View</a></li>
    <li><a href="javascript:void(0);" id="print" onclick="open_lightbox();">Print Preview</a></li>
    <div class="clear"></div>
    </ul>
</div>