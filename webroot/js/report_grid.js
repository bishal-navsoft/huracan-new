Ext.onReady(function(){

function onFilterItemCheck(item, checked){
        if(checked) {
            Ext.get('filterlabel').update('['+item.text+']');    
        }
    }
 
 
function checkboxAction(){
}

//Function for unblock selected records
function delete_hsse_report_main(id){
	if(confirm("Do You Want To Delete?"))
	  {
		$.ajax({
				type: "POST",
				url: path+"Reports/main_delete/",
				data:"id="+id,
				success: function(res)
				{	      
				   if(res=='ok'){
					 document.location=path+'Reports/report_hsse_list';
				       }
				   }
		      });
	  }else{
		return  false;
	  }
}

function unblockSelected()
{
	var selectedArray = new Array();
    selectedArray = checkBox.getSelections();
	if(selectedArray.length == 0)
	{
		return false;
	}	

	if(is_block == 1)
	{
		Ext.Msg.show({
				title:activ_select_record
			       ,msg:activate_select_record + '</b><br/>.'
			       ,icon:Ext.Msg.QUESTION
			       ,buttons:Ext.Msg.YESNO
			       ,scope:this
			       ,fn:function(response) {
				       	if('yes' !== response) {
					       	return;
				       	}
				       	else
				       	{
							var box = Ext.MessageBox.wait(please_wait, performing_actions);
							var selectedIds = "";
							for(var i=0; i<selectedArray.length; i++)
					       	{
						       if(i==0)
						       {
							       selectedIds = selectedArray[i]["data"]["id"];
						       }else
						       {
							       selectedIds = selectedIds+"^"+selectedArray[i]["data"]["id"];
						       }						
						    }
					       	Ext.Ajax.request(
							{
								url: path+'Reports/report_unblock/'+selectedIds+'/'
								,method:'GET'
								,success: function(response){
										ds.reload({
											params: ds.lastOptions.params
										});
										box.hide();
									}
									,failure: function(response){
										box.hide();
										Ext.Msg.alert(err, err_unblock);
										
										//ds.load();
									}
									,scope: this
							})
						}
						//console.info('Deleting record');
			       	}
				});
	
	}
	else
	{
		Ext.Msg.alert(warning,not_allowed_access);
	}	
	
}	

function blockSelected()
{
	var selectedArray = new Array();
    selectedArray = checkBox.getSelections();
	if(selectedArray.length == 0)
	{
		alert(select_one_record);
		return false;
	}
	if(is_block == 1)
	{
		Ext.Msg.show({
					title:deactiv_select_record
				       ,msg:deactivate_select_record + '</b><br/>.'
				       ,icon:Ext.Msg.QUESTION
				       ,buttons:Ext.Msg.YESNO
				       ,scope:this
				       ,fn:function(response) {
					       if('yes' !== response) {
						       return;
					       }
					       else
					       {
						       	var box = Ext.MessageBox.wait(please_wait, performing_actions);										
							   	var selectedIds = "";
						       	for(var i=0; i<selectedArray.length; i++)
						       	{
							       	if(i==0)
							       	{
								       selectedIds = selectedArray[i]["data"]["id"];
							       	}else
							       	{
								       selectedIds = selectedIds+"^"+selectedArray[i]["data"]["id"];
							       	}						
							    }
						       	Ext.Ajax.request(
								{
									url: path+'Reports/report_block/'+selectedIds+'/'
									,method:'GET'
									,success: function(response){
										ds.reload({
											params: ds.lastOptions.params
										});
										box.hide();
										}
										,failure: function(response){
											box.hide();
											Ext.Msg.alert(err, err_block);
											
											//ds.load();
										}
										,scope: this
											
								})
							}
	       					//console.info('Deleting record');
				       	}
					});
	}
	else
	{
		Ext.Msg.alert(warning,not_allowed_access);
	}			
}

	function deleteSelected()
	{

		var selectedArray = new Array();
		selectedArray = checkBox.getSelections();
		if(selectedArray.length == 0)
		{
			
			return false;
		}
		if(is_delete == 1)
		{
			Ext.Msg.show({
						title:del_select_record
						,msg:delete_select_record + '</b><br/>' + no_undo
						,icon:Ext.Msg.QUESTION
						,buttons:Ext.Msg.YESNO
						,scope:this
						,fn:function(response) {
							if('yes' !== response) {
								return;
							}
							else
							{
								var box = Ext.MessageBox.wait(please_wait, performing_actions);
								var selectedIds = "";
								for(var i=0; i<selectedArray.length; i++)
								{
									if(i==0)
									{
										selectedIds = selectedArray[i]["data"]["id"];
										
									}else
									{
										selectedIds = selectedIds+"^"+selectedArray[i]["data"]["id"];
									}						
									
									

								}
							
										delete_hsse_report_main(selectedIds);

								
								
												
								
							}
			//              console.info('Deleting record');
						}
		});
						
		}
		else
		{
			Ext.Msg.alert(warning,not_allowed_access);
		}
		
	}

	var ds = new Ext.data.Store({	
        proxy: new Ext.data.HttpProxy({
		url: AdminListPage+'reports/get_all_report/'+action+'/',
		headers: {
        'X-CSRF-Token': csrfToken}, method: 'GET'}),
		//note that I used host in the url
		reader: new Ext.data.JsonReader({
        root: 'admins',
		totalProperty: 'total',
        remoteSort: true,
		fields: [
			{name: 'id'},
			{name: 'report_no'},
			{name: 'event_date'},
			{name: 'closer_date'},
        	{name: 'event_date'},
			{name: 'event_date_val'},
			{name: 'client'},
			{name: 'incident_severity'},
			{name: 'incident_severity_name'},
			{name: 'client_name'},
			{name: 'creater_name'},
			{name: 'remidial'},
			{name: 'summary'},
			{name: 'isblocked'},
			{name: 'edit_permit',type: 'boolean'},
			{name: 'view_permit',type: 'boolean'},
			{name: 'delete_permit',type: 'boolean'},
			{name: 'block_permit',type: 'boolean'},
			{name: 'unblock_permit',type: 'boolean'},
			{name: 'blockHideIndex', type: 'boolean'},
			{name: 'unblockHideIndex', type: 'boolean'},
			{name: 'isdeletdHideIndex', type: 'boolean'}, 
			{name: 'checkbox_permit', type: 'boolean'},
		]
	})
    });  
	
	var pagingBar = new Ext.PagingToolbar({
        pageSize: eval(pagelmt),
        store: ds,
        displayInfo: true,
        displayMsg: display_topics, 
        emptyMsg: no_display_records
        
    });

	var strListVal =listVal.split(',');
	var checkBox = new Ext.grid.CheckboxSelectionModel({
		
	renderer: function(v, p, record, rowIndex){
		var j = strListVal[rowIndex];
		if(j==1)
		{
			return '<div class="x-grid3-row-checker">&#160;</div>';
		}

		},
		selectAll: function(){
			var rowIndex=0;
		       while(typeof(this.grid.getStore().getAt(rowIndex))!='undefined') {
			var record = this.grid.getStore().getAt(rowIndex);
			 var j = strListVal[rowIndex];
			 if(j==1){
	 			this.grid.getSelectionModel().deselectRow(rowIndex, true);
				this.grid.getSelectionModel().selectRow(rowIndex, true);
			 }
			    
	 		rowIndex++;		
		}
		}
	});
 
	//var checkBox='';
		var Actions = new Ext.ux.grid.RowActions({
		header: acts,
		dataIndex: 0,
		actions: [
			{
				qtip: edt,
				iconCls: 'edit',
				hide: 'edit_permit',
				callback: function(grid, record, action, rowIndex) {
					//console.log('edit_permit', record.get('edit_permit'));
					//console.log(record.get('id'));
					if (record.get('edit_permit') && is_edit == 1) {
						var redirectPath = path + "Reports/add_report_main/" + Base64.encode(String(record.get('id'))) + "/";
						window.open(redirectPath);
					} else {
						Ext.Msg.alert(warning, not_allowed_access);
					}
				}
			},
			{
				qtip: activ,
				iconCls: 'unblock',
				hide: 'blockHideIndex',
				callback: function(grid, record, action, rowIndex) {
					console.log('blockHideIndex', record.get('blockHideIndex'));
					if (record.get('blockHideIndex') && record.get('isblocked') === 'Y') {
						if (is_block == 1) {
							Ext.Msg.show({
								title: "Activate record",
								msg: activate_mobile_site + '<br/>',
								icon: Ext.Msg.QUESTION,
								buttons: Ext.Msg.YESNO,
								scope: this,
								fn: function(response) {
									if (response !== 'yes') return;
									var box = Ext.MessageBox.wait(please_wait, performing_actions);
									Ext.Ajax.request({
										url: path + 'Reports/report_unblock/' + record.get('id') + '/',
										method: 'GET',
										success: function(response) {
											ds.reload({
												params: ds.lastOptions.params
											});
											box.hide();
										},
										failure: function(response) {
											Ext.Msg.alert(err, err_unblock);
										},
										scope: this
									});
								}
							});
						} else {
							Ext.Msg.alert(warning, not_allowed_access);
						}
					} else {
						Ext.Msg.alert(message, already_delivered);
					}
				}
			},
			{
				qtip: deactiv,
				iconCls: 'block',
				hide: 'unblockHideIndex',
				callback: function(grid, record, action, rowIndex) {
					console.log('unblockHideIndex', record.get('unblockHideIndex'));
					if (record.get('unblockHideIndex') && record.get('isblocked') === 'N') {
						if (is_block == 1) {
							Ext.Msg.show({
								title: "Deactivate record",
								msg: deactivate_mobile_site + '<br/>',
								icon: Ext.Msg.QUESTION,
								buttons: Ext.Msg.YESNO,
								scope: this,
								fn: function(response) {
									if (response !== 'yes') return;
									var box = Ext.MessageBox.wait(please_wait, performing_actions);
									Ext.Ajax.request({
										url: path + 'Reports/report_block/' + record.get('id') + '/',
										method: 'GET',
										success: function(response) {
											ds.reload({
												params: ds.lastOptions.params
											});
											box.hide();
										},
										failure: function(response) {
											Ext.Msg.alert(err, err_unblock);
										},
										scope: this
									});
								}
							});
						} else {
							Ext.Msg.alert(warning, not_allowed_access);
						}
					} else {
						Ext.Msg.alert(message, already_not_delivered);
					}
				}
			},
			{
				qtip: dlt,
				iconCls: 'remove',
				hide: 'delete_permit',
				callback: function(grid, record, action, rowIndex) {
					console.log('delete_permit', record.get('delete_permit'));
					if (record.get('delete_permit') && is_delete == 1) {
						delete_hsse_report_main(record.get('id'));
					} else {
						Ext.Msg.alert(warning, not_allowed_access);
					}
				}
			},
			{
				qtip: view,
				iconCls: 'view',
				hide: 'view_permit',
				callback: function(grid, record, action, rowIndex) {
					console.log('view_permit', record.get('view_permit'));
					if (record.get('view_permit') && is_view == 1) {
						document.location = path + 'Reports/add_report_view/' + Base64.encode(record.get('id'));
					} else {
						Ext.Msg.alert(warning, not_allowed_access);
					}
				}
			}
		]
	});
	
	function status(val)
	{
		if(val == "N"){ return act;}		
		else{ return inact;}
	}
	
	
    //This is the column model.  This defines the columns in my datagrid.
    //It also maps each column with the appropriate json data from my database (dataIndex).
    var cm = new Ext.grid.ColumnModel([

	 checkBox,
	 Actions,
        /*{header: "ID", dataIndex: 'id', width: 100, hidden: true},*/
	
        {header: reportno,sortable: true,  dataIndex: 'report_no', width:150},
        {header: event,sortable: true, dataIndex: 'event_date_val', width:100},
		{header: summary,sortable: true, dataIndex: 'summary', width:170},
		{header: client,sortable: true, dataIndex: 'client_name', width:160},
		{header: incident_severity_name,sortable: true, dataIndex: 'incident_severity_name', width:120},
		{header: createdby,sortable: true, dataIndex: 'creater_name', width:150},
        {header: closure,sortable: true, dataIndex: 'closer_date', width:90},
        {header: remedial,sortable: true, dataIndex: 'remidial', width:100},
		{header: stts, sortable: true, renderer: status, dataIndex: 'isblocked', width: 80}

    ]);
	
	
	 Ext.QuickTips.init();
	 var toolBar = new Ext.Toolbar({
        items: [{text:activate_selected,
            tooltip:activ_select_record,
            iconCls:'unblock',
			enableToggle: true,
			toggleHandler: unblockSelected		
		},'-',{
            text:deactivate_selected,
            tooltip:deactiv_select_record,
            iconCls:'block',
			enableToggle: true,
			toggleHandler: blockSelected				
                 
        },'-',{
            text:delete_selected,
            tooltip:delete_select_record,
            iconCls:'remove',
			enableToggle: true,
			toggleHandler: deleteSelected				
                 
        }]
    });
    
	
    //Here's where we define our datagrid.  
    //We have to specify our dataStore and our columnModel.
    var grid = new Ext.grid.GridPanel({
		ds: ds,
		cm: cm,
		sm: checkBox,
		
        buttonAlign:'center',
		trackMouseOver:true,
		stripeRows: true,  
        disableSelection:true,
        loadMask: true,
        // inline toolbars
        tbar: toolBar,
		plugins: [Actions],
        //frame:true,
        iconCls:'icon-grid',
		stripeRows: true,
		height: 'auto',
		width: 'auto',
		//style: 'color:#0093a8',
		title: admins,
		bbar: pagingBar
		,cls: 'test'
		});
		grid.render('grid-paging'); 
		
	  var filterMenuItems = [
	    new Ext.menu.CheckItem({ 
            text: reportno, 
            checked: true, 
            group: 'filter',
            id: 'report_no'
            ,checkHandler: onFilterItemCheck 
        }),
	  new Ext.menu.CheckItem({ 
            text: client, 
            checked: true, 
            group: 'filter',
            id: 'client_name'
            ,checkHandler: onFilterItemCheck 
        }),
	  new Ext.menu.CheckItem({ 
            text: createdby, 
            checked: true, 
            group: 'filter',
            id: 'creater_name'
            ,checkHandler: onFilterItemCheck 
        }),
	new Ext.menu.CheckItem({ 
            text: event, 
            checked: true, 
            group: 'filter',
            id: 'event_date_val'
            ,checkHandler: onFilterItemCheck 
        })  
    ];
    var filterMenu = new Ext.menu.Menu({
	    id: 'filterMenu',
	    items: filterMenuItems
    });
	
	toolBar.addSeparator();
    
    toolBar.add({
	    text: search_by,
	    tooltip: set_column_search,
	    //icon: 'find.png',
	    cls: 'x-btn-text-icon btn-search-icon',
	    menu: filterMenu
    });

    var filterlabel = toolBar.addDom({
	    tag: 'div',
	    id: 'filterlabel',
        style:'color:#1099AC;padding:0 4px;width:80px;text-align:center;font-size: 14px;'
    });
    Ext.get('filterlabel').update(name_tag); 
     
    var filter = toolBar.addDom({
	    tag: 'input',
	    id: 'sfilter',
	    type: 'text',
	    size: 30,
	    value: '',
	    style: 'background: #ffffff;'
    });
	
	 var combo = new Ext.ux.form.HistoryClearableComboBox({
        emptyText:search_text,
        selectOnFocus:true,
        resizable:true,
        hideClearButton: false,
	    hideTrigger: false,
	    typeAhead: true,
	    triggerAction: 'all',
		applyTo:'sfilter'
    });
	

	var button = toolBar.addButton({
		text: '',
		disabled:false,
		iconCls:'search',
		tooltip:go,
		handler : function(){                         
		var filterCol = filterMenuItems.filter(function(element, index, array) {
			return element.checked;
		})[0].id;
						
		if(combo.getValue() == "")
		{
			alert(provide_search_string);
			return false;
		}
						
		ds.load({params:{
			start: 0, 
			limit: pagelmt, 
			filter: filterCol, 
			value: combo.getValue()
		}});
						 
    	}
    }); 

    //toolBar.addSeparator();

	ds.load({params:{start: 0, limit: pagelmt}});

	ds.on('beforeload', function(store, options) {
		console.log('Loading with params:', options.params);
	});

	ds.on('load', function(store, records, options) {
		console.log('Loaded records:', records.length, 'Total:', store.getTotalCount());
	});
	
    //ds.loadData(<?php echo '{"total":'.$total.', "products":'.$javascript->Object($products).'}'; ?>); //This loads data from the database into the datastore.
 
	//grid.render('grid-paging');  //This renders our grid to the grid-paging div in our index.ctp view.
});