<?php
if (!defined('OSTSCPINC') || !$thisstaff
        || !$thisstaff->hasPerm(Ticket::PERM_CREATE, false))
        die('Access Denied');

$info=array();
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);

if (!$info['topicId'])
    $info['topicId'] = $cfg->getDefaultTopicId();

$forms = array();
if ($info['topicId'] && ($topic=Topic::lookup($info['topicId']))) {
    foreach ($topic->getForms() as $F) {
        if (!$F->hasAnyVisibleFields())
            continue;
        if ($_POST) {
            $F = $F->instanciate();
            $F->isValidForClient();
        }
        $forms[] = $F;
    }
}

if ($_POST)
    $info['duedate'] = Format::date(strtotime($info['duedate']), false, false, 'UTC');

?>
<form action="tickets.php?a=open" method="post" id="save"  enctype="multipart/form-data" class="ticket_open_content">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="create">
 <input type="hidden" name="a" value="open">

    <div class="ticket_open_title">
        <h2><?php echo __('Open a New Suggestion');?></h2>
    </div>

 <table class="ticket_open" width="100%" border="0" cellspacing="0" cellpadding="2">
    <thead>
    <!-- This looks empty - but beware, with fixed table layout, the user
         agent will usually only consult the cells in the first row to
         construct the column widths of the entire toable. Therefore, the
         first row needs to have two cells -->
        <tr><td style="padding:0;"></td><td style="padding:0;"></td></tr>
    </thead>
    <tbody class="open_ticket_userinformation">
        <tr id="open_ticket_userinformation">
            <th colspan="2" style="min-width:120px;" width="160">
                <em><strong><?php echo __('User Information'); ?></strong>: </em>
                <div class="error"><?php echo $errors['user']; ?></div>
            </th>
        </tr>
        <tr id="open_ticket_userdata"><td><strong><?php echo __('Submitter'); ?>:</strong>
            
            <td>
                <select id="uid" name="uid">
                    
                    <?php
                    $associate = $thisstaff->GetId();
                    
                    if(($users=Staff::getAvailableStaffMembers())) {
                        
                        foreach ($users as $k => $v)
                        echo sprintf('<option value="%s" %s>%s</option>',
                                $k,
                                ($associate == $k ) ? 'selected="selected"' : '',
                                $v);
                    
                        }
                     ?>
                </select>&nbsp;<span class='error'>&nbsp;<?php echo $errors['submitter']; ?></span>
            </td>
        </tr>

        <?php
        if($cfg->notifyONNewStaffTicket()) {  ?>
        <tr  id="open_ticket_userdata">
            <td width="160"><strong><?php echo __('Ticket Notice'); ?>:</strong></td>
            <td>
            <input type="checkbox" name="alertuser" <?php echo (!$errors || $info['alertuser'])? '': ''; ?>><?php
                echo __('Send alert to user.'); ?>
            </td>
        </tr>
        <?php
        } ?>
    </tbody>
    <tbody class="open_ticket_informationdata">
        <tr id="open_ticket_informationoptions">
            <th colspan="2">
                <em><strong><?php echo __('Ticket Information');?></strong>:</em>
            </th>
        </tr>
        <tr id="open_ticket_informationdata" style="display: none;">
            <td width="160" class="required" >
                <?php echo __('Ticket Source');?>:
            </td>
            <td>
                <select name="source" class="requiredfield">
                    <?php
                    $source = $info['source'] ?: 'Phone';
                    $sources = Ticket::getSources();
                    unset($sources['Web'], $sources['API']);
                    foreach ($sources as $k => $v)
                        echo sprintf('<option value="%s" %s>%s</option>',
                                $k,
                                ($source == $k ) ? 'selected="selected"' : '',
                                $v);
                    ?>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font>
            </td>
        </tr>
        <tr id="open_ticket_informationdata">
            <td width="160" class="required">
                <?php echo __('Help Topic'); ?>:
            </td>
            <td>
                <select class="requiredfield" name="topicId" onchange="javascript:
                        var data = $(':input[name]', '#dynamic-form').serialize();
                        $.ajax(
                          'ajax.php/form/help-topic/' + this.value,
                          {
                            data: data,
                            dataType: 'json',
                            success: function(json) {
                              $('#dynamic-form').empty().append(json.html);
                              $(document.head).append(json.media);
                            }
                          });">
                    <?php
                    if ($topics=Topic::getHelpTopics(false, false, true)) {
                        if (count($topics) == 1)
                            $selected = 'selected="selected"';
                        else { ?>
                        <option value="" selected >&mdash; <?php echo __('Select Help Topic'); ?> &mdash;</option>
<?php                   }
                        foreach($topics as $id =>$name) {
                            echo sprintf('<option value="%d" %s %s>%s</option>',
                                $id, ($info['topicId']==$id)?'selected="selected"':'',
                                $selected, $name);
                        }
                        if (count($topics) == 1 && !$forms) {
                            if (($T = Topic::lookup($id)))
                                $forms =  $T->getForms();
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['topicId']; ?></font>
            </td>
        </tr>

         <tr  id="open_ticket_informationdata"  style="display:none;">
            <td width="160">
                <?php echo __('SLA Plan');?>:
            </td>
            <td>
                <select name="slaId">
                    <option value="0" selected="selected" >&mdash; <?php echo __('System Default');?> &mdash;</option>
                    <?php
                    if($slas=SLA::getSLAs()) {
                        foreach($slas as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['slaId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['slaId']; ?></font>
            </td>
         </tr>

         <tr id="open_ticket_informationdata">
            <td width="160">
                <strong><?php echo __('Due Date');?>:</strong>
            </td>
            <td>
                <input class="dp" id="duedate" name="duedate" value="<?php echo Format::htmlchars($info['duedate']); ?>" size="12" autocomplete=OFF>
                &nbsp;&nbsp;
                <?php
                $min=$hr=null;
                if($info['time'])
                    list($hr, $min)=explode(':', $info['time']);

                echo Misc::timeDropdown($hr, $min, 'time');
                ?>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['duedate']; ?> &nbsp; <?php echo $errors['time']; ?></font>
                <em><?php echo __('Time is based on your time zone');?> (GMT <?php echo Format::date(false, false, 'ZZZ'); ?>)</em>
            </td>
        </tr>
        
                <?php
        $info['assignId'] = 't'.$thisstaff->getDeptById($thisstaff->GetId());
        
        if($thisstaff->hasPerm(Ticket::PERM_ASSIGN, false)) { ?>
        <tr id="open_ticket_informationdata">
            <td width="160"><strong><?php echo __('Owned By');?>:</strong></td>
            <td>
                <select id="deptId" name="deptId">
                    <option value="0" selected="selected">&mdash; <?php echo __('Select a Team');?> &mdash;</option>
                    <?php
                    
                    
                    if(($teams=Dept::getDepartments(array('dept_id' => $thisstaff->getDepts())))) {
                        //echo '<OPTGROUP label="'.sprintf(__('Teams (%d)'), count($teams)).'">';
                        foreach($teams as $id => $name) {
                            $k="t$id";
                            if (strlen($name) > 5)
                            echo sprintf('<option value="%s" %s>%s</option>',
                                        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
                        }
                        echo '</OPTGROUP>';
                    }
                    
                   ?>
                </select>&nbsp;
                <font class='error'>&nbsp;<?php echo $errors['deptId']; ?></font> <em>The team that should own this suggestion</em><br>
                </td>
        </tr>
        <?php } ?>
            
        <?php
        $info['assignId'] = 't'.$thisstaff->getDeptById($thisstaff->GetId());
        
        if($thisstaff->hasPerm(Ticket::PERM_ASSIGN, false)) { ?>
        <tr id="open_ticket_informationdata">
            <td width="160"><strong><?php echo __('Assign To');?>:</strong></td>
            <td>
                <select id="assignId" name="assignId">
                    <option value="0" selected="selected">&mdash; <?php echo __('Select a Team');?> &mdash;</option>
                    <?php
                    
                    
                    if(($teams=Dept::getDepartments(array('dept_id' => $thisstaff->getDepts())))) {
                        //echo '<OPTGROUP label="'.sprintf(__('Teams (%d)'), count($teams)).'">';
                        foreach($teams as $id => $name) {
                            $k="t$id";
                            if (strlen($name) > 5)
                            echo sprintf('<option value="%s" %s>%s</option>',
                                        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
                        }
                        echo '</OPTGROUP>';
                    }
                    
                   ?>
                </select>&nbsp;
                <font class='error'>&nbsp;<?php echo $errors['assignId']; ?></font> <em>Assign the team that should work on this</em><br>
                </td>
        </tr>
        <?php } ?>
        
        
        </tbody>
        <tbody id="dynamic-form">
        <?php
            foreach ($forms as $form) {
                print $form->getForm()->getMedia();
                include(STAFFINC_DIR .  'templates/dynamic-form.tmpl.php');
            }
        ?>
        
        
        <tbody>
        
</table>
<p style="text-align:center;">
    <input type="submit" name="submit" class="save pending" value="<?php echo _P('action-button', 'Submit');?>">
    <input type="reset"  name="reset"  value="<?php echo __('Reset');?>">
    <input type="button" name="cancel" value="<?php echo __('Cancel');?>" onclick="javascript:
        $('.richtext').each(function() {
            var redactor = $(this).data('redactor');
            if (redactor && redactor.opts.draftDelete)
                redactor.draft.deleteDraft();
        });
        window.location.href='tickets.php';
    ">
</p>
</form>