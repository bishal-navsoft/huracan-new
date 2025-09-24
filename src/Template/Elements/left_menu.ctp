<script  language="javascript" type="text/javascript">
    $(document).ready(function() {
		$("#main").removeClass("selectedtab");
	        $("#job_data").removeClass("selectedtab");
	        $("#gyro_job_data").removeClass("selectedtab");
	        $("#gauge_data").removeClass("selectedtab");
	        $("#action_item").addClass("selectedtab");
	        $("#attachments").removeClass("selectedtab");
	        $("#link").removeClass("selectedtab");
	        $("#csr").removeClass("selectedtab");
	        $("#view").removeClass("selectedtab");
                $("#print").removeClass("selectedtab");
	      
	   });
    
    
    
    function change_main_dirction(tabval){
          switch(tabval){
            case'createhsse':
            document.location="<?php echo $this->webroot; ?>Reports/add_report_main";    
            break;
            case'listhsse':
            document.location="<?php echo $this->webroot; ?>Reports/report_hsse_list";
            break;
	    case'sq_main':
            document.location="<?php echo $this->webroot; ?>Sqreports/add_sq_report_main";
            break;
	    case'sq_list':
            document.location="<?php echo $this->webroot; ?>Sqreports/report_sq_list";
            break;
	    case'jn_main':
               document.location="<?php echo $this->webroot; ?>Jrns/add_jn_report_main";
            break;
	    case'jn_list':
            document.location="<?php echo $this->webroot; ?>Jrns/report_jrn_list";
            break;
	    case'jnr_created':
            document.location="<?php echo $this->webroot; ?>Jrns/add_jn_report_main";
            break;
	    case'jnr_list':
            document.location="<?php echo $this->webroot; ?>Jrns/report_jrn_list";
            break;
	    case'audit_created':
            document.location="<?php echo $this->webroot; ?>Audits/audit_report_main";
            break;
	    case'audit_list':
            document.location="<?php echo $this->webroot; ?>Audits/report_audit_list";
            break;
	    case'documentation_created':
            document.location="<?php echo $this->webroot; ?>Documents/add_document_report_main";
            break;
	    case'documentation_list':
            document.location="<?php echo $this->webroot; ?>Documents/document_main_list";
            break;
	    case'job_created':
            document.location="<?php echo $this->webroot; ?>Jobs/add_job_report_main";
            break;
	    case'job_list':
            document.location="<?php echo $this->webroot; ?>Jobs/report_job_list";
            break;
	    case'lesson_created':
            document.location="<?php echo $this->webroot; ?>Lessons/add_lesson_report_main";
            break;
	    case'lesson_list':
            document.location="<?php echo $this->webroot; ?>Lessons/lesson_main_list";
            break;
	    case'suggestion_created':
            document.location="<?php echo $this->webroot; ?>Suggestions/add_suggestion_report_main";
            break;
	    case'suggestion_list':
            document.location="<?php echo $this->webroot; ?>Suggestions/suggestion_main_list";
            break;
	    case'certification_created':
            document.location="<?php echo $this->webroot; ?>Certifications/add_certification_main";
            break;
	    case'certification_list':
            document.location="<?php echo $this->webroot; ?>Certifications/certification_main_list";
            break;
	    case'certification_list_view':
	    document.location="<?php echo $this->webroot; ?>Certifications/add_certificate_view"; 	
	    break;
	    case'jha_created':
            document.location="<?php echo $this->webroot; ?>Jhas/add_jha_report_main";
            break;
	    case'jha_list':
            document.location="<?php echo $this->webroot; ?>Jhas/jha_main_list";
            break;
          }
        
        
    }
    
</script>

<ul>
<li><a href="#"  <?php if($this->params['controller']=='Reports'){ ?> class="selectlink" <?php } ?>  id="listhsse"  onclick="change_main_dirction('listhsse');"  >HSSE Report</a>
<!--sub menu -->
<div class="leftmenu_box">
<div class="subdrop">
<ul>
 <?php if($is_add==1){ ?>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Report'){ ?> class="selectlink" <?php } ?>   onclick="change_main_dirction('createhsse');">Create Report</a></li>
<?php } ?>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Report'){ ?> class="selectlink" <?php } ?>  onclick="change_main_dirction('listhsse');">List</a></li>
</ul>
</div>
</div>
<!--sub menu -->
</li>
<li><a href="javascript:void(0);" id="sq_list"  <?php if($this->params['controller']=='Sqreports'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('sq_list');">SQ Report</a>
<!--sub menu -->
<div class="leftmenu_box">
<div class="subdrop">
<ul>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Sqreports'){ ?> class="selectlink" <?php } ?>  onclick="change_main_dirction('sq_main');">Create Report</a></li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Sqreports'){ ?> class="selectlink" <?php } ?>  onclick="change_main_dirction('sq_list');">List</a></li>
</ul>
</div>
</div>
<!--sub menu -->
</li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Jrns'){ ?> class="selectlink" <?php } ?> >Journey Management</a>
<!--sub menu -->
<div class="leftmenu_box">
<div class="subdrop">
<ul>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Jrns'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('jn_main');">Create Report</a></li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Jrns'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('jn_list');">List</a></li>
</ul>
</div>
</div>
<!--sub menu -->
</li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Audits'){ ?> class="selectlink" <?php } ?> >Audit / Inspection Report</a>
<!--sub menu -->
<div class="leftmenu_box">
<div class="subdrop">
<ul>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Audits'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('audit_created');">Create Report</a></li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Audits'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('audit_list');">List</a></li>
</ul>
</div>
</div>
<!--sub menu -->
</li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Jobs'){ ?> class="selectlink" <?php } ?> >Job Report</a>
<!--sub menu -->
<div class="leftmenu_box">
<div class="subdrop">
<ul>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Jobs'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('job_created');">Create Report</a></li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Jobs'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('job_list');">List</a></li>
</ul>
</div>
</div>
<!--sub menu -->
</li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Documents'){ ?> class="selectlink" <?php } ?> >Documentation Report</a>
<!--sub menu -->
<div class="leftmenu_box">
<div class="subdrop">
<ul>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Documents'){ ?> class="selectlink" <?php } ?>  onclick="change_main_dirction('documentation_created');">Create Report</a></li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Documents'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('documentation_list');">List</a></li>
</ul>
</div>
</div>
<!--sub menu -->
</li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Lessons'){ ?> class="selectlink" <?php } ?> >Best Practice / Lesson Learnt Report</a>
<div class="leftmenu_box">
<div class="subdrop">
<ul>
<li><a href="javascript:void(0);"  <?php if($this->params['controller']=='Lessons'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('lesson_created');">Create Report</a></li>
<li><a href="javascript:void(0);"  <?php if($this->params['controller']=='Lessons'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('lesson_list');">List</a></li>
</ul>
</div>
</div>
<!--sub menu -->
</li>

<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Certifications'){ ?> class="selectlink" <?php } ?> >Certification Report</a>
<!--sub menu -->
<div class="leftmenu_box">
<div class="subdrop">
<ul>
<?php if($_SESSION['adminData']['RoleMaster']['id']==1){ ?>    
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Certifications'){ ?> class="selectlink" <?php } ?>  onclick="change_main_dirction('certification_created');">Create Report</a></li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Certifications'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('certification_list');">List</a></li>
<?php }else{ ?>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Certifications'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('certification_list_view');">View</a></li>
<?php } ?>

</ul>
</div>
</div>
<!--sub menu -->
</li>

<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Suggestions'){ ?> class="selectlink" <?php } ?> >Suggestion Report</a>
<div class="leftmenu_box">
<div class="subdrop">
<ul>
<li><a href="javascript:void(0);" <a href="javascript:void(0);" <?php if($this->params['controller']=='Suggestions'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('suggestion_created');">Create Report</a></li>
<li><a href="javascript:void(0);" <a href="javascript:void(0);" <?php if($this->params['controller']=='Suggestions'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('suggestion_list');">List</a></li>
</ul>
</div>
</div>
<!--sub menu -->
</li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Jhas'){ ?> class="selectlink" <?php } ?> >Job Hazard Analysis Report</a>
<div class="leftmenu_box">
<div class="subdrop">
<ul>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Jhas'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('jha_created');">Create Report</a></li>
<li><a href="javascript:void(0);" <?php if($this->params['controller']=='Jhas'){ ?> class="selectlink" <?php } ?> onclick="change_main_dirction('jha_list');">List</a></li>
</ul>
</div>
</div>
<!--sub menu -->
</li>
</ul>