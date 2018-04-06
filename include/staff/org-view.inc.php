<?php
if(!defined('OSTSCPINC') || !$thisstaff || !is_object($org)) die('Invalid path');
?>
<div class="subnav">
   
      
       <div class="float-left subnavtitle">
      
      <a href="orgs.php?id=<?php echo $org->getId(); ?>"
             title="Reload"><i class="icon-refresh"></i> <?php echo $org->getName(); ?></a>
      </a>
      </div>

      
      <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
      
      
                          <div class="btn-group btn-group-sm" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" 
            data-toggle="dropdown" data-placement="bottom" data-toggle="tooltip" 
             title="<?php echo __('More'); ?>"><i class="fa fa-cog"></i>
            </button>
                    <div class="dropdown-menu dropdown-menu-right " aria-labelledby="btnGroupDrop1" id="action-dropdown-change-priority">
                    
                <?php if ($thisstaff->hasPerm(Organization::PERM_EDIT)) { ?>
                <a href="#ajax.php/orgs/<?php echo $org->getId();
                    ?>/forms/manage" onclick="javascript:
                    $.dialog($(this).attr('href').substr(1), 201);
                    return false"
                    ><i class="icon-paste"></i>
                    <?php echo __('Manage Forms'); ?></a>
<?php } ?>

                        </div>
                    </div>
      
      <?php if ($thisstaff->hasPerm(Organization::PERM_DELETE)) { ?>
            <a id="org-delete" class="btn btn-light org-action"
            href="#orgs/<?php echo $org->getId(); ?>/delete"  data-placement="bottom"
                    data-toggle="tooltip" title="<?php echo __('Delete Organization'); ?>"><i class="fa fa-trash-o"></i>
            </a>
<?php } ?>
<<<<<<< HEAD
              </ul>
            </div>
        </td>
    </tr>
</table>
<table class="ticket_info" cellspacing="0" cellpadding="0" width="940" border="0">
    <tr>
        <td width="50%">
            <table border="0" cellspacing="" cellpadding="4" width="100%">
                <tr>
                    <th width="200"><?php echo __('Name'); ?>:</th>
                    <td>
<?php if ($thisstaff->hasPerm(Organization::PERM_EDIT)) { ?>
=======
      
        <a class="btn btn-light"
            href="orgs.php"  data-placement="bottom"
                    data-toggle="tooltip" title="<?php echo __('Organizations'); ?>"><i class="fa fa-list-alt"></i>
        </a>
            
      </div>
      <div class="clearfix"></div>
</div>  

<div class="card-box">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="row"> 

    <div class="col-sm-3">
        <div>
            <label><?php echo __('Name'); ?>:</label>
        <?php if ($thisstaff->hasPerm(Organization::PERM_EDIT)) { ?>
>>>>>>> support/bootstrap
                    <b><a href="#orgs/<?php echo $org->getId();
                    ?>/edit" class="org-action"><i
                        class="icon-edit"></i>
<?php }
                    echo $org->getName();
    if ($thisstaff->hasPerm(Organization::PERM_EDIT)) { ?>
                    </a></b>
<?php } ?>
<<<<<<< HEAD
                    </td>
                </tr>
                <tr>
                    <th><?php echo __('Auto Assign to Members of'); ?>:</th>
                    <td><?php echo $org->getAccountManager(); ?>&nbsp;</td>
                </tr>
				<tr>
                    <th><?php echo __('AI Team to Auto Assign'); ?>:</th>
                    <td><?php echo $org->getDepartmentName(); ?>&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="50%" style="vertical-align:top">
            <table border="0" cellspacing="" cellpadding="4" width="100%">
                <tr>
                    <th width="150"><?php echo __('Created'); ?>:</th>
                    <td><?php echo Format::datetime($org->getCreateDate()); ?></td>
                </tr>
                <tr>
                    <th><?php echo __('Last Updated'); ?>:</th>
                    <td><?php echo Format::datetime($org->getUpdateDate()); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<div class="clear"></div>
<ul class="clean tabs" id="orgtabs">
    <li class="active"><a href="#users"><i
=======
        </div>
        <div>
            <label><?php echo __('Account Manager'); ?>: </label>
        <?php echo $org->getAccountManager(); ?>
        </div>
    </div>
    <div class="col-sm-9">
        <div>
            <label><?php echo __('Created'); ?>: </label>
        <?php echo Format::datetime($org->getCreateDate()); ?>
        </div>
        <div>
            <label> <?php echo __('Last Updated'); ?>:</label>
        <?php echo Format::datetime($org->getUpdateDate()); ?>
        </div>
    </div>
</div>    
</div>

<ul class="nav nav-tabs" id="orgtabs"  style="margin-top:10px;">
    <li class="nav-item"><a class="nav-link active" href="#users" role="tab" data-toggle="tab"><i
>>>>>>> support/bootstrap
    class="icon-user"></i>&nbsp;<?php echo __('Users'); ?></a></li>
    <li class="nav-item"><a  class="nav-link" href="#tickets" role="tab" data-toggle="tab"><i
    class="icon-list-alt"></i>&nbsp;<?php echo __('Tickets'); ?></a></li>
    <li class="nav-item"><a  class="nav-link" href="#notes" role="tab" data-toggle="tab"><i
    class="icon-pushpin"></i>&nbsp;<?php echo __('Notes'); ?></a></li>
</ul>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="users">
   <?php
include STAFFINC_DIR . 'templates/users.tmpl.php';
?>
   </div>
    <div role="tabpanel" class="tab-pane" id="tickets">
   <?php
include STAFFINC_DIR . 'templates/tickets.tmpl.php';
?>
   </div>
    <div role="tabpanel" class="tab-pane" id="notes">
   <?php
$notes = QuickNote::forOrganization($org);
$create_note_url = 'orgs/'.$org->getId().'/note';
include STAFFINC_DIR . 'templates/notes.tmpl.php';
?>
   </div>

</div>


<script type="text/javascript">
$(function() {
    $(document).on('click', 'a.org-action', function(e) {
        e.preventDefault();
        var url = 'ajax.php/'+$(this).attr('href').substr(1);
        $.dialog(url, [201, 204], function (xhr) {
            if (xhr.status == 204)
                window.location.href = 'orgs.php';
            else
                window.location.href = window.location.href;
         }, {
            onshow: function() { $('#org-search').focus(); }
         });
        return false;
    });
});
</script>
