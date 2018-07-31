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
                          
   <?php echo __('Open a New Incident');?>                       
    
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
   
   <div class="col-sm-3">
   
    <?php
        $info['assignId'] = 't'.$thisstaff->getDeptById($thisstaff->GetId());
        
        if($thisstaff->hasPerm(Ticket::PERM_ASSIGN, false)) { ?>
        <div  class="form-group">
            <label><?php echo __('Division');?>:</strong></label> 
            
                <select id="deptId" name="deptId" class="form-control form-control-sm requiredfield">
                    <option value="0" selected="selected">&mdash; <?php echo __('Select a Division');?> &mdash;</option>
                    <?php
                    
                    
                    if(($teams=Dept::getDepartments(array('dept_id' => $thisstaff->getDepts())))) {
                        //echo '<OPTGROUP label="'.sprintf(__('Teams (%d)'), count($teams)).'">';
                        foreach($teams as $id => $name) {
                            $k="t$id";
                            
                            echo sprintf('<option value="%s" %s>%s</option>',
                                        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
                        }
                        //echo '</OPTGROUP>';
                    }
                    
                   ?>
                </select>
                <?php if ($errors['deptId']){?><font class='error'>&nbsp;<?php echo $errors['deptId']; ?></font> <?php } ?>
                <em class="field-hint m-b-0">The location of this incident</em>
        </div>
        <?php } ?>
        </div>
    <div class="col-sm-3">
                     

<div class="form-group">
            <label>
                <?php echo __('Type of Incident'); ?>:
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
                          topic = this.value;
                          if (topic == 11){
                              $('#chkRecordable').show();
                          }                                   
                          ">
                    <?php
                    if ($topics=Topic::getHelpTopics(false, false, true)) {
                        if (count($topics) == 1)
                            $selected = 'selected="selected"';
                        else { ?>
                        <option value="" selected >&mdash; <?php echo __('Select Incident Type'); ?> &mdash;</option>
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
            
        
        <div class="col-sm-3 hidden" id="chkRecordable">
                <div class="form-group">
                      <div>
                        <div class=" <?php if ($errors['isrecordable'] || !$topic){ echo 'has-danger';}?>">
                        
                        <label for="isRecordable" class="custom-control custom-checkbox m-b-0">
                        <input  class="custom-control-input" id="isRecordable"
                                    type="checkbox" name="isRecordable" <?php
                                    if ($recordable==1) echo 'checked="checked"'; ?> />Recordable
                                
                                <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"></span>
                                
                                </label>
                        
                        
                            <?php if ($errors['isrecordable']){ ?>
                            <div class="form-control-feedback-danger"><?php echo __('');?></div>
                            <?php }?>
                                  </div>    
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class='col-sm-3'>
    

        <div class="form-group hidden">
            <label>
                <?php echo __('Incident Source');?>:
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
  
     
    <div class="row boldlabels">
    
<div id="ticketopen"> 
<div id="dynamic-form">
      
        <?php
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
