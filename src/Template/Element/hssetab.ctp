<?php 
//$webroot = $this->request->getAttribute('webroot');
// --- Initialize session and safe variables ---
$session = $this->request->getSession();

$webroot         = $this->request->getAttribute('webroot');
$reportCreate    = $session->read('report_create') ?? null;
$adminData       = $session->read('adminData') ?? [];
$clientTab       = $session->read('clienttab') ?? null;
$clientFeedback  = $client_feedback ?? 0;
// dd($webroot,$reportCreate,$adminData);
// Extract IDs safely
$adminMasterId = $adminData['id'] ?? null;
$roleMasterId  = $adminData['role_master']['id'] ?? null;
// debug($reportCreate);
// debug($adminMasterId);
// debug($roleMasterId);
// Current report ID from URL
$reportId = $this->request->getParam('pass.0');
?>
<script src="<?= $webroot ?>js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="<?= $webroot ?>js/fancybox/jquery.fancybox-1.3.4.js"></script>
<link rel="stylesheet" href="<?= $webroot ?>js/fancybox/jquery.fancybox-1.3.4.css" media="screen"/>

<script  language="javascript" type="text/javascript">
  function open_lightbox(id)
  {
	  jQuery.fancybox({
			'autoScale': true,
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'href'	:"<?= $webroot ?>Reports/print_view/" + id,
			'hideOnOverlayClick' : false,
			'overlayShow'   :   false
		});
  }
  function redirect_report() {
    var report_list_id = document.getElementById('report_list').value;
    document.location = "<?= $webroot ?>Reports/add_report_main/" + report_list_id;
  }

  function change_dirction(tabval) 
  {
    const base = "<?= $webroot ?>Reports/";
    const id = "<?= h($reportId) ?>";
    switch (tabval) {
      case 'main':             document.location = base + "add_report_main/" + id; break;
      case 'clientdata':       document.location = base + "add_report_client/" + id; break;
      case 'personnel':        document.location = base + "report_hsse_perssonel_list/" + id; break;
      case 'incident':         document.location = base + "report_hsse_incident_list/" + id; break;
      case 'investigation':    document.location = base + "add_hsse_investigation/" + id; break;
      case 'remidialaction':   document.location = base + "report_hsse_remidial_list/" + id; break;
      case 'remidialemail':    document.location = base + "hsse_remedila_email_list/" + id; break;
      case 'dataanalysis':     document.location = base + "report_hsse_investigation_data_list/" + id; break;
      case 'attachment':       document.location = base + "report_hsse_attachment_list/" + id; break;
      case 'link':             document.location = base + "report_hsse_link_list/" + id + "/<?= base64_encode('all') ?>"; break;
      case 'clientfeedback':   document.location = base + "add_hsse_feedback/" + id; break;
      case 'view':             document.location = base + "add_report_view/" + id; break;
    }
  }
</script>
<div class="tabspanel">
  <ul>
    <?php if (($reportCreate == $adminData['id']) || ($adminData['role_master']['id'] == 1)): ?>
      <li><a href="javascript:void(0);" id="main" class="selectedtab" onclick="change_dirction('main');">Main</a></li>

      <?php if ($clientTab != 10): ?>
        <li><a href="javascript:void(0);" id="clientdata" onclick="change_dirction('clientdata');">Client Data</a></li>
      <?php endif; ?>

      <li><a href="javascript:void(0);" id="personnel" onclick="change_dirction('personnel');">Personnel</a></li>
      <li><a href="javascript:void(0);" id="incident" onclick="change_dirction('incident');">Incident</a></li>
      <li><a href="javascript:void(0);" id="investigation" onclick="change_dirction('investigation');">Investigation</a></li>
      <li><a href="javascript:void(0);" id="remidialaction" onclick="change_dirction('remidialaction');">Remedial Action</a></li>

      <?php if ($roleMasterId == 1): ?>
        <li><a href="javascript:void(0);" id="remidialemail" onclick="change_dirction('remidialemail');">Remedial Action Email</a></li>
      <?php endif; ?>

      <li><a href="javascript:void(0);" id="investigationdata" onclick="change_dirction('dataanalysis');">Incident Investigation</a></li>
      <li><a href="javascript:void(0);" id="attachment" onclick="change_dirction('attachment');">Attachment</a></li>
      <li><a href="javascript:void(0);" id="link" onclick="change_dirction('link');">Link</a></li>

      <?php if ($clientFeedback == 1): ?>
        <li><a href="javascript:void(0);" id="clientfeedback" onclick="change_dirction('clientfeedback');">Client Feedback</a></li>
      <?php endif; ?>
    <?php endif; ?>

    <li><a href="javascript:void(0);" id="view" onclick="change_dirction('view');">View</a></li>
    <li><a href="javascript:void(0);" id="print" onclick="open_lightbox('<?= h($reportId) ?>');">Print Preview</a></li>

    <div class="clear"></div>
  </ul>
</div>