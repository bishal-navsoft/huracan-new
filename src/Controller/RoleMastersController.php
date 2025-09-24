<?PHP
class RoleMastersController extends AppController
{
	public $name = 'RoleMasters';
	public $uses = array('AdminMaster', 'RoleMaster', 'AdminMenu','RolePermission');
	public $helpers = array('Html', 'Form', 'Js');
	//public $components = array('RequestHandler');
	
		public function list_roles()
		{
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			if(!empty($this->request->data))
			{
				$action = $this->request->data['RoleMaster']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['RoleMaster']['limit'];
				$this->set('limit', $limit);
			}
			else
			{
				$action = 'all';
				$this->set('action','all');
				$limit = 50;
				$this->set('limit', $limit);
			}
			$this->Session->write('action', $action);
			$this->Session->write('limit', $limit);
			unset($_SESSION['filter']);
			unset($_SESSION['value']);
		}
		
		public function get_all_admin_role($action ='all')
		{
			Configure::write('debug', '0');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
			$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
			switch($action)
			{
			case "all":
				$condition = "RoleMaster.isdeleted = 'N'";
			break;
			case "unblocked":
				$condition = "RoleMaster.isblocked = 'N' AND RoleMaster.isdeleted = 'N'";
			break;
			case "blocked":
				$condition = "RoleMaster.isblocked = 'Y' AND RoleMaster.isdeleted = 'N'";
			break;
			}
			$condition .= " AND RoleMaster.id !=0";
			//$condition .= " AND RoleMaster.merchant_id = '$contact'";
		
			if(isset($_REQUEST['filter']) AND isset($_REQUEST['value']))
			{
				$_SESSION['filter'] = $_REQUEST['filter'];
				$_SESSION['value'] = $_REQUEST['value'];
				$condition .= " AND ucase(RoleMaster.".$_REQUEST['filter'].") like '".strtoupper(trim($_REQUEST['value']))."%'";
			}
			elseif(isset($_SESSION['filter']) AND isset($_SESSION['value']))
			{
				$_REQUEST['filter'] = $_SESSION['filter'];
				$_REQUEST['value'] = $_SESSION['value'];
				$condition .= " AND ucase(RoleMaster.".$_REQUEST['filter'].") like '".strtoupper(trim($_REQUEST['value']))."%'";
			}
			$count = $this->RoleMaster->find('count' ,array('conditions' => $condition)); //counts the number of records in Product.
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				//$condition .= " order by Category.id DESC";
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
			$adminArray = array();	//this will hold our data from the database.
			//echo $condition;
			//$limit = "";	
			$adminA = $this->RoleMaster->find('all' ,array('conditions' => $condition, 'order' => 'RoleMaster.id DESC','limit'=>$limit)); //gets all the Product records and sorts them by productname alphabetically.
		
			// Toggle the block unblock icon (START)
			$i = 0;
			foreach($adminA as $rec)
			{			
				if($rec['RoleMaster']['isblocked'] == 'N')
				{
					$adminA[$i]['RoleMaster']['blockHideIndex'] = "true";
					$adminA[$i]['RoleMaster']['unblockHideIndex'] = "false";
				}else{
					$adminA[$i]['RoleMaster']['blockHideIndex'] = "false";
					$adminA[$i]['RoleMaster']['unblockHideIndex'] = "true";
				}
				$i++;
			}
			// Toggle the block unblock icon (END)

			$adminArray = Set::extract($adminA, '{n}.RoleMaster');  //convert $adminArray into a json-friendly format
			//print_r($adminArray);exit;
			$this->set('total', $count);  //send total to the view
			$this->set('admins', $adminArray);  //send products to the view
			$this->set('status', $action);
			//print_r($count);
			//exit;
		}
		
		function admin_add() 
		{
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->layout="after_adminlogin_template";
			if (!empty($this->request->data)) 
			{
				//pr($this->request->data);exit;
				$this->RoleMaster->save($this->request->data['RoleMaster'], false);
				$role_id = $this->RoleMaster->id;
				foreach($this->request->data['RolePermission'] as $key => $eachRolePermission)
				{
					$this->request->data['RolePermission'][$key]['role_master_id'] = $role_id;
				}
				$this->RolePermission->saveAll($this->request->data['RolePermission'], array('validate' => false));
				$this->redirect(array('action' => 'list_roles'));
			}
			$this->_getRoleGroupMenus();
			//$log = $this->RoleMaster->getDataSource()->getLog(false, false);
			//debug($log);
		}
		
		function _getRoleGroupMenus($role_id = null)
		{
			
			
	
			if($role_id != null)
			{
				$rolePermissionHolder=array();
				$condn = array(
					'conditions' => array('RoleMaster.id' => $role_id));
				$roleDataCount = $this->RoleMaster->find('count', $condn);
				if($roleDataCount > 0)
				{
					$condn = array(
						'conditions' => array('RolePermission.role_master_id' => $role_id));
						$rolePermissions = $this->RolePermission->find('all', $condn);
						$this->set('rolePermissions', $rolePermissions);
						for($i=0;$i<count($rolePermissions);$i++){
					             for($j=0;$j<5;$j++){
						          $rolePermissionHolder[$rolePermissions[$i]['RolePermission']['admin_menu_id']]['view']=$rolePermissions[$i]['RolePermission']['view'];
						          $rolePermissionHolder[$rolePermissions[$i]['RolePermission']['admin_menu_id']]['add']=$rolePermissions[$i]['RolePermission']['add'];
						          $rolePermissionHolder[$rolePermissions[$i]['RolePermission']['admin_menu_id']]['edit']=$rolePermissions[$i]['RolePermission']['edit'];
						          $rolePermissionHolder[$rolePermissions[$i]['RolePermission']['admin_menu_id']]['block']=$rolePermissions[$i]['RolePermission']['block'];
						          $rolePermissionHolder[$rolePermissions[$i]['RolePermission']['admin_menu_id']]['delete']=$rolePermissions[$i]['RolePermission']['delete'];
					                }
				                }
				               $this->set('rolePermissionHolder', $rolePermissionHolder);
						
				}else{
				               $this->set('rolePermissionHolder', array());	
				}
				
				
			}else{
			  $this->set('rolePermissionHolder', array());	
			}
		}
		
		function add_roll($id = null) 
		{
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
       			$this->layout="after_adminlogin_template";
			if($id!=''){
			 $roll_id=base64_decode($id);
			 $this->set('roll_id', $roll_id);
			}else{
			 $roll_id=0;	
			 $this->set('roll_id', 0);	
			}
			$condn = array(
				'conditions' => array('AdminMenu.status' => 'Y'),
				'order' => array('AdminMenu.order_id')
			);
			$roleGroupMenus = $this->AdminMenu->find('threaded', $condn);
			$this->set('roleGroupMenus', $roleGroupMenus);
			
			$rollDetail = $this->RoleMaster->find('all', array('conditions' => array('RoleMaster.id' =>$roll_id)));
			$rollData = $this->RolePermission->find('all', array('conditions' => array('RolePermission.role_master_id' =>$roll_id)));
		         
			if(count($rollDetail)>0){
			   $this->set('roleName',$rollDetail[0]['RoleMaster']['role_name']);
			   $this->set('roleDescription',$rollDetail[0]['RoleMaster']['description']);
				
			}else{
			   $this->set('roleName','');
			   $this->set('roleDescription','');
			 
			}
			

		
			if(count($rollData)>0){
				 $this->_getRoleGroupMenus($roll_id);
				 $this->set('rollDataCount',count($rollData));
			}elseif(count($rollData)==0){
				$this->_getRoleGroupMenus();
				$this->set('rollDataCount',0);
			}
			
		
			
			
		}

		function rollprocess(){
			Configure::write('debug', 2);
			 $this->layout="ajax";
			 $rollData=array();
			 if($this->data['rollmasterID']!=0){
			   $rollMas = $this->RoleMaster->find('all', array('conditions' => array('RoleMaster.role_name' =>trim($this->data['rollname']),'RoleMaster.id !=' =>$this->data['rollmasterID'])));
			 }elseif($this->data['rollmasterID']==0){
			   $rollMas = $this->RoleMaster->find('all', array('conditions' => array('RoleMaster.role_name' =>trim($this->data['rollname']))));	
			 }
			 if(count($rollMas)==0){
			
			 $rollData['RoleMaster']['id']=$this->data['rollmasterID'];
			 $rollData['RoleMaster']['description']=$this->data['description'];
			 $rollData['RoleMaster']['role_name']=$this->data['rollname'];
		         if($this->RoleMaster->save($rollData)){
			     $getLastID=$this->RoleMaster->getLastInsertId();
			 }
			 $res='notavl';	 
			 }else{
			 $res='avl';	
			 }

			 if($res=='notavl'){
			

				if($this->data['rollmasterID']==0){
					$adminMenu = $this->AdminMenu->find('all');
					
					for($v=0;$v<count($adminMenu);$v++){
					
				$rollPer['RolePermission']['admin_menu_id']=$adminMenu[$v]['AdminMenu']['id'];
						$rollPer['RolePermission']['role_master_id']=$getLastID;
						$rollPer['RolePermission']['view']=0;
						$rollPer['RolePermission']['add']=0;
						$rollPer['RolePermission']['edit']=0;
						$rollPer['RolePermission']['block']=0;
						$rollPer['RolePermission']['delete']=0;
						$this->RolePermission->create();
						$this->RolePermission->save($rollPer);
					}
				      $explode_id=explode(",",$this->data['idlist']);
	                          
		                     for($i=0;$i<count($explode_id);$i++){ 
					$split_value=explode("_",$explode_id[$i]);
						if($split_value[1]!='all'){
							 $update_role_permissions_sql = "UPDATE `role_permissions` SET `$split_value[1]` = {$split_value[2]}  WHERE `role_master_id` = {$getLastID} AND `admin_menu_id`={$split_value[0]} ";
							 $this->RolePermission->query($update_role_permissions_sql); 
						}
			           }
					echo 'ok~'.base64_encode($getLastID);
					exit;
					
				}elseif($this->data['rollmasterID']!=0){
					
					
					
							
				 $explode_id=explode(",",$this->data['idlist']);
				for($i=0;$i<count($explode_id);$i++){
					$split_value=explode("_",$explode_id[$i]);
						if($split_value[1]!='all'){
							 $update_role_permissions_sql = "UPDATE `role_permissions` SET `$split_value[1]` = {$split_value[2]} WHERE `admin_menu_id`={$split_value[0]} AND `role_master_id` ={$split_value[3]}";
							 $this->RolePermission->query($update_role_permissions_sql); 
						}
			      }
			      echo 'ok~'.base64_encode($split_value[3]);
			       exit;
			    }	
			 }
				  
			
			
			
		}
			
		function block($id = null)
		{
			if(!$id)
			{
				   $this->Session->setFlash('Invalid id for admin');
				   $this->redirect(array('action'=>admin_user_list), null, true);
			}
			else
			{
				$idArray = explode("^", $id);
				foreach($idArray as $id)
				{
					   $id = $id;
					   $this->request->data['RoleMaster']['id'] = $id;
					   $this->request->data['RoleMaster']['isblocked'] = 'Y';
					   $this->RoleMaster->save($this->request->data,false);				
				}	     
				exit;			 
			}
		}
		
		function unblock($id = null)
		{
			if(!$id)
			{
				$this->Session->setFlash('Invalid id for admin');
				$this->redirect(array('action'=>admin_user_list), null, true);
			}
			else
			{
				$idArray = explode("^", $id);
				foreach($idArray as $id)
				{
					$id = $id;
					$this->request->data['RoleMaster']['id'] = $id;
					$this->request->data['RoleMaster']['isblocked'] = 'N';
					$this->RoleMaster->save($this->request->data,false);
				}	
				exit;
			 
			}
		}
			
		function delete($id = null)
			{
			if(!$id)
			{
				$this->Session->setFlash('Invalid id for admin');
				$this->redirect(array('action'=>admin_user_list), null, true);
			}
			else
			{
				$idArray = explode("^", $id);
				foreach($idArray as $id)
				{
					$id = $id;
					$this->request->data['RoleMaster']['id'] = $id;
					$this->request->data['RoleMaster']['isdeleted'] = 'Y';
					$this->RoleMaster->save($this->request->data,false);
					
				}  
				exit;
			}
		}
		
}
?>