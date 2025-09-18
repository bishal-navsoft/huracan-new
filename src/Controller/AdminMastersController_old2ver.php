<?PHP
class AdminMastersController extends AppController
{
	public $name = 'AdminMasters';
	public $uses = array('Report','Incident','BusinessType','Fieldlocation','Client','IncidentLocation','IncidentSeverity','Country','AdminMaster','HsseClient','HsseIncident','RolePermission','RoleMaster','AdminMenu','IncidentCategory','Loss','IncidentSubCategory','Residual','Division');
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	var $components = array('PhpMailerEmail');

	function language($language_code = 'eng')
	{	
		$this->Session->write('Config.language', $language_code);
		$this->redirect($this->referer());
	}
	
	// Administrator index method. Created by Naveen Gupta.
	public function index()
	{
		$this->layout="before_adminlogin_template";
		$this->set('login_error','');
		$this->set('err','');
		
		$err=0; $strchked = '';
	
	         
	    
	         
		if($this->_checkAdminLogin())
		{
			$this->redirect(array('controller' => 'Reports', 'action' => 'report_hsse_list'));
			$this->Session->setFlash('');
		}
		else
		{
			if(!empty($this->request->data))
			{
				//echo '<pre>';
				//print_r($this->request->data);
				
				
				
				$this->AdminMaster->bindModel(array('belongsTo' => array('RoleMaster')));
				  $condn = array('conditions' => array('AdminMaster.isblocked' => 'N', 'AdminMaster.isdeleted' => 'N', ' AdminMaster.admin_user' => $this->request->data['AdminMaster']['admin_user'],'AdminMaster.admin_pass' => md5($this->request->data['AdminMaster']['admin_pass']),
				'RoleMaster.isblocked' => 'N','RoleMaster.isdeleted' => 'N','RoleMaster.isdeleted' => 'N'));
				  

				  	//echo '<pre>';
	    			//var_dump($this->request->data);
	    			//var_dump($condn);

				  //echo '<pre>';
				  //print_r($condn);
    				$adminDataCount = $this->AdminMaster->find('count', $condn);
				
				//echo '<br>';
				//print_r($adminDataCount);
				//exit;

    				
	    			//var_dump($adminDataCount);
	    			//die();
			
				
				if($adminDataCount > 0)
				{
					
					$this->AdminMaster->bindModel(array('belongsTo' => array('RoleMaster')));
					$adminData = $this->AdminMaster->find('first', $condn);
           				$this->Session->write('adminData',$adminData);					
					$this->Session->write('sess_id', session_id());
					$this->Session->write('admin_id', $adminData['AdminMaster']['id']);
					$this->Session->write('admin_user_name', $adminData['AdminMaster']['admin_user']);
					$this->Session->write('admin_email', $adminData['AdminMaster']['admin_email']);
					$this->redirect(array('controller' => 'Reports', 'action' => 'report_hsse_list'));
					
				}
				elseif($adminDataCount==0)
				{
				        $this->set('err',1);
					$src = $this->webroot.'img/deactivatedIcon.png';
					$this->set('login_error',"<font style='color:red;'> The username or password is not correct.</font>");
				       
				}
			}else{
				  $this->set('err','');
				  $this->set('login_error','');
				
			}

			
		}
		
	}

	public function forgot_password()
	{
		$this->layout = 'before_adminlogin_template';
	}
	public function forgotpass()
	{
		$this->layout = 'ajax';
		$condn = array('conditions' => array('AdminMaster.admin_email' => $this->request->data['AdminMaster']['emailid']));
		$forgotAdminData = $this->AdminMaster->find('first', $condn);
		if($forgotAdminData)
		{
			$val = $this->request->data['AdminMaster']['emailid'];
			echo 'success'.'~'.$val;
			exit;
		}
		else
		{
			echo 'fail';
		}
		exit;
	}
	// Administrator list_all method. Created by Naveen Gupta.
	public function list_all()
	{
		$this->_checkAdminSession();
		//$this->_getAdminMenus();
		$this->_getRoleMenuPermission();
		$this->layout="after_adminlogin_template";

		// ........................................................................
		// ........................................................................
	}
	public function faq_category()
	{
		$this->_checkAdminSession();
		//$this->_getAdminMenus();
		$this->_getRoleMenuPermission();
		$this->layout="after_adminlogin_template";

		// ........................................................................
		// ........................................................................
	}

	// Administrator log-out method. Created by Naveen Gupta.
	public function logout()
	{
		// Update administrator's last_login_ip & last_login_time before log-out.
		$this->AdminMaster->id = $this->Session->read('admin_id');
		$updateData = array();
		$updateData['last_login_time'] = date('Y-m-d h:i:s');
		$updateData['last_login_ip'] = '';
		$this->AdminMaster->save($updateData, false);

		// Killing the administrator defined sessions
		$this->Session->delete('sess_id');
		$this->Session->delete('admin_id');
		$this->Session->delete('admin_user_name');
		//$this->Session->destroy();
		
			
			$this->redirect('/admin');
			
		
		
		//$this->redirect(array('controller' => 'AdminMasters', 'action' => 'index'));
		$this->Session->setFlash('');
	}
	

	public function user_list()
	{
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();
		$this->layout="after_adminlogin_template";
		//pr($this);

		if(!empty($this->request->data))
		{
			$action = $this->request->data['AdminMaster']['action'];
			
			$this->set('action',$action);
			$limit = $this->request->data['AdminMaster']['limit'];
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
	
	public function get_all_user($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		
		$condition='';
		
		$condition= "AdminMaster.id !=0 AND AdminMaster.isdeleted = 'N'";
		if(isset($_REQUEST['filter']) AND isset($_REQUEST['value'])){
			switch($_REQUEST['filter']){
				case'name':
					 $explode_name=explode(" ",trim($_REQUEST['value']));
					 $condition .= " AND AdminMaster.first_name like '".$explode_name[0]."%' AND AdminMaster.last_name like '".$explode_name[1]."%' ";
				break;
			        case'role_master_id':
					 $rollMaster = $this->RoleMaster->find('all' ,array('conditions'=>array('role_name'=>$_REQUEST['value'])));
					 $condition .= " AND AdminMaster.role_master_id =".$rollMaster[0]['RoleMaster']['id'];
			        break; 
				
				default:	
					 $condition .= " AND ucase(AdminMaster.".$_REQUEST['filter'].") like '".trim($_REQUEST['value'])."%'";
				break;
				
				
			}
		}

		$count = $this->AdminMaster->find('count' ,array('conditions' => $condition)); //counts the number of records in Product.
		$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		$adminArray = array();
		$adminA = $this->AdminMaster->find('all' ,array('conditions' => $condition, 'order' => 'AdminMaster.id DESC','limit'=>$limit)); //gets all the Product records and sorts them by productname alphabetically.
		
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
			$adminA[$i]['AdminMaster']['name'] = $rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
			$adminA[$i]['AdminMaster']['seniority'] = $rec['AdminMaster']['seniority'];
			$adminA[$i]['AdminMaster']['position'] = $rec['AdminMaster']['position'];
			$adminA[$i]['AdminMaster']['roll_id'] = $rec['AdminMaster']['role_master_id'];
			$adminA[$i]['AdminMaster']['roll_name'] = $rec['RoleMaster']['role_name'];
			$i++;
		}


		$adminArray = Set::extract($adminA, '{n}.AdminMaster');  //convert $adminArray into a json-friendly format
		
		$this->set('total', $count);  //send total to the view
               	$this->set('admins', $adminArray);  //send products to the view
		$this->set('status', $action);

	}
	
	function userprocess(){
		   $this->layout = "ajax";
	           $this->_checkAdminSession();
	           $userData=array();
	   	   if($this->data['add_user_form']['id']==0){
		       $res='add';
 	               $userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.admin_user' =>$this->data['user_name'],'AdminMaster.isdeleted' => 'N')));
		       if(count($userdetail)>0){
			    $res='useravl';
			    echo $res;
			    exit;
		       }
		        $emaildetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.admin_email' =>$this->data['email'],'AdminMaster.isdeleted' => 'N')));
		       if(count($emaildetail)>0){
			    $res='emailavl';
			    echo $res;
			    exit;
		        }
		       
		       
	               }else{
			
			 $res='update';
			$userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.admin_user' =>$this->data['user_name'],'AdminMaster.id !=' =>$this->data['add_user_form']['id'],'AdminMaster.isdeleted' => 'N')));
		         if(count($userdetail)>0){
			      $res='useravl';
			       echo $res;
			      exit;  
			   }
			  
			$emaildetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.admin_email' =>$this->data['email'],'AdminMaster.id !=' =>$this->data['add_user_form']['id'],'AdminMaster.isdeleted' => 'N')));
		       if(count($emaildetail)>0){
			    $res='emailavl';
			    echo $res;
			    exit; 
			
		       }
			
			
			$userData['AdminMaster']['id']=$this->data['add_user_form']['id'];
		  
	             }

		    $seniority_date = date_create($this->data['seniority_date']);
		    $seniority_date = date_format($seniority_date,'Y-m-d');

			$userData['AdminMaster']['admin_user']=$this->data['user_name']; 
			$userData['AdminMaster']['admin_pass'] =md5($this->data['password']);  
			$userData['AdminMaster']['first_name']=$this->data['fname'];
			$userData['AdminMaster']['last_name']=$this->data['lname'];
			$userData['AdminMaster']['admin_email']=$this->data['email'];

			$userData['AdminMaster']['phone']=$this->data['phone'];
			$userData['AdminMaster']['position']=$this->data['position'];
			$userData['AdminMaster']['seniority']=$seniority_date;
			$userData['AdminMaster']['role_master_id']=$this->data['roll_type'];
  	   
	                 if($this->AdminMaster->save($userData)){
				
			if($res=='add' || $this->data['password']!=''){
			$subject=''; 
			$subject='Your login detail';
			$message='';
			$from = '';
			$to=$this->data['email'];
			$fullname=$this->data['fname'].' '.$this->data['lname']; 
			 $message='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Huracan</title></head><body style="padding:0; margin:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td align="left" valign="top" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/spacer.png" height="20" width="1" /></td>
  </tr><tr><td align="center" valign="top"><table width="625" border="0" cellspacing="0" cellpadding="0">
      <tr><td align="left" valign="top" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/newstop.jpg" width="625" height="12" /></td>
      </tr><tr><td align="center" valign="top" bgcolor="#7b07de"><table width="600" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="10" align="left" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/topleft.gif"/></td>
            <td width="579" bgcolor="#FFFFFF" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/spacer.png" height="7" width="1" /></td>
            <td width="11" align="right" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/topright.gif"/></td>
            </tr>
          <tr>
            <td align="left" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/spacer.png" height="20" width="1" /></td>
            <td align="left" valign="top" bgcolor="#FFFFFF"><table width="579" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top"><table width="579" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="146" align="left" valign="top" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/huracan_logo.png" alt="Huracan" title="Huracan" /></td>
                    <td width="433" align="right" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">Visit us on<br />
                      <a href="#" style="color:#7B07DE; text-decoration:none;">http://huracan.com.au</a></td>
                    </tr>
                  </table></td>
                </tr>
              <tr>
                <td style="font-family: Tahoma, Geneva, sans-serif; font-size:10px; color:#ccc;">................................................................................................................................................................................................</td>
                </tr>
              <tr>
                <td style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/spacer.png" height="10" width="1" /></td>
                </tr>
              <tr>
                <td align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;"><p>Hello <strong>'.ucwords(strtolower($fullname)).',</strong></p>
                  <p>User Name:'.$this->data['user_name'].'</p>
                  <p>Password:'.$this->data['password'].'</p>
                  <p><a href="huracan.com.au/HIRD">Click here</a> for login</p>
                 </td>
                </tr>
              <tr>
                <td style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/spacer.png" height="10" width="1" /></td>
                </tr>
              <tr>
                <td bgcolor="#f0f0f0"><table width="579" border="0" cellspacing="8" cellpadding="0">
                  <tr>
                    <td width="358" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:18px; color:#7B07DE;">Thanks</td>
                    <td width="197" align="left" valign="top"><table width="197" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">Hurracan PTY Ltd</td>
                        </tr>
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">PO-1070</td>
                        </tr>
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">ROMA</td>
                        </tr>
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">Queensland 4455</td>
                        </tr>
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">Email: <a href="#" style="color:#7B07DE; text-decoration:none;">info@huracan.com.au</a></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
            <td align="right" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/spacer.png" height="20" width="1" /></td>
            </tr>
          <tr>
            <td align="left" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/bottomleft.gif"/></td>
            <td bgcolor="#FFFFFF" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/spacer.png" height="7" width="1" /></td>
            <td align="right" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/bottomright.gif"/></td>
            </tr>
          </table></td>
      </tr><tr><td align="left" valign="top" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/newsbottom.jpg" width="625" height="12" /></td>
      </tr></table></td></tr><tr><td align="left" valign="top" style="font-size:0;"><img src="Huracan.com.au/'.$this->webroot.'app/webroot/images/spacer.png" alt="" width="1" height="20" /></td>
  </tr></table></body></html>';
             $this->PhpMailerEmail->send_mail($to,$subject,$message,$from,'','','');
         
					
				}
					
		              echo $res;
	                 }else{
		             echo 'fail';
	                 }
           
			
	 
	   exit;
	
	}
	

	
	  function user_block($id = null)
	     {
          
			if(!$id)
			{
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
	      
	      function user_unblock($id = null)
	     {
          
			if(!$id)
			{
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
	     
	      
	      
	      
	       function user_delete()
	     {
		      $this->layout="ajax";
	             if($this->data['id']=='')
			{
		           $this->redirect(array('action'=>user_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $this->data['id']);
					
	            	        foreach($idArray as $id)
			       {
					
					  $this->request->data['AdminMaster']['id'] = $id;
					  $this->request->data['AdminMaster']['isdeleted'] = 'Y';
					  $this->AdminMaster->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			       exit;	     

		       }
		     
		      
	      }
	
	
	
	
	//Add admin-user method by Piyanwita Dey.
	public function add_user($id=null)
	{
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->layout="after_adminlogin_template";
		$roleList = $this->RoleMaster->find('all' ,array('conditions' =>array('isblocked'=>'N','isdeleted'=>'N')));
		$this->set('roleList', $roleList);
		if($id!=''){
	       $userData = $this->AdminMaster->find('all' ,array('conditions' =>array('AdminMaster.id'=>base64_decode($id))));
		   $this->set('id', $userData[0]['AdminMaster']['id']);
		   $this->set('heading', '');
		   $this->set('user_name', $userData[0]['AdminMaster']['admin_user']);
		   $this->set('fname', $userData[0]['AdminMaster']['first_name']);
		   $this->set('lname', $userData[0]['AdminMaster']['last_name']);
		   $this->set('email', $userData[0]['AdminMaster']['admin_email']);
		   $this->set('phone', $userData[0]['AdminMaster']['phone']);
		   $this->set('position', $userData[0]['AdminMaster']['position']);
		   $this->set('seniority_date_val', $userData[0]['AdminMaster']['seniority']);
		   $this->set('rollid', $userData[0]['AdminMaster']['role_master_id']);
		   $this->set('button','Update');	
			
		}else{
		   $this->set('id', 0);
		   $this->set('heading', '');
		   $this->set('user_name', '');
		   $this->set('fname', '');
		   $this->set('lname', '');
		   $this->set('email', '');
		   $this->set('phone', '');
		   $this->set('seniority_date_val', '');
		   $this->set('position', '');
		   $this->set('rollid', '');
		   $this->set('button','Submit');
			
		}
		
			
	}
	
	//Add admin-user method by Piyanwita Dey.
	public function change_password($id=null)
	{
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$admin_id=$this->Session->read('admin_id');
		$this->layout="after_adminlogin_template";
		if($id!='')
		{
		$seid=$id;
		}
		else
		{
		$seid=$admin_id;
		}
		$this->set('id', $seid);
		if(!empty($this->request->data))
		{
			$pass_info=array();
			$pass_info['AdminMaster']['admin_pass']=md5($this->request->data['AdminMaster']['admin_pass']);
			$pass_info['AdminMaster']['id']=$admin_id;
			//print_r($pass_info);exit;
			$this->AdminMaster->save($pass_info);		
			$this->Session->setFlash(__("Password sucessfully changed"));
			$this->redirect(array('controller' => 'AdminMasters', 'action' => 'change_password'));
		}
	}
	public function change_password_user($id=null)
	{
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$admin_id=$this->Session->read('admin_id');
		$this->layout="fancybox_template";
		
		$this->set('id', $id);
		if(!empty($this->request->data))
		{
			$pass_info=array();
			$pass_info['User']['user_pass']=md5($this->request->data['AdminMaster']['admin_pass']);
			$pass_info['User']['id']=$admin_id;
			//print_r($pass_info);exit;
			$this->User->save($pass_info);		
			$this->Session->setFlash(__("Password sucessfully changed"));
			$this->redirect(array('controller' => 'AdminMasters', 'action' => 'change_password_user'));
		}
	}
	
	// Admin check_pass method. Created by Piyanwita Dey.
	public function check_pass($pass = null)
	{	
		
	    $exp=explode('"_"',$pass);
	    $count_query= $this->AdminMaster->query("select * from admin_masters where id='".$exp[1]."' AND  admin_pass='".md5($exp[0])."'");
		$count_pass=count($count_query);
		
		   if($count_pass>0)
			{
				echo "1";
			}
			else
			{
				echo "2";
			}
				
		exit;			
	
	}

}
?>
