<section>
    <?php
    // Show HSSE tab if editing existing report
    if ($id != 0) {
        echo $this->element('hssetab');
    }

    // Create form
    echo $this->Form->create(null, [
        'url' => ['controller' => 'Reports', 'action' => 'addReportMain'],
        'id' => 'add_report_main_form',
        'type' => 'post',
        'class' => 'adminform'
    ]);

    echo $this->Form->hidden('id', ['value' => $id]);
    ?>

    <h2><?= h($heading) ?> <span class="textcmpul">Field marked with * are compulsory</span></h2>
    <br/>

    <label><?= __("Event Date:") ?><span>*</span></label>
    <input type="text" id="event_date" name="event_date" readonly onclick="displayDatePicker('event_date', this);" value="<?= h($event_date) ?>" />
    <span class="textcmpul" id="event_date_error"></span>
    <div class="clearflds"></div>

    <label><?= __("Report No:") ?><span>*</span></label>
    <label><?= h($reportno) ?></label>
    <div class="clearflds"></div>

    <label><?= __("Days Since Event:") ?><span>*</span></label>
    <label id="since_event"><?= h($since_event_hidden) ?></label>
    <input type="hidden" id="since_event_hidden" name="since_event_hidden" value="<?= h($since_event_hidden) ?>" />
    <div class="clearflds"></div>

    <label><?= __("Incident Type:") ?></label>
    <span id="incident_section">
        <select id="incident_type" name="incident_type">
            <?php foreach ($incidentDetail as $incident): ?>
                <option value="<?= $incident->id ?>" <?= $incident_type == $incident->id ? 'selected' : '' ?>>
                    <?= h($incident->type) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </span>
    <div class="clearflds"></div>

    <label><?= __("Created By:") ?><span>*</span></label>
    <span style="font-size: 13px;color:#666666"><?= h($created_by) ?></span>
    <div class="clearflds"></div>

    <label><?= __("Business Unit:") ?></label>
    <select id="business_unit" name="business_unit">
        <?php foreach ($businessDetail as $business): ?>
            <option value="<?= $business->id ?>" <?= $business_unit == $business->id ? 'selected' : '' ?>>
                <?= h($business->type) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="clearflds"></div>

    <label><?= __("Client:") ?></label>
    <select id="client" name="client">
        <?php foreach ($clientDetail as $clientObj): ?>
            <option value="<?= $clientObj->id ?>" <?= $client == $clientObj->id ? 'selected' : '' ?>>
                <?= h($clientObj->name) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="clearflds"></div>

    <label><?= __("Field Location:") ?></label>
    <select id="field_location" name="field_location">
        <?php foreach ($fieldlocationDetail as $loc): ?>
            <option value="<?= $loc->id ?>" <?= $field_location == $loc->id ? 'selected' : '' ?>>
                <?= h($loc->type) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="clearflds"></div>

    <label><?= __("Country:") ?></label>
    <select id="country" name="country">
        <?php foreach ($countryDetail as $c): ?>
            <option value="<?= $c->id ?>" <?= $cnt == $c->id ? 'selected' : '' ?>>
                <?= h($c->name) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="clearflds"></div>

    <label><?= __("Reporter:") ?></label>
    <select id="reporter" name="reporter">
        <?php foreach ($userDetail as $user): ?>
            <option value="<?= $user->id ?>" <?= $reporter == $user->id ? 'selected' : '' ?>>
                <?= h($user->first_name . ' ' . $user->last_name) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="clearflds"></div>

    <h2>Classification</h2>
    <div class="clearflds"></div>

    <label><?= __("Incident Severity:") ?></label>
    <select id="incident_severity" name="incident_severity">
        <?php foreach ($incidentSeverityDetail as $sev): ?>
            <option value="<?= $sev->id ?>" <?= $incident_severity == $sev->id ? 'selected' : '' ?>>
                <?= h($sev->type) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="clearflds"></div>

    <label><?= __("Recordable:") ?></label>
    <select id="recorable" name="recorable">
        <option value="1" <?= $recordable == 1 ? 'selected' : '' ?>>Yes</option>
        <option value="2" <?= $recordable == 2 ? 'selected' : '' ?>>No</option>
    </select>
    <div class="clearflds"></div>

    <label><?= __("Potential:") ?></label>
    <select id="potential" name="potential">
        <?php foreach ($potentialDetail as $pot): ?>
            <option value="<?= $pot->id ?>" <?= $potential == $pot->id ? 'selected' : '' ?>>
                <?= h($pot->type) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="clearflds"></div>

    <label><?= __("Residual:") ?></label>
    <select id="residual" name="residual">
        <?php foreach ($residualDetail as $res): ?>
            <option value="<?= $res->id ?>" <?= $residual == $res->id ? 'selected' : '' ?>>
                <?= h($res->type) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="clearflds"></div>

    <label><?= __("Summary:") ?></label>
    <?= $this->Form->textarea('summary', [
        'id' => 'summary',
        'value' => $summary,
        'onkeyup' => 'check_character();',
        'label' => false,
        'div' => false
    ]) ?>
    <div class="clearflds"></div>
    <label>&nbsp;</label>
    <label style="font-size: 11px;">Only 100 characters allowed for summary</label>
    <div class="clearflds"></div>

    <label><?= __("Details:") ?></label>
    <?= $this->Form->textarea('details', [
        'id' => 'details',
        'value' => $details,
        'label' => false,
        'div' => false
    ]) ?>
    <div class="clearflds"></div>

    <?= $this->Form->end() ?>

    <div class="buttonpanel">
        <span id="loader" style="float:left;font-size: 13px;"></span>
        <?php if ($button == 'Update' && $closer_date == '00-00-0000'): ?>
            <input type="button" class="buttonsave" onclick="add_report_main();" value="<?= h($button) ?>" />
        <?php elseif ($button == 'Submit'): ?>
            <input type="button" class="buttonsave" onclick="add_report_main();" value="<?= h($button) ?>" />
        <?php endif; ?>
    </div>
</section>

<aside>
    <?= $this->element('left_menu') ?>
</aside>

<?= $this->Html->css('calender') ?>

<script type="text/javascript">
var rootpath = '<?= $this->Url->build('/', ['fullBase' => false]) ?>';

function add_report_main() {
    var dataStr = $("#add_report_main_form").serialize();
    document.getElementById('loader').innerHTML = '<img src="<?= $this->Url->build('/img/loader.gif') ?>" />';
    
    $.ajax({
        type: "POST",
        url: rootpath + "Reports/reportprocess/",
        data: dataStr,
        success: function(res) {
            var resval = res.split("~");
            if (resval[0] == 'fail') {
                document.getElementById('loader').innerHTML = '<font color="red">Please try again</font>';
            } else if (resval[0] == 'add') {
                document.getElementById('loader').innerHTML = '<font color="green">Main Added Successfully</font>';
                document.location = rootpath + 'Reports/add_report_main/' + resval[1];
            } else if (resval[0] == 'update') {
                document.getElementById('loader').innerHTML = '<font color="green">Main Updated Successfully</font>';
                document.location = rootpath + 'Reports/add_report_main/' + resval[1];
            }
        }
    });
    return false;
}

function check_character() {
    var summary = document.getElementById('summary').value;
    if (summary.length > 100) {
        document.getElementById('summary').value = summary.substring(0, 100);
    }
}
</script>
