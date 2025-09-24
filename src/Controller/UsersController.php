<?PHP
/**
 * (Created by DEBI PRASAD SAHOO)
 *
*/

class UsersController extends AppController
{
	public $name = 'Users';

	public $uses = array('User','Division','Team','DataEntry','AdminMaster','RoleMaster', 'Currency', 'Language', 'Country', 'Payment','MobileSite', 'Faq', 'FaqCategory', 'RolePermission', 'AdminMenu', 'Module', 'Video', 'Log', 'NavigationGroup', 'NavigationMenu', 'Template', 'TemplateCategory', 'TemplateColor', 'TempMobileSitePage', 'ModuleComponent', 'PageModule', 'TempMobileSitePageModule', 'UserInvoice','Campaign','SiteInfo');

	public $helpers = array('Html', 'Form', 'Js','Nesting');
	//public $components = array('RequestHandler', 'Session', 'Cookie','Resizer');



	// User Listing method for admin section start by Piyanwita Dey
	public function user_list()
	{
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();
		$this->layout="after_adminlogin_template";
		if(!empty($this->request->data))
		{
			$action = $this->request->data['User']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['User']['limit'];
			$this->set('limit', $limit);
		}
		else
		{
			$action = 'all';
			$this->set('action','all');
			$limit = 10;
			$this->set('limit', $limit);
		}
		$this->Session->write('action', $action);
		$this->Session->write('limit', $limit);
		unset($_SESSION['filter']);
		unset($_SESSION['value']);
	}
	
	public function get_all_user($action ='all')
	{
		Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
		$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
		//pr($_REQUEST);
		switch($action)
		{
			case "all":
				$condition = "AdminMaster.isdeleted = 'N'";
			break;
			case "unblocked":
				$condition = "AdminMaster.isblocked = 'N' AND AdminMaster.isdeleted = 'N'";
			break;
			case "blocked":
				$condition = "AdminMaster.isblocked = 'Y' AND AdminMaster.isdeleted = 'N'";
			break;
		}
		$condition .= " AND AdminMaster.role_master_id = '50'";
		//$condition .= " AND User.merchant_id = '$contact'";
		
		if(isset($_REQUEST['filter']) AND isset($_REQUEST['value']))
		{
			$_SESSION['filter'] = $_REQUEST['filter'];
			$_SESSION['value'] = $_REQUEST['value'];
			$condition .= " AND ucase(AdminMaster.".$_REQUEST['filter'].") like '".strtoupper(trim($_REQUEST['value']))."%'";
		}
		elseif(isset($_SESSION['filter']) AND isset($_SESSION['value']))
		{
			$_REQUEST['filter'] = $_SESSION['filter'];
			$_REQUEST['value'] = $_SESSION['value'];
			$condition .= " AND ucase(AdminMaster.".$_REQUEST['filter'].") like '".strtoupper(trim($_REQUEST['value']))."%'";
		}
		$count = $this->AdminMaster->find('count' ,array('conditions' => $condition)); //counts the number of records in Product.
		$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		$adminArray = array();	//this will hold our data from the database.
		//echo $condition;exit;
		//$limit = "";
		$this->AdminMaster->bindModel(array(
			'belongsTo' => array(
				'Team' => array('foreignKey' => 'team_id'
								)				
							)
				)
		);
		$adminA = $this->AdminMaster->find('all' ,array('conditions' => $condition, 'order' => 'AdminMaster.id DESC','limit'=>$limit)); //gets all the Product records and sorts them by productname alphabetically.
		$count= $this->AdminMaster->find('count' ,array('conditions' => $condition));
		// Toggle the block unblock icon (START)
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['AdminMaster']['isblocked'] == 'N')
			{
				$adminA[$i]['AdminMaster']['blockHideIndex'] = "true";
				$adminA[$i]['AdminMaster']['unblockHideIndex'] = "false";
			}else{
				$adminA[$i]['AdminMaster']['blockHideIndex'] = "false";
				$adminA[$i]['AdminMaster']['unblockHideIndex'] = "true";
			}
			$adminA[$i]['AdminMaster']['name'] = $adminA[$i]['AdminMaster']['first_name']." ".$adminA[$i]['AdminMaster']['last_name'];
			$team_val = "";
			if($adminA[$i]['AdminMaster']['team_id']!='')
			{
			   $sql = "SELECT `team_name` FROM `teams` WHERE `id` IN (".$adminA[$i]['AdminMaster']['team_id'].")";
			   $arrval = $this->Team->query($sql);
			   foreach($arrval as $value)
			   {
				foreach($value as $value2)
				{
					
					$strvalue = $value2['team_name'];
					$team_val = $team_val.','.$strvalue;
				}
			   }
			   $adminA[$i]['AdminMaster']['team'] = substr($team_val, 1);   
			}
			else
			{
			   $adminA[$i]['AdminMaster']['team'] = "";
			}
			$i++;
		}
		
		$adminArray = Set::extract($adminA, '{n}.AdminMaster');  //convert $adminArray into a json-friendly format
                $adivisionDeatil='';
		for($di=0;$di<count($adminArray);$di++){
			if(isset($adminArray[$di]['division_id'])){
			  $divisionDeatil = $this->Division->find('all', array('conditions' => array('id' =>$adminArray[$di]['division_id'])));
	                  if(isset($divisionDeatil[0]['Division']['division_name'])){    
		                 $adminArray[$di]['division_name']=$divisionDeatil[0]['Division']['division_name'];
			    }
			  
			}
			  
		}
		

		$this->set('total', $count);  //send total to the view
		$this->set('admins', $adminArray);  //send products to the view
		$this->set('status', $action);
	}




	
	public function add_user($id=null)
	{
		$this->_checkAdminSession();
		//$this->_getAdminMenus();
		$this->_getRoleMenuPermission();
		$this->layout="after_adminlogin_template";
		if($id==null)
		{	
		  $divisionlist = $this->Division->find('all');
		  $this->set('divisionlist',$divisionlist);
		  $teamlist = $this->Team->find('all',array('conditions' => array('division_id'=>1)));
		  $this->set('teamlist',$teamlist);
		  $this->set('id','0');
  		  $this->set('division_name','');
		  $this->set('heading','Add User');
		  $this->set('team_name','');
		  //$this->set('filter',array());
		  $this->set('user_name','');
		  $this->set('password','');
		  $this->set('fname','');
		  $this->set('lname','');
		  $this->set('email','');
		  $this->set('phone','');
		  $this->set('division_id','');
		  $this->set('team_id','');
                  $this->set('hteamid','');
		  $this->set('teamID',array());
		  $this->set('userButton','Add');

		  //$teamlist=$this->Team->find('all', array('conditions' => array('division_id' =>$teamlist[0]['Team']['id'])));
		  if(count($teamlist)>0)
		  {
		      $this->set('teamlist',$teamlist);
		      $this->set('hid',$teamlist[0]['Team']['id']);
		  }else{
		      $this->set('hid',0);
		  }
                  //$this->set('enID',0);
		 
		}elseif($id!=null)
		{
	          
		  $userDeatil = $this->AdminMaster->find('all',array('conditions' => array('AdminMaster.id' => $id)));
		  $divisionlist = $this->Division->find('all',array('conditions' => array('id'=>$userDeatil[0]['AdminMaster']['division_id'])));
		  $this->set('divisionlist',$divisionlist);
		  $divisionDeatil = $this->Division->find('all',array('conditions' => array('Division.id' => $userDeatil[0]['AdminMaster']['division_id'])));
		  $teamlist = $this->Team->find('all',array('conditions' => array('division_id'=>$userDeatil[0]['AdminMaster']['division_id'])));
                 
                  $teamID=explode(",",$userDeatil[0]['AdminMaster']['team_id']);
                  $tid=implode(",",$teamID);
		  $this->set('teamID',$teamID);
                  $this->set('teamlist',$teamlist);
		  $this->set('id',$id);
		  $this->set('division_id',$userDeatil[0]['AdminMaster']['division_id']);
		  $this->set('division_name',$divisionDeatil[0]['Division']['division_name']);
	          $this->set('heading','Edit User');
		  $this->set('team_name','');
		  $this->set('user_name',$userDeatil[0]['AdminMaster']['admin_user']);
		  $this->set('password',$userDeatil[0]['AdminMaster']['admin_pass']);
		  $this->set('fname',$userDeatil[0]['AdminMaster']['first_name']);
		  $this->set('lname',$userDeatil[0]['AdminMaster']['last_name']);
		  $this->set('email',$userDeatil[0]['AdminMaster']['admin_email']);
		  $this->set('phone',$userDeatil[0]['AdminMaster']['phone']);
                  $this->set('hteamid', $tid);
		  $this->set('userButton','Update');

		}
	}
	function displayteam()
	{//echo 'helloaaa';
		$this->layout="ajax"; 
		if(!empty($this->data))
		{
			$teamlist=$this->Team->find('all', array('conditions' => array('division_id' =>$this->data['id'])));
			if(count($teamlist)>0)
			{
			        $this->set('teamlist',$teamlist);
			}	
			//$this->set('id',$this->data['id']);
			//break;
		}		
	}
	function userprocess()
	{ 
	   //pr($this->data);
	   //echo $this->data['add_user_form']['id'];
   
	   $userData=array();
	   if($this->data['add_user_form']['id']==0)
	   {
		$userDetail = $this->AdminMaster->find('all', array('conditions' => array('admin_user' =>$this->data['add_user_form']['user_name'])));		
		if(count($userDetail)==0)
		{
		     //pr($this->data);
		     $userData['AdminMaster']['division_id']=$this->data['division_name'];
		     //$userData['AdminMaster']['team_id']=$this->data['team_id'];
		     $userData['AdminMaster']['role_master_id']=50;
		     $userData['AdminMaster']['admin_user']=$this->data['add_user_form']['user_name'];
		     $userData['AdminMaster']['first_name']=$this->data['add_user_form']['fname'];
		     $userData['AdminMaster']['last_name']=$this->data['add_user_form']['lname'];
		     $userData['AdminMaster']['admin_pass']=md5($this->data['add_user_form']['password']);
		     $userData['AdminMaster']['admin_email']=$this->data['add_user_form']['email'];
		     $userData['AdminMaster']['phone']=$this->data['add_user_form']['phone'];
		     if($this->AdminMaster->save($userData))
		     {
			     echo 'add';exit;     
		     }
		     else
		     {
			     echo 'fail';	  
		     }
		}
		else
		{
		    echo 'avl'; 	 
		}	
	        exit;
 	   }
	   else
	   {
		      
		$userData['AdminMaster']['id']=$this->data['add_user_form']['id']; 		
	        $userDetail = $this->AdminMaster->find('all', array('conditions' => array('admin_user' =>$this->data['add_user_form']['user_name'],'AdminMaster.id !=' =>$this->data['add_user_form']['id'])));				
		if(count($userDetail)==0)
		{
		     //pr($this->data);
		     $userData['AdminMaster']['division_id']=$this->data['division_name'];
		     //$userData['AdminMaster']['team_id']=implode(",",$this->data['team_name']);
		     
		     $userData['AdminMaster']['role_master_id']=50;
		     $userData['AdminMaster']['admin_user']=$this->data['add_user_form']['user_name'];
		     $userData['AdminMaster']['first_name']=$this->data['add_user_form']['fname'];
		     $userData['AdminMaster']['last_name']=$this->data['add_user_form']['lname'];
		     if($this->data['add_user_form']['password']==''){
			$userInfo = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$this->data['add_user_form']['id'])));
			$userData['AdminMaster']['admin_pass']=$userInfo[0]['AdminMaster']['admin_pass'];
		     }else{
			$userData['AdminMaster']['admin_pass']=md5($this->data['add_user_form']['password']);
		     }
		     
		     $userData['AdminMaster']['admin_email']=$this->data['add_user_form']['email'];
		     $userData['AdminMaster']['phone']=$this->data['add_user_form']['phone'];
		     if($this->AdminMaster->save($userData))
		     {
			     echo 'update';exit;     
		     }
		     else
		     {
			     echo 'fail';	  
		     }
		}
		else
		{
		    echo 'avl'; 	 
		}	
	        exit;
	   }
	   
	   exit;
	}
	
	
	
	
	
	function block($id = null)
	{
		echo $id;
		if(!$id)
		{
			   $this->Session->setFlash('Invalid id for admin');
			   $this->redirect(array('action'=>user_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
			foreach($idArray as $id)
			{
				   $id = $id;
				   $this->request->data['AdminMaster']['id'] = $id;
				   $this->request->data['AdminMaster']['isblocked'] = 'Y';
				   $this->AdminMaster->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}	
	function unblock($id = null)
	{
		if(!$id)
		{
			$this->Session->setFlash('Invalid id for admin');
			$this->redirect(array('action'=>user_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['AdminMaster']['id'] = $id;
				$this->request->data['AdminMaster']['isblocked'] = 'N';
				$this->AdminMaster->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
			
	function delete($id = null)
	{
		if(!$id)
		{
			$this->Session->setFlash('Invalid id for admin');
			$this->redirect(array('action'=>user_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['AdminMaster']['id'] = $id;
				$this->request->data['AdminMaster']['isdeleted'] = 'Y';
				$this->AdminMaster->save($this->request->data,false);
				
			}  
			exit;
		}
	}
}
?>
