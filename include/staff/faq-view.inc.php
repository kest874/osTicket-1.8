<?php
if(!defined('OSTSTAFFINC') || !$faq || !$thisstaff) die('Access Denied');

$category=$faq->getCategory();

?><div class="has_bottom_border" style="padding-top:5px;">
<div class="pull-left"><h2><?php echo __('Frequently Asked Questions');?></h2></div>
<div class="pull-right flush-right">
<?php
$query = array();
parse_str($_SERVER['QUERY_STRING'], $query);
$query['a'] = 'print';
$query['id'] = $faq->getId();
$query = http_build_query($query); ?>
    <a href="faq.php?<?php echo $query; ?>" class="no-pjax action-button">
    <i class="icon-print"></i>
        <?php echo __('Print'); ?>
    </a>
<?php
if ($thisstaff->hasPerm(FAQ::PERM_MANAGE)) { ?>
    <a href="faq.php?id=<?php echo $faq->getId(); ?>&a=edit" class="action-button">
    <i class="icon-edit"></i>
        <?php echo __('Edit FAQ'); ?>
    </a>
<?php } ?>
</div><div class="clear"></div>

</div>

<div id="breadcrumbs">
    <a href="kb.php"><?php echo __('All Categories');?></a>
    &raquo; <a href="kb.php?cid=<?php echo $category->getId(); ?>"><?php
    echo $category->getFullName(); ?></a>
    <span class="faded">(<?php echo $category->isPublic()?__('Public'):__('Internal'); ?>)</span>
</div>

<div class="pull-right sidebar faq-meta">
<?php if ($attachments = $faq->getLocalAttachments()->all()) { ?>
<section>
    <header><?php echo __('Attachments');?>:</header>
<?php foreach ($attachments as $att) { ?>
<div>
    <i class="icon-paperclip pull-left"></i>
    <a target="_blank" href="<?php echo $att->file->getDownloadUrl(['id' =>
    $att->getId()]); ?>"
        class="attachment no-pjax">
        <?php echo Format::htmlchars($att->getFilename()); ?>
    </a>
</div>
<?php } ?>
</section>
<?php } ?>

<?php if ($faq->getHelpTopics()->count()) { ?>
<section>
    <header><?php echo __('Help Topics'); ?></header>
<?php foreach ($faq->getHelpTopics() as $T) { ?>
    <div><?php echo $T->topic->getFullName(); ?></div>
<?php } ?>
</section>
<?php } ?>

<?php
$displayLang = $faq->getDisplayLang();
$otherLangs = array();
if ($cfg->getPrimaryLanguage() != $displayLang)
    $otherLangs[] = $cfg->getPrimaryLanguage();
foreach ($faq->getAllTranslations() as $T) {
    if ($T->lang != $displayLang)
        $otherLangs[] = $T->lang;
}
if ($otherLangs) { ?>
<section>
    <div><strong><?php echo __('Other Languages'); ?></strong></div>
<?php
    foreach ($otherLangs as $lang) { ?>
    <div><a href="faq.php?kblang=<?php echo $lang; ?>&id=<?php echo $faq->getId(); ?>">
        <?php echo Internationalization::getLanguageDescription($lang); ?>
    </a></div>
    <?php } ?>
</section>
<?php } ?>

<section>
<div>
    <strong><?php echo $faq->isPublished()?__('Published'):__('Internal'); ?></strong>
</div>
<a data-dialog="ajax.php/kb/faq/<?php echo $faq->getId(); ?>/access" href="#"><?php echo __('Manage Access'); ?></a>
</section>


<div class="subnav">

    <div class="float-left subnavtitle">
                          
    <?php echo __('Frequently Asked Questions').' / <a href="kb.php">'.__('All Categories').'</a> / <a href="kb.php?cid='.$category->getId().'">'.$category->getName().'</a> <span class="faded">('.$view.')</span>';?>                        
    
    </div>
    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
   
        <?php
        $query = array();
        parse_str($_SERVER['QUERY_STRING'], $query);
        $query['a'] = 'print';
        $query['id'] = $faq->getId();
        $query = http_build_query($query); ?>
            <a href="faq.php?<?php echo $query; ?>" class="btn btn-icon waves-effect waves-light btn-light">
            <i class="icon-print"></i></a>
        <?php
        if ($thisstaff->hasPerm(FAQ::PERM_MANAGE)) { ?>
            <a href="faq.php?id=<?php echo $faq->getId(); ?>&a=edit" class="btn btn-icon waves-effect waves-light btn-light">
            <i class="icon-edit"></i></a>
        <?php } ?>
        
       
        <a class="btn btn-icon waves-effect waves-light btn-danger" href="#" data-toggle="modal" data-target="#con-delete-modal" data-placement="bottom" title="<?php echo __('Delete FAQ'); ?>"
                >
                                    <i class="icon-trash icon-fixed-width" ></i>
                                    
                                </a>
       
    </div>
        
   <div class="clearfix"></div> 
</div> 

 
 <div class="row">
    <div class="col-lg-8">
        <div class="card-box">
        
                     
            
            <div class="question-q-box">Q.</div>
            <h4 class="question"><?php echo $faq->getLocalQuestion() ?> <span class="faded m-b-10"><?php echo __('Last Updated'); ?>
            <?php echo Format::relativeTime(Misc::db2gmtime($faq->getUpdateDate())); ?>
            </span></h4>
            <p class="answer"><?php echo $faq->getLocalAnswerWithImages(); ?></p>
            
            
        </div> 
    </div>
    <div class="col-lg-4">
        
        
            <?php if ($attachments = $faq->getLocalAttachments()->all()) { ?>
            <div class="card-box">
                <h5 class="mt-0 card-title"><?php echo __('Attachments'); ?></h5>
            <?php foreach ($attachments as $att) { ?>
            <div>
                <i class="icon-paperclip pull-left"></i>
                <a target="_blank" href="<?php echo $att->file->getDownloadUrl(); ?>"
                    class="attachment no-pjax">
                    <?php echo Format::htmlchars($att->getFilename()); ?>
                </a>
            </div>
            <?php } ?>
            </div>
            <?php } ?>
            
            <?php if ($faq->getHelpTopics()->count()) { ?>
            <div class="card-box">
                                            
                                            
            <h5 class="mt-0 card-title"><?php echo __('Help Topics'); ?></h5>
                
            <?php foreach ($faq->getHelpTopics() as $T) { ?>
                <div><?php echo $T->topic->getFullName(); ?></div>
            <?php } ?>
            </div>
            <?php } ?>
            
            <?php
            $displayLang = $faq->getDisplayLang();
            $otherLangs = array();
            if ($cfg->getPrimaryLanguage() != $displayLang)
                $otherLangs[] = $cfg->getPrimaryLanguage();
            foreach ($faq->getAllTranslations() as $T) {
                if ($T->lang != $displayLang)
                    $otherLangs[] = $T->lang;
            }
            if ($otherLangs) { ?>
            <div class="card-box">
                <div><strong><?php echo __('Other Languages'); ?></strong></div>
            <?php
                foreach ($otherLangs as $lang) { ?>
                <div><a href="faq.php?kblang=<?php echo $lang; ?>&id=<?php echo $faq->getId(); ?>">
                    <?php echo Internationalization::getLanguageDescription($lang); ?>
                </a></div>
                <?php } ?>
            </div>
            <?php } ?>
            
            <div class="card-box">
            <h5 class="mt-0 card-title"><?php echo __('Visability'); ?></h5>
           
            <a data-dialog="ajax.php/kb/faq/<?php echo $faq->getId(); ?>/access" href="#"><?php echo $faq->isPublished()?__('Published'):__('Internal'); ?></a>
            </div>
            
            
                    
        
    
  </div>
</div>



<?php
if ($thisstaff->hasPerm(FAQ::PERM_MANAGE)) { ?>
<form action="faq.php?id=<?php echo  $faq->getId(); ?>" method="post">
    <?php csrf_token(); ?>
    <input type="hidden" name="do" value="manage-faq">
    <input type="hidden" name="id" value="<?php echo  $faq->getId(); ?>">
    <button name="a1" class="red button" value="delete" style="display:none;"><?php echo __('Delete FAQ'); ?></button>
</form>
<?php }
?>
</div>


<div id="con-delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Please Confirm</h4>
            </div>
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-md-12">
                       <p class="text-danger"><strong> Are you sure you want to DELETE this FAQ?</strong> </p>
                       <p> Deleted FAQs CANNOT be recovered, including any attached Files.</p>
                       <p> <strong>Please confirm to continue.</strong></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light waves-effect" data-dismiss="modal">No, Close</button>
                <a href="faq.php?id=<?php echo  $faq->getId();?>&a=delete" ><button type="button" class="btn btn-sm btn-danger waves-effect waves-light">Yes, Delete!</button></a>
            </div>
        </div>
    </div>
</div><!-- /.modal -->

