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
//if(!$user) {
//  $user = User::lookupByemail($thisstaff->getEmail());
 //}
?>
<div class="subnav">

    <div class="float-left subnavtitle">
                          
   <?php echo __('Open a New Suggestion');?>                       
    
    </div>
    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
   &nbsp;
      </div>   
   <div class="clearfix"></div> 
</div> 

<div class="card-box">
<div class="row">
<div class="col"> 
<form action="tickets.php?a=open" method="post" id="save"  enctype="multipart/form-data" >
<fieldset> 

<div class="row ticketform">
            <div class='col-sm-3'>
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="create">
 <input type="hidden" name="a" value="open">

    
       
        <div class="form-group">
        <label><?php echo __('Submitter'); ?>:</label>
           
            <select nid="uid" name="uid" class="form-control form-control-sm requiredfield">
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
                </select><?php if ($errors['submitter']){ echo '<span class="error">&nbsp;'.$errors['submitter'].'</span>';}?>
            
        </div>
        
        
   </div>
   <div class='col-sm-3'>
   <div class="m-t-30"> </div>
   <?php
        if($cfg->notifyONNewStaffTicket()) {  ?>
        <div  class="form-group">
        
        <label class="custom-control custom-checkbox m-b-0">
        <input  class="custom-control-input" id="_960bbada56ed74"
            type="checkbox" n name="alertuser"/><?php echo __('Suggestion Notice'); ?>        
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description"></span>
        
        </label>
        
                <em class="field-hint m-b-0"><?php echo __('Send alert to associate.'); ?></em>
         </div>              
        <?php
        } ?>
   </div>
   </div>
   <div class="row ticketform">
    <div class="col-sm-3">
         <div  class="form-group">
             <label><?php echo __('Due Date');?>:</label>
            
            <div class='input-group date' id="datepicker1" >
                    <input type='text' id="duedate" name="duedate" class="form-control form-control-sm"  />
                    <span class="input-group-addon" style="display: inline">
                        <span class="fa fa-calendar"></span>
                    </span>
                </div>
             <?php if ($errors['duedate']){ ?>
            <div class="form-control-feedback"><?php echo $errors['duedate'];?></div>
            <?php } ?>
            </div>
            
            

<div class="form-group">
            <label>
                <?php echo __('Category'); ?>:
            </label>
            
                    <select class="form-control form-control-sm requiredfield" name="topicId" onchange="javascript:
                        
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
                          });
                          
                          
                          $('#submit').show();
                          $('#reset').show();
                          ">
                    <?php
                    if ($topics=Topic::getHelpTopics(false, false, true)) {
                        if (count($topics) == 1)
                            $selected = 'selected="selected"';
                        else { ?>
                        <option value="" selected >&mdash; <?php echo __('Select Category'); ?> &mdash;</option>
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
                <?php if ($errors['topicId']){?>&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['topicId']; ?></font><?php } ?>
            
        </div>

        
    </div>
    <div class='col-sm-3'>
    

        <div class="form-group hidden">
            <label>
                <?php echo __('Suggestion Source');?>:
            </label>
            
                <select name="source" class="form-control form-control-sm requiredfield">
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
                <?php if ($errors['source']) { ?>
                <span><font class="error"><?php echo $errors['source']; ?></font></span>
                <?php } ?>
            
        </div>
        
        
       
  
       <?php
        $info['assignId'] = 't'.$thisstaff->getDeptById($thisstaff->GetId());
        
        if($thisstaff->hasPerm(Ticket::PERM_ASSIGN, false)) { ?>
        <div  class="form-group">
            <label><?php echo __('Owned By');?>:</strong></label>
            
                <select id="deptId" name="deptId" class="form-control form-control-sm requiredfield">
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
                        //echo '</OPTGROUP>';
                    }
                    
                   ?>
                </select>
                <?php if ($errors['deptId']){?><font class='error'>&nbsp;<?php echo $errors['deptId']; ?></font> <?php } ?>
                <em class="field-hint m-b-0">The team that should own this suggestion</em>
        </div>
        <?php } ?>
            
        <?php
        $info['assignId'] = 't'.$thisstaff->getDeptById($thisstaff->GetId());
        
        if($thisstaff->hasPerm(Ticket::PERM_ASSIGN, false)) { ?>
        
        <div  class="form-group">
            <label><?php echo __('Assign To');?>:</label>
                <select id="assignId" name="assignId" class="form-control form-control-sm requiredfield">
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
                </select>
                <?php if ($errors['assignId']){?><font class='error'>&nbsp;<?php echo $errors['assignId']; ?></font><?php } ?>
                <em class="field-hint m-b-0">Assign the team that should work on this</em>
        </div>
        <?php } ?>
 
       </div>
       <div class='col-sm-3'>
       
         <div class="form-group"  style="display:none;">
            <label>
                <?php echo __('SLA Plan');?>:
            </label>
            
                <select  class="form-control form-control-sm " name="slaId">
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
            
         </div>

                         
          
          
        

    </div>
    </div>
<div id="ticketopen"> 
<div id="dynamic-form">
      
        <?php
		    $_SESSION['tickettype'] =0;
            foreach ($forms as $form) {
				
                print $form->getForm()->getMedia();
                include(STAFFINC_DIR .  'templates/dynamic-form.tmpl.php');
            }
		
        ?>
</div>
       
 </div>       
 </fieldset>      

<div>
    <input class="btn btn-primary btn-sm" type="submit" name="submit" class="save pending" value="<?php echo _P('action-button', 'Submit');?>">
    <input class="btn btn-warning btn-sm" type="reset"  name="reset"  value="<?php echo __('Reset');?>">
    <input class="btn btn-warning btn-danger btn-sm" type="button" name="cancel" value="<?php echo __('Cancel');?>" onclick="javascript:
        $('.richtext').each(function() {
            var redactor = $(this).data('redactor');
            if (redactor && redactor.opts.draftDelete)
                redactor.draft.deleteDraft();
        });
        window.location.href='tickets.php';
    ">
</div>
</form>
</div>
</div> 
</div> 
<script type="text/javascript">
$(document).ready(function(){
    
    $('#datepicker1').datetimepicker({
                   useCurrent: false,
                   format: 'MM/DD/YYYY',
                   showClear: true,
                   showTodayButton: true
                   
               });
     
       
});
</script>
