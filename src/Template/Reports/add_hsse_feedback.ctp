 <script language="javascript" type="text/javascript">

 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
 
	     
function add_report_feedback()
{
             
  
            var client_summary = jQuery.trim(document.getElementById('client_summary').value);
	
	    var close_date = jQuery.trim(document.getElementById('close_date').value);
	    var currentdate='<?php echo date('m-d-Y'); ?>';
	    if(client_summary=='' && close_date==''){
	       document.getElementById('loader').innerHTML='<font color="red">Please insert data at least one field</font>';
	       return false;
	    }
	    
	    if(close_date!=''){
	       if(close_date>currentdate){
		    document.getElementById('close_date').focus();
	            document.getElementById('close_date_error').innerHTML='Close date always less than current date';
	            return false;
		    
	       }else{
		    document.getElementById('close_date_error').innerHTML='';
		    
	       }
	    }
	    
            var dataStr = $("#add_report_client_data_form").serialize();
	    var report_id='<?php echo $report_id; ?>';
            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/ajax-loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/clientfeedbackprocess/",
			  data:"data="+dataStr+"&report_id="+report_id,
			  success: function(res)
			  {
		         
		          if(res=='Add'){
				   document.getElementById('loader').innerHTML='<font color="green">Client Feedback Data Added Successfully</font>';
			     }else if(res=='Update'){
				   document.getElementById('loader').innerHTML='<font color="green">Client Feedback Update Successfully</font>';
				   
                             }
                            
                            
                         }
		 
	});
	

	return false;
 
}


 
 </script>

 <aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 
 <section>
 <?php

         echo $this->Element('hssetab');
   
  ?>
     <script language="javascript" type="text/javascript">
     
      
     $(document).ready(function() {
	   $("#main").removeClass("selectedtab");
	   $("#clientdata").removeClass("selectedtab");
	   $("#personnel").removeClass("selectedtab");
	   $("#incident").removeClass("selectedtab");
	   $("#investigation").removeClass("selectedtab");
	   $("#investigationdata").removeClass("selectedtab");
	   $("#remidialaction").removeClass("selectedtab");
	   $("#attachment").removeClass("selectedtab");
	   $("#clientfeedback").addClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });
     
     
 </script>   
   <?php echo $this->Element('client_feedback_element'); ?>
</section>
