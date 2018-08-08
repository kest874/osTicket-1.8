<?php
// If the form was removed using the trashcan option, and there was some
// other validation error, don't render the deleted form the second time
if (!$options){
//echo '<div class="row boldlabels">'."\n"; 
    
 }

if (isset($options['entry']) && $options['mode'] == 'edit'
    && $_POST
    && ($_POST['forms'] && !in_array($options['entry']->getId(), $_POST['forms']))
)
    return;

if (isset($options['entry']) && $options['mode'] == 'edit') { ?>

<?php } ?>
<?php
// Keep up with the entry id in a hidden field to decide what to add and
// delete when the parent form is submitted
if (isset($options['entry']) && $options['mode'] == 'edit') { ?>
    <input type="hidden" name="forms[]" value="<?php
        echo $options['entry']->getId(); ?>" />
<?php } ?>
<?php 
if ($options['modal'] !== 'ticketedit'){
if ($form->getTitle()) { ?>
  
     
<?php if ($options['mode'] == 'edit') { ?> 
        <div class="pull-right">
    <?php if ($options['entry']
                && $options['entry']->getDynamicForm()->get('type') == 'G') { ?>
            <a href="#" title="Delete Entry" onclick="javascript:
                $(this).closest('tbody').remove();
                return false;"><i class="icon-trash"></i></a>&nbsp;
    <?php } ?>
            <i class="icon-sort" title="Drag to Sort"></i>
        </div>
<?php } 


?>
        


    <?php
    }
    
    }
    
    foreach ($form->getFields() as $field) {
        try {
			if (!$field->isEnabled())
                continue;
            if ($options['mode'] == 'edit' && !$field->isEditableToStaff())
                continue;
        }
        catch (Exception $e) {
            // Not connected to a DynamicFormField 
        }
        //echo get_class($field);

        $render = 1;
        
        switch (get_class($field)){
            
            case 'RowStartField':
                echo '<div class="row boldlabels">'."\n"; 
                $render = 0;
                break;
                
            case 'CardStartField':
                echo '<div class="card card-body m-t-10">'."\n"; 
                $render = 0;
                break;
                
            case 'ThreeColumnStartField':
                echo '<div class="col-sm-3">'."\n".'<div class="form-group">'."\n";
                $render = 0;
                break;
                
            case 'SixColumnStartField':
                echo '<div class="col-sm-6">'."\n".'<div class="form-group">'."\n";
                $render = 0;
                break;
                
            case 'NineColumnStartField':
                echo '<div class="col-sm-9">'."\n".'<div class="form-group">'."\n";
                $render = 0;
                break;
            
            case 'TwelveColumnStartField':
                echo '<div class="col-sm-12">'."\n".'<div class="form-group">'."\n";
                $render = 0;
                break;
            
            case 'ColumnEndField':
                echo '</div>'."\n".'</div>'."\n";
                $render = 0;
                break;
            
            case 'CarorRowEndField':
                echo '</div>'."\n";
                $render = 0;
                break;
            
        }

       
        if ($render == 1) {
            
		    if ($field->isBlockLevel()) { ?>
                <div <?php if ($field->isRequiredForStaff() || $field->isRequiredForClose()) echo 'id="requiredfield"';
                ?> > <td style="padding-right:16px;" <?php if ($field->isRequiredForStaff() || $field->isRequiredForClose()) echo 'id="requiredfield"';
                ?>> 
                <?php
            }
            
			else {

                
            ?>
                
                
                <div class="multi-line dynamicformdatamulti <?php if ($field->isRequiredForStaff() || $field->isRequiredForClose()) echo 'required ';
                ?>" <?php if ($options['width'])
                    echo "width=\"{$options['width']}\""; ?>>
                <label <?php if (get_class($field) == 'BooleanField' || get_class($field) == 'FileUploadField'){echo 'style="display: none;"';}?>><?php echo Format::htmlchars($field->getLocal('label')); ?>:</label>
                <?php if (strlen($field->getAnswer()) ==0 && $field->isRequiredForClose() && $options) { ?>
                    <span class="form-control-feedback-danger"><i class="fa fa-warning"></i> Required to close Incident</span>
                <?php } ?>
            <div <?php if ($field->errors()){echo ' class="has-danger"';}?>style="position:relative" <?php if ($field->isRequiredForStaff() || $field->isRequiredForClose()) echo 'id="requiredfield"';
                ?>><?php
            }
			
            $field->render($options); 
           
            if ($field->isStorable() && ($a = $field->getAnswer()) && $a->isDeleted()) {
                ?><a class="action-button float-right danger overlay" title="Delete this data"
                    href="#delete-answer"
                    onclick="javascript:if (confirm('<?php echo __('You sure?'); ?>'))
                        $.ajax({
                            url: 'ajax.php/form/answer/'
                                +$(this).data('entryId') + '/' + $(this).data('fieldId'),
                            type: 'delete',
                            success: $.proxy(function() {
                                $(this).closest('tr').fadeOut();
                            }, this)
                        });"
                    data-field-id="<?php echo $field->getAnswer()->get('field_id');
                ?>" data-entry-id="<?php echo $field->getAnswer()->get('entry_id');
                ?>"> <i class="icon-trash"></i> </a></div><?php
            }

            if ($field->get('hint') && !$field->isBlockLevel()) { ?>
                <em class="field-hint"><?php
                    echo Format::viewableImages($field->getLocal('hint')); ?></em>
            <?php
            }
                     
            foreach ($field->errors() as $e) { ?>
                <div class="form-control-feedback-danger "><?php echo Format::htmlchars($e); ?></div>
            <?php } ?>
            </div></div>
            <?php if (Format::htmlchars($field->getLocal('label')) == 'Incident Summary' && !$options) //echo '</div>asdfasdfasdf</div>';?>
        
    <?php 
        }

    $render = 1;
    
    }
if (isset($options['entry']) && $options['mode'] == 'edit') { ?>

<?php } ?>
                         
                            
