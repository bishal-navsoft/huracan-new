<?php
/**
 * report-hsse-list.ctp
 */
?>

<script type="text/javascript">
    // Define a single global object to hold all variables
    
</script>

<aside>
   <?= $this->element('left_menu') ?>
</aside>
<section>
    <div class="topadmin_heading clearfix">
        <h2>HSSE Report (Main) List</h2>
        <?php if ($is_add == 1): ?>
            <input type="button" name="button" value="Add Main" onclick="pageRedrection();" class="buttonadmin fright" />
        <?php endif; ?>
    </div>

    <div id="grid-paging">
        <?= $this->Html->script('report_grid') ?>
    </div>
</section>

<script>
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    var action = <?= json_encode($action) ?>;
    var path = <?= json_encode($this->Url->build('/', ['fullBase' => true])) ?>;
    var AdminListPage = <?= json_encode($this->Url->build('/', ['fullBase' => true])) ?>;
    var is_add = <?= json_encode($is_add) ?>;
    var is_edit = <?= json_encode($is_edit) ?>;
    var is_view = <?= json_encode($is_view) ?>;
    var is_block = <?= json_encode($is_block) ?>;
    var is_delete = <?= json_encode($is_delete) ?>;
    var pagelmt = <?= json_encode(($limit === 'all') ? '1000' : $limit) ?>;

    var activate_selected = <?= json_encode(__('Activate ')) ?>;
    var deactivate_selected = <?= json_encode(__('Deactivate ')) ?>;
    var delete_selected = <?= json_encode(__('Delete')) ?>;
    var search_by = <?= json_encode(__('Search by')) ?>;
    var search_text = <?= json_encode(__('search text...')) ?>;
    var go = <?= json_encode(__('Go')) ?>;
    var stts = <?= json_encode(__('Status')) ?>;
    var acts = <?= json_encode(__('Actions')) ?>;
    var edt = <?= json_encode(__('Edit')) ?>;
    var activ = <?= json_encode(__('Activate')) ?>;
    var deactiv = <?= json_encode(__('Deactivate')) ?>;
    var view = <?= json_encode(__('View')) ?>;
    var dataentry = <?= json_encode(__('Data Entry')) ?>;
    var rejection = <?= json_encode(__('Rejection')) ?>;
    var dlt = <?= json_encode(__('Delete')) ?>;
    var activ_select_record = <?= json_encode(__('Activate selected record(s)')) ?>;
    var deactiv_select_record = <?= json_encode(__('Deactivate selected record(s)')) ?>;
    var select_one_record = <?= json_encode(__('Please select at least one record.')) ?>;
    var activate_select_record = <?= json_encode(__('Do you really want to activate selected record(s)?')) ?>;
    var deactivate_select_record = <?= json_encode(__('Do you really want to deactivate selected record(s)?')) ?>;
    var delete_select_record = <?= json_encode(__('Do you really want to delete selected record(s)?')) ?>;
    var please_wait = <?= json_encode(__('Please wait ....')) ?>;
    var performing_actions = <?= json_encode(__('Performing Actions')) ?>;
    var warning = <?= json_encode(__('Warning')) ?>;
    var not_allowed_access = <?= json_encode(__('Sorry! You are not allowed to access this link.')) ?>;
    var err = <?= json_encode(__('ERROR')) ?>;
    var err_unblock = <?= json_encode(__('Error during unblock ')) ?>;
    var err_block = <?= json_encode(__('Error during block ')) ?>;
    var err_delete = <?= json_encode(__('Error during delete ')) ?>;
    var del_select_record = <?= json_encode(__('Delete selected record(s)')) ?>;
    var no_undo = <?= json_encode(__('There is no undo.')) ?>;
    var display_topics = <?= json_encode(__('Displaying topics {0} - {1} of {2}')) ?>;
    var no_display_records = <?= json_encode(__('No records to display')) ?>;
    var message = <?= json_encode(__('Message')) ?>;
    var already_delivered = <?= json_encode(__('Already Activated.')) ?>;
    var already_not_delivered = <?= json_encode(__('Already Deactivated.')) ?>;
    var saving_scores = <?= json_encode(__('Saving scores in this page now...')) ?>;
    var set_column_search = <?= json_encode(__('set column for search.')) ?>;
    var provide_search_string = <?= json_encode(__('Please provide search string.')) ?>;
    var del_record = <?= json_encode(__('Delete record')) ?>;
    var delete_mobile_site = <?= json_encode(__('Do you really want to delete this report?')) ?>;
    var deactivate_mobile_site = <?= json_encode(__('Do you really want to deactivate this report?')) ?>;
    var activate_mobile_site = <?= json_encode(__('Do you really want to activate this report?')) ?>;
    var admins = <?= json_encode(__('Report Management')) ?>;
    var name = <?= json_encode(__('Email')) ?>;
    var name_tag = <?= json_encode(__('[Report No]')) ?>;
    var created = <?= json_encode(__('Created Date')) ?>;
    var reportno = <?= json_encode(__('Report')) ?>;
    var event = <?= json_encode(__('Event Date')) ?>;
    var closure = <?= json_encode(__('Closure Date')) ?>;
    var createdby = <?= json_encode(__('Created By')) ?>;
    var summary = <?= json_encode(__('summary')) ?>;
    var client = <?= json_encode(__('Client')) ?>;
    var businessunit = <?= json_encode(__('Business Unit')) ?>;
    var incident_severity_name = <?= json_encode(__('Incident Severity')) ?>;
    var remedial = <?= json_encode(__('Remedial C/O')) ?>;
    var act = <?= json_encode(__('Active')) ?>;
    var inact = <?= json_encode(__('Inactive')) ?>;
    var login_history = <?= json_encode(__('Login History')) ?>;

    var listVal = <?= json_encode(is_array($_SESSION['idBollen']) ? implode(',', $_SESSION['idBollen']) : $_SESSION['idBollen']) ?>;
  
    function pageRedrection() { 
        document.location = path + "Reports/add_report_main/"; 
    }
</script>
<?= $this->fetch('script') ?>
<style>
.ux-row-action-item {
    display: inline-block !important;
    margin: 0 2px;
}
</style>