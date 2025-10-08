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
			'href'				:"<?php echo $this->webroot; ?>Lessons/print_view/"+id,
			'hideOnOverlayClick' : false,
			'overlayShow'   :   false
			
		});
       }
    
    
    function change_dirction(tabval){
          switch(tabval){
            case'main':
            document.location="<?php echo $this->webroot; ?>Lessons/add_lesson_report_main/<?php echo $this->params['pass'][0]; ?>";   
            break;
            case'revalidation':
            document.location="<?php echo $this->webroot; ?>Lessons/lesson_email_list/<?php echo $this->params['pass'][0]; ?>";
            break;
            case'attachment':
            document.location="<?php echo $this->webroot; ?>Lessons/report_lesson_attachment_list/<?php echo $this->params['pass'][0]; ?>"; 
            break;
            case'link':
            document.location="<?php echo $this->webroot; ?>Lessons/report_lesson_link_list/<?php echo $this->params['pass'][0]; ?>/<?php echo base64_encode('all'); ?>";
            break;
            case'view':
            document.location="<?php echo $this->webroot; ?>Lessons/add_lsreport_view/<?php echo $this->params['pass'][0]; ?>";  
            break;
           
         }
    }
    
</script>
    <div class="tabspanel">
    <ul>
     <?php
    
    if(($_SESSION['report_create']==$_SESSION['adminData']['AdminMaster']['id'])|| ($_SESSION['adminData']['RoleMaster']['id']==1)){ ?>   
    <li><a href="javascript:void(0);" id="main" class="selectedtab"  onclick="change_dirction('main');">Main</a></li>
    <?php if($_SESSION['adminData']['RoleMaster']['id']==1){ ?>
       <li><a href="javascript:void(0);" id="revalidation" onclick="change_dirction('revalidation');">Revalidation Email</a></li>
    <?php } ?>
    <li><a href="javascript:void(0);" id="attachments" onclick="change_dirction('attachment');">Attachment</a></li>
    <li><a href="javascript:void(0);" id="link" onclick="change_dirction('link');">Link</a></li>
    
    <?php } ?>
    <li><a href="javascript:void(0);" id="view" onclick="change_dirction('view');">View</a></li>
    <li><a href="javascript:void(0);" id="print" onclick="open_lightbox('<?php echo $this->params['pass'][0]; ?>');">Print Preview</a></li>
    <div class="clear"></div>
    </ul>
</div>