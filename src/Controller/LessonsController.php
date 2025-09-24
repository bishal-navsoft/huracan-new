<?PHP
class LessonsController extends AppController
{
	public $name = 'Lessons';
	public $uses = array('Report','JobReportMain','DocumentMain','JhaMain','SuggestionMain','LessonAttachment','LdjsEmail','Service','LessonLink','AdminMaster','LessonMain','SqReportMain','AuditReportMain','JnReportMain','Incident','BusinessType','Fieldlocation','Client','HsseLink','JobLink','LessonLink','IncidentLocation','IncidentSeverity','Country','AdminMaster','RolePermission','RoleMaster','AdminMenu','LessonType');
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	//var $components = array('PhpMailerEmail');
	public function lesson_main_list(){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();		
		$this->layout="after_adminlogin_template";
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['Lessons']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['Lessons']['limit'];
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
		$idLessonBoolen=array();
		$condition="";
		$condition = "LessonMain.isdeleted = 'N'";
		
		$adminA = $this->LessonMain->find('all' ,array('conditions' => $condition,'order' => 'LessonMain.id DESC','limit'=>$limit));
		for($i=0;$i<count($adminA);$i++){
			if(($_SESSION['adminData']['AdminMaster']['id']==$adminA[$i]['LessonMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
			   array_push($idLessonBoolen,1);	
				
			}else{
			  array_push($idLessonBoolen,0);
			}
			
		}
		$this->Session->write('idLessonBoolen',$idLessonBoolen);	
		unset($_SESSION['filter']);
		unset($_SESSION['value']);
		
	}
	
	public function get_all_report($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		$condition = "LessonMain.isdeleted = 'N'";
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'report_no':
		$condition .= "AND ucase(LessonMain.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
			break;	
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		
		$count = $this->LessonMain->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();
		
		 $adminA = $this->LessonMain->find('all' ,array('conditions' => $condition,'order' => 'LessonMain.id DESC','limit'=>$limit)); 
		
	
                
		$idLessonBoolen=array();
		unset($_SESSION['idLessonBoolen']);
		$i = 0;
		foreach($adminA as $rec)
		{
                        array_push($idLessonBoolen,1);
			if(($_SESSION['adminData']['AdminMaster']['id']==$rec['LessonMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
				
				$adminA[$i]['LessonMain']['edit_permit'] ="false";
				$adminA[$i]['LessonMain']['view_permit'] ="false";
				$adminA[$i]['LessonMain']['delete_permit'] ="false";
				$adminA[$i]['LessonMain']['block_permit'] ="false";
				$adminA[$i]['LessonMain']['unblock_permit'] ="false";
				$adminA[$i]['LessonMain']['checkbox_permit'] ="false";
				
				if($rec['LessonMain']['isblocked'] == 'N')
			            {
					$adminA[$i]['LessonMain']['blockHideIndex'] = "true";
					$adminA[$i]['LessonMain']['unblockHideIndex'] = "false";
				        
			         }else{
					$adminA[$i]['LessonMain']['blockHideIndex'] = "false";
					$adminA[$i]['LessonMain']['unblockHideIndex'] = "true";
					
			        }
				
					
			}else{
				array_push($idLessonBoolen,0);
				$adminA[$i]['LessonMain']['edit_permit'] ="true";
				$adminA[$i]['LessonMain']['view_permit'] ="false";
				$adminA[$i]['LessonMain']['delete_permit'] ="true";
				$adminA[$i]['LessonMain']['block_permit'] ="true";
				$adminA[$i]['LessonMain']['unblock_permit'] ="true";
			        $adminA[$i]['LessonMain']['blockHideIndex'] = "true";
				$adminA[$i]['LessonMain']['unblockHideIndex'] = "true";
				$adminA[$i]['LessonMain']['checkbox_permit'] ="true";
				
				
			}
			
         		$adminA[$i]['LessonMain']['creater_name'] =$rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
	                $adminA[$i]['LessonMain']['incident_type_name'] =$rec['Incident']['incident_type'];
			$adminA[$i]['LessonMain']['services_name'] =$rec['Service']['type'];
			$adminA[$i]['LessonMain']['business_type_name'] =$rec['BusinessType']['type'];
			$adminA[$i]['LessonMain']['filedlocation_type_name'] =$rec['Fieldlocation']['type'];
			$adminA[$i]['LessonMain']['country_name'] =$rec['Country']['name'];
			$validateDetail = $this->AdminMaster->find('all',array('conditions' => array('AdminMaster.id' =>$rec['LessonMain']['validate_by'])));
			$adminA[$i]['LessonMain']['validate_name'] =$validateDetail[0]['AdminMaster']['first_name'].' '.$validateDetail[0]['AdminMaster']['last_name'];
			$ex_create=explode("-",$rec['LessonMain']['create_date']);
			$createDATE=date("d/M/y", mktime(0, 0, 0, $ex_create[1], $ex_create[2],$ex_create[0]));
		        $adminA[$i]['LessonMain']['create_date_val'] =$createDATE;
			
			if($rec['LessonMain']['validation_date']!=''){
			$val_date=explode("-",$rec['LessonMain']['validation_date']);
			$validateDATE=date("d/M/y", mktime(0, 0, 0, $val_date[1], $val_date[2],$val_date[0]));
		        $adminA[$i]['LessonMain']['validate_date_val']=$validateDATE;
			}else{
			$adminA[$i]['LessonMain']['validate_date_val']='';	
			}
			
			if($rec['LessonMain']['revalidate_date']!=''){
			$rval_date=explode("-",$rec['LessonMain']['revalidate_date']);
			$rvalidateDATE=date("d/M/y", mktime(0, 0, 0, $rval_date[1], $rval_date[2],$rval_date[0]));
		        $adminA[$i]['LessonMain']['rvalidate_date_val']=$rvalidateDATE;
			}else{
			$adminA[$i]['LessonMain']['rvalidate_date_val']='';	
			}
			if($rec['LessonMain']['closer_date']!='0000-00-00'){
			$cls_date=explode("-",$rec['LessonMain']['closer_date']);
			$lessonCLSDATE=date("d/M/y", mktime(0, 0, 0, $cls_date[1], $cls_date[2],$cls_date[0]));
		        $adminA[$i]['LessonMain']['closer_date_val']=$lessonCLSDATE;
			}
		    $i++;
		}
		
        
		if($count==0){
			$adminArray=array();
	        }else{
			$adminArray = Set::extract($adminA, '{n}.LessonMain');
		      }
		
		
		$this->Session->write('idLessonBoolen',$idLessonBoolen);	
		$this->set('total', $count);  //send total to the view
		$this->set('admins', $adminArray);  //send products to the view
         	$this->set('status', $action);
	}
	
	
	public function add_lesson_report_main($id=null)
		
	{
		$this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
                 $incidentDetail = $this->Incident->find('all', array('conditions' => array('incident_type' =>'all')));
		 $lType = $this->LessonType->find('all');
		 $this->set('lType',$lType);
     		 $businessDetail = $this->BusinessType->find('all');
		 $fieldlocationDetail = $this->Fieldlocation->find('all');
		 $service = $this->Service->find('all');
		 $countryDetail = $this->Country->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('service',$service);
		 $this->set('incidentDetail',$incidentDetail);
		 $this->set('businessDetail',$businessDetail);
		 $this->set('fieldlocationDetail',$fieldlocationDetail);
	
		 $this->set('country',$countryDetail);
		 $this->set('userDetail',$userDetail);

		 if($id==null)
		 {
          
			$this->set('id','0');
			$this->set('since_event_hidden',0);
			$this->set('heading','Add Best Practice Lesson Report (Main)');
			$this->set('button','Submit');
			$this->set('report_no','');
			$this->set('closer_date','00-00-0000');
			$this->set('incident_type','');
			$this->set('service_type','');
			$this->set('created_date','');
			$this->set('business_unit','');
			$this->set('field_location','');
			$this->set('l_type','');
			$this->set('l_type_val',$lType[0]['LessonType']['type']);
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
			
	          
		       $reportdetail = $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>base64_decode($id))));
		        $this->Session->write('report_create',$reportdetail[0]['LessonMain']['created_by']);
                       $adminDATA = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['LessonMain']['created_by'])));
		       $validateBy = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['LessonMain']['validate_by'])));
		        if($reportdetail[0]['LessonMain']['validation_date']!=''){
		                 $valed=explode("-",$reportdetail[0]['LessonMain']['validation_date']);
				 $valedt=$valed[1]."-".$valed[2]."-".$valed[0];
		        }else{
		                $valedt='';	
		        }
	                if($reportdetail[0]['LessonMain']['revalidate_date']!=''){
		                 $rvaled=explode("-",$reportdetail[0]['LessonMain']['revalidate_date']);
				 $rvaledt=$rvaled[1]."-".$rvaled[2]."-".$rvaled[0];
		        }else{
		                $rvaledt='';	
		        }
		       if($reportdetail[0]['LessonMain']['closer_date']!=''){
		                 $clsd=explode("-",$reportdetail[0]['LessonMain']['closer_date']);
				 $closedt= $clsd[1]."-".$clsd[2]."-".$clsd[0];
		        }else{
		                $closedt='';	
		        }
		       
		       $this->set('id',base64_decode($id));
		      	       
			if($reportdetail[0]['LessonMain']['create_date']!=''){
			 
			         $crtd=explode("-",$reportdetail[0]['LessonMain']['create_date']);
			         $createDATE=date("d/M/y", mktime(0, 0, 0, $crtd[1], $crtd[2], $crtd[0]));
			 
			}else{
			         $createDATE=''; 	
			}
	  	        $this->set('create_date',$createDATE);
		        $this->set('heading','Edit Best Practice Lesson Report (Main)');
		        $this->set('button','Update');
			$this->set('closer_date',$closedt);
		        $this->set('reportno',$reportdetail[0]['LessonMain']['report_no']);
			$this->set('incident_type',$reportdetail[0]['LessonMain']['incident_type']);
			$this->set('cnt',$reportdetail[0]['LessonMain']['country']);
			$this->set('revalidation_date_val',$rvaledt);
			$this->set('validate_date_val',$valedt);
			$this->set('l_type', $reportdetail[0]['LessonMain']['type']);
			$this->set('l_type_val',$reportdetail[0]['LessonType']['type']);
			$this->set('business_unit',$reportdetail[0]['LessonMain']['business_unit']);
			$this->set('validate_user', $reportdetail[0]['LessonMain']['validate_by']);
			$this->set('field_location',$reportdetail[0]['LessonMain']['field_location']);
			$this->set('service_type',$reportdetail[0]['LessonMain']['services']);
	      		$this->set('summary',$reportdetail[0]['LessonMain']['summary']);
			$this->set('details',$reportdetail[0]['LessonMain']['details']);
                	$this->set('report_id',base64_decode($id));
			$this->set('created_by',$adminDATA[0]['AdminMaster']['first_name']." ".$adminDATA[0]['AdminMaster']['last_name']);
		 
		 
		 }
	}
       function lsreportprocess(){
			
		
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
           $lsreportMainData=array();
	   
	   
          if($this->data['add_lesson_main_form']['id']==0){
		   $res='add';
 	                
	   }else{
		   $res='update';
		   $lsreportMainData['LessonMain']['id']=$this->data['add_lesson_main_form']['id'];
		  
	    }
	    
	   if($this->data['validate_date']!=''){
           $vdate=explode("-",$this->data['validate_date']);
	       $lsreportMainData['LessonMain']['validation_date']=$vdate[2]."-".$vdate[0]."-".$vdate[1];
	   }else{
	       $lsreportMainData['LessonMain']['validation_date']='';	
	   }
	   
	   if($this->data['revalidation_date']!=''){
           $rvdate=explode("-",$this->data['revalidation_date']);
	       $lsreportMainData['LessonMain']['revalidate_date']=$rvdate[2]."-".$rvdate[0]."-".$rvdate[1];
	   }else{
	       $lsreportMainData['LessonMain']['revalidate_date']='';	
	   } 
	   
	   $lsreportMainData['LessonMain']['report_no']=$this->data['report_no'];
	   $lsreportMainData['LessonMain']['create_date']=date('Y-m-d'); 
	   $lsreportMainData['LessonMain']['incident_type']=$this->data['incident_type'];
	   $lsreportMainData['LessonMain']['business_unit'] =$this->data['business_unit'];  
	   $lsreportMainData['LessonMain']['field_location']=$this->data['field_location'];
	   $lsreportMainData['LessonMain']['country']=$this->data['country'];
	   $lsreportMainData['LessonMain']['validate_by']=$this->data['validate_by'];
	   $lsreportMainData['LessonMain']['type']=$this->data['l_type'];
	   $lsreportMainData['LessonMain']['services']=$this->data['service'];
	   $lsreportMainData['LessonMain']['created_by']=$_SESSION['adminData']['AdminMaster']['id'];
           $lsreportMainData['LessonMain']['summary']=$this->data['add_lesson_main_form']['summary'];
	   $lsreportMainData['LessonMain']['details']=$this->data['add_lesson_main_form']['details'];
	   
	   $strCreateOn = strtotime('Y-m-d');
	   $revalidationstr = strtotime($lsreportMainData['LessonMain']['revalidate_date']);
	   $dateHolder=array($lsreportMainData['LessonMain']['revalidate_date']);
	   $dateIndex=array(3,7,30);
	 
	   
	 if($this->LessonMain->save($lsreportMainData)){
		if($res=='add'){
			 $lastReport=base64_encode($this->LessonMain->getLastInsertId());
		 }elseif($res=='update'){
			 $lastReport=base64_encode($this->data['add_lesson_main_form']['id']);
		 }
	 	
		
		
		 if($lsreportMainData['LessonMain']['revalidate_date']!=''){ 
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
					     
			     $deleteLE = "DELETE FROM `ldjs_emails` WHERE  `leid` = {$lR} AND `type`='lesson' ";
			     $deleteval=$this->LdjsEmail->query($deleteLE);
					     
			     for($d=0;$d<count($dateHolder);$d++){
				  
					     $lessonEmailList=array();
					     $userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$_SESSION['adminData']['AdminMaster']['id'])));
					     $fullname=$userdetail[0]['AdminMaster']['first_name'].' '.$userdetail[0]['AdminMaster']['last_name'];
					     $to=$userdetail[0]['AdminMaster']['admin_email'];
					     $lessonEmailList['LdjsEmail']['leid']=base64_decode($lastReport);
					     $lessonEmailList['LdjsEmail']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
					     $lessonEmailList['LdjsEmail']['status']='N';
					     $lessonEmailList['LdjsEmail']['type']='lesson';
					     $lessonEmailList['LdjsEmail']['email_date']=$dateHolder[$d];
					     $this->LdjsEmail->create();
					     $this->LdjsEmail->save($lessonEmailList);
				     
			     }
	   
	   
	       }
		
		echo $res.'~'.$lastReport;
		
	    }else{
		echo 'fail';
	    }
            
         
		
	   exit;
	
	
       }
       	function lesson_block($id = null)
	{
          
	
		if(!$id)
		{

			   $this->redirect(array('action'=>lesson_main_list), null, true);
		}
		else
		{
			 $idArray = explode("^", $id);
			 
			 
		
			foreach($idArray as $id)
			{
				   $id = $id;
				   $this->request->data['LessonMain']['id'] = $id;
				   $this->request->data['LessonMain']['isblocked'] = 'Y';
				   $this->LessonMain->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}
	
	function lesson_unblock($id = null)
	{

		
		if(!$id)
		{
	
			$this->redirect(array('action'=>lesson_main_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
		
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['LessonMain']['id'] = $id;
				$this->request->data['LessonMain']['isblocked'] = 'N';
				$this->LessonMain->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
	
	function lessons_main_delete()
	     {
		      $this->layout="ajax";
		      
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
				
					   $deleteLsLink = "DELETE FROM `lesson_links` WHERE `report_id` = {$id}";
                                           $dls=$this->LessonLink->query($deleteLsLink);
		        $deleteLsEmail = "DELETE FROM  `ldjs_emails` WHERE `leid` = {$id}  AND  `type` ='lesson'";
                                           $dls=$this->LdjsEmail->query($deleteLsEmail);
					   $deleteLsAttachment = "DELETE FROM `lesson_attachments` WHERE `report_id` = {$id}";
                                           $dlA=$this->LessonAttachment->query($deleteLsAttachment);
					   $deleteLsMain = "DELETE FROM `lesson_mains` WHERE `id` = {$id}";
                                           $dlm=$this->LessonMain->query($deleteLsMain);
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>lesson_main_list), null, true);
		      }
			 
			      
			      
			       
	}
	      function add_lesson_attachment($reoprt_id=null,$attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";

			$reportList = $this->LessonMain->find('all', array('conditions' => array('LessonMain.isdeleted' =>'N')));
			$this->set('reportList',$reportList);
			   
			
			if($attachment_id!=''){
			      $attchmentdetail = $this->LessonAttachment->find('all', array('conditions' => array('LessonAttachment.id' =>base64_decode($attachment_id))));
				$imagepath=$this->file_edit_list($attchmentdetail[0]['LessonAttachment']['file_name'],'LessonAttachment',$attchmentdetail);
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['LessonAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['LessonAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['LessonAttachment']['file_name']);
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
			

			$this->set('report_id',base64_decode($reoprt_id));
			
			$reportdetail = $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>base64_decode($reoprt_id))));
			$this->set('report_number',$reportdetail[0]['LessonMain']['report_no']);        
			
	       }
	       function uploadimage($upparname=NULL,$deleteImageName=NULL){
		        $this->layout="ajax";
		  	$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Lessons',$allowed_image);
			exit;
	       }
	        function  lessonattachmentprocess(){
		         $this->layout="ajax";
		         $attachmentArray=array();
		 
		        if($this->data['Lessons']['id']!=0){
				$res='update';
				$attachmentArray['LessonAttachment']['id']=$this->data['Lessons']['id'];
			 }else{
				 $res='add';
			  }
			   if($this->data['attachment_description']!=''){
				$attachmentArray['LessonAttachment']['description']=$this->data['attachment_description'];
			   }else{
				$attachmentArray['LessonAttachment']['description']='';
			   }
		   	   
			   $attachmentArray['LessonAttachment']['file_name']=$this->data['hiddenFile'];
			   $attachmentArray['LessonAttachment']['report_id']=$this->data['report_id'];
			   if($this->LessonAttachment->save($attachmentArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
			
                         
		   exit;
		
	        }
		
		
		
		
		public function report_lesson_attachment_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>base64_decode($id))));

			$this->set('report_number',$reportdetail[0]['LessonMain']['report_no']);	       
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['LessonAttachment']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['LessonAttachment']['limit'];
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
		
		$condition="LessonAttachment.report_id = $report_id  AND  LessonAttachment.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(LessonAttachment.".$_REQUEST['filter'].") like '".trim($_REQUEST['value'])."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

	   	$adminArray = array();	
		 $count = $this->LessonAttachment->find('count' ,array('conditions' => $condition));
		 $adminA = $this->LessonAttachment->find('all',array('conditions' => $condition,'order' => 'LessonAttachment.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['LessonAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['LessonAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['LessonAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['LessonAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['LessonAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['LessonAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['LessonAttachment']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['LessonAttachment']['image_src']=$this->attachment_list('LessonAttachment',$rec);
	
		     $i++;
		     
		}

		  if(count($adminA)>0){
		      $adminArray = Set::extract($adminA, '{n}.LessonAttachment');
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
					  $this->request->data['LessonAttachment']['id'] = $id;
					  $this->request->data['LessonAttachment']['isblocked'] = 'Y';
					  $this->LessonAttachment->save($this->request->data,false);				
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
					  $this->request->data['LessonAttachment']['id'] = $id;
					  $this->request->data['LessonAttachment']['isblocked'] = 'N';
					  $this->LessonAttachment->save($this->request->data,false);				
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
				
					  $this->request->data['LessonAttachment']['id'] = $id;
					  $this->request->data['LessonAttachment']['isdeleted'] = 'Y';
					  $this->LessonAttachment->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			   
			       exit;			 
		     
	      }
	      
	 public function report_lesson_link_list($id=null,$typSearch){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$sqlinkDetail = $this->LessonLink->find('all', array('conditions' => array('LessonLink.report_id' =>base64_decode($id))));
		$reportdetail = $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>base64_decode($id))));            
		$this->set('report_number',$reportdetail[0]['LessonMain']['report_no']);
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
		           $this->redirect(array('action'=>'lesson_main_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['LessonLink']['id'] = $id;
					  $this->request->data['LessonLink']['isblocked'] = 'Y';
					  $this->LessonLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function link_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'lesson_main_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['LessonLink']['id'] = $id;
					  $this->request->data['LessonLink']['isblocked'] = 'N';
					  $this->LessonLink->save($this->request->data,false);				
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
					  
					  $deleteproperty = "DELETE FROM `lesson_links` WHERE `id` = {$id}";
                                          $deleteval=$this->LessonLink->query($deleteproperty);
					  
			       }
		
			       echo 'ok';
			       exit;
		      }else{
			 $this->redirect(array('action'=>'lesson_main_list'), null, true);
		      }
			       
			      
			       
	
	      }
	     function  linkrocess(){
		   
		         $this->layout="ajax";
			 $this->_checkAdminSession();
         	         $this->_getRoleMenuPermission();
		         $this->grid_access();
			 $linkArray=array();
			 $explode_id_type=explode("_",$this->data['type']);
		          $sqlinkDetail = $this->LessonLink->find('all', array('conditions' => array('LessonLink.report_id' =>base64_decode($this->data['report_no']),'LessonLink.link_report_id' =>$explode_id_type[1],'LessonLink.type' =>$explode_id_type[0])));
			 if(count($sqlinkDetail)>0){
				echo 'avl';
				
			 }else{
			 $linkArray['LessonLink']['type']=$explode_id_type[0];
			 $linkArray['LessonLink']['report_id']=base64_decode($this->data['report_no']);
			 $linkArray['LessonLink']['link_report_id']=$explode_id_type[1];
			 $linkArray['LessonLink']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
			 
			 if($this->LessonLink->save($linkArray)){
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
	
                $condition="LessonLink.report_id =".base64_decode($report_id);
		
                if(isset($_REQUEST['filter'])){
	             $link_type=explode("~",$this->link_search($_REQUEST['value']));
		     
		     $condition .= " AND LessonLink.link_report_id ='".$link_type[0]."' AND LessonLink.type ='".$link_type[1]."'";
		     
		}
		
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 $adminArray = array();
		  if($filterTYPE!='all'){
			
		    $condition .= " AND LessonLink.type ='".$filterTYPE."'";
		
		 }
		 $count = $this->LessonLink->find('count' ,array('conditions' => $condition));
		 $adminA = $this->LessonLink->find('all',array('conditions' => $condition,'order' => 'LessonLink.id DESC','limit'=>$limit));
		 

		  
		$i = 0;
		foreach($adminA as $rec)
		{
	
			if($rec['LessonLink']['isblocked'] == 'N')
			{
				$adminA[$i]['LessonLink']['blockHideIndex'] = "true";
				$adminA[$i]['LessonLink']['unblockHideIndex'] = "false";
				$adminA[$i]['LessonLink']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['LessonLink']['blockHideIndex'] = "false";
				$adminA[$i]['LessonLink']['unblockHideIndex'] = "true";
				$adminA[$i]['LessonLink']['isdeletdHideIndex'] = "false";
			}
			 $link_type=$this->link_grid($adminA[$i],$rec['LessonLink']['type'],'LessonLink',$rec);
		         $explode_link_type=explode("~",$link_type);
		         $adminA[$i]['LessonLink']['link_report_no']=$explode_link_type[0];
		         $adminA[$i]['LessonLink']['type_name']=$explode_link_type[1];

		
			
		    $i++;
		}
		
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.LessonLink');
		  }
	 
		  
                  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
        function add_lsreport_view($id=null){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$reportdetail = $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>base64_decode($id))));
			 $this->Session->write('report_create',$reportdetail[0]['LessonMain']['created_by']);
			$rdate_time=explode("-",$reportdetail[0]['LessonMain']['create_date']);
		        $reportdetail[0]['LessonMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
			
			if($reportdetail[0]['LessonMain']['validation_date']!=''){
			$vdate_time=explode("-",$reportdetail[0]['LessonMain']['validation_date']);
		        $reportdetail[0]['LessonMain']['validation_date_val']=date("d-M-y", mktime(0, 0, 0, $vdate_time[1],$vdate_time[2],$vdate_time[0]));
			}
			if($reportdetail[0]['LessonMain']['revalidate_date']!=''){
			$rvdate_time=explode("-",$reportdetail[0]['LessonMain']['revalidate_date']);
		        $reportdetail[0]['LessonMain']['revalidate_date_val']=date("d-M-y", mktime(0, 0, 0, $rvdate_time[1],$rvdate_time[2],$rvdate_time[0]));
			}
			$validateBy= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['LessonMain']['validate_by']))); 
			$reportdetail[0]['LessonMain']['validate_by_person']=$validateBy[0]['AdminMaster']['first_name']." ".$validateBy[0]['AdminMaster']['last_name'];
			$this->set('reportdetail',$reportdetail);
			if(isset($reportdetail[0]['LessonLink'][0])){
			      $linkHolder=array();
				$typeHolder=array();
				$linkDataHolder=array();
				
				for($i=0;$i<count($reportdetail[0]['LessonLink']);$i++){
					$typeHolder[]=$reportdetail[0]['LessonLink'][$i]['type'];
				}
			$uniqueType=array_values(array_unique($typeHolder));
			$madleVal=$this->LessonLink;
			$linkDataHolder=$this->retrive_link_summary($madleVal,$uniqueType,'LessonLink');
	            	if(count($linkDataHolder)>0){
					      $this->set('linkDataHolder',$linkDataHolder);
					 }else{
					      $this->set('linkDataHolder',array());	
					 }
			
			}else{
			    $this->set('linkDataHolder',array());
			}
		
		 }
		 
		 function print_view($id=null){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="ajax";
			$reportdetail = $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>base64_decode($id))));
			$rdate_time=explode("-",$reportdetail[0]['LessonMain']['create_date']);
		        $reportdetail[0]['LessonMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
			
			if($reportdetail[0]['LessonMain']['validation_date']!=''){
			$vdate_time=explode("-",$reportdetail[0]['LessonMain']['validation_date']);
		        $reportdetail[0]['LessonMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $vdate_time[1],$vdate_time[2],$vdate_time[0]));
			}
			
			if($reportdetail[0]['LessonMain']['revalidate_date']!=''){
			$rvdate_time=explode("-",$reportdetail[0]['LessonMain']['revalidate_date']);
		        $reportdetail[0]['LessonMain']['revalidate_date_val']=date("d-M-y", mktime(0, 0, 0, $rvdate_time[1],$rvdate_time[2],$rvdate_time[0]));
			}
			
			
			
			
			$validateBy= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['LessonMain']['validate_by']))); 
			$reportdetail[0]['LessonMain']['validate_by_person']=$validateBy[0]['AdminMaster']['first_name']." ".$validateBy[0]['AdminMaster']['last_name'];
			$this->set('reportdetail',$reportdetail);
			if(isset($reportdetail[0]['LessonLink'][0])){
			      $linkHolder=array();
				$typeHolder=array();
				$linkDataHolder=array();
				
				for($i=0;$i<count($reportdetail[0]['LessonLink']);$i++){
					$typeHolder[]=$reportdetail[0]['LessonLink'][$i]['type'];
				}
			$uniqueType=array_values(array_unique($typeHolder));
			$madleVal=$this->LessonLink;
			$linkDataHolder=$this->retrive_link_summary($madleVal,$uniqueType,'LessonLink');
	            	if(count($linkDataHolder)>0){
					      $this->set('linkDataHolder',$linkDataHolder);
					 }else{
					      $this->set('linkDataHolder',array());	
					 }
			
			}else{
			    $this->set('linkDataHolder',array());
			}
		
		 }
		 
	    	function lesson_email_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
	               
		    
	
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['LessonMain']['report_no']);
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
	
	
		public function get_all_lesson_email_list($report_id)
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
			
			$condition.=" AND  LdjsEmail.type = 'lesson'";
			
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
				
			    $remC= $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>$rec['LdjsEmail']['leid'])));
			    
			    $rrd=explode(" ",$remC[0]['LessonMain']['revalidate_date']);
			    $rrdE=explode("-",$rrd[0]);
			    $adminA[$i]['LdjsEmail']['revalidate_date_val']=date("d/M/y", mktime(0, 0, 0, $rrdE[1], $rrdE[2], $rrdE[0]));
			    
			    $create_on=explode("-",$remC[0]['LessonMain']['create_date']);
			    $adminA[$i]['LdjsEmail']['create_on_val']=date("d/M/y", mktime(0, 0, 0, $create_on[1], $create_on[2], $create_on[0]));
			    $user_detail_createby= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['LdjsEmail']['user_id'])));
			    $adminA[$i]['LdjsEmail']['createby_person']=$user_detail_createby[0]['AdminMaster']['first_name']."  ".$user_detail_createby[0]['AdminMaster']['last_name'];
			    $adminA[$i]['LdjsEmail']['email']=$user_detail_createby[0]['AdminMaster']['admin_email'];
			    $explodemail=explode("-",$rec['LdjsEmail']['email_date']);
                            $adminA[$i]['LdjsEmail']['email_date']=date("d/M/y", mktime(0, 0, 0, $explodemail[1], $explodemail[2], $explodemail[0]));
			   
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
        function lesson_email_view($id,$leid){
		
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
                 $this->grid_access();
		 $this->layout="ajax";
		 $documentEmail = $this->LdjsEmail->find('all', array('conditions' =>array('LdjsEmail.id' =>$id,'LdjsEmail.leid' =>$leid,'LdjsEmail.type' =>'lesson'),'recursive'=>2));
                 $reportdetail = $this->LessonMain->find('all', array('conditions' => array('LessonMain.id' =>$documentEmail[0]['LdjsEmail']['leid'])));

		 $vx=explode("-",$reportdetail[0]['LessonMain']['validation_date']);
		 $v_date=date("d/M/y", mktime(0, 0, 0, $vx[1], $vx[2],$vx[0]));
		 $rvd=explode("-",$reportdetail[0]['LessonMain']['revalidate_date']);
		 $rv_date=date("d/M/y", mktime(0, 0, 0, $rvd[1], $rvd[2],$rvd[0]));
		 $this->set('v_date', $v_date);
		 $this->set('rv_date', $rv_date);
		 $fullname=$documentEmail[0]['AdminMaster']['first_name'].' '.$documentEmail[0]['AdminMaster']['last_name'];
		 $this->set('fullname', $fullname);
		 $this->set('documentEmail', $documentEmail);
		 $this->set('reportdetail', $reportdetail);
	}
	      
}	