<?php $webroot = $this->request->getAttribute('webroot');?>
<div class="wrapall">

<aside>
	<?php echo $this->Element('left_menu'); ?>
</aside>
 
<section>
 	<?php
        echo $this->Element('hssetab');
	?>
       
    <?php
    	echo $this->Form->create('add_report_incident_form', array('controller' => 'Reports','name'=>"add_report_incident_form", 'id'=>"add_report_incident_form", 'method'=>'post','class'=>'adminform'));
    	echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$incident_id));
	?>

 	<h2><?php echo $heading; ?>&nbsp;&nbsp;(<?php echo $report_number; ?>)</h2>
 	<br/>
	<span><label><?PHP echo __("Incident No:");?></label><label><?php echo $incident_no; ?></label></span>
	<div class="clearflds"></div>
	<label><?PHP echo __("Time of Incident:");?></label>
	<select name="incident_time" id="incident_time">
     	<option value="0">Select One</option>
		<?php
			for ($i = 0; $i <= 23; $i++)
			{ 
				for ($j = 0; $j <= 59; $j+=15)
				{
					$time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
				?>
					<option value="<?php echo $time; ?>"<?php if($time_incident==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
		<?php 	} 
			} 
		?>
	</select>
	<div class="clearflds"></div>
	<label><?PHP echo __("Date of Incident:");?></label>
	<?php echo $this->Form->input('date_incident', [
		'type' => 'text',
		'id' => 'date_incident',
		'name' => 'date_incident',
		'value' => $date_incident,
		'readonly' => 'readonly',
		'label' => false,
		'div' => false
	]); ?>
	<span class="textcmpul" id="date_incident_error"></span>
	<div class="clearflds"></div>
	<label><?PHP echo __("Incident Severity:");?></label>
	<span id="clientreviewed_section">
		<span id="incident_severity_section">
			<select id="incident_severity" name="incident_severity">
				<option value="0">Select One</option>	   
				<?php for($i=0;$i<count($incidentSeverityDetail);$i++){?>
				<option value="<?php echo $incidentSeverityDetail[$i]['id']; ?>" <?php if($incident_severity==$incidentSeverityDetail[$i]['id']){echo "selected";}else{} ?>><?php echo $incidentSeverityDetail[$i]['type']; ?></option>
				<?php } ?>
			</select>
		</span>	
	</span>
	<div class="clearflds"></div>
	<label>
		<?PHP
		echo __("Loss:");?>
	</label>
	<span id="incident_loss_section">
		<select id="incident_loss" name="incident_loss">	
			<option value="0">Select One</option>
			<?php for($i=0;$i<count($incidentLossDetail);$i++){ ?>
				<option value="<?php echo $incidentLossDetail[$i]['id']; ?>" 
					<?php if($incident_loss==$incidentLossDetail[$i]['id']){echo "selected";} ?>>
					<?php echo $incidentLossDetail[$i]['type']; ?>
				</option>
			<?php } ?>
		</select>
	</span>
	<div class="clearflds"></div>
	
	<span id="incident_category_section">
		<?php if (!empty($incidentCategoryDetail)) { ?>
			<label><?php echo __("Category:"); ?></label>
			<select id="incident_category" name="incident_category">    
				<option value="0">Select One</option>
				<?php foreach ($incidentCategoryDetail as $category) { ?>
					<option value="<?= $category->id ?>" 
						<?= ($incident_category == $category->id) ? "selected" : "" ?>>
						<?= h($category->type) ?>
					</option>
				<?php } ?>
			</select>
		<?php } ?>
	</span>
    <div class="clearflds"></div>

	<span id="incident_sub_category_section">
		<?php if (!empty($incidentSubCategoryDetail)) { ?>
			<label><?= __("Sub Category:"); ?></label>
			<select id="incident_sub_category" name="incident_sub_category">
				<option value="0">Select One</option>
				<?php foreach ($incidentSubCategoryDetail as $subCategory) { ?>
					<option value="<?= $subCategory->id ?>" 
						<?= ($incident_sub_category == $subCategory->id) ? "selected" : "" ?>>
						<?= h($subCategory->type) ?>
					</option>
				<?php } ?>
			</select>
		<?php } ?>
	</span>

    <div class="clearflds"></div>

	<label><?php echo __("Incident Summary:"); ?></label>
	<?php echo $this->Form->input('incident_summary', [
		'type' => 'textarea',
		'id' => 'incident_summary',
		'name' => 'incident_summary',
		'value' => $incident_summary,
		'label' => false,
		'div' => false
	]); ?>
	<div class="clearflds"></div>
	<label style="font-size: 11px;">Only 100 characters allowed for summary</label>
	<div class="clearflds"></div>
	<label><?php echo __("Details:"); ?></label>
	<?php echo $this->Form->input('detail', [
		'type' => 'textarea',
		'id' => 'detail',
		'name' => 'detail',
		'value' => $detail,
		'label' => false,
		'div' => false
	]); ?>
	<div class="clearflds"></div>
	<?php echo $this->Form->end(); ?>
	<div class="buttonpanel">
		<span id="loader" class="textcmpul" style="float:left;font-size: 13px;"></span>
		<input type="button" name="save" id="save" class="buttonsave" value="<?php echo $button; ?>" />
	</div>
</section>

<script type="text/javascript">
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	$(document).ready(function () {
    // Highlight active tab
		$("#main, #clientdata, #personnel, #investigation, #investigationdata, #remidialaction, #attachment, #clientfeedback, #view")
			.removeClass("selectedtab");
		$("#incident").addClass("selectedtab");

		// ✅ Bind events (instead of inline HTML attributes)
		$("#incident_loss").on("change", function () {
			assign_id("incident_loss");
		});

		$("#incident_category").on("change", function () {
			assign_id("incident_category");
		});

		$("#incident_summary").on("keyup", function () {
			check_character();
		});

		$("#save").on("click", function () {
			add_incident_client();
		});
		// Initialize Date Picker
		$('#date_incident').on('click', function() {
			displayDatePicker('date_incident', this);
		});
	});
	window.isNumberKey = function (evt) {
		var charCode = evt.which ? evt.which : event.keyCode;
		return !(charCode > 31 && (charCode < 48 || charCode > 57));
	};
	window.assign_id = function (type) {
		var path = '<?php echo $webroot; ?>';
		var passurl = "";

		switch (type) {
			case "incident_loss":
				var incidentloss = $("#incident_loss").val();
				if (incidentloss != 0) {
					$("#incident_category_section").html('<span><label>&nbsp;</label><label><img src="' + path + 'img/ajax-loader.gif" /></label></span><div class="clearflds"></div>');
					passurl = "incident_loss&id=" + incidentloss;
				} else {
					$("#incident_category_section").html("");
					$("#incident_sub_category_section").html("");
					return false;
				}
				break;

			case "incident_category":
				var incidentcategory = $("#incident_category").val();
				if (incidentcategory != 0) {
					$("#incident_sub_category_section").html('<span><label>&nbsp;</label><label><img src="' + path + 'img/ajax-loader.gif" /></label></span><div class="clearflds"></div>');
					passurl = "incident_category&id=" + incidentcategory;
				} else {
					$("#incident_sub_category_section").html("");
					return false;
				}
				break;
		}

		$.ajax({
			type: "POST",
			url: path + "Reports/displaycontentforloss/",
			data: "type=" + passurl,
			headers: {
				'X-CSRF-Token': csrfToken
			},
			success: function (res) {
				var splitvalue = res.split("~");
				switch (splitvalue[0]) {
					case "incident_loss":
						$("#incident_category_section").html(splitvalue[1]);
						break;
					case "incident_category":
						$("#incident_sub_category_section").html(splitvalue[1]);
						break;
				}
			},
		});
		return false;
	};
	window.add_incident_client = function () {
		var incident_time = $.trim($("#incident_time").val());
		var incident_severity = $.trim($("#incident_severity").val());
		var date_incident = $.trim($("#date_incident").val());
		var incident_summary = $.trim($("#incident_summary").val());
		var incident_loss = $.trim($("#incident_loss").val());
		var incident_category = $("#incident_category").length ? $.trim($("#incident_category").val()) : 0;
		var incident_sub_category = $("#incident_sub_category").length ? $.trim($("#incident_sub_category").val()) : 0;
		var detail = $.trim($("#detail").val());
		var report_id = '<?php echo $report_id; ?>';
		var curdate = '<?php echo date('m-d-Y'); ?>';
		var dataStr = $("#add_report_incident_form").serialize();
		var rootpath = '<?php echo $webroot; ?>';

		if (date_incident !== '' && curdate < date_incident) {
			$("#date_incident_error").text("Incident date must be before or equal to current date");
			$("#date_incident").focus();
			return false;
		}

		if (
			incident_time === "0" && incident_severity === "0" && date_incident === "" &&
			incident_loss === "0" && incident_category === "0" && incident_sub_category === "0" &&
			detail === "" && incident_summary === ""
		) {
			$("#loader").text("Please select at least one value");
			return false;
		}

		$("#loader").html('<img src="' + rootpath + 'img/loader.gif" />');

		$.ajax({
			type: "POST",
			url: rootpath + "Reports/hsseincidentprocess/",
			data: "data=" + dataStr + "&report_id=" + report_id + "&incident_no=<?php echo $incident_no; ?>",
			headers: {
				'X-CSRF-Token': csrfToken
			},
			success: function (res) {
				if (res === "fail") {
					$("#loader").html('<font color="red">Please try again</font>');
				} else if (res === "add") {
					$("#loader").html('<font color="green">Incident Data Added Successfully</font>');
					document.location = rootpath + "Reports/report_hsse_incident_list/<?php echo base64_encode($report_id); ?>";
				} else if (res === "update") {
					$("#loader").html('<font color="green">Incident Data Updated Successfully</font>');
					document.location = rootpath + "Reports/report_hsse_incident_list/<?php echo base64_encode($report_id); ?>";
				}
			},
		});
		return false;
	};

	window.check_character = function () {
		var incident_summary = $("#incident_summary").val();
		if (incident_summary.length > 100) {
			$("#incident_summary").val(incident_summary.substring(0, 100));
		}
	};
</script>