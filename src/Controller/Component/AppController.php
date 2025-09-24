<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $uses = array('ModuleSetting');

	/* ///////////////////////////////////////////////////////////////////////////////////////////// */
	/* ///////////////////////// NEEDED FOR WOUMBA TOOL MODULE STARTS HERE ///////////////////////// */

	/* Function _assignArrayKeyValue() created by Naveen Gupta.
	   This function takes input a ~(tilt) separated string like "external~internal" and convert it into an array like 
			Array 
			(
				[external] => external
				[internal] => internal
			)*/
	function _assignArrayKeyValue($str = '')
	{
		$returnArray = array();
		if($str != '')
		{
			$explodeArray = explode('~', $str);
			foreach($explodeArray AS $explodeValue):
				$returnArray[$explodeValue] = $explodeValue;
			endforeach;
		}
		return $returnArray;
	}

	function _assignKeyValue($str = '')
	{
		$returnArray = array();
		if($str != '')
		{
			$tiltExplodedArray = explode('~', $str);
			foreach($tiltExplodedArray AS $tiltExplodedValue):
				$tmpArray = explode('^', $tiltExplodedValue);
				$returnArray[$tmpArray[0]] = $tmpArray[1];
			endforeach;
		}
		return $returnArray;
	}
	
	function _getModuleSettings()
	{
		$condn = array('id' => '1');
		$modulesettings = $this->ModuleSetting->find('first', $condn);

		$font_size = $this->_assignArrayKeyValue($modulesettings['ModuleSetting']['text_size']);
		$this->set('font_size', $font_size);

		$shadow_sizes = $this->_assignArrayKeyValue($modulesettings['ModuleSetting']['shadow_size']);
		$this->set('shadow_sizes', $shadow_sizes);

		$link_option = $this->_assignArrayKeyValue($modulesettings['ModuleSetting']['link_option']);
		$this->set('link_option', $link_option);

		$link_design = $this->_assignKeyValue($modulesettings['ModuleSetting']['link_design']);
		$this->set('link_design', $link_design);
	}

	/* List of font_sizes */
	//public $font_size =  array('10'=>'10px', '12'=>'12px', '14'=>'14px', '16'=>'16px', '18'=>'18px', '20'=>'20px', '22'=>'22px', '24'=>'24px', '26'=>'26px', '28'=>'28px', '30'=>'30px', '32'=>'32px', '34'=>'34px', '36'=>'36px', '38'=>'38px', '40'=>'40px');

	/* List of shadow_sizes */
	//public $shadow_sizes =  array('1'=>'1px', '2'=>'2px', '3'=>'3px', '4'=>'4px', '5'=>'5px', '6'=>'6px', '7'=>'7px', '8'=>'8px', '9'=>'9px', '10'=>'10px');

	/* List of link options like EXTERNAL & INTERNAL */
	//public $link_option = array('external' => 'external', 'internal' => 'internal');

	/* List of link_design on buttons like button+icon, button+icon+text, button+text, text etc */
	public $link_design = array('bi' => 'Button, Icon', 'bit' => 'Button, Icon, Text', 'bt' => 'Button, Text', 't' => 'Text');
	/* /////////////////////////  NEEDED FOR WOUMBA TOOL MODULE ENDS HERE  ///////////////////////// */
	/* ///////////////////////////////////////////////////////////////////////////////////////////// */

	/* List of speeches */
	public $speeches = array('Mr'=>'Mr', 'Ms'=>'Ms');

	/* List of 'Mobile Site' status */
	public $mobile_site_status = array('on'=>'online', 'off'=>'offline');

	/* List of allow scrolling */
	public $allow_scrolling = array('on'=>'online', 'off'=>'offline');

	/* List of credit card names */
	public $creditCardNames = array('visa'=>'Visa', 'mastercard'=>'Mastercard');

	/* List of months in a calendar year */
	public $months = array( '1'=>'January', '2'=>'February', '3'=>'March', '4'=>'April', '5'=>'May', '6'=>'June', '7'=>'July', '8'=>'August', '9'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');

	function beforeFilter()
	{
		//define('WEB_SITE_URL','http://'.$_SERVER['HTTP_HOST'].$this->webroot);
		//Configure::write('Config.language', $this->Session->read('Config.language'));
	}

	// This function checks USER's type. Created by Naveen Gupta.
	// If PARTNER is accessing sections related to USER then PARTNER will be redirected to PARTNER's home page. 
	function _checkUserType()
	{
		if($this->Session->read('user_type') == 'P')
		{
			$this->redirect(array('controller' => 'Partners', 'action' => 'home')); 
		}
	}

	// This function checks USER's type. Created by Naveen Gupta.
	// If USER is accessing sections related to PARTNER then USER will be redirected to USER's home page. 
	function _checkPartnerType()
	{
		if($this->Session->read('user_type') == 'U')
		{
			$this->redirect(array('controller' => 'Users', 'action' => 'home')); 
		}
	}

	// This function checks whether user has logged in or not. Created by Naveen Gupta.
	function _checkUserLogin()
	{
		$userid = $this->Session->read('user_id');
		if(!$userid) {return false;}
		return true;
	}
	
	// This function redirects a user for log-in (if not logged in already) after storing reference URL. Created by Naveen Gupta.
	function _checkUserSession()
	{
		if(!$this->Session->check('user_id'))
		{
			$this->Session->setFlash('You need to be logged in to access this area.');
			if(isset($this->params['url']['url']))
			{
				$this->Session->write('referData', $this->params['url']['url']);
			}
			$this->redirect(array('controller' => 'Users', 'action'=>'index'));
			exit();
		}
	}

	// This function checks whether administrator has logged in or not. Created by Naveen Gupta.
	function _checkAdminLogin()
	{
		$adminid = $this->Session->read('admin_id');
		if(!$adminid) {return false;}
		return true;
	}
	
	// This function redirects administrator for log-in (if not logged in already) after storing reference URL. Created by Naveen Gupta.
	function _checkAdminSession()
	{
		
		//echo '<pre>'; print_r($this->Session); 
		if(!$this->Session->check('admin_id'))
		{   
			
			$this->Session->setFlash('You need to be logged in to access this area.');
			if(isset($this->params['url']['url']))
			{
				$this->Session->write('referAdminData', $this->params['url']['url']);
			}
			$this->redirect(array('controller' => 'AdminMasters', 'action'=>'index'));
			exit();
		}
		
	
	}
	
	/************************** FOR ADMIN USER MENU PERMISSION START BY PIYANWITA DEY ON 03-07-2012 ******************/
	/*function _getAdminMenus()
	{
		$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'),'RolePermission.view' => '1'),
			'fields' => array('RolePermission.admin_menu_id'),
			'order' => array('RolePermission.id')
		);
		$rolePermissionData = $this->RolePermission->find('list', $condn);
		//pr($rolePermissionData);
		$condn1 = array(
			'conditions' => array('AdminMenu.status' => 'Y', 'AdminMenu.show_menu' => 'Y', 'AdminMenu.id' => $rolePermissionData),
			'order' => array('AdminMenu.order_id')
		);
		$admin_menus = $this->AdminMenu->find('threaded', $condn1);
		$this->set('admin_menus', $admin_menus);
		//pr($admin_menus);
	}
	
	$this->AdminMenu->bindModel(
        array('hasOne' => array(
                'RolePermission' => array(
                    'className' => 'RolePermission',
										'foreignKey' => 'admin_menu_id',
												)
								)
				    )
				);
					$condn2 = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id')),
			'order' => array('AdminMenu.id')
		);
		$admin_menus2 = $this->AdminMenu->find('threaded', $condn2);
		$this->set('admin_menus2', $admin_menus2);
		pr($admin_menus2);*/
	
	function _getRoleMenuPermission()
	{
		     
		$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'RolePermission.view' => '1'),
			'fields' => array('RolePermission.admin_menu_id'),
			'order' => array('RolePermission.id')
		);

		$rpmData = $this->RolePermission->find('all', $condn);
		
				
		
		$rolePermissionData = $this->RolePermission->find('list', $condn);
	
	    
	       for($i=0;$i<count($rpmData);$i++){
	        $admin_menus_data = $this->AdminMenu->find('all', array('conditions' => array('AdminMenu.id' =>$rpmData[$i]['RolePermission']['admin_menu_id'])));
		$admin_menus_children[$admin_menus_data[0]['AdminMenu']['parent_id']][]=$admin_menus_data[0];
		$admin_menus_parentId[] = $admin_menus_data[0]['AdminMenu']['parent_id'];
	       }
	       
	       $admin_menus_parentId=array_values(array_unique($admin_menus_parentId));

	       
	       
	       for($p=0;$p<count($admin_menus_parentId);$p++){
	        $admin_menus_parrentdata[]= $this->AdminMenu->find('all', array('conditions' => array('AdminMenu.id' =>$admin_menus_parentId[$p])));
	        }
	       
	       
	      // echo '<pre>';
	     // print_r($admin_menus_parentId);
	       //echo '<br>';
	      // print_r($admin_menus_parrentdata);
	       //echo '<br>';
	//print_r($admin_menus_children);

	       $this->set('admin_menus_children', $admin_menus_children);
	       $this->set('admin_menus_parrentdata', $admin_menus_parrentdata);
	
	
	}
	
	/*********REPORT LISN********************************/


	
	function  report_hsse_link(){
		
		$allHsseReport = $this->Report->find('all' ,array('conditions' =>array('Report.isdeleted'=>'N'),'order' => 'Report.id DESC'));
		$this->Session->write('allHsseReport', $allHsseReport);
			
		
		
	}
	function  report_sq_link(){
		
		$allSqReport = $this->SqReportMain->find('all' ,array('conditions' =>array('SqReportMain.isdeleted'=>'N'),'order' => 'SqReportMain.id DESC'));
		$this->Session->write('allSqReport', $allSqReport);
			
		
		
	}
	
	function  hsse_client_tab(){
		
		
		$clientTab = $this->Report->find('all' ,array('conditions' =>array('Report.id'=>base64_decode($this->params['pass'][0]))));
		$this->Session->write('clienttab',$clientTab[0]['Report']['client']);
			
		
	}
	function remedial_email($to,$fullname,$remedialno,$rportno,$remidial_summery,$remidial_closure_date){
		 $subject='';
		 $from = 'Huracan Team';
		 $subject='Your remedial action detail';
	         $message='<html><head><title></title></head><body><table  style="width:550px; border:1px solid #ddd; padding:18px 0 0; margin:20px auto 0; overflow:hidden; background:#fff;" >
<tr><td style=" min-height:60px; border-bottom:1px solid #ddd; padding:0px 15px;">&nbsp;<a href="javascript:void(0);" style="float:left"><img src="'.$this->webroot.'app/webroot/images/huracan_logo.png" alt="" /></a>
	   <p style="float:right; color:#666; font-size:12px; font-family:Arial; text-align:right;">Visit us on <br /> http://www.huracan.com.au</p><br /><br /></td></tr>
<tr><td style="padding:18px 15px 0;">&nbsp;<p style="font-size:12px !important;margin-bottom:30px;">
<strong>Hello ,'.ucwords(strtolower($fullname)).'</strong></p><p style="font-size:12px !important;font-family:Arial">
Your Remedial Action No:'.$remedialno.'</p><p style="font-size:12px !important;font-family:Arial">
Your Report No:'.$rportno.'</p><p style="font-size:12px !important;font-family:Arial">Summary:'.$remidial_summery.'</p>
<p style="font-size:12px !important;font-family:Arial">Closure Date:'.$remidial_closure_date.'</p>
</td></tr><tr><td style="padding:18px 15px; background:#ddd; overflow:hidden;">&nbsp;<p style="float:left;color:#333; font-size:12px; font-family:Arial; text-align:left;">Thank You </p>
<p style="float:right; color:#333; font-size:12px; font-family:Arial; text-align:left;margin-right:55px;"><span>Huracan Pty Ltd</span>
<br/><br/><span>PO Box 1070</span><br/><span>ROMA</span><br/><span>QUEENSLAND 4455</span>
<br/><span>Email: info@huracan.com.au</span></p></td></tr>
<tr><td>&nbsp;</td></tr></table></body></html>';
$this->PhpMailerEmail->send_mail($to,$subject,$message,$from,'','','');
$responsemsg='ok';
               return  $responsemsg; 
		
	}
	
	
	/************************** FOR ADMIN USER GRID PERMISSION START BY DEBI PRASAD SAHOO ON 04-04-2013******************/
	/************************** FOR ADMIN USER GRID PERMISSION START BY DEBI PRASAD SAHOO ON 04-04-2013 ******************/
	function grid_access()
	{
	        switch($this->params['controller']){
			case'Reports':
			$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'AdminMenu.url like' =>'Reports/report_hsse_list','parent_id !='=>0));	
			break;
		        case'Sqreports':
			$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'AdminMenu.url like' =>'Reports/report_hsse_list','parent_id !='=>0));	
			break;
		        case'Jrns':
			$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'AdminMenu.url like' =>'Reports/report_hsse_list','parent_id !='=>0));	
			break;
		        case'Audits':
			$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'AdminMenu.url like' =>'Reports/report_hsse_list','parent_id !='=>0));	
			break;
		        case'Jobs':
			$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'AdminMenu.url like' =>'Reports/report_hsse_list','parent_id !='=>0));	
			break;
		        case'Lessons':
			$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'AdminMenu.url like' =>'Reports/report_hsse_list','parent_id !='=>0));	
			break;
		        case'RoleMasters':
			$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'AdminMenu.url like' =>'RoleMasters/list_roles','parent_id !='=>0));	
			break;
		        case'Certifications':
			$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'AdminMenu.url like' =>'RoleMasters/list_roles','parent_id !='=>0));	
			break;
		        default:
			$condn = array(
			'conditions' => array('RolePermission.role_master_id' => $this->Session->read('adminData.AdminMaster.role_master_id'), 'AdminMenu.url like' => $this->params['controller'].'/'.$this->params['action'],'parent_id !='=>0));
			break;
				
			
		}
		
		$this->RolePermission->bindModel(array('belongsTo' => array('AdminMenu')));
		$roleMenuData = $this->RolePermission->find('first', $condn);
		
	       
	       
	
		if($roleMenuData['RolePermission']['add'] == '1')
		{
			$this->set('is_add', 1);
		}
		else
		{
			$this->set('is_add', 0);
		}
		if($roleMenuData['RolePermission']['view'] == '1')
		{
			$this->set('is_view', 1);
		}
		else
		{
			$this->set('is_view', 0);
		}
		if($roleMenuData['RolePermission']['edit'] == '1')
		{
			$this->set('is_edit', 1);
		}
		else
		{
			$this->set('is_edit', 0);
		}
		if($roleMenuData['RolePermission']['block'] == '1')
		{
			$this->set('is_block', 1);
		}
		else
		{
			$this->set('is_block', 0);
		}
		if($roleMenuData['RolePermission']['delete'] == '1')
		{
			$this->set('is_delete', 1);
		}
		else
		{
			$this->set('is_delete', 0);
		}
	}
	
	function retrive_link_summary($madleVal,$uniqueType,$aTP){
		
		for($u=0;$u<count($uniqueType);$u++){
		    if($uniqueType[$u]=='lesson'){
			
			 $condition= $aTP.".type ='lesson'";
		         $reportType=$madleVal->find('all', array('conditions' => $condition ));
			  
			   for($t=0;$t<count($reportType);$t++){
			       $reportdetail = $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>$reportType[$t][$aTP]['link_report_id'])));
				if(count($reportdetail)>0){
				
				   $linkHolder[$u]['report_number'][$t][]='<a href='.$this->webroot.'Lessons/add_lsreport_view/'.base64_encode($reportdetail[0]['LessonMain']['id']).' target="_blank" >'.$reportdetail[0]['LessonMain']['report_no'].'</a>';
				   $linkHolder[$u]['summary'][$t][]=$reportdetail[0]['LessonMain']['summary'];
				}
			    
			   }
			 
			   
			}	
		      if($uniqueType[$u]=='job'){
			
			  $condition= $aTP.".type ='job'";
			  $reportType=$madleVal->find('all', array('conditions' => $condition));
			  
			   for($t=0;$t<count($reportType);$t++){
			    $reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>$reportType[$t][$aTP]['link_report_id'])));
				if(count($reportdetail)>0){
				
				   $linkHolder[$u]['report_number'][$t][]='<a href='.$this->webroot.'Jobs/add_jobreport_view/'.base64_encode($reportdetail[0]['JobReportMain']['id']).' target="_blank" >'.$reportdetail[0]['JobReportMain']['report_no'].'</a>';
				   $linkHolder[$u]['summary'][$t][]=$reportdetail[0]['JobReportMain']['comment'];
				}
			    
			   }
			 
			   
			}
			 if($uniqueType[$u]=='audit'){
			
		          $condition= $aTP.".type ='audit'";
			  $reportType=$madleVal->find('all', array('conditions' => $condition));
			  
			   for($t=0;$t<count($reportType);$t++){
			  
				$reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>$reportType[$t][$aTP]['link_report_id'])));
					if(count($reportdetail)>0){
						$linkHolder[$u]['report_number'][$t][]='<a href='.$this->webroot.'Audits/add_audit_view/'.base64_encode($reportdetail[0]['AuditReportMain']['id']).' target="_blank" >'.$reportdetail[0]['AuditReportMain']['report_no'].'</a>';
						$linkHolder[$u]['summary'][$t][]=$reportdetail[0]['AuditReportMain']['summary'];
					}
			   }
			 
			   
			} 
	
			 if($uniqueType[$u]=='sq'){
			
				$condition= $aTP.".type ='sq'";
			         $reportType=$madleVal->find('all', array('conditions' => $condition));
			       
				for($t=0;$t<count($reportType);$t++){
			       
					$reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>$reportType[$t][$aTP]['link_report_id'])));
					if(count($reportdetail)>0){
						$linkHolder[$u]['report_number'][$t][]='<a href='.$this->webroot.'SqReports/add_report_view/'.base64_encode($reportdetail[0]['SqReportMain']['id']).' target="_blank" >'.$reportdetail[0]['SqReportMain']['report_no'].'</a>';
						$linkHolder[$u]['summary'][$t][]=$reportdetail[0]['SqReportMain']['summary'];
					}
				}
			 
			   
			}
			if($uniqueType[$u]=='jn'){
			
			   $condition= $aTP.".type ='jn'";
			   $reportType=$madleVal->find('all', array('conditions' => $condition));
			   for($t=0;$t<count($reportType);$t++){
			  
				$reportdetail = $this->JnReportMain->find('all', array('conditions' => array('JnReportMain.id' =>$reportType[$t][$aTP]['link_report_id'])));
				if(count($reportdetail)>0){
			       
				   $linkHolder[$u]['report_number'][$t][]='<a href='.$this->webroot.'Jrns/add_report_view/'.base64_encode($reportdetail[0]['JnReportMain']['id']).' target="_blank" >'.$reportdetail[0]['JnReportMain']['trip_number'].'</a>';
				   $linkHolder[$u]['summary'][$t][]=$reportdetail[0]['JnReportMain']['summary'];
			       
				}
		 
			   
			   }
			 
			   
			}
		     if($uniqueType[$u]=='hsse'){
			
			   $condition= $aTP.".type ='hsse'";
			   $reportType=$madleVal->find('all', array('conditions' => $condition));
	  
				for($t=0;$t<count($reportType);$t++){
			       
				 $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>$reportType[$t][$aTP]['link_report_id'])));
					if(count($reportdetail)){
					     $linkHolder[$u]['report_number'][$t][]='<a href='.$this->webroot.'Report/add_report_view/'.base64_encode($reportdetail[0]['Report']['id']).' target="_blank" >'.$reportdetail[0]['Report']['report_no'].'</a>';
					     $linkHolder[$u]['summary'][$t][]=$reportdetail[0]['Report']['summary'];
					  }
				
				}
			 
			   
			}
		      }
		      
	
		     for($h=0;$h<count($linkHolder);$h++){
			if(isset($linkHolder[$h])){
			
			  for($k=0;$k<count($linkHolder[$h]['report_number']);$k++){
				
				$linkDataHolder['rep_no'][]=$linkHolder[$h]['report_number'][$k][0];
			  
			  }
			  for($k=0;$k<count($linkHolder[$h]['summary']);$k++){
				
				$linkDataHolder['rep_summary'][]=$linkHolder[$h]['summary'][$k][0];
			  
			  }
			}
		     }
		     
		return  $linkDataHolder; 
		
	}

}