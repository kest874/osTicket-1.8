<?php

$_SESSION["alrt"]=1;
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

<div class="row">

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
        <div class='col-sm-3 hidden'>
    
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
                
                <em class="field-hint m-b-0">The location of this incident</em>
        </div>
        <?php } ?>
        </div>
    <div class="col-sm-3">
         <div class="form-group">
            <label>
                <?php echo __('Type of Incident'); ?>:
            </label>
            
                    <select class="form-control form-control-sm requiredfield" name="topicId" id="topicId" onchange="javascript:
                        $.busyLoadFull('show',  { 
                            text: 'LOADING ...',
                            textColor: '#dd2c00',
                            color: '#dd2c00',
                            background: 'rgba(0, 0, 0, 0.2)'
                            });
                        var data = $(':input[name]', '#dynamic-form').serialize();
                        $.ajax(
                          'ajax.php/form/help-topic/' + this.value,
                          {
                            data: data,
                            dataType: 'json',
                            success: function(json) {
                              $('#dynamic-form').empty().append(json.html);
                              $(document.head).append(json.media); 
                              $.busyLoadFull('hide', {}); 
                              $('#submitrow').show();
                            }
                          });
                          
                          
                          
                          
                          topic = this.value;
                          if (topic == 11){
                              $('#chkRecordable').show();
                              $('#chkDART').show();
                          } else {
                              $('#chkRecordable').hide();
                              $('#chkDART').hide();
                          }
                         
                          ">
                    <?php
                    if ($topics=Topic::getHelpTopics(false, false, true)) {
                        if (count($topics) == 1)
                            $selected = 'selected="selected"';
                        else { ?>
                        <option value="" selected >&mdash; <?php echo __('Select Incident Type'); ?> &mdash;</option>
                    <?php    }
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
                            
        </div>
    </div>
            <div class="col-sm-3 ">
                <div class="form-group">
                &nbsp;
                </div>
                <div class="form-group hidden" id="chkRecordable">
                      <div>
                        <div class=" <?php if ($errors['isrecordable'] || !$topic){ echo 'has-danger';}?>">
                        
                        <label for="isRecordable" class="custom-control custom-checkbox m-b-0">
                        <input  class="custom-control-input" id="isRecordable"
                                    type="checkbox" name="isRecordable" />Recordable
                                
                                <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"></span>
                                
                                </label>
                        
                        
                            <?php if ($errors['isrecordable']){ ?>
                            <div class="form-control-feedback-danger"><?php echo __('');?></div>
                            <?php }?>
                                  </div>    
                        </div>
                    </div>
           
                <div class="form-group hidden" id="chkDART">
                      <div>
                        <div class=" <?php if ($errors['isdart'] || !$topic){ echo 'has-danger';}?>">
                        
                        <label for="isdart" class="custom-control custom-checkbox m-b-0" style="display:none;" id="isdartlbl">
                        <input  class="custom-control-input" id="isdart"
                                    type="checkbox" name="isdart"  />Days Away Restricted or Transferred
                                
                                <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"></span>
                                
                                </label>
                        
                        
                            <?php if ($errors['isdart']){ ?>
                            <div class="form-control-feedback-danger"><?php echo __('');?></div>
                            <?php }?>
                                  </div>    
                        </div>
                    </div>
            </div>
       

    

  
<script>    
var screensize= $(window).width();
$("#isRecordable").on("click", function(){
    
    if($('#isRecordable')[0].checked){
        $("#isdartlbl").css('display','');
        if (screensize < 1400){
            $("#dynamic-form").css('margin-top','-25px');
            
        }
        if (screensize > 1400){
            $("#dynamic-form").css('margin-top','-1px');
        }
    } else {
        $("#isdartlbl").css('display','none');
        $('#isdart').prop('checked', false);
        if (screensize < 1400){
            $("#dynamic-form").css('margin-top','0px');
        }
        if (screensize > 1400){
            $("#dynamic-form").css('margin-top','0px');
        }
    }
});

$(window).resize(function() {
 var screensize= $(window).width();
  if (screensize > 1400) {
      $("#dynamic-form").css('margin-top','0px');
  }
});

</script>
    
 

 
<div id="dynamic-form" style="width: 100%">
      
        <?php
            foreach ($forms as $form) {
                print $form->getForm()->getMedia();
                include(STAFFINC_DIR .  'templates/dynamic-form.tmpl.php');
            }
        ?>
</div>
       
 </div>   
   

<div style="margin-top: 15px;" class="hidden" id="submitrow">
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
<?php
 if ($errors){?>

<script>
$('#submitrow').show();
</script>
    
<?php    
}
?> 
</form>

</div> 
</div> 
<script type="text/javascript">

$(document).ready(function(){
    var topic = $('#topicId').find(":selected").val();
    
    if (topic == 11){
            $('#chkRecordable').show();
            $('#chkDART').show();
     } 
 
    //$("#dynamic-form").css('margin-top','-17px');
    $('#datepicker1').datetimepicker({
                   useCurrent: false,
                   format: 'MM/DD/YYYY',
                   showClear: true,
                   showTodayButton: true
                   
               });
     
       
});
</script>
