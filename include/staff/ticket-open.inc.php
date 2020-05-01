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
                          
   <?php echo __('Open a New Ticket');?>                      
    
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
            <div class='col-sm-4'>
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="create">
 <input type="hidden" name="a" value="open">

    

        <div class="form-group">
                <em><strong><?php echo __('User and Collaborators:'); ?></strong>: </em>
                <div class="error"><?php echo $errors['user']; ?></div>
        </div>
<div class="form-group">	
	 					<label>
                <?php echo __('User');?>:
            </label>
	
        <div class="input-group select2open"> 
          
                  <select class="custom-select custom-select-sm userSelection hidden" name="name" id="user-name"

                    data-placeholder="<?php echo __('Select User'); ?>">
                  </select>
              
						<div class="input-group">
                <button class="btn btn-sm btn-outline-secondary" href="#"
                onclick="javascript:
                $.userLookup('ajax.php/users/lookup/form', function (user) {
                  var newUser = new Option(user.email + ' - ' + user.name, user.id, true, true);
                  return $(&quot;#user-name&quot;).append(newUser).trigger('change');
                });
                return false;
                "><i class="icon-plus"></i> <?php echo __('Add New'); ?></a>
						
                <span class="error">*</span>
                <span class="error"><?php echo $errors['name']; ?></span>
                </div>
				</div>
              <div>
                <input type="hidden" size=45 name="email" id="user-email" class="attached"
                placeholder="<?php echo __('User Email'); ?>"
                autocomplete="off" autocorrect="off" value="<?php echo $info['email']; ?>" />
              </div>
</div>    
<div class="form-group">	
	 					<label>
                <?php echo __('Cc');?>:
            </label>
	
        <div class="input-group">      
                  <select class="custom-select custom-select-sm collabSelections hidden" name="ccs[]" id="cc_users_open" multiple="multiple"
                ref="tags" data-placeholder="<?php echo __('Select Contacts'); ?>">
                  </select>
              
						<div class="input-group">
                <button class="btn btn-sm btn-outline-secondary" href="#"
                onclick="javascript:
                 $.userLookup('ajax.php/users/lookup/form', function (user) {
              var newUser = new Option(user.name, user.id, true, true);
              return $(&quot;#cc_users_open&quot;).append(newUser).trigger('change');
            });
            return false;
                "><i class="icon-plus"></i> <?php echo __('Add New'); ?></a>
						
                    <span class="error"><?php echo $errors['ccs']; ?></span>
                </div>
				</div>
              
</div>            
 
        <?php
         ?>

        <?php
        if($cfg->notifyONNewStaffTicket()) {  ?>
        <div class="form-group">
            <label><?php echo __('Ticket Notice'); ?>:</label>
         <div class="form-check">    
            <label class="form-check-label">
      <select class="form-control form-control-sm" id="reply-to" name="reply-to">
              <option value="all"><?php echo __('Alert All'); ?></option>
              <option value="user"><?php echo __('Alert to User'); ?></option>
              <option value="none">&mdash; <?php echo __('Do Not Send Alert'); ?> &mdash;</option>
            </select>
      
    </label>
            
        </div>    
        </div>
        <?php
        } ?>
    </div>
    <div class='col-sm-4'>
    
        <div class="form-group">
            
                <em><strong><?php echo __('Ticket Information');?></strong>:</em>
           
        </div>
        <div class="form-group">
            <label>
                <?php echo __('Ticket Source');?>:
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
        <div class="form-group">
            <label>
                <?php echo __('Type'); ?>:
            </label>
            
                    <input id="cc" name="topicId" class="easyui-combotree "  style="width:95%;  border-radius: 2px !important;"></input>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['topicId']; ?></font>
            
        </div>
        <div  class="form-group" style="display:none;">
            <label>
                <?php echo __('Department'); ?>:
            <label>
            
                <select name="deptId">
                    <option  class="form-control form-control-sm " value="" selected >&mdash; <?php echo __('Select Department'); ?>&mdash;</option>
                    <?php
                    if($depts=Dept::getDepartments(array('dept_id' => $thisstaff->getDepts()))) {
                        foreach($depts as $id =>$name) {
                            if (!($role = $thisstaff->getRole($id))
                                || !$role->hasPerm(Ticket::PERM_CREATE)
                            ) {
                                // No access to create tickets in this dept
                                continue;
                            }
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['deptId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error"><?php echo $errors['deptId']; ?></font>
            
        </div>
        
       </div>
       <div class='col-sm-4'>
       
         <div class="form-group">
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
          
          <div  class="form-group">
             <label><?php echo __('Due Date');?>:</label>
            
            <div class='input-group date'>
                                    <?php
                $duedateField = Ticket::duedateField('duedate', $info['duedate']);
                $duedateField->render();
                ?>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['duedate']; ?> &nbsp; <?php echo $errors['time']; ?></font>
                <em><?php echo __('Time is based on your time
                        zone');?>&nbsp;(<?php echo $cfg->getTimezone($thisstaff); ?>)</em>
                </div>
                
                        
        
                
            </div>

        <?php
        if($thisstaff->hasPerm(Ticket::PERM_ASSIGN, false)) { ?>
        <div class="form-group">
            <label><?php echo __('Assign To');?>:</label>
            
                <select class="form-control form-control-sm " id="assignId" name="assignId">
                    <option value="0" selected="selected">&mdash; <?php echo __('Select an Agent OR a Team');?> &mdash;</option>
                    <?php
                    if(($users=Staff::getAvailableStaffMembers())) {
                        echo '<OPTGROUP label="'.sprintf(__('Agents (%d)'), count($users)).'">';
                        foreach($users as $id => $name) {
                            $k="s$id";
                            echo sprintf('<option value="%s" %s>%s</option>',
                                        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
                        }
                        echo '</OPTGROUP>';
                    }

                    if(($teams=Team::getActiveTeams())) {
                        echo '<OPTGROUP label="'.sprintf(__('Teams (%d)'), count($teams)).'">';
                        foreach($teams as $id => $name) {
                            $k="t$id";
                            echo sprintf('<option value="%s" %s>%s</option>',
                                        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
                        }
                        echo '</OPTGROUP>';
                    }
                    ?>
                </select>&nbsp;<span class='error'>&nbsp;<?php echo $errors['assignId']; ?></span>
            
        </div>
        <?php } ?>
     

    </div>
    </div>
<div class='col-sm-12'><hr></div>    
<div id="dynamic-form">
        
        <?php
            foreach ($forms as $form) {
                print $form->getForm()->getMedia();
                include(STAFFINC_DIR .  'templates/dynamic-form.tmpl.php');
            }
        ?>
        </div>
        
        
 </fieldset>      

<div class="hidden" id="submitrow">
    <input class="btn btn-primary btn-sm" type="submit" name="submit" class="save pending" value="<?php echo _P('action-button', 'Submit');?>">
    <input class="btn btn-warning btn-sm" type="reset"  name="reset"  value="<?php echo __('Reset');?>">
     <input class="btn btn-danger btn-sm" type="button" name="cancel" value="<?php echo __('Cancel');?>" onclick="javascript:
        $(this.form).find('textarea.richtext')
          .redactor('plugin.draft.deleteDraft');
        window.location.href='tickets.php'; " />
</div>
</form>
</div>
</div> 
</div> 
<script type="text/javascript">
$(function() {
    $('input#user-email').typeahead({
        source: function (typeahead, query) {
            $.ajax({
                url: "ajax.php/users?q="+query,
                dataType: 'json',
                success: function (data) {
                    typeahead.process(data);
                }
            });
        },
        onselect: function (obj) {
            $('#uid').val(obj.id);
            $('#user-name').val(obj.name);
            $('#user-email').val(obj.email);
        },
        property: "/bin/true"
    });
    
  $.extend($.fn.tree.methods,{
    getLevel: function(jq, target){
        return $(target).find('span.tree-indent,span.tree-hit').length;
    }
});

$(function() {
    $('a#editorg').click( function(e) {
        e.preventDefault();
        $('div#org-profile').hide();
        $('div#org-form').fadeIn();
        return false;
     });

    $(document).on('click', 'form.org input.cancel', function (e) {
        e.preventDefault();
        $('div#org-form').hide();
        $('div#org-profile').fadeIn();
        return false;
    });

    $('.userSelection').select2({
      width: '450px',
      minimumInputLength: 3,
      ajax: {
        url: "ajax.php/users/local",
        dataType: 'json',
        data: function (params) {
          return {
            q: params.term,
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.email + ' - ' + item.name,
                slug: item.slug,
                email: item.email,
                id: item.id
              }
            })
          };
          $('#user-email').val(item.name);
        }
      }
    });

    $('.userSelection').on('select2:select', function (e) {
      var data = e.params.data;
      $('#user-email').val(data.email);
    });

    $('.userSelection').on("change", function (e) {
      var data = $('.userSelection').select2('data');
      var data = data[0].text;
      var email = data.substr(0,data.indexOf(' '));
      $('#user-email').val(data.substr(0,data.indexOf(' ')));
     });

    $('.collabSelections').select2({
      width: '450px',
      minimumInputLength: 3,
      ajax: {
        url: "ajax.php/users/local",
        dataType: 'json',
        data: function (params) {
          return {
            q: params.term,
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.name,
                slug: item.slug,
                id: item.id
              }
            })
          };
        }
      }
    });

  });
  
$(document).ready(function(){
    var val = <?php echo Topic::getHelpTopicsTree();?> ;

    $('#cc').combotree({ 
        onChange: function (r) { 
            var c = $('#cc');
            var t = c.combotree('tree');  // get tree object
            var node = t.tree('getSelected');
            var nodeLevel = t.tree('getLevel',node.target);
            parentArry = new Array();
            var parentArry = new Array();
                var parents = getParentArry(t,node,nodeLevel,parentArry);
                var parentStr = "";
                if(parents.length > 0){
                    var parentStr = "";
                    for(var i = 0; i < parents.length; i++){
                        parentStr += parents[i].text + " / ";
                    }
                }
             $('#cc').combotree('setText', parentStr + node.text);            
           } 
    });
    
    $('#cc').combotree({
	
	onClick:function(node){
	     
	   var c = $('#cc');
        var t = c.combotree('tree');  // get tree object
        var node = t.tree('getSelected');
           
            
		$(t).tree('toggle', node.target);
		c.combobox('showPanel');
		if(t.tree('getLevel',node.target) == '1'){ 
		    $('#submitrow').hide();
		    $('#dynamic-form').hide();
	      }
	      if(t.tree('getLevel',node.target) == '2'){ 
		   c.combobox('hidePanel');
	      }
	}
})
    
    $('#cc').combotree({ 
        onSelect: function (r) { 
        
        var c = $('#cc');
        var t = c.combotree('tree');  // get tree object
        var node = t.tree('getSelected');

          if(t.tree('getLevel',node.target) == '2'){
               
           
                  
            //Loads the dynamic form on selection
            var data = $(':input[name]', '#dynamic-form').serialize();
         
            $('#submitrow').show();
            $('#dynamic-form').show();
            
            $.ajax(
              'ajax.php/form/help-topic/' + r.id,
              {
                data: data,
                dataType: 'json',
                success: function(json) {
                  $('#dynamic-form').empty().append(json.html);
                  $(document.head).append(json.media);
                }
              });
            }    
        } 
    });

    $('#cc').combotree('loadData', val);
    
    function getParentArry(tree,selectedNode,nodeLevel,parentArry){
            //end condition: level of selected node equals 1, means it's root
           if(nodeLevel == 1){
              return parentArry;
           }else{//if selected node isn't root
              nodeLevel -= 1;
              //the parent of the node
              var parent = $(tree).tree('getParent',selectedNode.target);
              //record the parent of selected to a array
              parentArry.unshift(parent);
              //recursive, to judge whether parent of selected node has more parent
              return getParentArry(tree,parent,nodeLevel,parentArry);
            }
        }
    $('#cc').combotree('setText', '\u2014 <?php echo __('Select Type'); ?> \u2014');
    
    $('#datepicker1').datetimepicker({
                   useCurrent: false,
                   format: 'MM/DD/YYYY',
                   showClear: true,
                   showTodayButton: true
                   
               });   
});
   
});
</script>