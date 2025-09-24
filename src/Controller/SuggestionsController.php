<?PHP
class SuggestionsController extends AppController
{
	public $name = 'Suggestions';
	public $uses = array('Report','SuggestionMain','JhaMain','DocumentMain','RemidialEmailList','SuggestionLink','SuggestionAttachment','Priority','SuggestionType','SuggestionRemidial','JobReportMain','LessonAttachment','LdjsEmail','Service','LessonLink','AdminMaster','LessonMain','SqReportMain','AuditReportMain','JnReportMain','Incident','BusinessType','Fieldlocation','Client','HsseLink','JobLink','LessonLink','IncidentLocation','IncidentSeverity','Country','AdminMaster','RolePermission','RoleMaster','AdminMenu','LessonType');
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	//var $components = array('PhpMailerEmail');
	public function suggestion_main_list(){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();		
		$this->layout="after_adminlogin_template";
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['Suggestions']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['Suggestions']['limit'];
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
		$condition = "SuggestionMain.isdeleted = 'N'";
		
		$adminA = $this->SuggestionMain->find('all' ,array('conditions' => $condition,'order' => 'SuggestionMain.id DESC','limit'=>$limit));
		for($i=0;$i<count($adminA);$i++){
			if(($_SESSION['adminData']['AdminMaster']['id']==$adminA[$i]['SuggestionMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
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
		$condition = "SuggestionMain.isdeleted = 'N'";
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'report_no':
		$condition .= "AND ucase(SuggestionMain.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
			break;	
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		
		$count = $this->SuggestionMain->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();
		
		
		
		
		$adminA = $this->SuggestionMain->find('all' ,array('conditions' => $condition,'order' => 'SuggestionMain.id DESC','limit'=>$limit)); 
		$idBoolen=array();
		unset($_SESSION['idBollen']);

		$i = 0;
		foreach($adminA as $rec)
		{
		
				
			
			
			
			if(($_SESSION['adminData']['AdminMaster']['id']==$rec['SuggestionMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
				array_push($idBoolen,1);
				$adminA[$i]['SuggestionMain']['edit_permit'] ="false";
				$adminA[$i]['SuggestionMain']['view_permit'] ="false";
				$adminA[$i]['SuggestionMain']['delete_permit'] ="false";
				$adminA[$i]['SuggestionMain']['block_permit'] ="false";
				$adminA[$i]['SuggestionMain']['unblock_permit'] ="false";
				$adminA[$i]['SuggestionMain']['checkbox_permit'] ="false";
				
				
				if($rec['SuggestionMain']['isblocked'] == 'N')
			            {
					$adminA[$i]['SuggestionMain']['blockHideIndex'] = "true";
					$adminA[$i]['SuggestionMain']['unblockHideIndex'] = "false";
				        
			         }else{
					$adminA[$i]['SuggestionMain']['blockHideIndex'] = "false";
					$adminA[$i]['SuggestionMain']['unblockHideIndex'] = "true";
					
			        }
				
					
			}else{
				
				array_push($idBoolen,0);
				$adminA[$i]['SuggestionMain']['edit_permit'] ="true";
				$adminA[$i]['SuggestionMain']['view_permit'] ="false";
				$adminA[$i]['SuggestionMain']['delete_permit'] ="true";
				$adminA[$i]['SuggestionMain']['block_permit'] ="true";
				$adminA[$i]['SuggestionMain']['unblock_permit'] ="true";
			        $adminA[$i]['SuggestionMain']['blockHideIndex'] = "true";
				$adminA[$i]['SuggestionMain']['unblockHideIndex'] = "true";
				$adminA[$i]['SuggestionMain']['checkbox_permit'] ="true";
				
				
			}
		
			
			
         		$adminA[$i]['SuggestionMain']['creater_name'] =$rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
	                $adminA[$i]['SuggestionMain']['incident_type_name'] =$rec['Incident']['incident_type'];
			$adminA[$i]['SuggestionMain']['services_name'] =$rec['Service']['type'];
			$adminA[$i]['SuggestionMain']['business_type_name'] =$rec['BusinessType']['type'];
			$adminA[$i]['SuggestionMain']['filedlocation_type_name'] =$rec['Fieldlocation']['type'];
			$adminA[$i]['SuggestionMain']['country_name'] =$rec['Country']['name'];
			$validateDetail = $this->AdminMaster->find('all',array('conditions' => array('AdminMaster.id' =>$rec['SuggestionMain']['validate_by'])));
			$adminA[$i]['SuggestionMain']['validate_name'] =$validateDetail[0]['AdminMaster']['first_name'].' '.$validateDetail[0]['AdminMaster']['last_name'];
			$ex_create=explode("-",$rec['SuggestionMain']['create_date']);
			$createDATE=date("d/M/y", mktime(0, 0, 0, $ex_create[1], $ex_create[2],$ex_create[0]));
		        $adminA[$i]['SuggestionMain']['create_date_val'] =$createDATE;
			
			if($rec['SuggestionMain']['validation_date']!=''){
			$val_date=explode("-",$rec['SuggestionMain']['validation_date']);
			$validateDATE=date("d/M/y", mktime(0, 0, 0, $val_date[1], $val_date[2],$val_date[0]));
		        $adminA[$i]['SuggestionMain']['validate_date_val']=$validateDATE;
			}else{
			$adminA[$i]['SuggestionMain']['validate_date_val']='';	
			}
			
			if($rec['SuggestionMain']['revalidate_date']!=''){
			$rval_date=explode("-",$rec['SuggestionMain']['revalidate_date']);
			$rvalidateDATE=date("d/M/y", mktime(0, 0, 0, $rval_date[1], $rval_date[2],$rval_date[0]));
		        $adminA[$i]['SuggestionMain']['rvalidate_date_val']=$rvalidateDATE;
			}else{
			$adminA[$i]['SuggestionMain']['rvalidate_date_val']='';	
			}
			if($rec['SuggestionMain']['closer_date']!='0000-00-00'){
			$cls_date=explode("-",$rec['SuggestionMain']['closer_date']);
			$lessonCLSDATE=date("d/M/y", mktime(0, 0, 0, $cls_date[1], $cls_date[2],$cls_date[0]));
		        $adminA[$i]['SuggestionMain']['closer_date_val']=$lessonCLSDATE;
			}
		    $i++;
		}
		
        
		if($count==0){
			$adminArray=array();
	        }else{
			$adminArray = Set::extract($adminA, '{n}.SuggestionMain');
		      }
		
		
		
		$this->set('total', $count);  //send total to the view
		$this->set('admins', $adminArray);  //send products to the view
         	$this->set('status', $action);
	}
	
	
	public function add_suggestion_report_main($id=null)
		
	{
		$this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
                 $incidentDetail = $this->Incident->find('all', array('conditions' => array('incident_type' =>'all')));
		 $lType = $this->SuggestionType->find('all');
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
			$this->set('heading','Add Suggestion Report (Main)');
			$this->set('button','Submit');
			$this->set('report_no','');
			$this->set('closer_date','00-00-0000');
			$this->set('incident_type','');
			$this->set('service_type','');
			$this->set('created_date','');
			$this->set('business_unit','');
			$this->set('field_location','');
			$this->set('l_type','');
			$this->set('l_type_val',$lType[0]['SuggestionType']['type']);
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
			
	               
		       $reportdetail = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.id' =>base64_decode($id))));
                       $adminDATA = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['SuggestionMain']['created_by'])));
		       $validateBy = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['SuggestionMain']['validate_by'])));
		       $this->Session->write('report_create',$reportdetail[0]['SuggestionMain']['created_by']);
		        if($reportdetail[0]['SuggestionMain']['validation_date']!=''){
		                 $valed=explode("-",$reportdetail[0]['SuggestionMain']['validation_date']);
				 $valedt=$valed[1]."-".$valed[2]."-".$valed[0];
		        }else{
		                $valedt='';	
		        }
	                if($reportdetail[0]['SuggestionMain']['revalidate_date']!=''){
		                 $rvaled=explode("-",$reportdetail[0]['SuggestionMain']['revalidate_date']);
				 $rvaledt=$rvaled[1]."-".$rvaled[2]."-".$rvaled[0];
		        }else{
		                $rvaledt='';	
		        }
		       if($reportdetail[0]['SuggestionMain']['closer_date']!=''){
		                 $clsd=explode("-",$reportdetail[0]['SuggestionMain']['closer_date']);
				 $closedt= $clsd[1]."-".$clsd[2]."-".$clsd[0];
		        }else{
		                $closedt='';	
		        }
		       
		       $this->set('id',base64_decode($id));
		      	       
			if($reportdetail[0]['SuggestionMain']['create_date']!=''){
			 
			         $crtd=explode("-",$reportdetail[0]['SuggestionMain']['create_date']);
			         $createDATE=date("d/M/y", mktime(0, 0, 0, $crtd[1], $crtd[2], $crtd[0]));
			 
			}else{
			         $createDATE=''; 	
			}
	  	        $this->set('create_date',$createDATE);
		        $this->set('heading','Edit Suggestion Report (Main)');
		        $this->set('button','Update');
			$this->set('closer_date',$closedt);
		        $this->set('reportno',$reportdetail[0]['SuggestionMain']['report_no']);
			$this->set('incident_type',$reportdetail[0]['SuggestionMain']['incident_type']);
			$this->set('cnt',$reportdetail[0]['SuggestionMain']['country']);
			$this->set('revalidation_date_val',$rvaledt);
			$this->set('validate_date_val',$valedt);
			$this->set('l_type', $reportdetail[0]['SuggestionMain']['type']);
			$this->set('l_type_val',$reportdetail[0]['SuggestionType']['type']);
			$this->set('business_unit',$reportdetail[0]['SuggestionMain']['business_unit']);
			$this->set('validate_user', $reportdetail[0]['SuggestionMain']['validate_by']);
			$this->set('field_location',$reportdetail[0]['SuggestionMain']['field_location']);
			$this->set('service_type',$reportdetail[0]['SuggestionMain']['services']);
	      		$this->set('summary',$reportdetail[0]['SuggestionMain']['summary']);
			$this->set('details',$reportdetail[0]['SuggestionMain']['details']);
                	$this->set('report_id',base64_decode($id));
			$this->set('created_by',$adminDATA[0]['AdminMaster']['first_name']." ".$adminDATA[0]['AdminMaster']['last_name']);
		 
		 
		 }
	}
       function sgreportprocess(){
			

		
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
           $sgreportMainData=array();
	   
	   
          if($this->data['add_suggestion_main_form']['id']==0){
		   $res='add';
 	                
	   }else{
		   $res='update';
		   $sgreportMainData['SuggestionMain']['id']=$this->data['add_suggestion_main_form']['id'];
		  
	    }
	    
	   if($this->data['validate_date']!=''){
           $vdate=explode("-",$this->data['validate_date']);
	       $sgreportMainData['SuggestionMain']['validation_date']=$vdate[2]."-".$vdate[0]."-".$vdate[1];
	   }else{
	       $sgreportMainData['SuggestionMain']['validation_date']='';	
	   }
	   
	   if($this->data['revalidation_date']!=''){
           $rvdate=explode("-",$this->data['revalidation_date']);
	       $sgreportMainData['SuggestionMain']['revalidate_date']=$rvdate[2]."-".$rvdate[0]."-".$rvdate[1];
	   }else{
	       $sgreportMainData['SuggestionMain']['revalidate_date']='';	
	   } 
	   
	   $sgreportMainData['SuggestionMain']['report_no']=$this->data['report_no'];
	   $sgreportMainData['SuggestionMain']['create_date']=date('Y-m-d'); 
	   $sgreportMainData['SuggestionMain']['incident_type']=$this->data['incident_type'];
	   $sgreportMainData['SuggestionMain']['business_unit'] =$this->data['business_unit'];  
	   $sgreportMainData['SuggestionMain']['field_location']=$this->data['field_location'];
	   $sgreportMainData['SuggestionMain']['country']=$this->data['country'];
	   $sgreportMainData['SuggestionMain']['validate_by']=$this->data['validate_by'];
	   $sgreportMainData['SuggestionMain']['type']=$this->data['l_type'];
	   $sgreportMainData['SuggestionMain']['services']=$this->data['service'];
	   $sgreportMainData['SuggestionMain']['created_by']=$_SESSION['adminData']['AdminMaster']['id'];
           $sgreportMainData['SuggestionMain']['summary']=$this->data['add_suggestion_main_form']['summary'];
	   $sgreportMainData['SuggestionMain']['details']=$this->data['add_suggestion_main_form']['details'];
	   
	   $strCreateOn = strtotime('Y-m-d');
	   $revalidationstr = strtotime($sgreportMainData['SuggestionMain']['revalidate_date']);
	   $dateHolder=array($sgreportMainData['SuggestionMain']['revalidate_date']);
	   $dateIndex=array(3,7,30);
	 
	   
	 if($this->SuggestionMain->save($sgreportMainData)){
		if($res=='add'){
			 $lastReport=base64_encode($this->SuggestionMain->getLastInsertId());
		 }elseif($res=='update'){
			 $lastReport=base64_encode($this->data['add_suggestion_main_form']['id']);
		 }
	 		 
		echo $res.'~'.$lastReport;
		
	    }else{
		echo 'fail';
	    }
            
         
		
	   exit;
	
	
       }
       	function suggestion_block($id = null)
	{
          
	
		if(!$id)
		{

			   $this->redirect(array('action'=>suggestion_main_list), null, true);
		}
		else
		{
			 $idArray = explode("^", $id);
			 
			 
		
			foreach($idArray as $id)
			{
				   $id = $id;
				   $this->request->data['SuggestionMain']['id'] = $id;
				   $this->request->data['SuggestionMain']['isblocked'] = 'Y';
				   $this->SuggestionMain->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}
	
	function suggestion_unblock($id = null)
	{

		
		if(!$id)
		{
	
			$this->redirect(array('action'=>suggestion_main_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
		
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['SuggestionMain']['id'] = $id;
				$this->request->data['SuggestionMain']['isblocked'] = 'N';
				$this->SuggestionMain->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
	
	function suggestion_main_delete()
	     {
		      $this->layout="ajax";
		      
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
				
					   $deleteLsLink = "DELETE FROM `suggestion_links` WHERE `report_id` = {$id}";
                                           $dls=$this->LessonLink->query($deleteLsLink);
					   $deleteLsAttachment = "DELETE FROM `suggestion_attachments` WHERE `report_id` = {$id}";
                                           $dlA=$this->LessonAttachment->query($deleteLsAttachment);
					   $deletejr= "DELETE FROM `suggestion_remidials` WHERE `report_no` = {$id}";
                                           $deleteRD=$this->SuggestionRemidial->query($deletejr);
					   $deleteSq_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$id} AND `report_type`='suggestion'";
                                           $dHRem=$this->RemidialEmailList->query($deleteSq_remidials_email);
					   $deleteLsMain = "DELETE FROM `suggestion_mains` WHERE `id` = {$id}";
                                           $dlm=$this->SuggestionMain->query($deleteLsMain);
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>suggestion_main_list), null, true);
		      }
			 
			      
			      
			       
	}
	      function add_suggestion_attachment($reoprt_id=null,$attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";

			$reportList = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.isdeleted' =>'N')));
			$this->set('reportList',$reportList);
			   
			
			if($attachment_id!=''){
				$attchmentdetail = $this->SuggestionAttachment->find('all', array('conditions' => array('SuggestionAttachment.id' =>base64_decode($attachment_id))));
				$imagepath=$this->file_edit_list($attchmentdetail[0]['SuggestionAttachment']['file_name'],'SuggestionAttachment',$attchmentdetail);
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['SuggestionAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['SuggestionAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['SuggestionAttachment']['file_name']);
				$this->set('attachmentstyle','style="display:block;"');;
		
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
			
			$reportdetail = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.id' =>base64_decode($reoprt_id))));
			$this->set('report_number',$reportdetail[0]['SuggestionMain']['report_no']);        
			
	       }
	       function uploadimage($upparname=NULL,$deleteImageName=NULL){
		        $this->layout="ajax";
			$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Suggestions',$allowed_image);
			exit;
	       }
	        function  suggestionsattachmentprocess(){
		         $this->layout="ajax";
		         $attachmentArray=array();
		 
		        if($this->data['Suggestions']['id']!=0){
				$res='update';
				$attachmentArray['SuggestionAttachment']['id']=$this->data['Suggestions']['id'];
			 }else{
				 $res='add';
			  }
			   if($this->data['attachment_description']!=''){
				$attachmentArray['SuggestionAttachment']['description']=$this->data['attachment_description'];
			   }else{
				$attachmentArray['SuggestionAttachment']['description']='';
			   }
		   	   
			   $attachmentArray['SuggestionAttachment']['file_name']=$this->data['hiddenFile'];
			   $attachmentArray['SuggestionAttachment']['report_id']=$this->data['report_id'];
			   if($this->SuggestionAttachment->save($attachmentArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
			
                         
		   exit;
		
	        }
		
		
		
		
		public function report_suggestion_attachment_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.id' =>base64_decode($id))));

			$this->set('report_number',$reportdetail[0]['SuggestionMain']['report_no']);	       
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['SuggestionAttachment']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['SuggestionAttachment']['limit'];
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
		
		$condition="SuggestionAttachment.report_id = $report_id  AND  SuggestionAttachment.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(SuggestionAttachment.".$_REQUEST['filter'].") like '".trim($_REQUEST['value'])."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

	   	$adminArray = array();	
		$count = $this->SuggestionAttachment->find('count' ,array('conditions' => $condition));
		$adminA = $this->SuggestionAttachment->find('all',array('conditions' => $condition,'order' => 'SuggestionAttachment.id DESC','limit'=>$limit));
		
		
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['SuggestionAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['SuggestionAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['SuggestionAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['SuggestionAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['SuggestionAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['SuggestionAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['SuggestionAttachment']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['SuggestionAttachment']['image_src']=$this->attachment_list('SuggestionAttachment',$rec);
	
		     $i++;
		     
		}
    
		   
		 
		  if(count($adminA)>0){
		      $adminArray = Set::extract($adminA, '{n}.SuggestionAttachment');
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
					  $this->request->data['SuggestionAttachment']['id'] = $id;
					  $this->request->data['SuggestionAttachment']['isblocked'] = 'Y';
					  $this->SuggestionAttachment->save($this->request->data,false);				
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
					  $this->request->data['SuggestionAttachment']['id'] = $id;
					  $this->request->data['SuggestionAttachment']['isblocked'] = 'N';
					  $this->SuggestionAttachment->save($this->request->data,false);				
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
				
					  $this->request->data['SuggestionAttachment']['id'] = $id;
					  $this->request->data['SuggestionAttachment']['isdeleted'] = 'Y';
					  $this->SuggestionAttachment->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			   
			       exit;			 
		     
	      }
	      
	 public function report_suggestion_link_list($id=null,$typSearch){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$sqlinkDetail = $this->SuggestionLink->find('all', array('conditions' => array('SuggestionLink.report_id' =>base64_decode($id))));
		$reportdetail = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.id' =>base64_decode($id))));            
		$this->set('report_number',$reportdetail[0]['SuggestionMain']['report_no']);
		$this->set('report_id',$id);
		$this->set('id',base64_decode($id));
		
		
		  $this->set('report_id_val',$id);
		  $this->AdminMaster->recursive=2;
		  $userDeatil = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$_SESSION['adminData']['AdminMaster']['id'])));
           	   $reportDeatil=$this->derive_link_data($userDeatil);
		 
		  
		  $this->set('typSearch', base64_decode($typSearch));
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
		           $this->redirect(array('action'=>'suggestion_main_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SuggestionLink']['id'] = $id;
					  $this->request->data['SuggestionLink']['isblocked'] = 'Y';
					  $this->SuggestionLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function link_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'suggestion_main_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SuggestionLink']['id'] = $id;
					  $this->request->data['SuggestionLink']['isblocked'] = 'N';
					  $this->SuggestionLink->save($this->request->data,false);				
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
					  
					  $deleteproperty = "DELETE FROM `suggestion_links` WHERE `id` = {$id}";
                                          $deleteval=$this->SuggestionLink->query($deleteproperty);
					  
			       }
		
			       echo 'ok';
			       exit;
		      }else{
			 $this->redirect(array('action'=>'suggestion_main_list'), null, true);
		      }
			       
			      
			       
	
	      }
	     function  linkrocess(){
		   
		         $this->layout="ajax";
			 $this->_checkAdminSession();
         	         $this->_getRoleMenuPermission();
		         $this->grid_access();
			 $linkArray=array();
			 $explode_id_type=explode("_",$this->data['type']);
		          $sqlinkDetail = $this->SuggestionLink->find('all', array('conditions' => array('SuggestionLink.report_id' =>base64_decode($this->data['report_no']),'SuggestionLink.link_report_id' =>$explode_id_type[1],'SuggestionLink.type' =>$explode_id_type[0])));
			 if(count($sqlinkDetail)>0){
				echo 'avl';
				
			 }else{
			 $linkArray['SuggestionLink']['type']=$explode_id_type[0];
			 $linkArray['SuggestionLink']['report_id']=base64_decode($this->data['report_no']);
			 $linkArray['SuggestionLink']['link_report_id']=$explode_id_type[1];
			 $linkArray['SuggestionLink']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
			 
			 if($this->SuggestionLink->save($linkArray)){
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
	
                $condition="SuggestionLink.report_id =".base64_decode($report_id);
		
                if(isset($_REQUEST['filter'])){
	             $link_type=explode("~",$this->link_search($_REQUEST['value']));
		     
		     $condition .= " AND SuggestionLink.link_report_id ='".$link_type[0]."' AND SuggestionLink.type ='".$link_type[1]."'";
		     
		}
		
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 $adminArray = array();
		 if($filterTYPE!='all'){
			
		    $condition .= " AND SuggestionLink.type ='".$filterTYPE."'";
		
		 }
		 $count = $this->SuggestionLink->find('count' ,array('conditions' => $condition));
		 $adminA = $this->SuggestionLink->find('all',array('conditions' => $condition,'order' => 'SuggestionLink.id DESC','limit'=>$limit));
		 

		  
		$i = 0;
		foreach($adminA as $rec)
		{
	
			if($rec['SuggestionLink']['isblocked'] == 'N')
			{
				$adminA[$i]['SuggestionLink']['blockHideIndex'] = "true";
				$adminA[$i]['SuggestionLink']['unblockHideIndex'] = "false";
				$adminA[$i]['SuggestionLink']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['SuggestionLink']['blockHideIndex'] = "false";
				$adminA[$i]['SuggestionLink']['unblockHideIndex'] = "true";
				$adminA[$i]['SuggestionLink']['isdeletdHideIndex'] = "false";
			}
			 $link_type=$this->link_grid($adminA[$i],$rec['SuggestionLink']['type'],'SuggestionLink',$rec);
		         $explode_link_type=explode("~",$link_type);
		         $adminA[$i]['SuggestionLink']['link_report_no']=$explode_link_type[0];
		         $adminA[$i]['SuggestionLink']['type_name']=$explode_link_type[1];
				
		    $i++;
		}
		
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.SuggestionLink');
		  }
	 
		  
                  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
        function add_suggestion_view($id=null){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
                        $reportdetail = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.id' =>base64_decode($id)),'recursive'=>2));
                        $this->Session->write('report_create',$reportdetail[0]['SuggestionMain']['created_by']); 
			$rdate_time=explode("-",$reportdetail[0]['SuggestionMain']['create_date']);
		        $reportdetail[0]['SuggestionMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
			
			if($reportdetail[0]['SuggestionMain']['validation_date']!=''){
			$vdate_time=explode("-",$reportdetail[0]['SuggestionMain']['validation_date']);
		        $reportdetail[0]['SuggestionMain']['validation_date_val']=date("d-M-y", mktime(0, 0, 0, $vdate_time[1],$vdate_time[2],$vdate_time[0]));
			}else{
			 $reportdetail[0]['SuggestionMain']['validation_date_val']='';	
			}
			if($reportdetail[0]['SuggestionMain']['revalidate_date']!=''){
			$rvdate_time=explode("-",$reportdetail[0]['SuggestionMain']['revalidate_date']);
		        $reportdetail[0]['SuggestionMain']['revalidate_date_val']=date("d-M-y", mktime(0, 0, 0, $rvdate_time[1],$rvdate_time[2],$rvdate_time[0]));
			}else{
				 $reportdetail[0]['SuggestionMain']['revalidate_date_val']='';
			}
			$validateBy= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['SuggestionMain']['validate_by']))); 
			$reportdetail[0]['SuggestionMain']['validate_by_person']=$validateBy[0]['AdminMaster']['first_name']." ".$validateBy[0]['AdminMaster']['last_name'];
			
			if(isset($reportdetail[0]['SuggestionRemidial'][0])){
                        for($h=0;$h<count($reportdetail[0]['SuggestionRemidial']);$h++){
			     

				$responsibility= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['SuggestionRemidial'][$h]['remidial_responsibility'])));

				$reportdetail[0]['SuggestionRemidial'][$h]['responsibility_person']=$responsibility[0]['AdminMaster']['first_name']." ".$responsibility[0]['AdminMaster']['last_name'];
				$reportdetail[0]['SuggestionRemidial'][$h]['Priority']['prority_colorcode']='<font color='.$reportdetail[0]['SuggestionRemidial'][$h]['Priority']['colorcoder'].'>'.$reportdetail[0]['SuggestionRemidial'][$h]['Priority']['type'].'</font>';
                                $explode_remedial=explode("-",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_create']);     
                                $reportdetail[0]['SuggestionRemidial'][$h]['createRemidial']=date("d-M-y", mktime(0, 0, 0, $explode_remedial[1], $explode_remedial[2], $explode_remedial[0]));
	 
				if($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
					 $reportdetail[0]['SuggestionRemidial'][$h]['closeDate']='';
					 $reportdetail[0]['SuggestionRemidial'][$h]['remidial_closer_summary']='';
			       
				}elseif($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					$closerDate=explode("-",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']);

					$reportdetail[0]['SuggestionRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
			        }

				if($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						$reportdetail[0]['SuggestionRemidial'][$h]['closeDate']='';
						$reportdetail[0]['SuggestionRemidial'][$h]['remidial_closer_summary']='';
				      
				 }elseif($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					       $closerDate=explode("-",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']);

					       $reportdetail[0]['SuggestionRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
				 }

                               
			       
			         $rcdt=explode("-",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_create']);
                       		
				 $reportdetail[0]['SuggestionRemidial'][$h]['rem_crt_val']=date("d-M-y", mktime(0, 0, 0, $rcdt[1],  $rcdt[0],  $rcdt[2])); 
			       		       
                                
                               if($reportdetail[0]['SuggestionRemidial'][$h]['remidial_reminder_data']!=''){
                                 $rrd=explode(" ",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_reminder_data']);
                       		 $exrrd=explode("/",$rrd[0]);
				 $reportdetail[0]['SuggestionRemidial'][$h]['rem_rim_data']=date("d-M-y", mktime(0, 0, 0, $exrrd[1], $exrrd[0], $exrrd[2])); 
                                }else{
                                 $reportdetail[0]['SuggestionRemidial'][$h]['rem_rim_data']='';
                                 }

                                if($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_target']!=''){
                                 $rcd=explode(" ",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_target']);
				 $exrcd=explode("/",$rcd[0]);
				 $reportdetail[0]['SuggestionRemidial'][$h]['rem_cls_trgt']=date("d-M-y", mktime(0, 0, 0, $exrcd[1], $exrcd[0], $exrcd[2])); 
                               }else{
                                  $reportdetail[0]['SuggestionRemidial'][$h]['rem_cls_trgt']='';
                               }
		           }
                  }
			if(isset($reportdetail[0]['SuggestionLink'][0])){
			      $linkHolder=array();
				$typeHolder=array();
				$linkDataHolder=array();
				
				for($i=0;$i<count($reportdetail[0]['SuggestionLink']);$i++){
					$typeHolder[]=$reportdetail[0]['SuggestionLink'][$i]['type'];
				}
			$uniqueType=array_values(array_unique($typeHolder));
			$madleVal=$this->SuggestionLink;
			$linkDataHolder=$this->retrive_link_summary($madleVal,$uniqueType,'SuggestionLink');
			
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
                        $reportdetail = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.id' =>base64_decode($id)),'recursive'=>2));

			$rdate_time=explode("-",$reportdetail[0]['SuggestionMain']['create_date']);
		        $reportdetail[0]['SuggestionMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
			
			if($reportdetail[0]['SuggestionMain']['validation_date']!=''){
			$vdate_time=explode("-",$reportdetail[0]['SuggestionMain']['validation_date']);
		        $reportdetail[0]['SuggestionMain']['validation_date_val']=date("d-M-y", mktime(0, 0, 0, $vdate_time[1],$vdate_time[2],$vdate_time[0]));
			}else{
			 $reportdetail[0]['SuggestionMain']['validation_date_val']='';	
			}
			if($reportdetail[0]['SuggestionMain']['revalidate_date']!=''){
			$rvdate_time=explode("-",$reportdetail[0]['SuggestionMain']['revalidate_date']);
		        $reportdetail[0]['SuggestionMain']['revalidate_date_val']=date("d-M-y", mktime(0, 0, 0, $rvdate_time[1],$rvdate_time[2],$rvdate_time[0]));
			}else{
				 $reportdetail[0]['SuggestionMain']['revalidate_date_val']='';
			}
			$validateBy= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['SuggestionMain']['validate_by']))); 
			$reportdetail[0]['SuggestionMain']['validate_by_person']=$validateBy[0]['AdminMaster']['first_name']." ".$validateBy[0]['AdminMaster']['last_name'];
			
			if(isset($reportdetail[0]['SuggestionRemidial'][0])){
                        for($h=0;$h<count($reportdetail[0]['SuggestionRemidial']);$h++){
			     

				$responsibility= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['SuggestionRemidial'][$h]['remidial_responsibility'])));

				$reportdetail[0]['SuggestionRemidial'][$h]['responsibility_person']=$responsibility[0]['AdminMaster']['first_name']." ".$responsibility[0]['AdminMaster']['last_name'];
				$reportdetail[0]['SuggestionRemidial'][$h]['Priority']['prority_colorcode']='<font color='.$reportdetail[0]['SuggestionRemidial'][$h]['Priority']['colorcoder'].'>'.$reportdetail[0]['SuggestionRemidial'][$h]['Priority']['type'].'</font>';
                                $explode_remedial=explode("-",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_create']);     
                                $reportdetail[0]['SuggestionRemidial'][$h]['createRemidial']=date("d-M-y", mktime(0, 0, 0, $explode_remedial[1], $explode_remedial[2], $explode_remedial[0]));
	 
				if($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
					 $reportdetail[0]['SuggestionRemidial'][$h]['closeDate']='';
					 $reportdetail[0]['SuggestionRemidial'][$h]['remidial_closer_summary']='';
			       
				}elseif($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					$closerDate=explode("-",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']);

					$reportdetail[0]['SuggestionRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
			        }

				if($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						$reportdetail[0]['SuggestionRemidial'][$h]['closeDate']='';
						$reportdetail[0]['SuggestionRemidial'][$h]['remidial_closer_summary']='';
				      
				 }elseif($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					       $closerDate=explode("-",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_date']);

					       $reportdetail[0]['SuggestionRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
				 }

                               
			       
			         $rcdt=explode("-",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_create']);
                       		
				 $reportdetail[0]['SuggestionRemidial'][$h]['rem_crt_val']=date("d-M-y", mktime(0, 0, 0, $rcdt[1],  $rcdt[0],  $rcdt[2])); 
			       		       
                                
                               if($reportdetail[0]['SuggestionRemidial'][$h]['remidial_reminder_data']!=''){
                                 $rrd=explode(" ",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_reminder_data']);
                       		 $exrrd=explode("/",$rrd[0]);
				 $reportdetail[0]['SuggestionRemidial'][$h]['rem_rim_data']=date("d-M-y", mktime(0, 0, 0, $exrrd[1], $exrrd[0], $exrrd[2])); 
                                }else{
                                 $reportdetail[0]['SuggestionRemidial'][$h]['rem_rim_data']='';
                                 }

                                if($reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_target']!=''){
                                 $rcd=explode(" ",$reportdetail[0]['SuggestionRemidial'][$h]['remidial_closure_target']);
				 $exrcd=explode("/",$rcd[0]);
				 $reportdetail[0]['SuggestionRemidial'][$h]['rem_cls_trgt']=date("d-M-y", mktime(0, 0, 0, $exrcd[1], $exrcd[0], $exrcd[2])); 
                               }else{
                                  $reportdetail[0]['SuggestionRemidial'][$h]['rem_cls_trgt']='';
                               }
		           }
                        }
			if(isset($reportdetail[0]['SuggestionLink'][0])){
			      $linkHolder=array();
				$typeHolder=array();
				$linkDataHolder=array();
				
				for($i=0;$i<count($reportdetail[0]['SuggestionLink']);$i++){
					$typeHolder[]=$reportdetail[0]['SuggestionLink'][$i]['type'];
				}
			$uniqueType=array_values(array_unique($typeHolder));
			$madleVal=$this->SuggestionLink;
			$linkDataHolder=$this->retrive_link_summary($madleVal,$uniqueType,'SuggestionLink');
			
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
		 
	function add_suggestion_remidial($report_id=null,$remidial_id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
		 $this->layout="after_adminlogin_template";
		 $priority = $this->Priority->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('priority',$priority);
		 $this->set('responsibility',$userDetail);
		 $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		 $reportdetail = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.id' =>base64_decode($report_id))));
		 $countRem= $this->SuggestionRemidial->find('count',array('conditions'=>array('SuggestionRemidial.report_no'=>base64_decode($report_id))));      
      		 $this->set('report_number',$reportdetail[0]['SuggestionMain']['report_no']);      
		 $this->set('reportno',base64_decode($report_id));     
		 if($remidial_id==''){
			 $this->set('id', 0);
			 if($countRem!=0){
		                 $countRem=$countRem+1;	
			 }else{
				 $countRem=1;		
			}
			 $this->set('countRem', $countRem);
			 $this->set('heading', 'Add Remedial Action Item' );
			 $this->set('button','Submit' );
			 $this->set('remidial_create','');
			 $this->set('remidial_summery','');
			 $this->set('remidial_closer_summary','');
			 $this->set('remidial_action','');
			 $this->set('remidial_priority','');
			 $this->set('remidial_closure_target','');
			 $this->set('remidial_responsibility','');
			 $this->set('remidial_reminder_data','');
			 $this->set('remidial_closer_summary',' ');
			 $this->set('remidial_closure_date','');
			 $this->set('remidial_style','style="display:none"');
			 $this->set('remidial_button_style','style="display:block"');
		 }else{
			
		         $remidialData= $this->SuggestionRemidial->find('all',array('conditions'=>array('SuggestionRemidial.id'=>base64_decode($remidial_id))));
			 $this->set('countRem',  $remidialData[0]['SuggestionRemidial']['remedial_no']);
			 $this->set('id', base64_decode($remidial_id));
			 $rempriority = $this->Priority->find('all',array('conditions'=>array('id'=>base64_decode($remidial_id))));
			 $this->set('heading', 'Edit Remedial Action Item' );
			 $this->set('button', 'Update');
			 $remidialcreate=explode("-",$remidialData[0]['SuggestionRemidial']['remidial_create']);
			 $this->set('remidial_create',$remidialcreate[1].'-'.$remidialcreate[2].'-'.$remidialcreate[0]);
			 $this->set('remidial_summery',$remidialData[0]['SuggestionRemidial']['remidial_summery']);
			 
			 $this->set('remidial_action',$remidialData[0]['SuggestionRemidial']['remidial_action']);
			 $this->set('remidial_priority',$remidialData[0]['SuggestionRemidial']['remidial_priority']);
			 $this->set('remidial_closure_target',$remidialData[0]['SuggestionRemidial']['remidial_closure_target']);
			 $this->set('remidial_responsibility',$remidialData[0]['SuggestionRemidial']['remidial_responsibility']);
			if($remidialData[0]['SuggestionRemidial']['remidial_closure_date']!='0000-00-00'){
			 $closerdate=explode("-",$remidialData[0]['SuggestionRemidial']['remidial_closure_date']);
			 $this->set('remidial_closure_date',$closerdate[1].'/'.$closerdate[2].'/'.$closerdate[0]);
			 $this->set('remidial_closer_summary',$remidialData[0]['SuggestionRemidial']['remidial_closer_summary']);
			 $this->set('remidial_button_style','style="display:none"');
			}else{
			  $this->set('remidial_closer_summary',' ');
			  $this->set('remidial_closure_date','');
			  $this->set('remidial_button_style','style="display:block"');
			}
			 $this->set('remidial_style','style="display:block"');
			 $this->set('remidial_reminder_data',$remidialData[0]['SuggestionRemidial']['remidial_reminder_data']);
			 }
		}
		
		function datecalculate(){
			$this->layout="ajax";
			$rempriority = $this->Priority->find('all',array('conditions'=>array('id'=>$this->data['remidial_priority'])));
			$ymd_format_array=explode('-',$this->data['remidial_create']);
			switch($rempriority[0]['Priority']['time_type']){
				case'days':
					 
					       echo date('d/m/Y H:i:s', mktime(0,0,0,$ymd_format_array[0],$ymd_format_array[1]+$rempriority[0]['Priority']['time'],$ymd_format_array[2]));
				break;
				case'hrs':
		
					       $time=$rempriority[0]['Priority']['time'];
					       echo date('d/m/Y H:i:s', mktime($time,0,0,$ymd_format_array[0],$ymd_format_array[1],$ymd_format_array[2]));
								
			         break;
				
			}
			exit;
		}
		
			     

		
		
		
		function remidialprocess(){
			$this->layout="ajax";
			$this->_checkAdminSession();
			
	                $remidialArray=array();
			 
				if($this->data['add_report_remidial_form']['id']!=0){
				       $res='update';
				       $remidialArray['SuggestionRemidial']['id']=$this->data['add_report_remidial_form']['id'];
				}else{
					$res='add';
				 }
			   $remidialdate=explode("-",$this->data['remidial_create']);	       
		   	   $remidialArray['SuggestionRemidial']['remidial_create']=$remidialdate[2].'-'.$remidialdate[0].'-'.$remidialdate[1];
			   $remidialArray['SuggestionRemidial']['remidial_createby']=$_SESSION['adminData']['AdminMaster']['id']; 
		           $remidialArray['SuggestionRemidial']['report_no']=$this->data['report_no'];
			   $remidialArray['SuggestionRemidial']['remedial_no']=$this->data['countRem'];
			   $prority=explode("~",$this->data['remidial_priority']);
			   $remidialArray['SuggestionRemidial']['remidial_priority']=$prority[0];
			   $remidialArray['SuggestionRemidial']['remidial_responsibility']=$this->data['responsibility'];
			   $remidialArray['SuggestionRemidial']['remidial_summery']=$this->data['add_report_remidial_form']['remidial_summery'];
			   $remidialArray['SuggestionRemidial']['remidial_closer_summary']=$this->data['add_report_remidial_form']['remidial_closer_summary'];
			   $remidialArray['SuggestionRemidial']['remidial_action']=$this->data['add_report_remidial_form']['remidial_action'];
			   $remidialArray['SuggestionRemidial']['remidial_reminder_data']=$this->data['add_report_remidial_form']['remidial_reminder_data'];
			   $remidialArray['SuggestionRemidial']['remidial_closure_target']=$this->data['add_report_remidial_form']['remidial_closure_target'];
			   if(isset($this->data['remidial_closure_date']) && $this->data['remidial_closure_date']!=''){
			     $closerdate=explode("-",$this->data['remidial_closure_date']);
			     $remidialArray['SuggestionRemidial']['remidial_closure_date']=$closerdate[0].'-'.$closerdate[1].'-'.$closerdate[2];
			     }else{
			     $remidialArray['SuggestionRemidial']['remidial_closure_date']=' ';
			     }
			     
			     
			     $createON = $remidialArray['SuggestionRemidial']['remidial_create'].' 00:00:00';
		             $explodeCTR=explode(" ",$remidialArray['SuggestionRemidial']['remidial_closure_target']);
			     $explodeCTD=explode("/",$explodeCTR[0]);
			     $reminderON=$explodeCTD[1].'-'.$explodeCTD[0].'-'.$explodeCTD[2]." ".$explodeCTR[1];
			     $strCreateOn = strtotime($createON);
			     $strReminderOn = strtotime($reminderON);
                             $remdate=$explodeCTD[2].'-'.$explodeCTD[1].'-'.$explodeCTD[0];
			     $dateHolder=array($remdate);
			     $dateIndex=array(3,7,30);
			     
			     for($e=0;$e<count($dateIndex);$e++){
				      $emaildateBefore=date('Y-m-d', mktime(0,0,0,$explodeCTD[1],$explodeCTD[0]-$dateIndex[$e],$explodeCTD[2]));
			              $strEmailBefore=strtotime($emaildateBefore); 
				      
				       if($strCreateOn<$strEmailBefore){
					    $dateHolder[]= $emaildateBefore;
					
				       }
				       
				
			        }
				
				for($e=0;$e<count($dateIndex);$e++){
				      
				      $emaildateAfter=date('Y-m-d', mktime(0,0,0,$explodeCTD[1],$explodeCTD[0]+$dateIndex[$e],$explodeCTD[2]));
				      $strEmailAfter=strtotime($emaildateAfter);
			               if($strCreateOn<$strEmailAfter){
					    $dateHolder[]= $emaildateAfter;
					
				       }
				 
			        }
				
				
				
				   $deleteREL = "DELETE FROM `remidial_email_lists` WHERE  `remedial_no` = {$remidialArray['SuggestionRemidial']['remedial_no']} AND `report_id` = {$remidialArray['SuggestionRemidial']['report_no']} AND `report_type`='suggestion'";
                                   $deleteval=$this->RemidialEmailList->query($deleteREL);
				 if($this->data['remidial_closure_date']==''){    
					
					for($d=0;$d<count($dateHolder);$d++){
					     
							$remidialEmailList=array();
							$userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$this->data['responsibility'])));
							$fullname=$userdetail[0]['AdminMaster']['first_name'].' '.$userdetail[0]['AdminMaster']['last_name'];
							$to=$userdetail[0]['AdminMaster']['admin_email'];
							$remidialEmailList['RemidialEmailList']['report_id']=$remidialArray['SuggestionRemidial']['report_no'];
							$remidialEmailList['RemidialEmailList']['remedial_no']=$remidialArray['SuggestionRemidial']['remedial_no'];
							$remidialEmailList['RemidialEmailList']['report_type']='suggestion';
							$remidialEmailList['RemidialEmailList']['email']=$to;
							$remidialEmailList['RemidialEmailList']['status']='N';
							$remidialEmailList['RemidialEmailList']['email_date']=$dateHolder[$d];
							$remidialEmailList['RemidialEmailList']['send_to']=$userdetail[0]['AdminMaster']['id'];
							
							$this->RemidialEmailList->create();
							$this->RemidialEmailList->save($remidialEmailList);
						
					}
				 }
						     

			if($this->SuggestionRemidial->save($remidialArray)){
				     echo $res.'~suggestion';
		                }else{
				     echo 'fail';
			        }
			        
                         

			exit;
			
		}
		
		
		function report_suggestion_remidial_list($id=null){
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
			

			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['SuggestionMain']['report_no']);
			if(!empty($this->request->data))
			{
				$action = $this->request->data['SuggestionRemidial']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['SuggestionRemidial']['limit'];
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
	
	
		public function get_all_remidial_list($report_id)
		{
			Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
			$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
			//pr($_REQUEST);
			$this->_checkAdminSession();
			$condition="";

			
			$condition="SuggestionRemidial.report_no = $report_id AND SuggestionRemidial.isdeleted = 'N'";
			
			if(isset($_REQUEST['filter'])){
				switch($this->data['filter']){
				case'create_on':
				    $explodemonth=explode('/',$this->data['value']);
				    $day=$explodemonth[0];
				    $month=date('m', strtotime($explodemonth[1]));
				    $year="20$explodemonth[2]";
				    $createon=$year."-".$month."-".$day;
				    $condition .= "AND SuggestionRemidial.remidial_create ='".$createon."'";	
				break;
				case'remidial_create_name':
				     $spliNAME=explode(" ",$_REQUEST['value']);
				     $spliLname=$spliNAME[count($spliNAME)-1];
				     $spliFname=$spliNAME[0];
				     $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
				     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
				     $addimid=$userDetail[0]['AdminMaster']['id'];
				     $condition .= "AND SuggestionRemidial.remidial_createby ='".$addimid."'";
					
				break;
				case'responsibility_person':
				     $spliNAME=explode(" ",$_REQUEST['value']);
				     $spliLname=$spliNAME[count($spliNAME)-1];
				     $spliFname=$spliNAME[0];
				     $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
				     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
				     $addimid=$userDetail[0]['AdminMaster']['id'];
				     $condition .= "AND SuggestionRemidial.remidial_responsibility ='".$addimid."'";	
				break;
				case'remidial_priority_name':
				     $priorityCondition = "Priority.type='".trim($_REQUEST['value'])."'";
				     $priorityDetail = $this->Priority->find('all',array('conditions'=>$priorityCondition));
				     $condition .= "AND SuggestionRemidial.remidial_priority ='".$priorityDetail[0]['Priority']['id']."'";	
				break;
				}
			}
			
			
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
	
			 $adminArray = array();	
			 $count = $this->SuggestionRemidial->find('count' ,array('conditions' => $condition));
			 $adminA = $this->SuggestionRemidial->find('all',array('conditions' => $condition,'order' => 'SuggestionRemidial.id DESC','limit'=>$limit));

			$i = 0;
			foreach($adminA as $rec)
			{			
				if($rec['SuggestionRemidial']['isblocked'] == 'N')
				{
					$adminA[$i]['SuggestionRemidial']['blockHideIndex'] = "true";
					$adminA[$i]['SuggestionRemidial']['unblockHideIndex'] = "false";
					$adminA[$i]['SuggestionRemidial']['isdeletdHideIndex'] = "true";
				}else{
					$adminA[$i]['SuggestionRemidial']['blockHideIndex'] = "false";
					$adminA[$i]['SuggestionRemidial']['unblockHideIndex'] = "true";
					$adminA[$i]['SuggestionRemidial']['isdeletdHideIndex'] = "false";
				}
				
			    $create_on=explode("-",$rec['SuggestionRemidial']['remidial_create']);
			    $adminA[$i]['SuggestionRemidial']['create_on']=date("d/M/y", mktime(0, 0, 0, $create_on[1], $create_on[2], $create_on[0]));
			    $lastupdated=explode(" ", $rec['SuggestionRemidial']['modified']);
			    $lastupdatedate=explode("-",$lastupdated[0]);
			    $adminA[$i]['SuggestionRemidial']['lastupdate']=date("d/M/y", mktime(0, 0, 0, $lastupdatedate[1], $lastupdatedate[2], $lastupdatedate[0]));
			    $createdate=explode("-",$rec['SuggestionRemidial']['remidial_create']);
			    $adminA[$i]['SuggestionRemidial']['createRemidial']=date("d/M/y", mktime(0, 0, 0, $createdate[1], $createdate[2], $createdate[0]));
			    $adminA[$i]['SuggestionRemidial']['remidial_priority_name'] ='<font color='.$rec['Priority']['colorcoder'].'>'.$rec['Priority']['type'].'</font>';
			    $adminA[$i]['SuggestionRemidial']['remidial_create_name'] = $rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
			    $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['SuggestionRemidial']['remidial_responsibility'])));
			    $adminA[$i]['SuggestionRemidial']['responsibility_person']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
			    if($rec['SuggestionRemidial']['remidial_closure_date']!='0000-00-00'){
			       $rem_cls_date=explode("-",$rec['SuggestionRemidial']['remidial_closure_date']);
			       $adminA[$i]['SuggestionRemidial']['closure']=date("d-M-y", mktime(0, 0, 0, $rem_cls_date[1], $rem_cls_date[2], $rem_cls_date[0]));
			    }else{
			        $adminA[$i]['SuggestionRemidial']['closure']='';	
			    }
			    $i++;
			}
			if($count==0){
			   $adminArray=array();
		        }else{
			  $adminArray = Set::extract($adminA, '{n}.SuggestionRemidial');
		        }
			 
			  $this->set('total', $count); 
			  $this->set('admins', $adminArray); 
			 
		}
		 
	    function remidial_block($id = null)
	     {

	     
	                if(!$id)
			{
		           $this->redirect(array('action'=>report_suggestion_list), null, true);
			}
		       else
		       
		       
		       {
			  
	                 	$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SuggestionRemidial']['id'] = $id;
					  $this->request->data['SuggestionRemidial']['isblocked'] = 'Y';
					  $this->SuggestionRemidial->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	     
	      }
	      
	      function remidial_unblock($id = null)
	     {
                  
			if(!$id)
			{
		           $this->redirect(array('action'=>report_suggestion_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SuggestionRemidial']['id'] = $id;
					  $this->request->data['SuggestionRemidial']['isblocked'] = 'N';
					  $this->SuggestionRemidial->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function remidial_delete()
	     {
		        $this->layout="ajax";
			 $idArray = explode("^", $this->data['id']);
				
	            	        foreach($idArray as $id)
			       {
				 $remidialData= $this->SuggestionRemidial->find('all',array('conditions'=>array('SuggestionRemidial.id'=>$id)));
				 $deleteHsse_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$remidialData[0]['SuggestionRemidial']['report_no']}  AND  `remedial_no` = {$remidialData[0]['SuggestionRemidial']['remedial_no']} AND `report_type`='suggestion'";
                                 $dHRem=$this->RemidialEmailList->query($deleteHsse_remidials_email);
				 $deleteHsse_remidials = "DELETE FROM `suggestion_remidials` WHERE `id` = {$id}";
                                 $dHR=$this->SuggestionRemidial->query($deleteHsse_remidials);
					  
					  
					  
					  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	      }
	     
	     	function report_sq_remidial_list($id=null){
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
			

			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);
			if(!empty($this->request->data))
			{
				$action = $this->request->data['SqRemidial']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['SqRemidial']['limit'];
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
	
	         function report_suggestion_remedila_email_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->SuggestionMain->find('all', array('conditions' => array('SuggestionMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
	               	$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['SuggestionMain']['report_no']);
			if(!empty($this->request->data))
			{
				$action = $this->request->data['RemidialEmailList']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['RemidialEmailList']['limit'];
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
	
	
		public function get_all_remidial_email_list($report_id)
		{
			Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
			$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
			//pr($_REQUEST);
			$this->_checkAdminSession();
			$condition="";

			
			$condition="RemidialEmailList.report_id = $report_id AND report_type='suggestion'";
			
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
						$condition .= " AND RemidialEmailList.email_date ='".$createon."'";	
					break;
				        case'email':
			                        $condition .= " AND RemidialEmailList.email like '%".$_REQUEST['value']."%'";
						
					break;
				        case'responsibility_person':
					     $spliNAME=explode(" ",$_REQUEST['value']);
			                     $spliLname=$spliNAME[count($spliNAME)-1];
			                     $spliFname=$spliNAME[0];
		                             $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			                     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
					     $addimid=$userDetail[0]['AdminMaster']['id'];
		                             $condition .= " AND RemidialEmailList.send_to ='".$addimid."'";	
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
			 $count = $this->RemidialEmailList->find('count' ,array('conditions' => $condition));
			 $adminA = $this->RemidialEmailList->find('all',array('conditions' => $condition,'order' => 'RemidialEmailList.id DESC','limit'=>$limit));
			
			$i = 0;
			foreach($adminA as $rec)
			{			
			  if($rec['RemidialEmailList']['status'] == 'N')
				{
					$adminA[$i]['RemidialEmailList']['status_value'] = 'Not Send';
								
				}else{
					$adminA[$i]['RemidialEmailList']['status_value'] = "Sent";
					
				
				}
			    $remC= $this->SuggestionRemidial->find('all', array('conditions' => array('SuggestionRemidial.report_no' =>$rec['RemidialEmailList']['report_id'],'SuggestionRemidial.remedial_no' =>$rec['RemidialEmailList']['remedial_no'])));
			   if($remC[0]['SuggestionRemidial']['remidial_reminder_data']!=''){
			    $rrd=explode(" ",$remC[0]['SuggestionRemidial']['remidial_reminder_data']);
			    $rrdE=explode("/",$rrd[0]);
			    $adminA[$i]['RemidialEmailList']['reminder_data']=date("d/M/y", mktime(0, 0, 0, $rrdE[1], $rrdE[0], $rrdE[2]));		
			    }else{
			       $adminA[$i]['RemidialEmailList']['reminder_data']=''; 	
			    }
			    if($remC[0]['SuggestionRemidial']['remidial_create']!=''){
			    $create_on=explode("-",$remC[0]['SuggestionRemidial']['remidial_create']);
			    $adminA[$i]['RemidialEmailList']['create_on']=date("d/M/y", mktime(0, 0, 0, $create_on[1], $create_on[2], $create_on[0]));
			    }else{
			    $adminA[$i]['RemidialEmailList']['create_on']='';	
			    }
			    $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['RemidialEmailList']['send_to'])));
			   	    
			    $adminA[$i]['RemidialEmailList']['responsibility_person']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
			    $explodemail=explode("-",$rec['RemidialEmailList']['email_date']);
                            $adminA[$i]['RemidialEmailList']['email_date']=date("d/M/y", mktime(0, 0, 0, $explodemail[1], $explodemail[2], $explodemail[0]));
			    $i++;
			
			}
			
			
			
			if($count==0){
			   $adminArray=array();
		        }else{
			   $adminArray = Set::extract($adminA, '{n}.RemidialEmailList');
		        }
			 

			  
			  
			  $this->set('total', $count);  //send total to the view
			  $this->set('admins', $adminArray);  //send products to the view
			  //$this->set('status', $action);
		}
	      
	        function remidial_email_delete()
	        {
		        $this->layout="ajax";
		         $idArray = explode("^",$this->data['id']);
				
	            	        foreach($idArray as $id)
			       {
				
					 $deleteRem = "DELETE FROM `remidial_email_lists` WHERE `id` = {$id}";
                                         $deleteval=$this->RemidialEmailList->query($deleteRem);
						  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	       }
	       
	       
	       function remidial_email_view($id,$remedial_no,$report_id){
			 $this->_checkAdminSession();
		         $this->_getRoleMenuPermission();
                         $this->grid_access();
			 $this->layout="ajax";
			 $remidialData= $this->SuggestionRemidial->find('all',array('conditions'=>array('SuggestionRemidial.report_no'=>$report_id,'SuggestionRemidial.remedial_no'=>$remedial_no)));
			 $reportData= $this->SuggestionMain->find('all',array('conditions'=>array('SuggestionMain.id'=>$remidialData[0]['SuggestionRemidial']['report_no'])));
			 $userData= $this->AdminMaster->find('all',array('conditions'=>array('AdminMaster.id'=>$remidialData[0]['SuggestionRemidial']['remidial_responsibility'])));
			 $this->set('fullname',$userData[0]['AdminMaster']['first_name']." ".$userData[0]['AdminMaster']['last_name']);
			 $this->set('report_no',$reportData[0]['SuggestionMain']['report_no']);
			 $this->set('remidialData',$remidialData);
		
		}
	
        
	      
}	