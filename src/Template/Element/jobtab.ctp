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
			'href'				:"<?php echo $this->webroot; ?>Jobs/print_view/"+id,
			'hideOnOverlayClick' : false,
			'overlayShow'   :   false
			
		});
       }
    
    
    function change_dirction(tabval){
          switch(tabval){
            case'main':
            document.location="<?php echo $this->webroot; ?>Jobs/add_job_report_main/<?php echo $this->params['pass'][0]; ?>";    
            break;
            case'job_data':
            document.location="<?php echo $this->webroot; ?>Jobs/welldata/<?php echo $this->params['pass'][0]; ?>"; 
            break;
            case'gyro_job_data':
            document.location="<?php echo $this->webroot; ?>Jobs/report_gyro_job_list/<?php echo $this->params['pass'][0]; ?>"; 
            break;
            case'gauge_data':
            document.location="<?php echo $this->webroot; ?>Jobs/report_gauge_job_list/<?php echo $this->params['pass'][0]; ?>"; 
            break;
	    case'action_item':
            document.location="<?php echo $this->webroot; ?>Jobs/report_job_remidial_list/<?php echo $this->params['pass'][0]; ?>"; 
            break;
            case'attachments':
            document.location="<?php echo $this->webroot; ?>Jobs/report_job_attachment_list/<?php echo $this->params['pass'][0]; ?>"; 
            break;
            case'remidialemail':
            document.location="<?php echo $this->webroot; ?>Jobs/job_remedila_email_list/<?php echo $this->params['pass'][0]; ?>";  
            break;
            case'csr':
            document.location="<?php echo $this->webroot; ?>Jobs/add_job_customer/<?php echo $this->params['pass'][0]; ?>";
            break;
            case'link':
            document.location="<?php echo $this->webroot; ?>Jobs/report_job_link_list/<?php echo $this->params['pass'][0]; ?>/<?php echo base64_encode('all'); ?>";
            break;
            case'view':
            document.location="<?php echo $this->webroot; ?>Jobs/add_jobreport_view/<?php echo $this->params['pass'][0]; ?>";  
            break;
            case'print':
            document.location="<?php echo $this->webroot; ?>Jobs/print_view/<?php echo $this->params['pass'][0]; ?>";  
            break;
         }
    }
    
</script>
    <div class="tabspanel">
    <ul>
   <?php
    
    if(($_SESSION['report_create']==$_SESSION['adminData']['AdminMaster']['id'])|| ($_SESSION['adminData']['RoleMaster']['id']==1)){ ?>     
    <li><a href="javascript:void(0);" id="main" class="selectedtab"  onclick="change_dirction('main');">Main</a></li>
    
    <li><a href="javascript:void(0);" id="job_data" onclick="change_dirction('job_data');" >Job Data</a></li>
    
    <?php
        if(isset($_SESSION['business_type'])){
        if($_SESSION['business_type']==1){ ?>
    <li><a href="javascript:void(0);" id="gyro_job_data" onclick="change_dirction('gyro_job_data');">Gyro Job Data</a></li>
    <?php }elseif($_SESSION['business_type']==2){?>
    <li><a href="javascript:void(0);" id="gauge_data" onclick="change_dirction('gauge_data');">Gauge Data</a></li>
    <?php }
    }?>
    <li><a href="javascript:void(0);" id="action_item" onclick="change_dirction('action_item');">Follow Up Action Item</a></li>
    <?php if($_SESSION['adminData']['RoleMaster']['id']==1){ ?>
    <li><a href="javascript:void(0);" id="remidialemail" onclick="change_dirction('remidialemail');">Remedial Action Email</a></li>
    <?php } ?>
    <li><a href="javascript:void(0);" id="attachments" onclick="change_dirction('attachments');">End of Well Reports and Attachments</a></li>
    <li><a href="javascript:void(0);" id="link" onclick="change_dirction('link');">Link</a></li>
    <li><a href="javascript:void(0);" id="csr" onclick="change_dirction('csr');">Customer Satisfaction Report</a></li>
    <?php } ?>
    <li><a href="javascript:void(0);" id="view" onclick="change_dirction('view');">View</a></li>
    <li><a href="javascript:void(0);" id="print" onclick="open_lightbox('<?php echo $this->params['pass'][0]; ?>');">Print Preview</a></li>
    <div class="clear"></div>
    </ul>
</div>