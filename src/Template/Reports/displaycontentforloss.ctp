<?php

 switch($type){
		
	case'incident_loss':
	      echo 'incident_loss~';		    
	?>
	    <label><?PHP echo __("Category:");?></label>
	           <?php if(count($incidentCategoryDetail)>0){ ?>
	          <select id="incident_category" name="incident_category" onchange="assign_id('incident_category');">	
                          <option value="0">Select One</option>
		      <?php for($i=0;$i<count($incidentCategoryDetail);$i++){?>
		         <option value="<?php echo $incidentCategoryDetail[$i]['IncidentCategory']['id']; ?>"><?php echo $incidentCategoryDetail[$i]['IncidentCategory']['type']; ?></option>
		      <?php } ?>
	           </select>
		  <?php }else{ ?>
		   <lable>No Category </lable>
		  <?php } ?>
	<div class="clearflds"></div>
			    
        <?php break;
 
        case'incident_category':
	       echo  'incident_category~';		        
	 ?>
	     <label><?PHP echo __("Sub Category:");?></label>
	            <?php if(count($incidentSubCategoryDetail)>0){ ?>
	            <select id="incident_sub_category" name="incident_sub_category" >
			     <option value="0">Select One</option>
                             <?php for($i=0;$i<count($incidentSubCategoryDetail);$i++){?>
			     <option value="<?php echo $incidentSubCategoryDetail[$i]['IncidentSubCategory']['id']; ?>" ><?php echo $incidentSubCategoryDetail[$i]['IncidentSubCategory']['type']; ?></option>
		    	     <?php } ?>
		     </select>
		    <?php }else{ ?>
		    <lable>No Sub Category </lable>
		    <?php } ?>
              <div class="clearflds"></div>
         <?php 
			    
        break;	
   }
?>