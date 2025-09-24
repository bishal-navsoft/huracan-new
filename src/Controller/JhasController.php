<?PHP
class JhasController extends AppController
{
	public $name = 'Jhas';
	public $uses = array('Report','SuggestionMain','JhaLink','JhaMain','JhaType','RemidialEmailList','SuggestionLink','JhaAttachment','Priority','SuggestionRemidial','JobReportMain','LessonAttachment','LdjsEmail','Service','LessonLink','AdminMaster','LessonMain','SqReportMain','AuditReportMain','JnReportMain','Incident','BusinessType','Fieldlocation','Client','HsseLink','JobLink','LessonLink','IncidentLocation','IncidentSeverity','Country','AdminMaster','RolePermission','RoleMaster','AdminMenu','LessonType');
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	//var $components = array('PhpMailerEmail');
	public function jha_main_list(){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();		
		$this->layout="after_adminlogin_template";
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['Jhas']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['Jhas']['limit'];
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
				unset($_SESSION['idBollen']);
		$idBoolen=array();
		$condition="";
		$condition = "JhaMain.isdeleted = 'N'";
		
		$adminA = $this->JhaMain->find('all' ,array('conditions' => $condition,'order' => 'JhaMain.id DESC','limit'=>$limit));
		for($i=0;$i<count($adminA);$i++){
			if(($_SESSION['adminData']['AdminMaster']['id']==$adminA[$i]['JhaMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
			   array_push($idBoolen,1);	
				
			}else{
			  array_push($idBoolen,0);
			}
			
		}
		$this->Session->write('idBollen',$idBoolen);
	}
	
	public function get_all_report($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		$condition = "JhaMain.isdeleted = 'N'";
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'report_no':
		$condition .= "AND ucase(JhaMain.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
			break;	
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		
		$count = $this->JhaMain->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();
		
		
		$adminA = $this->JhaMain->find('all' ,array('conditions' => $condition,'order' => 'JhaMain.id DESC','limit'=>$limit)); 
               	$idBoolen=array();
		unset($_SESSION['idBollen']);

		$i = 0;
		foreach($adminA as $rec)
		{
		
			
			
			
			if(($_SESSION['adminData']['AdminMaster']['id']==$rec['JhaMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
				array_push($idBoolen,1);
				$adminA[$i]['JhaMain']['edit_permit'] ="false";
				$adminA[$i]['JhaMain']['view_permit'] ="false";
				$adminA[$i]['JhaMain']['delete_permit'] ="false";
				$adminA[$i]['JhaMain']['block_permit'] ="false";
				$adminA[$i]['JhaMain']['unblock_permit'] ="false";
				$adminA[$i]['JhaMain']['checkbox_permit'] ="false";
				
				
				if($rec['JhaMain']['isblocked'] == 'N')
			            {
					$adminA[$i]['JhaMain']['blockHideIndex'] = "true";
					$adminA[$i]['JhaMain']['unblockHideIndex'] = "false";
				        
			         }else{
					$adminA[$i]['JhaMain']['blockHideIndex'] = "false";
					$adminA[$i]['JhaMain']['unblockHideIndex'] = "true";
					
			        }
				
					
			}else{
				
				array_push($idBoolen,0);
				$adminA[$i]['JhaMain']['edit_permit'] ="true";
				$adminA[$i]['JhaMain']['view_permit'] ="false";
				$adminA[$i]['JhaMain']['delete_permit'] ="true";
				$adminA[$i]['JhaMain']['block_permit'] ="true";
				$adminA[$i]['JhaMain']['unblock_permit'] ="true";
			        $adminA[$i]['JhaMain']['blockHideIndex'] = "true";
				$adminA[$i]['JhaMain']['unblockHideIndex'] = "true";
				$adminA[$i]['JhaMain']['checkbox_permit'] ="true";
				
				
			}
		
		
			
			
         		$adminA[$i]['JhaMain']['creater_name'] =$rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
	              	$adminA[$i]['JhaMain']['business_type_name'] =$rec['BusinessType']['type'];
			$adminA[$i]['JhaMain']['filedlocation_type_name'] =$rec['Fieldlocation']['type'];
			$adminA[$i]['JhaMain']['country_name'] =$rec['Country']['name'];
			$validateDetail = $this->AdminMaster->find('all',array('conditions' => array('AdminMaster.id' =>$rec['JhaMain']['validate_by'])));
		        $adminA[$i]['JhaMain']['validate_name'] =$validateDetail[0]['AdminMaster']['first_name'].' '.$validateDetail[0]['AdminMaster']['last_name'];
			$ex_create=explode("-",$rec['JhaMain']['create_date']);
			$createDATE=date("d/M/y", mktime(0, 0, 0, $ex_create[1], $ex_create[2],$ex_create[0]));
		        $adminA[$i]['JhaMain']['create_date_val'] =$createDATE;
			
			if($rec['JhaMain']['validation_date']!=''){
			$val_date=explode("-",$rec['JhaMain']['validation_date']);
			$validateDATE=date("d/M/y", mktime(0, 0, 0, $val_date[1], $val_date[2],$val_date[0]));
		        $adminA[$i]['JhaMain']['validate_date_val']=$validateDATE;
			}else{
			$adminA[$i]['JhaMain']['validate_date_val']='';	
			}
			
			if($rec['JhaMain']['revalidate_date']!=''){
			$rval_date=explode("-",$rec['JhaMain']['revalidate_date']);
			$rvalidateDATE=date("d/M/y", mktime(0, 0, 0, $rval_date[1], $rval_date[2],$rval_date[0]));
		        $adminA[$i]['JhaMain']['rvalidate_date_val']=$rvalidateDATE;
			}else{
			$adminA[$i]['JhaMain']['rvalidate_date_val']='';	
			}
			if($rec['JhaMain']['closer_date']!=' '){
			$cls_date=explode("-",$rec['JhaMain']['closer_date']);
			$lessonCLSDATE=date("d/M/y", mktime(0, 0, 0, $cls_date[1], $cls_date[2],$cls_date[0]));
		        $adminA[$i]['JhaMain']['closer_date_val']=$lessonCLSDATE;
			}
		    $i++;
		}
		
        
		if($count==0){
			$adminArray=array();
	        }else{
			$adminArray = Set::extract($adminA, '{n}.JhaMain');
		      }
		
		
		
		$this->set('total', $count);  //send total to the view
		$this->set('admins', $adminArray);  //send products to the view
         	$this->set('status', $action);
	}
	
	
	public function add_jha_report_main($id=null)
		
	{
		$this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
                 $incidentDetail = $this->Incident->find('all', array('conditions' => array('incident_type' =>'all')));
		 $lType = $this->JhaType->find('all');
		 $this->set('lType',$lType);
     		 $businessDetail = $this->BusinessType->find('all');
		 $fieldlocationDetail = $this->Fieldlocation->find('all');
		 $countryDetail = $this->Country->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('incidentDetail',$incidentDetail);
		 $this->set('businessDetail',$businessDetail);
		 $this->set('fieldlocationDetail',$fieldlocationDetail);
	
		 $this->set('country',$countryDetail);
		 $this->set('userDetail',$userDetail);

		 if($id==null)
		 {
          
			$this->set('id','0');
			$this->set('since_event_hidden',0);
			$this->set('heading','Add Job Hazard Analysis Report (Main)');
			$this->set('button','Submit');
			$this->set('report_no','');
			$this->set('closer_date','00-00-0000');
			$this->set('incident_type','');
			$this->set('created_date','');
			$this->set('business_unit','');
			$this->set('field_location','');
			$this->set('l_type','');
			$this->set('l_type_val',$lType[0]['JhaType']['type']);
			$this->set('summary','');
			$this->set('details','');
			$this->set('validate_user','');
			$this->set('validate_date_val','');
			$this->set('revalidation_date_val','');
			$this->set('cnt',13);
			$this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
			$reportno=date('YmdHis');
			$this->set('reportno',$reportno);
			$this->set('report_id',base64_decode($id));
                        $this->set('incidentLocation_id',0);
			$createDATE=date("d/M/y", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
		        $this->set('create_date',$createDATE);
		 
		 }else if(base64_decode($id)!=null){
			
	          
		       $reportdetail = $this->JhaMain->find('all', array('conditions' => array('JhaMain.id' =>base64_decode($id))));
		       $this->Session->write('report_create',$reportdetail[0]['JhaMain']['created_by']);
                       $adminDATA = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['JhaMain']['created_by'])));
		       $validateBy = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['JhaMain']['validate_by'])));
		        if($reportdetail[0]['JhaMain']['validation_date']!=''){
		                 $valed=explode("-",$reportdetail[0]['JhaMain']['validation_date']);
				 $valedt=$valed[1]."-".$valed[2]."-".$valed[0];
		        }else{
		                $valedt='';	
		        }
	                if($reportdetail[0]['JhaMain']['revalidate_date']!=''){
		                 $rvaled=explode("-",$reportdetail[0]['JhaMain']['revalidate_date']);
				 $rvaledt=$rvaled[1]."-".$rvaled[2]."-".$rvaled[0];
		        }else{
		                $rvaledt='';	
		        }
		       if($reportdetail[0]['JhaMain']['closer_date']!=''){
		                 $clsd=explode("-",$reportdetail[0]['JhaMain']['closer_date']);
				 $closedt= $clsd[1]."-".$clsd[2]."-".$clsd[0];
		        }else{
		                $closedt='';	
		        }
		       
		       $this->set('id',base64_decode($id));
		      	       
			if($reportdetail[0]['JhaMain']['create_date']!=''){
			 
			         $crtd=explode("-",$reportdetail[0]['JhaMain']['create_date']);
			         $createDATE=date("d/M/y", mktime(0, 0, 0, $crtd[1], $crtd[2], $crtd[0]));
			 
			}else{
			         $createDATE=''; 	
			}
	  	        $this->set('create_date',$createDATE);
		        $this->set('heading','Edit Job Hazard Analysis Report (Main)');
		        $this->set('button','Update');
			$this->set('closer_date',$closedt);
		        $this->set('reportno',$reportdetail[0]['JhaMain']['report_no']);
			$this->set('incident_type',$reportdetail[0]['JhaMain']['incident_type']);
			$this->set('cnt',$reportdetail[0]['JhaMain']['country']);
			$this->set('revalidation_date_val',$rvaledt);
			$this->set('validate_date_val',$valedt);
			$this->set('l_type', $reportdetail[0]['JhaMain']['type']);
			$this->set('l_type_val',$reportdetail[0]['JhaType']['type']);
			$this->set('business_unit',$reportdetail[0]['JhaMain']['business_unit']);
			$this->set('validate_user', $reportdetail[0]['JhaMain']['validate_by']);
			$this->set('field_location',$reportdetail[0]['JhaMain']['field_location']);
	      		$this->set('summary',$reportdetail[0]['JhaMain']['summary']);
			$this->set('details',$reportdetail[0]['JhaMain']['details']);
                	$this->set('report_id',base64_decode($id));
			$this->set('created_by',$adminDATA[0]['AdminMaster']['first_name']." ".$adminDATA[0]['AdminMaster']['last_name']);
		 
		 
		 }
	}
       function jhareportprocess(){
			

		
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
           $jhreportMainData=array();
	   
	   
          if($this->data['add_jha_main_form']['id']==0){
		   $res='add';
 	                
	   }else{
		   $res='update';
		   $jhreportMainData['JhaMain']['id']=$this->data['add_jha_main_form']['id'];
		  
	    }
	    
	   if($this->data['validate_date']!=''){
           $vdate=explode("-",$this->data['validate_date']);
	       $jhreportMainData['JhaMain']['validation_date']=$vdate[2]."-".$vdate[0]."-".$vdate[1];
	   }else{
	       $jhreportMainData['JhaMain']['validation_date']='';	
	   }
	   
	   if($this->data['revalidation_date']!=''){
           $rvdate=explode("-",$this->data['revalidation_date']);
	       $jhreportMainData['JhaMain']['revalidate_date']=$rvdate[2]."-".$rvdate[0]."-".$rvdate[1];
	   }else{
	       $jhreportMainData['JhaMain']['revalidate_date']='';	
	   } 
	   
	   $jhreportMainData['JhaMain']['report_no']=$this->data['report_no'];
	   $jhreportMainData['JhaMain']['create_date']=date('Y-m-d'); 
           $jhreportMainData['JhaMain']['business_unit'] =$this->data['business_unit'];  
	   $jhreportMainData['JhaMain']['field_location']=$this->data['field_location'];
	   $jhreportMainData['JhaMain']['country']=$this->data['country'];
	   $jhreportMainData['JhaMain']['validate_by']=$this->data['validate_by'];
	   $jhreportMainData['JhaMain']['type']=$this->data['l_type'];
	   $jhreportMainData['JhaMain']['created_by']=$_SESSION['adminData']['AdminMaster']['id'];
           $jhreportMainData['JhaMain']['summary']=$this->data['add_jha_main_form']['summary'];
	   $jhreportMainData['JhaMain']['details']=$this->data['add_jha_main_form']['details'];
	   
	   $strCreateOn = strtotime('Y-m-d');
	   $revalidationstr = strtotime($jhreportMainData['JhaMain']['revalidate_date']);
	   $dateHolder=array($jhreportMainData['JhaMain']['revalidate_date']);
	   $dateIndex=array(3,7,30);
	 
	   
	 if($this->JhaMain->save($jhreportMainData)){
		if($res=='add'){
			 $lastReport=base64_encode($this->JhaMain->getLastInsertId());
		 }elseif($res=='update'){
			 $lastReport=base64_encode($this->data['add_jha_main_form']['id']);
		 }
	 	
		
		
		 if($jhreportMainData['JhaMain']['revalidate_date']!=''){ 
			for($e=0;$e<count($dateIndex);$e++){
				   $emaildateBefore=date('Y-m-d', mktime(0,0,0,$rvdate[0],$rvdate[1]-$dateIndex[$e],$rvdate[2]));
				   $strEmailBefore=strtotime($emaildateBefore); 
				   
				    if($strCreateOn<$strEmailBefore){
					 $dateHolder[]= $emaildateBefore;
				     
				    }
				    
			     
			     }
					     
			     for($e=0;$e<count($dateIndex);$e++){
				   
				   $emaildateAfter=date('Y-m-d', mktime(0,0,0,$rvdate[0],$rvdate[1]+$dateIndex[$e],$rvdate[2]));
				   $strEmailAfter=strtotime($emaildateAfter);
				    if($strCreateOn<$strEmailAfter){
					 $dateHolder[]= $emaildateAfter;
				      }
			      
			     }
			     $lR=base64_decode($lastReport);
					     
			     $deleteLE = "DELETE FROM `ldjs_emails` WHERE  `leid` = {$lR} AND `type`='jha' ";
			     $deleteval=$this->LdjsEmail->query($deleteLE);

					     
			      for($d=0;$d<count($dateHolder);$d++){
				  
					     $jhaEmailList=array();
					     $userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$_SESSION['adminData']['AdminMaster']['id'])));
					     $fullname=$userdetail[0]['AdminMaster']['first_name'].' '.$userdetail[0]['AdminMaster']['last_name'];
					     $to=$userdetail[0]['AdminMaster']['admin_email'];
					     $jhaEmailList['LdjsEmail']['leid']=base64_decode($lastReport);
					     $jhaEmailList['LdjsEmail']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
					     $jhaEmailList['LdjsEmail']['status']='N';
					     $jhaEmailList['LdjsEmail']['type']='jha';
					     $jhaEmailList['LdjsEmail']['email_date']=$dateHolder[$d];
					     $this->LdjsEmail->create();
					     $this->LdjsEmail->save($jhaEmailList);
				     
			     }
	   
	   
	       }
		
		echo $res.'~'.$lastReport;
		
	    }else{
		echo 'fail';
	    }
            
         
		
	   exit;
	
	
       }
       	function jha_block($id = null)
	{
          
	
		if(!$id)
		{

			   $this->redirect(array('action'=>jha_main_list), null, true);
		}
		else
		{
			 $idArray = explode("^", $id);
			 
			 
		
			foreach($idArray as $id)
			{
				   $id = $id;
				   $this->request->data['JhaMain']['id'] = $id;
				   $this->request->data['JhaMain']['isblocked'] = 'Y';
				   $this->JhaMain->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}
	
	function jha_unblock($id = null)
	{

		
		if(!$id)
		{
	
			$this->redirect(array('action'=>jha_main_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
		
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['JhaMain']['id'] = $id;
				$this->request->data['JhaMain']['isblocked'] = 'N';
				$this->JhanMain->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
	
	function jha_main_delete()
	     {
		      $this->layout="ajax";
		      
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
				
					   $deleteJhLink = "DELETE FROM `jha_links` WHERE `report_id` = {$id}";
                                           $djh=$this->JhaLink->query($deleteJhLink);
					   $deleteJhAttachment = "DELETE FROM `jha_attachments` WHERE `report_id` = {$id}";
                                           $dJhAttach=$this->JhaAttachment->query($deleteJhAttachment);
					   $deleteJhAttachment = "DELETE FROM `jha_attachments` WHERE `report_id` = {$id}";
                                           $dJhAttach=$this->JhaAttachment->query($deleteJhAttachment);
					   $deleteLsEmail = "DELETE FROM  `ldjs_emails` WHERE `leid` = {$id}  AND  `type` ='jha'";
                                           $dls=$this->LdjsEmail->query($deleteLsEmail);
					   $deleteJhMain = "DELETE FROM `jha_mains` WHERE `id` = {$id}";
                                           $dlm=$this->JhaMain->query($deleteJhMain);
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>jha_main_list), null, true);
		      }
			 
			      
			      
			       
	}
	      function add_jha_attachment($reoprt_id=null,$attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";

			$reportList = $this->JhaMain->find('all', array('conditions' => array('JhaMain.isdeleted' =>'N')));
			$this->set('reportList',$reportList);
			   
			
			if($attachment_id!=''){
			       $attchmentdetail = $this->JhaAttachment->find('all', array('conditions' => array('JhaAttachment.id' =>base64_decode($attachment_id))));
				$imagepath=$this->file_edit_list($attchmentdetail[0]['JhaAttachment']['file_name'],'JhaAttachment',$attchmentdetail);
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['JhaAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['JhaAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['JhaAttachment']['file_name']);
				$this->set('attachmentstyle','style="display:block;"');
		
			}else{
				$this->set('ridHolder',array());
			  	$this->set('heading','Add File Attachment');
			        $this->set('attachment_id',0);
			        $this->set('description','');
			        $this->set('button','Add');
			        $this->set('imagepath','');
			        $this->set('imagename','');
				$this->set('attachmentstyle','style="display:none;"');
				$this->set('edit_id',0);
			        $this->set('id_holder',0);
				
			}
			

			$this->set('report_id',base64_decode($reoprt_id));
			
			$reportdetail = $this->JhaMain->find('all', array('conditions' => array('JhaMain.id' =>base64_decode($reoprt_id))));
			$this->set('report_number',$reportdetail[0]['JhaMain']['report_no']);        
			
	       }
	      function uploadimage($upparname=NULL,$deleteImageName=NULL){
		        $this->layout="ajax";
			$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Jhas',$allowed_image);
			exit;
	       }
	        function  jhaattachmentprocess(){
		         $this->layout="ajax";
		         $attachmentArray=array();
	 
		        if($this->data['Jhas']['id']!=0){
				$res='update';
				$attachmentArray['JhaAttachment']['id']=$this->data['Jhas']['id'];
			 }else{
				 $res='add';
			  }
			   if($this->data['attachment_description']!=''){
				$attachmentArray['JhaAttachment']['description']=$this->data['attachment_description'];
			   }else{
				$attachmentArray['JhaAttachment']['description']='';
			   }
		   	   
			   $attachmentArray['JhaAttachment']['file_name']=$this->data['hiddenFile'];
			   $attachmentArray['JhaAttachment']['report_id']=$this->data['report_id'];
			   if($this->JhaAttachment->save($attachmentArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
			
                         
		   exit;
		
	        }
		
		
		
		
		public function report_jha_attachment_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->JhaMain->find('all', array('conditions' => array('JhaMain.id' =>base64_decode($id))));

			$this->set('report_number',$reportdetail[0]['JhaMain']['report_no']);	       
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['JhaAttachment']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['JhaAttachment']['limit'];
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
	
	
	
	
	
	
	
	public function get_all_attachment_list($report_id)
	{
		Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
		$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
		//pr($_REQUEST);
		$this->_checkAdminSession();
		$condition="";
		
		$condition="JhaAttachment.report_id = $report_id  AND  JhaAttachment.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(JhaAttachment.".$_REQUEST['filter'].") like '".trim($_REQUEST['value'])."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

	   	$adminArray = array();	
		$count = $this->JhaAttachment->find('count' ,array('conditions' => $condition));
		$adminA = $this->JhaAttachment->find('all',array('conditions' => $condition,'order' => 'JhaAttachment.id DESC','limit'=>$limit));
		
		
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['JhaAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['JhaAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['JhaAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['JhaAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['JhaAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['JhaAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['JhaAttachment']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['JhaAttachment']['image_src']=$this->attachment_list('JhaAttachment',$rec);
	
		     $i++;
		     
		}
    
		   
		 
		  if(count($adminA)>0){
		      $adminArray = Set::extract($adminA, '{n}.JhaAttachment');
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
					  $this->request->data['JhaAttachment']['id'] = $id;
					  $this->request->data['JhaAttachment']['isblocked'] = 'Y';
					  $this->JhaAttachment->save($this->request->data,false);				
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
					  $this->request->data['JhaAttachment']['id'] = $id;
					  $this->request->data['JhaAttachment']['isblocked'] = 'N';
					  $this->JhaAttachment->save($this->request->data,false);				
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
				
					  $this->request->data['JhaAttachment']['id'] = $id;
					  $this->request->data['JhaAttachment']['isdeleted'] = 'Y';
					  $this->JhaAttachment->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			   
			       exit;			 
		     
	      }
	      
	 public function report_jha_link_list($id=null,$typSearch){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$sqlinkDetail = $this->JhaLink->find('all', array('conditions' => array('JhaLink.report_id' =>base64_decode($id))));
		$reportdetail = $this->JhaMain->find('all', array('conditions' => array('JhaMain.id' =>base64_decode($id))));            
		$this->set('report_number',$reportdetail[0]['JhaMain']['report_no']);
		$this->set('report_id',$id);
		$this->set('id',base64_decode($id));
		
		
		  $this->set('report_id_val',$id);
		  $this->AdminMaster->recursive=2;
		  $userDeatil = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$_SESSION['adminData']['AdminMaster']['id'])));
           	  $reportDeatil=$this->derive_link_data($userDeatil);
		  $this->set('reportDeatil',$reportDeatil);
		
		
		if(!empty($this->request->data))
		{
			//$action = $this->request->data['HssePersonnel']['action'];
			//$this->set('action',$action);
			//$limit = $this->request->data['HssePersonnel']['limit'];
			//$this->set('limit', $limit);
		}
		else
		{
			$action = 'all';
			$this->set('action','all');
			$limit =50;
			$this->set('limit', $limit);
		}
		$this->set('typSearch', base64_decode($typSearch));
		$this->Session->write('action', $action);
		$this->Session->write('limit', $limit);
			
		unset($_SESSION['filter']);
		unset($_SESSION['value']);
		
	}

	  function link_block($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'jha_main_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JhaLink']['id'] = $id;
					  $this->request->data['JhaLink']['isblocked'] = 'Y';
					  $this->JhaLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function link_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'jha_main_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JhaLink']['id'] = $id;
					  $this->request->data['JhaLink']['isblocked'] = 'N';
					  $this->JhaLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function link_delete()
	     {
		      $this->layout="ajax";
		      $this->_checkAdminSession();
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
					  
					  $deleteproperty = "DELETE FROM `jha_links` WHERE `id` = {$id}";
                                          $deleteval=$this->JhaLink->query($deleteproperty);
					  
			       }
		
			       echo 'ok';
			       exit;
		      }else{
			 $this->redirect(array('action'=>'jha_main_list'), null, true);
		      }
			       
			      
			       
	
	      }
	     function  linkrocess(){
		   
		         $this->layout="ajax";
			 $this->_checkAdminSession();
         	         $this->_getRoleMenuPermission();
		         $this->grid_access();
			 $linkArray=array();
			 $explode_id_type=explode("_",$this->data['type']);
		          $sqlinkDetail = $this->JhaLink->find('all', array('conditions' => array('JhaLink.report_id' =>base64_decode($this->data['report_no']),'JhaLink.link_report_id' =>$explode_id_type[1],'JhaLink.type' =>$explode_id_type[0])));
			 if(count($sqlinkDetail)>0){
				echo 'avl';
				
			 }else{
			 $linkArray['JhaLink']['type']=$explode_id_type[0];
			 $linkArray['JhaLink']['report_id']=base64_decode($this->data['report_no']);
			 $linkArray['JhaLink']['link_report_id']=$explode_id_type[1];
			 $linkArray['JhaLink']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
			 
			 if($this->JhaLink->save($linkArray)){
				 echo 'ok';
			 }else{
			         echo 'fail';
			 }
			
			 
			 }
			   
			   
			
                        
		   exit;
		
	}

	
	public function get_all_link_list($report_id,$filterTYPE)
	{
		Configure::write('debug', '2'); 
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
	
                $condition="JhaLink.report_id =".base64_decode($report_id);
		if(isset($_REQUEST['filter'])){
	             $link_type=explode("~",$this->link_search($_REQUEST['value']));
		     
		     $condition .= " AND JhaLink.link_report_id ='".$link_type[0]."' AND JhaLink.type ='".$link_type[1]."'";
		     
		}
		
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 $adminArray = array();
		 if($filterTYPE!='all'){
			
		    $condition .= " AND JhaLink.type ='".$filterTYPE."'";
		
		 }
		 $count = $this->JhaLink->find('count' ,array('conditions' => $condition));
		 $adminA = $this->JhaLink->find('all',array('conditions' => $condition,'order' => 'JhaLink.id DESC','limit'=>$limit));
		 

		  
		$i = 0;
		foreach($adminA as $rec)
		{
	
			if($rec['JhaLink']['isblocked'] == 'N')
			{
				$adminA[$i]['JhaLink']['blockHideIndex'] = "true";
				$adminA[$i]['JhaLink']['unblockHideIndex'] = "false";
				$adminA[$i]['JhaLink']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['JhaLink']['blockHideIndex'] = "false";
				$adminA[$i]['JhaLink']['unblockHideIndex'] = "true";
				$adminA[$i]['JhaLink']['isdeletdHideIndex'] = "false";
			}
			$link_type=$this->link_grid($adminA[$i],$rec['JhaLink']['type'],'JhaLink',$rec);
		        $explode_link_type=explode("~",$link_type);
		        $adminA[$i]['JhaLink']['link_report_no']=$explode_link_type[0];
		        $adminA[$i]['JhaLink']['type_name']=$explode_link_type[1];
				
		    $i++;
		}
		
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.JhaLink');
		  }
	 
		  
                  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
        function add_jha_view($id=null){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
                        $reportdetail = $this->JhaMain->find('all', array('conditions' => array('JhaMain.id' =>base64_decode($id)),'recursive'=>2));
                        $this->Session->write('report_create',$reportdetail[0]['JhaMain']['created_by']);
			$rdate_time=explode("-",$reportdetail[0]['JhaMain']['create_date']);
		        $reportdetail[0]['JhaMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
			
			if($reportdetail[0]['JhaMain']['validation_date']!=''){
			$vdate_time=explode("-",$reportdetail[0]['JhaMain']['validation_date']);
		        $reportdetail[0]['JhaMain']['validation_date_val']=date("d-M-y", mktime(0, 0, 0, $vdate_time[1],$vdate_time[2],$vdate_time[0]));
			}else{
			 $reportdetail[0]['JhaMain']['validation_date_val']='';	
			}
			if($reportdetail[0]['JhaMain']['revalidate_date']!=''){
			$rvdate_time=explode("-",$reportdetail[0]['JhaMain']['revalidate_date']);
		        $reportdetail[0]['JhaMain']['revalidate_date_val']=date("d-M-y", mktime(0, 0, 0, $rvdate_time[1],$rvdate_time[2],$rvdate_time[0]));
			}else{
			$reportdetail[0]['JhaMain']['revalidate_date_val']='';	
			}
			$validateBy= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['JhaMain']['validate_by']))); 
			$reportdetail[0]['JhaMain']['validate_by_person']=$validateBy[0]['AdminMaster']['first_name']." ".$validateBy[0]['AdminMaster']['last_name'];
			
		
			if(isset($reportdetail[0]['JhaLink'][0])){
			      $linkHolder=array();
				$typeHolder=array();
				$linkDataHolder=array();
				
				for($i=0;$i<count($reportdetail[0]['JhaLink']);$i++){
					$typeHolder[]=$reportdetail[0]['JhaLink'][$i]['type'];
				}
			$uniqueType=array_values(array_unique($typeHolder));
			$madleVal=$this->JhaLink;
			$linkDataHolder=$this->retrive_link_summary($madleVal,$uniqueType,'JhaLink');
			
	            	if(count($linkDataHolder)>0){
					      $this->set('linkDataHolder',$linkDataHolder);
					 }else{
					      $this->set('linkDataHolder',array());	
					 }
			
			}else{
			    $this->set('linkDataHolder',array());
			}
			
		     $this->set('reportdetail',$reportdetail);
		 }
		 
		 function print_view($id=null){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="ajax";
                        $reportdetail = $this->JhaMain->find('all', array('conditions' => array('JhaMain.id' =>base64_decode($id)),'recursive'=>2));

			$rdate_time=explode("-",$reportdetail[0]['JhaMain']['create_date']);
		        $reportdetail[0]['JhaMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
			
			if($reportdetail[0]['JhaMain']['validation_date']!=''){
			$vdate_time=explode("-",$reportdetail[0]['JhaMain']['validation_date']);
		        $reportdetail[0]['JhaMain']['validation_date_val']=date("d-M-y", mktime(0, 0, 0, $vdate_time[1],$vdate_time[2],$vdate_time[0]));
			}else{
			 $reportdetail[0]['JhaMain']['validation_date_val']='';	
			}
			if($reportdetail[0]['JhaMain']['revalidate_date']!=''){
			$rvdate_time=explode("-",$reportdetail[0]['JhaMain']['revalidate_date']);
		        $reportdetail[0]['JhaMain']['revalidate_date_val']=date("d-M-y", mktime(0, 0, 0, $rvdate_time[1],$rvdate_time[2],$rvdate_time[0]));
			}else{
			$reportdetail[0]['JhaMain']['revalidate_date_val']='';	
			}
			$validateBy= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['JhaMain']['validate_by']))); 
			$reportdetail[0]['JhaMain']['validate_by_person']=$validateBy[0]['AdminMaster']['first_name']." ".$validateBy[0]['AdminMaster']['last_name'];
			
		
			if(isset($reportdetail[0]['JhaLink'][0])){
			      $linkHolder=array();
				$typeHolder=array();
				$linkDataHolder=array();
				
				for($i=0;$i<count($reportdetail[0]['JhaLink']);$i++){
					$typeHolder[]=$reportdetail[0]['JhaLink'][$i]['type'];
				}
			$uniqueType=array_values(array_unique($typeHolder));
			$madleVal=$this->JhaLink;
			$linkDataHolder=$this->retrive_link_summary($madleVal,$uniqueType,'JhaLink');
			
	            	if(count($linkDataHolder)>0){
					      $this->set('linkDataHolder',$linkDataHolder);
					 }else{
					      $this->set('linkDataHolder',array());	
					 }
			
			}else{
			    $this->set('linkDataHolder',array());
			}
			
		     $this->set('reportdetail',$reportdetail);
		
		 }
		 
			
		
	    	function jha_email_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->JhaMain->find('all', array('conditions' => array('JhaMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
	               
		    
	
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['JhaMain']['report_no']);
			if(!empty($this->request->data))
			{
				$action = $this->request->data['LdjsEmail']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['LdjsEmail']['limit'];
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
	
	
		public function get_all_jha_email_list($report_id)
		{
			Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
			$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
			//pr($_REQUEST);
			$this->_checkAdminSession();
			$condition="";

			
			$condition="LdjsEmail.leid = $report_id";
			
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
						$condition .= " AND LdjsEmail.email_date ='".$createon."'";	
					break;
				        case'create_by_email':
					     $spliNAME=explode(" ",$_REQUEST['value']);
			                     $spliLname=$spliNAME[count($spliNAME)-1];
			                     $spliFname=$spliNAME[0];
		                             $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			                     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
					     $addimid=$userDetail[0]['AdminMaster']['id'];
		                             $condition .= " AND LdjsEmail.send_to ='".$addimid."'";	
					break;
				      				
				}
				
				
				
			}
			
			$condition.=" AND  LdjsEmail.type = 'jha'";
			
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				//$condition .= " order by Category.id DESC";
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
	
			//$count = $this->HsseIncident->find('count' ,array('conditions' => $condition)); 
			 $adminArray = array();	
			 $count = $this->LdjsEmail->find('count' ,array('conditions' => $condition));
			 $adminA = $this->LdjsEmail->find('all',array('conditions' => $condition,'order' =>'LdjsEmail.id DESC','limit'=>$limit));
	
			$i = 0;
			foreach($adminA as $rec)
			{			
			if($rec['LdjsEmail']['status'] == 'N')
				{
					$adminA[$i]['LdjsEmail']['status_value'] = 'Not Send';
								
				}else{
					$adminA[$i]['LdjsEmail']['status_value'] = "Sent";
					
				
				}
				
			    $remC= $this->JhaMain->find('all', array('conditions' => array('JhaMain.id' =>$rec['LdjsEmail']['leid'])));
			    if($remC[0]['JhaMain']['revalidate_date']!=''){
				$rrd=explode(" ",$remC[0]['JhaMain']['revalidate_date']);
				$rrdE=explode("-",$rrd[0]);
				$adminA[$i]['LdjsEmail']['revalidate_date_val']=date("d/M/y", mktime(0, 0, 0, $rrdE[1], $rrdE[2], $rrdE[0]));
			    }else{
			        $adminA[$i]['LdjsEmail']['revalidate_date_val']='';
			    }
			    
			    if($remC[0]['JhaMain']['create_date']!=''){
			        $create_on=explode("-",$remC[0]['JhaMain']['create_date']);
				$adminA[$i]['LdjsEmail']['create_on_val']=date("d/M/y", mktime(0, 0, 0, $create_on[1], $create_on[2], $create_on[0]));
			    }
			    
			    
			   
			    $user_detail_createby= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['LdjsEmail']['user_id'])));
			    $adminA[$i]['LdjsEmail']['createby_person']=$user_detail_createby[0]['AdminMaster']['first_name']."  ".$user_detail_createby[0]['AdminMaster']['last_name'];
			    $adminA[$i]['LdjsEmail']['email']=$user_detail_createby[0]['AdminMaster']['admin_email'];
			    if($rec['LdjsEmail']['email_date']!=''){
			        $explodemail=explode("-",$rec['LdjsEmail']['email_date']);
                                $adminA[$i]['LdjsEmail']['email_date']=date("d/M/y", mktime(0, 0, 0, $explodemail[1], $explodemail[2], $explodemail[0]));
			    }
			    $i++;
			}
			
			if($count==0){
			   $adminArray=array();
		        }else{
			   $adminArray = Set::extract($adminA, '{n}.LdjsEmail');
		        }
			 

			  
			  
			  $this->set('total', $count);  //send total to the view
			  $this->set('admins', $adminArray);  //send products to the view
			  //$this->set('status', $action);
		}
        function jha_email_view($id,$leid){
		
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
                 $this->grid_access();
		 $this->layout="ajax";
		 $documentEmail = $this->LdjsEmail->find('all', array('conditions' =>array('LdjsEmail.id' =>$id,'LdjsEmail.leid' =>$leid,'LdjsEmail.type' =>'jha'),'recursive'=>2));
                 $reportdetail = $this->JhaMain->find('all', array('conditions' => array('JhaMain.id' =>$documentEmail[0]['LdjsEmail']['leid'])));            
		 $vx=explode("-",$reportdetail[0]['JhaMain']['validation_date']);
		 $v_date=date("d/M/y", mktime(0, 0, 0, $vx[1], $vx[2],$vx[0]));
		 $rvd=explode("-",$reportdetail[0]['JhaMain']['revalidate_date']);
		 $rv_date=date("d/M/y", mktime(0, 0, 0, $rvd[1], $rvd[2],$rvd[0]));
		 $this->set('v_date', $v_date);
		 $this->set('rv_date', $rv_date);
		 $fullname=$documentEmail[0]['AdminMaster']['first_name'].' '.$documentEmail[0]['AdminMaster']['last_name'];
		 $this->set('fullname', $fullname);
		 $this->set('documentEmail', $documentEmail);
		 $this->set('reportdetail', $reportdetail);
		
	}
	
        
	      
}	