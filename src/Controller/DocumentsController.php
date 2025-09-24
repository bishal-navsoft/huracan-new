<?PHP
class DocumentsController extends AppController
{
	public $name = 'Documents';
	public $uses = array('Report','JobReportMain','DocumentLink','SuggestionMain','JhaMain','LessonAttachment','DocumentAttachment','DocumentMain','LdjsEmail','DocumentationType','LessonEmail','Service','LessonLink','AdminMaster','LessonMain','SqReportMain','AuditReportMain','JnReportMain','Incident','BusinessType','Fieldlocation','Client','HsseLink','JobLink','LessonLink','IncidentLocation','IncidentSeverity','Country','AdminMaster','RolePermission','RoleMaster','AdminMenu');
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	//var $components = array('PhpMailerEmail');
	public function document_main_list(){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();		
		$this->layout="after_adminlogin_template";
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['DocumentMain']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['DocumentMain']['limit'];
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
		$idDocumentBoolen=array();
		$condition="";
		$condition = "DocumentMain.isdeleted = 'N'";
		
		$adminA = $this->DocumentMain->find('all' ,array('conditions' => $condition,'order' => 'DocumentMain.id DESC','limit'=>$limit));
		for($i=0;$i<count($adminA);$i++){
			if(($_SESSION['adminData']['AdminMaster']['id']==$adminA[$i]['DocumentMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
			   array_push($idDocumentBoolen,1);	
				
			}else{
			  array_push($idDocumentBoolen,0);
			}
			
		}
		$this->Session->write('idDocumentBoolen',$idDocumentBoolen);
		
	}
	
	public function get_all_report($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		$condition = "DocumentMain.isdeleted = 'N'";
                if(isset($_REQUEST['filter'])){
			switch($_REQUEST['filter']){
				case'report_no':
				$condition .= "AND ucase(DocumentMain.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
				break;
				case'creater_name':
				$spliNAME=explode(" ",$_REQUEST['value']);
				$spliLname=$spliNAME[count($spliNAME)-1];
				$spliFname=$spliNAME[0];
				$adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
				$userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
				$addimid=$userDetail[0]['AdminMaster']['id'];
				$condition .= "AND DocumentMain.created_by ='".$addimid."'";	
				break;
				case'validate_date_val':
				$explodemonth=explode('/',$_REQUEST['value']);
				$day=$explodemonth[0];
				$month=date('m', strtotime($explodemonth[1]));
				$year="20$explodemonth[2]";
				$createon=$year."-".$month."-".$day;
				$condition .= "AND DocumentMain.validation_date ='".$createon."'";	
				break;
			        case'rvalidate_date_val':
				$explodemonth=explode('/',$_REQUEST['value']);
				$day=$explodemonth[0];
				$month=date('m', strtotime($explodemonth[1]));
				$year="20$explodemonth[2]";
				$createon=$year."-".$month."-".$day;
				$condition .= "AND DocumentMain.revalidate_date ='".$createon."'";	
				break;
			        case'create_date_val':
				$explodemonth=explode('/',$_REQUEST['value']);
				$day=$explodemonth[0];
				$month=date('m', strtotime($explodemonth[1]));
				$year="20$explodemonth[2]";
				$createon=$year."-".$month."-".$day;
				$condition .= "AND DocumentMain.create_date ='".$createon."'";	
				break; 
			}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		
		$count = $this->DocumentMain->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();
		
		$adminA = $this->DocumentMain->find('all' ,array('conditions' => $condition,'order' => 'DocumentMain.id DESC','limit'=>$limit)); 
		
		$i = 0;
		$idDocumentBoolen=array();
		unset($_SESSION['idDocumentBoolen']);
		foreach($adminA as $rec)
		{
		
			if(($_SESSION['adminData']['AdminMaster']['id']==$rec['DocumentMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
				array_push($idDocumentBoolen,1);
				$adminA[$i]['DocumentMain']['edit_permit'] ="false";
				$adminA[$i]['DocumentMain']['view_permit'] ="false";
				$adminA[$i]['DocumentMain']['delete_permit'] ="false";
				$adminA[$i]['DocumentMain']['block_permit'] ="false";
				$adminA[$i]['DocumentMain']['unblock_permit'] ="false";
				$adminA[$i]['DocumentMain']['checkbox_permit'] ="false";
				
				if($rec['DocumentMain']['isblocked'] == 'N')
			            {
					$adminA[$i]['DocumentMain']['blockHideIndex'] = "true";
					$adminA[$i]['DocumentMain']['unblockHideIndex'] = "false";
				        
			         }else{
					$adminA[$i]['DocumentMain']['blockHideIndex'] = "false";
					$adminA[$i]['DocumentMain']['unblockHideIndex'] = "true";
					
			        }
				
					
			}else{
				array_push($idDocumentBoolen,0);
				$adminA[$i]['DocumentMain']['edit_permit'] ="true";
				$adminA[$i]['DocumentMain']['view_permit'] ="false";
				$adminA[$i]['DocumentMain']['delete_permit'] ="true";
				$adminA[$i]['DocumentMain']['block_permit'] ="true";
				$adminA[$i]['DocumentMain']['unblock_permit'] ="true";
			        $adminA[$i]['DocumentMain']['blockHideIndex'] = "true";
				$adminA[$i]['DocumentMain']['unblockHideIndex'] = "true";
				$adminA[$i]['DocumentMain']['checkbox_permit'] ="true";
				
				
			}
			
         		$adminA[$i]['DocumentMain']['creater_name'] =$rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
	               	$adminA[$i]['DocumentMain']['type'] =$rec['DocumentationType']['type'];
			$adminA[$i]['DocumentMain']['business_type_name'] =$rec['BusinessType']['type'];
			$adminA[$i]['DocumentMain']['filedlocation_type_name'] =$rec['Fieldlocation']['type'];
			$adminA[$i]['DocumentMain']['country_name'] =$rec['Country']['name'];
			$validateDetail = $this->AdminMaster->find('all',array('conditions' => array('AdminMaster.id' =>$rec['DocumentMain']['validate_by'])));
			$adminA[$i]['DocumentMain']['validate_name'] =$validateDetail[0]['AdminMaster']['first_name'].' '.$validateDetail[0]['AdminMaster']['last_name'];
			$ex_create=explode("-",$rec['DocumentMain']['create_date']);
			$createDATE=date("d/M/y", mktime(0, 0, 0, $ex_create[1], $ex_create[2],$ex_create[0]));
		        $adminA[$i]['DocumentMain']['create_date_val'] =$createDATE;
			
			if($rec['DocumentMain']['validation_date']!=''){
			$val_date=explode("-",$rec['DocumentMain']['validation_date']);
			$validateDATE=date("d/M/y", mktime(0, 0, 0, $val_date[1], $val_date[2],$val_date[0]));
		        $adminA[$i]['DocumentMain']['validate_date_val']=$validateDATE;
			}else{
			$adminA[$i]['DocumentMain']['validate_date_val']='';	
			}
			
			if($rec['DocumentMain']['revalidate_date']!=''){
			$rval_date=explode("-",$rec['DocumentMain']['revalidate_date']);
			$rvalidateDATE=date("d/M/y", mktime(0, 0, 0, $rval_date[1], $rval_date[2],$rval_date[0]));
		        $adminA[$i]['DocumentMain']['rvalidate_date_val']=$rvalidateDATE;
			}else{
			$adminA[$i]['DocumentMain']['rvalidate_date_val']='';	
			}
			
		    $i++;
		}
		
                 
		 
		 
		if($count==0){
			$adminArray=array();
	        }else{
			$adminArray = Set::extract($adminA, '{n}.DocumentMain');
		      }
		
		$this->Session->write('idDocumentBoolen',$idDocumentBoolen);
		$this->set('total', $count);  //send total to the view
		$this->set('admins', $adminArray);  //send products to the view
         	$this->set('status', $action);
	}
	
	
	public function add_document_report_main($id=null)
		
	{
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
                 $incidentDetail = $this->Incident->find('all', array('conditions' => array('incident_type' =>'all')));
     		 $businessDetail = $this->BusinessType->find('all');
		 $dType = $this->DocumentationType->find('all');
		 $fieldlocationDetail = $this->Fieldlocation->find('all');
		 $countryDetail = $this->Country->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('dType',$dType);
		 $this->set('incidentDetail',$incidentDetail);
		 $this->set('businessDetail',$businessDetail);
		 $this->set('fieldlocationDetail',$fieldlocationDetail);
		 $this->set('country',$countryDetail);
		 $this->set('userDetail',$userDetail);

		 if($id==null)
		 {
          
			$this->set('id','0');

			$this->set('heading','Add Documentation (Main)');
			$this->set('button','Submit');
			$this->set('report_no','');
			$this->set('closer_date','00-00-0000');
			$this->set('incident_type','');
			$this->set('created_date','');
			$this->set('business_unit','');
			$this->set('field_location','');
			$this->set('d_type','');
			$this->set('doc_type',$dType[0]['DocumentationType']['type']);
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
			
	               
		       $reportdetail = $this->DocumentMain->find('all', array('conditions' => array('DocumentMain.id' =>base64_decode($id))));
		       $this->Session->write('report_create',$reportdetail[0]['DocumentMain']['created_by']);
		       $dType = $this->DocumentationType->find('all' ,array('conditions' => array('id' =>$reportdetail[0]['DocumentMain']['d_type'])));
                       $adminDATA = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['DocumentMain']['created_by'])));
		       $validateBy = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['DocumentMain']['validate_by'])));
		        if($reportdetail[0]['DocumentMain']['validation_date']!=''){
		                 $valed=explode("-",$reportdetail[0]['DocumentMain']['validation_date']);
				 $valedt=$valed[1]."-".$valed[2]."-".$valed[0];
		        }else{
		                $valedt='';	
		        }
	                if($reportdetail[0]['DocumentMain']['revalidate_date']!=''){
		                 $rvaled=explode("-",$reportdetail[0]['DocumentMain']['revalidate_date']);
				 $rvaledt=$rvaled[1]."-".$rvaled[2]."-".$rvaled[0];
		        }else{
		                $rvaledt='';	
		        }
		       
		       $this->set('id',base64_decode($id));
		      	       
			if($reportdetail[0]['DocumentMain']['create_date']!=''){
			 
			         $crtd=explode("-",$reportdetail[0]['DocumentMain']['create_date']);
			         $createDATE=date("d/M/y", mktime(0, 0, 0, $crtd[1], $crtd[2], $crtd[0]));
			 
			}else{
			         $createDATE=''; 	
			}
			$this->set('doc_type',$dType[0]['DocumentationType']['type']);
	  	        $this->set('create_date',$createDATE);
		        $this->set('heading','Edit Documentation (Main)');
		        $this->set('button','Update');
		        $this->set('reportno',$reportdetail[0]['DocumentMain']['report_no']);
			$this->set('cnt',$reportdetail[0]['DocumentMain']['country']);
			$this->set('revalidation_date_val',$rvaledt);
			$this->set('validate_date_val',$valedt);
			$this->set('business_unit',$reportdetail[0]['DocumentMain']['business_unit']);
			$this->set('validate_user', $reportdetail[0]['DocumentMain']['validate_by']);
			$this->set('field_location',$reportdetail[0]['DocumentMain']['field_location']);
			$this->set('d_type',$reportdetail[0]['DocumentMain']['d_type']);
	      		$this->set('summary',$reportdetail[0]['DocumentMain']['summary']);
			$this->set('details',$reportdetail[0]['DocumentMain']['details']);
                	$this->set('report_id',base64_decode($id));
			$this->set('created_by',$adminDATA[0]['AdminMaster']['first_name']." ".$adminDATA[0]['AdminMaster']['last_name']);
		 
		 
		 }
	}
       function dcreportprocess(){
			
		
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
	  $dcreportMainData=array();
          if($this->data['add_documentation_main_form']['id']==0){
		   $res='add';
 	                
	   }else{
		   $res='update';
		   $dcreportMainData['DocumentMain']['id']=$this->data['add_documentation_main_form']['id'];
		  
	    }
	    
	   if($this->data['validate_date']!=''){
           $vdate=explode("-",$this->data['validate_date']);
	       $dcreportMainData['DocumentMain']['validation_date']=$vdate[2]."-".$vdate[0]."-".$vdate[1];
	   }else{
	       $dcreportMainData['DocumentMain']['validation_date']='';	
	   }
	   
	   if($this->data['revalidation_date']!=''){
           $rvdate=explode("-",$this->data['revalidation_date']);
	      $dcreportMainData['DocumentMain']['revalidate_date']=$rvdate[2]."-".$rvdate[0]."-".$rvdate[1];
	   }else{
	      $dcreportMainData['DocumentMain']['revalidate_date']='';	
	   } 
	   
	   $dcreportMainData['DocumentMain']['report_no']=$this->data['report_no'];
	   $dcreportMainData['DocumentMain']['create_date']=date('Y-m-d'); 
	   $dcreportMainData['DocumentMain']['business_unit'] =$this->data['business_unit'];  
	   $dcreportMainData['DocumentMain']['field_location']=$this->data['field_location'];
	   $dcreportMainData['DocumentMain']['country']=$this->data['country'];
	   $dcreportMainData['DocumentMain']['validate_by']=$this->data['validate_by'];
	   $dcreportMainData['DocumentMain']['d_type']=$this->data['d_type'];
	   $dcreportMainData['DocumentMain']['created_by']=$_SESSION['adminData']['AdminMaster']['id'];
           $dcreportMainData['DocumentMain']['summary']=$this->data['add_documentation_main_form']['summary'];
	   $dcreportMainData['DocumentMain']['details']=$this->data['add_documentation_main_form']['details'];
	   
	   $strCreateOn = strtotime('Y-m-d');
	   $revalidationstr = strtotime($dcreportMainData['DocumentMain']['revalidate_date']);
	   $dateHolder=array($dcreportMainData['DocumentMain']['revalidate_date']);
	   $dateIndex=array(3,7,30);
	 
	   
	 if($this->DocumentMain->save($dcreportMainData)){
		if($res=='add'){
			 $lastReport=base64_encode($this->DocumentMain->getLastInsertId());
		 }elseif($res=='update'){
			 $lastReport=base64_encode($this->data['add_documentation_main_form']['id']);
		 }
	 	
		
		
		 if($dcreportMainData['DocumentMain']['revalidate_date']!=''){ 
			for($e=0;$e<count($dateIndex);$e++){
				   $emaildateBefore=date('Y-m-d', mktime(0,0,0,$rvdate[0],$rvdate[1]-$dateIndex[$e],$rvdate[2]));
				   $strEmailBefore=strtotime($emaildateBefore); 
				   
				    if($strCreateOn<$strEmailBefore){
					 $dateHolder[]= $emaildateBefore;
				     
				    }
				    
			     
			     }
					     
			     for($e=0;$e<count($dateIndex);$e++){
				   
				   $emaildateAfter=date('Y-m-d', mktime(0,0,0,$rvdate[1],$rvdate[0]+$dateIndex[$e],$rvdate[2]));
				   $strEmailAfter=strtotime($emaildateAfter);
				    if($strCreateOn<$strEmailAfter){
					 $dateHolder[]= $emaildateAfter;
				      }
			      
			     }
			     $lR=base64_decode($lastReport);
					     
			     $deleteLE = "DELETE FROM `ldjs_emails` WHERE  `leid` = {$lR} AND `type`='document'";
			     $deleteval=$this->LdjsEmail->query($deleteLE);
					     
			     for($d=0;$d<count($dateHolder);$d++){
				  
					     $dcEmailList=array();
					     $userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$this->data['validate_by'])));
					     $fullname=$userdetail[0]['AdminMaster']['first_name'].' '.$userdetail[0]['AdminMaster']['last_name'];
					     $to=$userdetail[0]['AdminMaster']['admin_email'];
					     $dcEmailList['LdjsEmail']['leid']=base64_decode($lastReport);
					     $dcEmailList['LdjsEmail']['user_id']=$this->data['validate_by'];
					     $dcEmailList['LdjsEmail']['status']='N'; 
					     $dcEmailList['LdjsEmail']['type']='document';
					     $dcEmailList['LdjsEmail']['email_date']=$dateHolder[$d];
					     $this->LdjsEmail->create();
					     $this->LdjsEmail->save($dcEmailList);
				     
			     }
	   
	   
	       }
		
		echo $res.'~'.$lastReport;
		
	    }else{
		echo 'fail';
	    }
            
 
	  

	  
		
	   exit;
	
	
       }
       	function document_block($id = null)
	{
          
	
		if(!$id)
		{

			   $this->redirect(array('action'=>document_main_list), null, true);
		}
		else
		{
			 $idArray = explode("^", $id);
			 
			 
		
			foreach($idArray as $id)
			{
				   $id = $id;
				   $this->request->data['DocumentMain']['id'] = $id;
				   $this->request->data['DocumentMain']['isblocked'] = 'Y';
				   $this->DocumentMain->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}
	
	function document_unblock($id = null)
	{

		
		if(!$id)
		{
	
			$this->redirect(array('action'=>document_main_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
		
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['DocumentMain']['id'] = $id;
				$this->request->data['DocumentMain']['isblocked'] = 'N';
				$this->DocumentMain->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
	
	function document_main_delete()
	     {
		      $this->layout="ajax";
		      
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
				
					  $deleteLsLink = "DELETE FROM `document_links` WHERE `report_id` = {$id}";
                                          $dls=$this->DocumentLink->query($deleteLsLink);
					  $deleteLsAttachment = "DELETE FROM `document_attachments` WHERE `report_id` = {$id}";
                                          $dlA=$this->DocumentAttachment->query($deleteLsAttachment);
					  $deleteLsEmail = "DELETE FROM  `ldjs_emails` WHERE `leid` = {$id}  AND  `type` ='document'";
                                          $dls=$this->LdjsEmail->query($deleteLsEmail);
					  $deleteLsMain = "DELETE FROM `document_mains` WHERE `id` = {$id}";
                                          $dlm=$this->DocumentMain->query($deleteLsMain);
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>document_main_list), null, true);
		      }
			 
			      
			      
			       
	}

	 public function report_document_link_list($id=null,$typSearch){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$sqlinkDetail = $this->DocumentLink->find('all', array('conditions' => array('DocumentLink.report_id' =>base64_decode($id))));
		$reportdetail = $this->DocumentMain->find('all', array('conditions' => array('DocumentMain.id' =>base64_decode($id))));            
		$this->set('report_number',$reportdetail[0]['DocumentMain']['report_no']);
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
		           $this->redirect(array('action'=>'document_main_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['DocumentLink']['id'] = $id;
					  $this->request->data['DocumentLink']['isblocked'] = 'Y';
					  $this->DocumentLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function link_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'document_main_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['DocumentLink']['id'] = $id;
					  $this->request->data['DocumentLink']['isblocked'] = 'N';
					  $this->DocumentLink->save($this->request->data,false);				
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
					  
					  $deleteproperty = "DELETE FROM `document_links` WHERE `id` = {$id}";
                                          $deleteval=$this->DocumentLink->query($deleteproperty);
					  
			       }
		
			       echo 'ok';
			       exit;
		      }else{
			 $this->redirect(array('action'=>'document_main_list'), null, true);
		      }
			       
			      
			       
	
	      }
	     function  linkrocess(){
		   
		         $this->layout="ajax";
			 $this->_checkAdminSession();
         	         $this->_getRoleMenuPermission();
		         $this->grid_access();
			 $linkArray=array();
			 $explode_id_type=explode("_",$this->data['type']);
		          $sqlinkDetail = $this->DocumentLink->find('all', array('conditions' => array('DocumentLink.report_id' =>base64_decode($this->data['report_no']),'DocumentLink.link_report_id' =>$explode_id_type[1],'DocumentLink.type' =>$explode_id_type[0])));
			 if(count($sqlinkDetail)>0){
				echo 'avl';
				
			 }else{
			 $linkArray['DocumentLink']['type']=$explode_id_type[0];
			 $linkArray['DocumentLink']['report_id']=base64_decode($this->data['report_no']);
			 $linkArray['DocumentLink']['link_report_id']=$explode_id_type[1];
			 $linkArray['DocumentLink']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
			 
			 if($this->DocumentLink->save($linkArray)){
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
	
                $condition="DocumentLink.report_id =".base64_decode($report_id);
		 if(isset($_REQUEST['filter'])){
	             $link_type=explode("~",$this->link_search($_REQUEST['value']));
		     
		     $condition .= " AND DocumentLink.link_report_id ='".$link_type[0]."' AND DocumentLink.type ='".$link_type[1]."'";
		     
		}
		
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 $adminArray = array();
		  if($filterTYPE!='all'){
			
		    $condition .= " AND DocumentLink.type ='".$filterTYPE."'";
		
		 }
		 $count = $this->DocumentLink->find('count' ,array('conditions' => $condition));
		 $adminA = $this->DocumentLink->find('all',array('conditions' => $condition,'order' => 'DocumentLink.id DESC','limit'=>$limit));
		 

		  
		$i = 0;
		foreach($adminA as $rec)
		{
	
			if($rec['DocumentLink']['isblocked'] == 'N')
			{
				$adminA[$i]['DocumentLink']['blockHideIndex'] = "true";
				$adminA[$i]['DocumentLink']['unblockHideIndex'] = "false";
				$adminA[$i]['DocumentLink']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['DocumentLink']['blockHideIndex'] = "false";
				$adminA[$i]['DocumentLink']['unblockHideIndex'] = "true";
				$adminA[$i]['DocumentLink']['isdeletdHideIndex'] = "false";
			}
			 $link_type=$this->link_grid($adminA[$i],$rec['DocumentLink']['type'],'DocumentLink',$rec);
		         $explode_link_type=explode("~",$link_type);
		         $adminA[$i]['DocumentLink']['link_report_no']=$explode_link_type[0];
		         $adminA[$i]['DocumentLink']['type_name']=$explode_link_type[1];

		
			
		    $i++;
		}
		
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.DocumentLink');
		  }
	 
		  
                  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
 
			 

	
	

		
	        function add_dcreport_view($id=null){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$reportdetail = $this->DocumentMain->find('all', array('conditions' => array('DocumentMain.id' =>base64_decode($id))));
			$this->Session->write('report_create',$reportdetail[0]['DocumentMain']['created_by']);
			$rdate_time=explode("-",$reportdetail[0]['DocumentMain']['create_date']);
		        $reportdetail[0]['DocumentMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
			
			if($reportdetail[0]['DocumentMain']['validation_date']!=''){
			$vdate_time=explode("-",$reportdetail[0]['DocumentMain']['validation_date']);
		        $reportdetail[0]['DocumentMain']['validation_date_val']=date("d-M-y", mktime(0, 0, 0, $vdate_time[1],$vdate_time[2],$vdate_time[0]));
			}else{
		        $reportdetail[0]['DocumentMain']['validation_date_val']='';		
			}
			if($reportdetail[0]['DocumentMain']['revalidate_date']!=''){
			$rvdate_time=explode("-",$reportdetail[0]['DocumentMain']['revalidate_date']);
		        $reportdetail[0]['DocumentMain']['revalidate_date_val']=date("d-M-y", mktime(0, 0, 0, $rvdate_time[1],$rvdate_time[2],$rvdate_time[0]));
			}else{
			 $reportdetail[0]['DocumentMain']['revalidate_date_val']='';	
			}
			$validateBy= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['DocumentMain']['validate_by']))); 
			$reportdetail[0]['DocumentMain']['validate_by_person']=$validateBy[0]['AdminMaster']['first_name']." ".$validateBy[0]['AdminMaster']['last_name'];
			$this->set('reportdetail',$reportdetail);
			
		
		 }
		 
		 function print_view($id=null){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="ajax";
			$reportdetail = $this->DocumentMain->find('all', array('conditions' => array('DocumentMain.id' =>base64_decode($id))));
			$rdate_time=explode("-",$reportdetail[0]['DocumentMain']['create_date']);
		        $reportdetail[0]['DocumentMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
			
			if($reportdetail[0]['DocumentMain']['validation_date']!=''){
			$vdate_time=explode("-",$reportdetail[0]['DocumentMain']['validation_date']);
		        $reportdetail[0]['DocumentMain']['validation_date_val']=date("d-M-y", mktime(0, 0, 0, $vdate_time[1],$vdate_time[2],$vdate_time[0]));
			}else{
		        $reportdetail[0]['DocumentMain']['validation_date_val']='';		
			}
			if($reportdetail[0]['DocumentMain']['revalidate_date']!=''){
			$rvdate_time=explode("-",$reportdetail[0]['DocumentMain']['revalidate_date']);
		        $reportdetail[0]['DocumentMain']['revalidate_date_val']=date("d-M-y", mktime(0, 0, 0, $rvdate_time[1],$rvdate_time[2],$rvdate_time[0]));
			}else{
			 $reportdetail[0]['DocumentMain']['revalidate_date_val']='';	
			}
			$validateBy= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['DocumentMain']['validate_by']))); 
			$reportdetail[0]['DocumentMain']['validate_by_person']=$validateBy[0]['AdminMaster']['first_name']." ".$validateBy[0]['AdminMaster']['last_name'];
			$this->set('reportdetail',$reportdetail);
		
		 }
		public function report_doucument_attachment_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->DocumentMain->find('all', array('conditions' => array('DocumentMain.id' =>base64_decode($id))));
			$this->set('report_number',$reportdetail[0]['DocumentMain']['report_no']);	       
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['DocumentAttachment']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['DocumentAttachment']['limit'];
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
	
		
		$condition="DocumentAttachment.report_id = $report_id AND   DocumentAttachment.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(DocumentAttachment.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		//$count = $this->HsseIncident->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();	
		 $count = $this->DocumentAttachment->find('count' ,array('conditions' => $condition));
		 $adminA = $this->DocumentAttachment->find('all',array('conditions' => $condition,'order' => 'DocumentAttachment.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['DocumentAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['DocumentAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['DocumentAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['DocumentAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['DocumentAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['DocumentAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['DocumentAttachment']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['DocumentAttachment']['image_src']=$this->attachment_list('DocumentAttachment',$rec);
			
		    $i++;
		}
		
		
		 if($count==0){
			   $adminArray=array();
		 }else{
			  $adminArray = Set::extract($adminA, '{n}.DocumentAttachment');
		 }
		
	  
		  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	      
	          function add_document_attachment($reoprt_id=null,$attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";
		
			if($attachment_id!=''){
				$attchmentdetail = $this->DocumentAttachment->find('all', array('conditions' => array('DocumentAttachment.id' =>base64_decode($attachment_id))));
				$filetype1=explode('.',$attchmentdetail[0]['DocumentAttachment']['file_name']);
				
				$filetype = end($filetype1);
				//echo '<pre>'; var_dump($attchmentdetail);
				$imagepath=$this->file_edit_list($attchmentdetail[0]['DocumentAttachment']['file_name'],'DocumentAttachment',$attchmentdetail);
				//var_dump($imagepath);
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['DocumentAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['DocumentAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['DocumentAttachment']['file_name']);
				$this->set('attachmentstyle','style="display:block;"');
			}else{
			  	$this->set('heading','Add File');
			        $this->set('attachment_id',0);
			        $this->set('description','');
			        $this->set('button','Add');
			        $this->set('imagepath','');
			        $this->set('imagename','');
				$this->set('attachmentstyle','style="display:none;"');
				
			}
			

			$this->set('report_id',base64_decode($reoprt_id));
			$reportdetail = $this->DocumentMain->find('all', array('conditions' => array('DocumentMain.id' =>base64_decode($reoprt_id))));
			$this->set('report_number',$reportdetail[0]['DocumentMain']['report_no']);        
			
	       }
	       
       
	       
	       
	          function uploadimage($upparname=NULL,$deleteImageName=NULL){
		        $this->layout="ajax"; 
	              	$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Documents',$allowed_image);
			exit;
	           }
		   
	           function  dcattachmentprocess(){
		         $this->layout="ajax";
	
		         $attachmentArray=array();
					 
		         if($this->data['Documents']['id']!=0){
				$res='update';
				$attachmentArray['DocumentAttachment']['id']=$this->data['Documents']['id'];
			 }else{
				 $res='add';
			  }
				
		   	   $attachmentArray['DocumentAttachment']['description']=$this->data['attachment_description'];
			   $attachmentArray['DocumentAttachment']['file_name']=$this->data['hiddenFile']; 
		           $attachmentArray['DocumentAttachment']['report_id']=$this->data['report_id'];
			   if($this->DocumentAttachment->save($attachmentArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
                         
		   exit;
		
	        }
		   function attachment_block($id = null)
	     {
         
			if(!$id)
			{
		           $this->redirect(array('action'=>document_main_list), null, true);
			}
		       else
		        
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['DocumentAttachment']['id'] = $id;
					  $this->request->data['DocumentAttachment']['isblocked'] = 'Y';
					  $this->DocumentAttachment->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function attachment_unblock($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>document_main_list), null, true);
			}
		       else
		       {
			
				
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['DocumentAttachment']['id'] = $id;
					  $this->request->data['DocumentAttachment']['isblocked'] = 'N';
					  $this->DocumentAttachment->save($this->request->data,false);				
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
				
					  $this->request->data['DocumentAttachment']['id'] = $id;
					  $this->request->data['DocumentAttachment']['isdeleted'] = 'Y';
					  $this->DocumentAttachment->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			   
			       exit;			 
		     
	      }
	      
	       	function document_email_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->DocumentMain->find('all', array('conditions' => array('DocumentMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
	               
		    
	
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['DocumentMain']['report_no']);
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
	
	
		public function get_all_document_email_list($report_id)
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
			
			$condition.=" AND   LdjsEmail.type = 'document'";
			
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
				
			    $remC= $this->DocumentMain->find('all', array('conditions' => array('DocumentMain.id' =>$rec['LdjsEmail']['leid'])));
			    
			    $rrd=explode(" ",$remC[0]['DocumentMain']['revalidate_date']);
			    $rrdE=explode("-",$rrd[0]);
			    $adminA[$i]['LdjsEmail']['revalidate_date_val']=date("d/M/y", mktime(0, 0, 0, $rrdE[1], $rrdE[2], $rrdE[0]));
			    
			    $create_on=explode("-",$remC[0]['DocumentMain']['create_date']);
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
        function document_email_view($id,$leid){
		
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
                 $this->grid_access();
		 $this->layout="ajax";
		 $documentEmail = $this->LdjsEmail->find('all', array('conditions' =>array('LdjsEmail.id' =>$id,'LdjsEmail.leid' =>$leid,'LdjsEmail.type' =>'document'),'recursive'=>2));
                 $reportdetail = $this->DocumentMain->find('all', array('conditions' => array('DocumentMain.id' =>$documentEmail[0]['LdjsEmail']['leid'])));            
		 $vx=explode("-",$reportdetail[0]['DocumentMain']['validation_date']);
		 $v_date=date("d/M/y", mktime(0, 0, 0, $vx[1], $vx[2],$vx[0]));
		 $rvd=explode("-",$reportdetail[0]['DocumentMain']['revalidate_date']);
		 $rv_date=date("d/M/y", mktime(0, 0, 0, $rvd[1], $rvd[2],$rvd[0]));
		 $this->set('v_date', $v_date);
		 $this->set('rv_date', $rv_date);
		 $fullname=$documentEmail[0]['AdminMaster']['first_name'].' '.$documentEmail[0]['AdminMaster']['last_name'];
		 $this->set('fullname', $fullname);
		 $this->set('documentEmail', $documentEmail);
		 $this->set('reportdetail', $reportdetail);
		
	}
}	