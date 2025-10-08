<script type="text/javascript" src="<?php echo $this->webroot ?>js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/fancybox/jquery.fancybox-1.3.4.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script  language="javascript" type="text/javascript">
    
         function redirect_report(){
              var report_list_id=document.getElementById('report_list').value;
              document.location="<?php echo $this->webroot; ?>Sqreports/add_sq_report_main/"+report_list_id;    
     }
      function open_lightbox(id)
       {
	jQuery.fancybox({
			'autoScale': true,
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'href'				:"<?php echo $this->webroot; ?>Sqreports/print_view/"+id,
			'hideOnOverlayClick' : false,
			'overlayShow'   :   false
			
		});
       }
    
    
    function change_dirction(tabval){
          switch(tabval){
            case'main':
            document.location="<?php echo $this->webroot; ?>Sqreports/add_sq_report_main/<?php echo $this->params['pass'][0]; ?>";    
            break;
            case'welldata':
            document.location="<?php echo $this->webroot; ?>Sqreports/welldata/<?php echo $this->params['pass'][0]; ?>";
            break;
            case'personnel':
            document.location="<?php echo $this->webroot; ?>Sqreports/report_sq_perssonel_list/<?php echo $this->params['pass'][0]; ?>"; 
            break;
            case'incident':
            document.location="<?php echo $this->webroot; ?>Sqreports/report_sq_incident_list/<?php echo $this->params['pass'][0]; ?>";  
            break;
            case'investigation':
            document.location="<?php echo $this->webroot; ?>Sqreports/add_sq_investigation/<?php echo $this->params['pass'][0]; ?>";
            break;
            case'remidialaction':
            document.location="<?php echo $this->webroot; ?>Sqreports/report_sq_remidial_list/<?php echo $this->params['pass'][0]; ?>";  
            break;
            case 'dataanalysis':
            document.location="<?php echo $this->webroot; ?>Sqreports/report_sq_investigation_data_list/<?php echo $this->params['pass'][0]; ?>";      
            break;
            case'remidialemail':
            document.location="<?php echo $this->webroot; ?>Sqreports/report_sq_remedila_email_list/<?php echo $this->params['pass'][0]; ?>";  
            break;
            case'attachment':
            document.location="<?php echo $this->webroot; ?>Sqreports/report_sq_attachment_list/<?php echo $this->params['pass'][0]; ?>";
            break;
            case'link':
            document.location="<?php echo $this->webroot; ?>Sqreports/report_sq_link_list/<?php echo $this->params['pass'][0]; ?>/<?php echo base64_encode('all'); ?>";
            break;
            case'clientfeedback':
            document.location="<?php echo $this->webroot; ?>Sqreports/add_sq_feedback/<?php echo $this->params['pass'][0]; ?>";  
            break;
            case'view':
            document.location="<?php echo $this->webroot; ?>Sqreports/add_sqreport_view/<?php echo $this->params['pass'][0]; ?>";  
            break;
         }
    }
    
</script>
    <div class="tabspanel">
    <ul>
    <?php   if(($_SESSION['report_create']==$_SESSION['adminData']['AdminMaster']['id'])|| ($_SESSION['adminData']['RoleMaster']['id']==1)){ ?>      
    <li><a href="javascript:void(0);" id="main" class="selectedtab"  onclick="change_dirction('main');">Main</a></li>
    <li><a href="javascript:void(0);" id="welldata" onclick="change_dirction('welldata');" >Well Data</a></li>
    <li><a href="javascript:void(0);" id="personnel" onclick="change_dirction('personnel');">Incident - Personnel</a></li>
    <li><a href="javascript:void(0);" id="incident" onclick="change_dirction('incident');">Incident</a></li>
    <li><a href="javascript:void(0);" id="investigation"  onclick="change_dirction('investigation');">Investigation</a></li>
    <li><a href="javascript:void(0);" id="remidialaction" onclick="change_dirction('remidialaction');">Remedial Action</a></li>
    <?php if($_SESSION['adminData']['RoleMaster']['id']==1){ ?>
    <li><a href="javascript:void(0);" id="remidialemail" onclick="change_dirction('remidialemail');">Remedial Action Email</a></li>
    <?php } ?>
    <li><a href="javascript:void(0);" id="investigationdata" onclick="change_dirction('dataanalysis');" >Incident Investigation</a></li>
    <li><a href="javascript:void(0);" id="attachment" onclick="change_dirction('attachment');">Attachment</a></li> 
    <li><a href="javascript:void(0);" id="link" onclick="change_dirction('link');">Link</a></li>
     <?php if($_SESSION['clientfeed_back']=='yes'){ ?>
            <li><a href="javascript:void(0);" id="clientfeedback" onclick="change_dirction('clientfeedback');">Client Feedback</a></li>
     <?php  }} ?>
    <li><a href="javascript:void(0);" id="view" onclick="change_dirction('view');">View</a></li>
    <li><a href="javascript:void(0);" id="print" onclick="open_lightbox('<?php echo $this->params['pass'][0]; ?>');">Print Preview</a></li>
    <div class="clear"></div>
    </ul>
</div> 
