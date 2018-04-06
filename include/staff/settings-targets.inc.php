<div class="subnav">

    <div class="float-left subnavtitle">
                          
    <?php echo __('Associate Targets');?>
    
    </div>
    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
   &nbsp;
      </div>   
   <div class="clearfix"></div> 
</div> 

<?php
//if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin() || !$config) die('Access Denied');
?>
<div class="card-box">
<form action="settings.php?t=targets" method="post" class="save">
<?php csrf_token(); ?>
<input type="hidden" name="t" value="targets"> 
<?php csrf_token(); ?>

<table class="form_table settings_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <em><?php echo __("Define the target for Suggestions and Implementations.");?></em>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="180"><?php echo __('Suggestions per Year per Associate');?>:</td>
            <td>
                <input type="text" name="sug_per_yr" value="<?php echo $config['sug_per_yr']; ?>" >
                &nbsp;<font class="error">&nbsp;<?php echo $errors['sug_per_yr']; ?></font>
                <i class="help-tip icon-question-sign" href="#sug_per_yr"></i>
            </td>
        </tr>
        <tr>
            <td width="180"><?php echo __('Implementations per Year per Associate');?>:</td>
            <td>
                <input type="text" name="imp_per_yr" value="<?php echo $config['imp_per_yr']; ?>" >
                &nbsp;<font class="error">&nbsp;<?php echo $errors['imp_per_yr']; ?></font>
                <i class="help-tip icon-question-sign" href="#imp_per_yr"></i>
            </td>
        </tr>		
    </tbody>
</table>
<div></br>
    <input type="submit" name="submit" value="<?php echo __('Save Changes'); ?>" class="btn btn-sm btn-primary" >
    <input type="reset" name="reset" value="<?php echo __('Reset Changes'); ?>" class="btn btn-sm btn-warning" >
</div>
</form>
</div>