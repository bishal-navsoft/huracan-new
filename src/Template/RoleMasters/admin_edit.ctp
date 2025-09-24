<script language="javascript" type="text/javascript">
		function checkuncheck(gv){
		     		var split_val=gv.split('_');
				if(document.getElementById(split_val[0]+'_all').checked==true){
					document.getElementById(split_val[0]+'_view').checked=true;	
					document.getElementById(split_val[0]+'_view').value=split_val[0]+'_view_1_<?php echo $roll_id ?>';	
				  }else if(document.getElementById(split_val[0]+'_all').checked==false){
					document.getElementById(split_val[0]+'_view').checked=false;		
					document.getElementById(split_val[0]+'_view').value=split_val[0]+'_view_0_<?php echo $roll_id ?>';	
					
				  }
				if(document.getElementById(split_val[0]+'_all').checked==true){
					document.getElementById(split_val[0]+'_add').checked=true;	
					document.getElementById(split_val[0]+'_add').value=split_val[0]+'_add_1_<?php echo $roll_id ?>';	
				  }else if(document.getElementById(split_val[0]+'_all').checked==false){
					document.getElementById(split_val[0]+'_add').checked=false;		
					document.getElementById(split_val[0]+'_add').value=split_val[0]+'_add_0_<?php echo $roll_id ?>';	
				 }
				 if(document.getElementById(split_val[0]+'_all').checked==true){
					document.getElementById(split_val[0]+'_edit').checked=true;	
					document.getElementById(split_val[0]+'_edit').value=split_val[0]+'_edit_1_<?php echo $roll_id ?>';	
				  }else if(document.getElementById(split_val[0]+'_all').checked==false){
					document.getElementById(split_val[0]+'_edit').checked=false;		
					document.getElementById(split_val[0]+'_edit').value=split_val[0]+'_edit_0_<?php echo $roll_id ?>';	
				 }
				 if(document.getElementById(split_val[0]+'_all').checked==true){
					document.getElementById(split_val[0]+'_block').checked=true;	
					document.getElementById(split_val[0]+'_block').value=split_val[0]+'_block_1_<?php echo $roll_id ?>';	
				  }else if(document.getElementById(split_val[0]+'_all').checked==false){
					document.getElementById(split_val[0]+'_block').checked=false;		
					document.getElementById(split_val[0]+'_block').value=split_val[0]+'_block_0_<?php echo $roll_id ?>';	
				 }
				 if(document.getElementById(split_val[0]+'_all').checked==true){
					document.getElementById(split_val[0]+'_delete').checked=true;	
					document.getElementById(split_val[0]+'_delete').value=split_val[0]+'_delete_1_<?php echo $roll_id ?>';	
				  }else if(document.getElementById(split_val[0]+'_all').checked==false){
					document.getElementById(split_val[0]+'_delete').checked=false;		
					document.getElementById(split_val[0]+'_delete').value=split_val[0]+'_delete_0_<?php echo $roll_id ?>';	
				 }
				   /* if(document.getElementById(split_val[0]+'_add').checked==true){
					document.getElementById(split_val[0]+'_add').value=split_val[0].+'_add_1';	
				  }else if(document.getElementById(split_val[0]+'_add').checked==false){
					document.getElementById(split_val[0]+'_add').value=split_val[0].+'_add_0';	
					
				  }
				  */
				
		}
		function getRollMasterData(){
			var  rollIDHolder=new Array();	
			var checkboxholder=document.getElementById('per_holder').getElementsByTagName("input");
			var rollname=document.getElementById('role_name').value;
			var description=document.getElementById('description').value;
			if(rollname==''){
			   document.getElementById('role_name').focus();
			   document.getElementById('role_name_error').innerHTML='Please enter name';
			   return false;
			}else{
			   document.getElementById('role_name_error').innerHTML='';
			  	
			}
			if(description==''){
			   document.getElementById('description').focus();
			   document.getElementById('description_error').innerHTML='Please enter description';
			   return false;
			}else{
			   document.getElementById('description_error').innerHTML='';	
			}
			
			for(var i=0; i<checkboxholder.length;i++){
				if(checkboxholder[i].type=='checkbox'){
						
					var splitval=checkboxholder[i].id.split("_");
				        if(checkboxholder[i].checked==true){
						
						
						document.getElementById(checkboxholder[i].id).value=splitval[0]+'_'+splitval[1]+'_1_<?php echo $roll_id ?>';
				   	}else if(checkboxholder[i].checked==false){
						 document.getElementById(checkboxholder[i].id).value=splitval[0]+'_'+splitval[1]+'_0_<?php echo $roll_id ?>';
					}
									
					rollIDHolder.push(checkboxholder[i].value);
				
				}
				
			}
			rollIDHolder.toString();
		
			var rootpath='<?php echo $this->webroot; ?>';
			document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';
			
			            $.ajax({
			               type: "POST",
			               url: rootpath+"RoleMasters/rollprocess/",
			               data:"idlist="+rollIDHolder+"&rollname="+rollname+"&description="+description+"&rollmasterID=<?php echo $roll_id; ?>",
			               success: function(res)
			                {
						
				         var resD=res.split("~");
					 if(resD[0]=='ok'){
						document.getElementById('loader').innerHTML='<font color="green">RolePermission Data Added Successfully</font>';
						document.location='<?php echo $this->webroot; ?>RoleMasters/admin_edit/'+resD[1];
					 }else if(resD[0]=='avl'){
						document.getElementById('loader').innerHTML='<font color="red">Name already exist</font>';
						document.getElementById('role_name').focus();
					 }
					                        
                                         }  
		 
	                             });
				    
				      
			
		 		
		}

function redirectProject(){
	  document.location="<?php echo $this->webroot; ?>RoleMasters/list_roles";
}
		
		
</script>

<div class="clearflds"></div>
<div id="body_container">
<div class="wrapall clearfix">
<section class="adminwrap">
<div class="topadmin_heading clearfix">
<h2>Add New Administrator Role</h2>

</div>
<h2>General<span class="textcmpul">Field marked with * are compulsory  </span></h2>
<?php echo $this->Form->create('RoleMaster', array('name'=>'rollmasterform','id'=>'rollmasterform','method'=>'post','class'=>'adminform'));?>
<div class="clearflds"></div>
<label>Name<span>*</span></label>
<?PHP echo $this->Form->input('role_name', array('id'=>'role_name','type' =>'text', 'value'=>$roleName, 'label' => false,'div' => false)); ?><span class="textcmpul" id="role_name_error"></span>
<div class="clearflds"></div>
<label>Description<span>*</span></label> <?PHP echo $this->Form->input('description', array('type' =>'textarea','id'=>'description', 'escape' => false, 'value'=>$roleDescription, 'label' => false,'div' => false)); ?><span class="textcmpul" id="description_error"></span>
<div class="clearflds"></div>
<label>Assign</label>
<div class="admintable_wrap">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="per_holder" >
    <tr>
      <td width="35%" class="topcell">Menu's Name</td>
      <td width="10%" class="topcell tcenter">All</td>
      <td width="12%" class="topcell tcenter">View</td>
      <td width="12%" class="topcell tcenter">Add</td>
      <td width="11%" class="topcell tcenter">Edit</td>
      <td width="11%" class="topcell tcenter">Block</td>
      <td width="9%" class="topcell tcenter">Delete</td>
    </tr>
    <?php for($p=0;$p<count($roleGroupMenus);$p++){ ?>
    
     <tr class="sunheadingbg">
      <td align="left" valign="middle" class="subcellheading"><?php echo $roleGroupMenus[$p]['AdminMenu']['menu_name']; ?></td>
		<td align="center" valign="middle">&nbsp;</td>
		<td align="center" valign="middle">&nbsp;</td>
		<td align="center" valign="middle">&nbsp;</td>
		<td align="center" valign="middle">&nbsp;</td>
		<td align="center" valign="middle">&nbsp;</td>
		<td align="center" valign="middle">&nbsp;</td>
     </tr>
     
     <?php
     if(count($roleGroupMenus[$p]['children'])>0){
	     for($k=0;$k<count($roleGroupMenus[$p]['children']);$k++){
		   if($rollDataCount!=0){
		?>
     
     <tr>
      <td align="left" valign="middle" class="cellinfo"><?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['menu_name']; ?></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_all' ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_all' ?>" onclick=checkuncheck('<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_all' ?>'); value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_all' ?>" /></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_view'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_view'; ?>" <?php if($rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['view']==1){echo "checked";}else{}; ?> value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_view_'.$rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['view'].'_'.$roll_id; ?>"/></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_add'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_add'; ?>" <?php if($rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['add']==1){echo "checked";}else{}; ?> value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_add_'.$rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['add'].'_'.$roll_id; ?>"/></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_edit'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_edit'; ?>"  <?php if($rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['edit']==1){echo "checked";}else{}; ?> value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_edit_'.$rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['edit'].'_'.$roll_id; ?>"/></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_block'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_block'; ?>" <?php if($rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['block']==1){echo "checked";}else{}; ?> value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_block_'.$rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['block'].'_'.$roll_id; ?>"/></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_delete'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_delete'; ?>" <?php if($rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['delete']==1){echo "checked";}else{}; ?> value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_delete_'.$rolePermissionHolder[$roleGroupMenus[$p]['children'][$k]['AdminMenu']['id']]['delete'].'_'.$roll_id; ?>"/></td>
    </tr>
    <?php }else{?>
     <tr>
      <td align="left" valign="middle" class="cellinfo"><?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['menu_name']; ?></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_all' ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_all' ?>" onclick=checkuncheck('<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_all' ?>'); value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_all' ?>" /></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_view'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_view'; ?>"  value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_view_0_'.$roll_id; ?>"/></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_add'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_add'; ?>"  value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_add_0_'.$roll_id; ?>"/></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_edit'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_edit'; ?>"  value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_edit_0_'.$roll_id; ?>"/></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_block'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_block'; ?>"  value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_block_0_'.$roll_id; ?>"/></td>
      <td align="center" valign="middle"><input type="checkbox" name="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_delete'; ?>" id="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_delete'; ?>" value="<?php echo $roleGroupMenus[$p]['children'][$k]['AdminMenu']['id'].'_delete_0_'.$roll_id; ?>"/></td>
    </tr>
    <?php } }} }?>



  </table>
</div>
<div class="clear"></div>
</form>
<div class="buttonpanel"><span id="loader" style="float:left;font-size: 13px;"></span>
  <input type="button" name="button" value="Save" class="buttonsave" onclick="getRollMasterData();" />
<input type="button" name="button" value="Cancel" onclick="redirectProject();" class="buttoncancel" />
</div>
<?php echo $this->Form->end(); ?>
</section>
</div>
</div>