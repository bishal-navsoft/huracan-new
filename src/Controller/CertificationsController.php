<?PHP
class CertificationsController extends AppController
{
	public $name = 'Certifications';
	public $uses = array('CertificationList','CertificationMain','CertificationEmail','CertificateAttachment','RolePermission','AdminMenu','AdminMaster',);
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	//var $components = array('PhpMailerEmail');
	public function certification_main_list(){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();		
		$this->layout="after_adminlogin_template";
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['Certifications']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['Certifications']['limit'];
			$this->set('limit', $limit);
		}
		else
		{
			$action = 'all';
			$this->set('action','all');
			$limit =50;
			$this->set('limit', $limit);
		}
		$this->Session->write('action', $action);
		$this->Session->write('limit', $limit);
			
		unset($_SESSION['filter']);
		unset($_SESSION['value']);
		
	}
	
	public function get_all_report($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		if($_SESSION['adminData']['RoleMaster']['id']!=1){ 
		
		   $condition =" CertificationMain.certificate_user =".$_SESSION['adminData']['AdminMaster']['id'];	
		}
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
		case'certificate_type':
	        $certDetail = $this->CertificationList->find('all', array('conditions' => array('CertificationList.type' =>$_REQUEST['value'])));
		if($_SESSION['adminData']['RoleMaster']['id']==1){ 
		     $condition = " CertificationMain.cretficate_id = '".$certDetail[0]['CertificationList']['id']."%'";
		}else{
		     $condition.= "  AND  CertificationMain.cretficate_id = '".$certDetail[0]['CertificationList']['id']."%'";	
		}
		break;	
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		
		$count = $this->CertificationMain->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();
		
		
		
		
		
                $adminA = $this->CertificationMain->find('all' ,array('conditions' => $condition,'order' => 'CertificationMain.id DESC','limit'=>$limit));

		
		$i = 0;
		foreach($adminA as $rec)
		{
		
			
		        $adminA[$i]['CertificationMain']['certificate_user'] =$rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
         		$adminA[$i]['CertificationMain']['certificate_type'] =$rec['CertificationList']['type'];
			$ex_create=explode("-",$rec['CertificationMain']['cert_date']);
			$createDATE=date("d/M/y", mktime(0, 0, 0, $ex_create[1], $ex_create[2],$ex_create[0]));
		        $adminA[$i]['CertificationMain']['cert_date_val'] =$createDATE;
			
			if($rec['CertificationMain']['expire_date']!='0000-00-00'){
			$cer_exp=explode("-",$rec['CertificationMain']['expire_date']);
			$cerExpDATE=date("d/M/y", mktime(0, 0, 0, $cer_exp[1], $cer_exp[2],$cer_exp[0]));
		        $adminA[$i]['CertificationMain']['expire_date_val']=$cerExpDATE;
			}else{
			$adminA[$i]['CertificationMain']['expire_date_val']='';	
			}
			
		    $i++;
		}
		
        
		if($count==0){
			$adminArray=array();
	        }else{
			$adminArray = Set::extract($adminA, '{n}.CertificationMain');
		      }
		
		
		
		$this->set('total', $count);  //send total to the view
		$this->set('admins', $adminArray);  //send products to the view
         	$this->set('status', $action);
	}
	
	
	public function add_certification_main($id=null)
		
	{
		$this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
                 $cList = $this->CertificationList->find('all');
		 $userDetail = $this->AdminMaster->find('all');
         	 $this->set('cList',$cList);
		 $this->set('userDetail',$userDetail);

		 if($id==null)
		 {
          
			$this->set('id','0');
			$this->set('user',0);
			$this->set('heading','Add Certification');
			$this->set('button','Submit');
			$this->set('cretficate_id','');
			$this->set('expire_date_value','');
                        $this->set('cert_date','');
			$this->set('valid_date','');
			$this->set('expire_date','');
			$this->set('trainer','');
		 
		 }else if(base64_decode($id)!=null){
			
	          
		       $reportdetail = $this->CertificationMain->find('all', array('conditions' => array('CertificationMain.id' =>base64_decode($id))));
		       
		       
		        $this->set('id', $reportdetail[0]['CertificationMain']['id']);
			$this->set('user',$reportdetail[0]['CertificationMain']['certificate_user']);
			$this->set('heading','Edit Certification');
			$this->set('button','Update');
			$this->set('cretficate_id',$reportdetail[0]['CertificationMain']['cretficate_id']);
			                  
			$this->set('valid_date',$reportdetail[0]['CertificationMain']['valid_date']);
			
			$this->set('trainer',$reportdetail[0]['CertificationMain']['triner']);
		       
		        if($reportdetail[0]['CertificationMain']['cert_date']!=''){
		                 $rcc=explode("-",$reportdetail[0]['CertificationMain']['cert_date']);
				 $rccdt=$rcc[1]."-".$rcc[2]."-".$rcc[0];
		        }else{
		                $rccdt='';	
		        }
		       
		        $this->set('cert_date',$rccdt);
			
			if($reportdetail[0]['CertificationMain']['expire_date']!=''){
		                 $rcc=explode("-",$reportdetail[0]['CertificationMain']['expire_date']);
				 $cedt=$rcc[2]."/".$rcc[1]."/".$rcc[0];
		        }else{
		                $cedt='';	
		        }
		       
		        $this->set('expire_date_value',$cedt);
		        $this->set('expire_date',$cedt);
			 
		 
		 }
	}
       function certificationprocess(){
			
		
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
           $certMainData=array();
	   
	   
          if($this->data['add_certificate_main_form']['id']==0){
		   $res='add';
	           
	   }else{
		   $res='update';
		  
		   $certMainData['CertificationMain']['id']=$this->data['add_certificate_main_form']['id'];
		   
		   $cerEMAIL = "DELETE FROM `certification_emails` WHERE  `cid` = {$this->data['add_certificate_main_form']['id']}";
		   
                   $deletE=$this->CertificationEmail->query($cerEMAIL);	
	    }

	    
	    
	    $cdt=explode("-",$this->data['cert_date']);
	    $certMainData['CertificationMain']['cert_date']=$cdt[2]."-".$cdt[0]."-".$cdt[1];
	    $extdt=explode("/",$this->data['hepd']);
	    $certMainData['CertificationMain']['expire_date']=$extdt[2]."-".$extdt[1]."-".$extdt[0];

	    $certMainData['CertificationMain']['valid_date']=$this->data['add_certificate_main_form']['valid_date'];
	    $certMainData['CertificationMain']['triner']=$this->data['add_certificate_main_form']['trainer'];
	    $certMainData['CertificationMain']['cretficate_id']=$this->data['cretficate_name'];
	    $certMainData['CertificationMain']['certificate_user']=$this->data['cert_user'];
  
	    if($this->CertificationMain->save($certMainData)){
		 if($res=='add'){
		    $cid=$this->CertificationMain->getLastInsertId();
		 }else{
		    $cid=$this->data['add_certificate_main_form']['id'];	
		 }

		 $emailHolder=array();
		 $emailSendDateHolder=array();
		 $dateIndex=array(14,30,60,90);
		 $currentDate=strtotime(date('Y-m-d'));
		 $dateHolder[]=$certMainData['CertificationMain']['expire_date'];
		 $explodeCTD=explode("-",$certMainData['CertificationMain']['expire_date']);	
	         		
		 for($e=0;$e<count($dateIndex);$e++){
			 $emaildateBefore=date('Y-m-d', mktime(0,0,0,$explodeCTD[1],$explodeCTD[2]-$dateIndex[$e],$explodeCTD[0]));
			 $strEmailBefore=strtotime($emaildateBefore);
			 if($strEmailBefore>$currentDate){
		             $dateHolder[]= $emaildateBefore;
			 }
		   }
		   
		   for($e=0;$e<count($dateIndex);$e++){
			 
			 $emaildateAfter=date('Y-m-d', mktime(0,0,0,$explodeCTD[1],$explodeCTD[2]+$dateIndex[$e],$explodeCTD[0]));
			 $strEmailAfter=strtotime($emaildateAfter);
			 if($emaildateAfter>$currentDate){
		            $dateHolder[]= $emaildateAfter;
			 } 
		   }
		
		for($d=0;$d<count($dateHolder);$d++){
				     
			$certficateEmailList=array();
			$userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$this->data['cert_user'])));
			$fullname=$userdetail[0]['AdminMaster']['first_name'].' '.$userdetail[0]['AdminMaster']['last_name'];
			$to=$this->data['cert_user'];
			$certficateEmailList['CertificationEmail']['cid']=$cid;
			$certficateEmailList['CertificationEmail']['cert_id']=$this->data['cretficate_name'];
			$certficateEmailList['CertificationEmail']['status']='N';
			$certficateEmailList['CertificationEmail']['email_date']=$dateHolder[$d];
			$certficateEmailList['CertificationEmail']['send_to']=$userdetail[0]['AdminMaster']['id'];
			$this->CertificationEmail->create();
			$this->CertificationEmail->save($certficateEmailList);
					
		 }
	
		
		if($res=='add'){
			 $lastReport=base64_encode($this->CertificationMain->getLastInsertId());
		 }elseif($res=='update'){
			 $lastReport=base64_encode($this->data['add_certificate_main_form']['id']);
		 }
	 	
		echo $res.'~'.$lastReport;
		
	    }else{
		echo 'fail';
	    }
            
      
		
	   exit;
	
	
       }
    
	function certification_delete()
	     {
		      $this->layout="ajax";
		      
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
				           $cerEMAIL = "DELETE FROM `certification_emails` WHERE  `cid` = {$id}";
		                           $deletE=$this->CertificationEmail->query($cerEMAIL);
				
					   $deleteLsMain = "DELETE FROM `certification_mains` WHERE `id` = {$id}";
                                           $dlm=$this->CertificationMain->query($deleteLsMain);
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>certification_main_list), null, true);
		      }
			 
			      
			      
			       
	}
	      function add_certificate_attachment($attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";

			$reportList = $this->CertificationMain->find('all');
			$this->set('reportList',$reportList);
			   
			
			if($attachment_id!=''){
			       $attchmentdetail = $this->CertificateAttachment->find('all', array('conditions' => array('CertificateAttachment.id' =>base64_decode($attachment_id))));
				$imagepath=$this->file_edit_list($attchmentdetail[0]['CertificateAttachment']['file_name'],'CertificateAttachment',$attchmentdetail);
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['CertificateAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['CertificateAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['CertificateAttachment']['file_name']);
				$this->set('attachmentstyle','style="display:block;"');
		
			}else{
				$this->set('ridHolder',array());
			  	$this->set('heading','Add attachment');
			        $this->set('attachment_id',0);
			        $this->set('description','');
			        $this->set('button','Add');
			        $this->set('imagepath','');
			        $this->set('imagename','');
				$this->set('attachmentstyle','style="display:none;"');
				$this->set('edit_id',0);
			        $this->set('id_holder',0);
				
			}
			
	
	       }
	        function uploadimage($upparname=NULL,$deleteImageName=NULL){
			$this->layout="ajax";
			$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Certifications',$allowed_image);
			exit;
	       }
	        function  certificateattachmentprocess(){
		         $this->layout="ajax";
		         $attachmentArray=array();
		 
		        if($this->data['Certifications']['id']!=0){
				$res='update';
				$attachmentArray['CertificateAttachment']['id']=$this->data['Certifications']['id'];
			 }else{
				 $res='add';
			  }
			   if($this->data['attachment_description']!=''){
				$attachmentArray['CertificateAttachment']['description']=$this->data['attachment_description'];
			   }else{
				$attachmentArray['CertificateAttachment']['description']='';
			   }
		   	   
			   $attachmentArray['CertificateAttachment']['file_name']=$this->data['hiddenFile'];
			   if($this->CertificateAttachment->save($attachmentArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
			
                         
		   exit;
		
	        }
		
		
		
		
		public function certification_attach_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->CertificationMain->find('all', array('conditions' => array('CertificationMain.id' =>base64_decode($id))));
   
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['CertificationMain']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['CertificationMain']['limit'];
					$this->set('limit', $limit);
				}
				else
				{
					$action = 'all';
					$this->set('action','all');
					$limit =50;
					$this->set('limit', $limit);
				}
		        $this->Session->write('action', $action);
		        $this->Session->write('limit', $limit);
			
		        unset($_SESSION['filter']);
		        unset($_SESSION['value']);
		
	      }
	
	
	
	
	
	public function get_all_attachment_list()
	{
		Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
		$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
		//pr($_REQUEST);
		$this->_checkAdminSession();
		$condition="";
		

		$condition.="CertificateAttachment.isdeleted='N'";
		
                if(isset($_REQUEST['filter'])){
			$condition = " AND  ucase(CertificateAttachment.".$_REQUEST['filter'].") like '".trim($_REQUEST['value'])."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

	   	$adminArray = array();
		
		$count = $this->CertificateAttachment->find('count' ,array('conditions' => $condition));
		$adminA = $this->CertificateAttachment->find('all',array('conditions' => $condition,'order' => 'CertificateAttachment.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['CertificateAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['CertificateAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['CertificateAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['CertificateAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['CertificateAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['CertificateAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['CertificateAttachment']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['CertificateAttachment']['image_src']=$this->attachment_list('CertificateAttachment',$rec);
	
		     $i++;
		     
		}

		  if(count($adminA)>0){
		      $adminArray = Set::extract($adminA, '{n}.CertificateAttachment');
		  }else{
		      $adminArray =array();
		  }
		  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	 
	    function attachment_block($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>admin), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['CertificateAttachment']['id'] = $id;
					  $this->request->data['CertificateAttachment']['isblocked'] = 'Y';
					  $this->CertificateAttachment->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function attachment_unblock($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>admin), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['CertificateAttachment']['id'] = $id;
					  $this->request->data['CertificateAttachment']['isblocked'] = 'N';
					  $this->CertificateAttachment->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function attachment_delete()
	     {
		        $this->layout="ajax";

			 $idArray = explode("^", $this->data['id']);
				
	            	        foreach($idArray as $id)
			       {
				
					  $this->request->data['CertificateAttachment']['id'] = $id;
					  $this->request->data['CertificateAttachment']['isdeleted'] = 'Y';
					  $this->CertificateAttachment->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			   
			       exit;			 
		     
	      }
	      

        function add_certificate_view($id=null){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			if($_SESSION['adminData']['RoleMaster']['id']==1){ 
			  $reportdetail = $this->CertificationMain->find('all');
			}else{
			   $reportdetail = $this->CertificationMain->find('all',array('conditions' => array('CertificationMain.certificate_user' =>$_SESSION['adminData']['AdminMaster']['id'])));	
			}
			$idHolder=array();
			$contentHolder=array();
			$certificatetHolder=array();
			$daylen = 60*60*24;
			$currentDate =date('Y-m-d');
			$daylen = 60*60*24;
			for($c=0;$c<count($reportdetail);$c++){
				$idHolder[]=$reportdetail[$c]['CertificationMain']['certificate_user'];
				$ex_create=explode("-",$reportdetail[$c]['CertificationMain']['cert_date']);
				
				$days=(strtotime($reportdetail[$c]['CertificationMain']['expire_date'])-strtotime($currentDate))/$daylen;
				
				$createDATE=date("d/M/y", mktime(0, 0, 0, $ex_create[1], $ex_create[2],$ex_create[0]));
					
				$reportdetail[$c]['CertificationMain']['cert_date_val'] =$createDATE;
				  	
				
				
						
				$cer_exp=explode("-",$reportdetail[$c]['CertificationMain']['expire_date']);
				$cerExpDATE=date("d/M/y", mktime(0, 0, 0, $cer_exp[1], $cer_exp[2],$cer_exp[0]));
			
				
				
				if($days<1){
				     
				     $reportdetail[$c]['CertificationMain']['expire_date_val'] ='<font color="red">'.$cerExpDATE.'</font>';	
				    	
				}elseif($days>1 && $days<90){
				     $reportdetail[$c]['CertificationMain']['expire_date_val'] ='<font color="orange">'.$cerExpDATE.'</font>';		
					
				}else{
					
				     $reportdetail[$c]['CertificationMain']['expire_date_val'] =$cerExpDATE;
				  	
				}
				
				
				$reportdetail[$c]['CertificationMain']['certificate_user_name'] =$reportdetail[$c]['AdminMaster']['first_name']." ".$reportdetail[$c]['AdminMaster']['last_name'];
			}
		
			$idUniqueHolder=array_values(array_unique($idHolder));
	
			
			for($i=0;$i<count($idUniqueHolder);$i++){
				
				for($c=0;$c<count($reportdetail);$c++){
				      if($idUniqueHolder[$i]==$reportdetail[$c]['CertificationMain']['certificate_user']){
					 $contentHolder[$i][]=$reportdetail[$c]['CertificationMain'];
					 $certificatetHolder[$i][]=$reportdetail[$c]['CertificationList'];
				      }
					
				}
				
			}
			$this->set('certificatetHolder',$certificatetHolder);	
			$this->set('contentHolder',$contentHolder);
			$this->set('reportdetail',$reportdetail);
			
		
		 }
		 
		 function print_view($id=null){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="ajax";
			if($_SESSION['adminData']['RoleMaster']['id']==1){ 
			  $reportdetail = $this->CertificationMain->find('all');
			}else{
			   $reportdetail = $this->CertificationMain->find('all',array('conditions' => array('CertificationMain.certificate_user' =>$_SESSION['adminData']['AdminMaster']['id'])));	
			}
			$idHolder=array();
			$contentHolder=array();
			$certificatetHolder=array();
			$daylen = 60*60*24;
			$currentDate =date('Y-m-d');
			$daylen = 60*60*24;
			for($c=0;$c<count($reportdetail);$c++){
				$idHolder[]=$reportdetail[$c]['CertificationMain']['certificate_user'];
				$ex_create=explode("-",$reportdetail[$c]['CertificationMain']['cert_date']);
				
				$days=(strtotime($reportdetail[$c]['CertificationMain']['expire_date'])-strtotime($currentDate))/$daylen;
				
				$createDATE=date("d/M/y", mktime(0, 0, 0, $ex_create[1], $ex_create[2],$ex_create[0]));
					
				$reportdetail[$c]['CertificationMain']['cert_date_val'] =$createDATE;
				  	
				
				
						
				$cer_exp=explode("-",$reportdetail[$c]['CertificationMain']['expire_date']);
				$cerExpDATE=date("d/M/y", mktime(0, 0, 0, $cer_exp[1], $cer_exp[2],$cer_exp[0]));
			
				
				
				
				if($days<1){
				     
				     $reportdetail[$c]['CertificationMain']['expire_date_val'] ='<font color="red">'.$cerExpDATE.'</font>';	
				    	
				}elseif($days>1 && $days<90){
					
				     $reportdetail[$c]['CertificationMain']['expire_date_val'] ='<font color="orange">'.$cerExpDATE.'</font>';		
					
				}else{
					
				     $reportdetail[$c]['CertificationMain']['expire_date_val'] =$cerExpDATE;
				  	
				}
				
				
				$reportdetail[$c]['CertificationMain']['certificate_user_name'] =$reportdetail[$c]['AdminMaster']['first_name']." ".$reportdetail[$c]['AdminMaster']['last_name'];
			}
		
			$idUniqueHolder=array_values(array_unique($idHolder));
	
			
			for($i=0;$i<count($idUniqueHolder);$i++){
				
				for($c=0;$c<count($reportdetail);$c++){
				      if($idUniqueHolder[$i]==$reportdetail[$c]['CertificationMain']['certificate_user']){
					 $contentHolder[$i][]=$reportdetail[$c]['CertificationMain'];
					 $certificatetHolder[$i][]=$reportdetail[$c]['CertificationList'];
				      }
					
				}
				
			}
			$this->set('certificatetHolder',$certificatetHolder);	
			$this->set('contentHolder',$contentHolder);
			$this->set('reportdetail',$reportdetail);
			
		
		 }
		 
	function date_calculate(){
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();
		$this->layout="ajax";
		if($this->data['valid_date']!='' && $this->data['cert_date']!=''){
		$ymd_format_array=explode("-",$this->data['cert_date']);
		   echo date('d/m/Y', mktime(0,0,0,$ymd_format_array[0],$ymd_format_array[1]+$this->data['valid_date'],$ymd_format_array[2]));
		}else{
		    echo 'fail';	
		}
		exit;
	}
	
	 	function cerficate_email_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->CertificationEmail->find('all');
			
	                if(!empty($this->request->data))
			{
				$action = $this->request->data['CertificationEmail']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['CertificationEmail']['limit'];
				$this->set('limit', $limit);
			}
			else
			{
				$action = 'all';
				$this->set('action','all');
				$limit =50;
				$this->set('limit', $limit);
			}
		        $this->Session->write('action', $action);
		        $this->Session->write('limit', $limit);
			unset($_SESSION['filter']);
			unset($_SESSION['value']);
		
		}
	
	
		public function get_all_certificationemail_list()
		{
			Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
			$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
			//pr($_REQUEST);
			$this->_checkAdminSession();
			$condition="";
		
			
			
			if(isset($_REQUEST['filter'])){
				//echo '<pre>';
				//print_r($_REQUEST);
		
				switch($this->data['filter']){
					case'email_date':
						$explodemonth=explode('/',$this->data['value']);
						$day=$explodemonth[0];
						$month=date('m', strtotime($explodemonth[1]));
						$year="20$explodemonth[2]";
						$createon=$year."-".$month."-".$day;
						$condition = " CertificationEmail.email_date ='".$createon."'";	
					break;
				        case'email':
					     $spliNAME=explode(" ",$_REQUEST['value']);
			                     $spliLname=$spliNAME[count($spliNAME)-1];
			                     $spliFname=$spliNAME[0];
		                             $adminCondition="AdminMaster.admin_email like '%".$_REQUEST['value']."%'";
			                     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
					     $addimid=$userDetail[0]['AdminMaster']['id'];
		                             $condition .= " AND CertificationEmail.send_to ='".$addimid."'";	
					break;
				      				
				}
				
				
				
			}
			
			
			
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				//$condition .= " order by Category.id DESC";
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
	
			//$count = $this->HsseIncident->find('count' ,array('conditions' => $condition)); 
			 $adminArray = array();	
			 $count = $this->CertificationEmail->find('count' ,array('conditions' => $condition));
			 $adminA = $this->CertificationEmail->find('all',array('conditions' => $condition,'order' => 'CertificationEmail.id DESC','limit'=>$limit));
	
			$i = 0;
			foreach($adminA as $rec)
			{
			if($rec['CertificationEmail']['status'] == 'N')
				{
					$adminA[$i]['CertificationEmail']['status_value'] = 'Not Send';
								
				}else{
					$adminA[$i]['CertificationEmail']['status_value'] = "Sent";
					
				
				}
				
			    $rC= $this->CertificationMain->find('all', array('conditions' => array('CertificationMain.id' =>$rec['CertificationEmail']['cid'])));
			    
			    $adminA[$i]['CertificationEmail']['cert_type']= $rec['CertificationList']['type'];
			    $expire_date_ex=explode("-",$rC[0]['CertificationMain']['expire_date']);
			    $adminA[$i]['CertificationEmail']['expire_date_val']=date("d/M/y", mktime(0, 0, 0, $expire_date_ex[1],$expire_date_ex[2],$expire_date_ex[0]));
			    $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['CertificationEmail']['send_to'])));
			    $adminA[$i]['CertificationEmail']['send_person']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
			    $adminA[$i]['CertificationEmail']['email']=$user_detail_reponsibilty[0]['AdminMaster']['admin_email'];
			    
			    $explodcrdt=explode(" ",$rec['CertificationEmail']['modified']);
			    $explodDT=explode("-",$explodcrdt[0]);
                            $adminA[$i]['CertificationEmail']['crt_date']=date("d/M/y", mktime(0, 0, 0, $explodDT[1], $explodDT[2], $explodDT[0]));
			    
			    
			    $explodemaildt=explode("-",$rec['CertificationEmail']['email_date']);
                            $adminA[$i]['CertificationEmail']['email_date']=date("d/M/y", mktime(0, 0, 0, $explodemaildt[1], $explodemaildt[2], $explodemaildt[0]));
			   
			    $i++;
			}
			
			if($count==0){
			   $adminArray=array();
		        }else{
			   $adminArray = Set::extract($adminA, '{n}.CertificationEmail');
		        }
			 
 

			  $this->set('total', $count);  //send total to the view
			  $this->set('admins', $adminArray);  //send products to the view
			  //$this->set('status', $action);
		}
	function cetification_email_delete(){
		 Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
		 $this->layout = "ajax";
		 $cerEMAIL = "DELETE FROM `certification_emails` WHERE  `cid` = {$this->data['id']}";
		 $deletE=$this->CertificationEmail->query($cerEMAIL);
		 echo 'ok';
		 exit;
		
	}
}	