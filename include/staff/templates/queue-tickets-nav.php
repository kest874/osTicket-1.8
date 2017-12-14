
<nav class="navbar navbar-toggleable-md navbar-light" role="navigation" id="queuenav">
      
      <button class="navbar-toggler navbar-toggler-right" data-toggle="collapse" data-target="#ticket-navbar-collapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <a class="navbar-brand hidden-md-up" href="index.html">
        Tickets
      </a>

      <div class="collapse navbar-collapse" id="ticket-navbar-collapse">
        <ul class="navbar-nav mr-auto">
  
  <?php include STAFFINC_DIR . "templates/sub-navigation.tmpl.php"; ?>
  
        </ul>
    
    <ul class="navbar-nav"  style="margin-right: 15px;">
    <li class="navbar-right">
    <form  class="form-inline" style="margin-top:12px;" action="tickets.php" method="get" onsubmit="javascript:
      $.pjax({
        url:$(this).attr('action') + '?' + $(this).serialize(),
        container:'#pjax-container',
        timeout: 2000
      });
    return false;">
        <input type="hidden" name="a" value="search">
        <input type="hidden" name="search-type" value=""/>
        
          <input type="text" class="basic-search" data-url="ajax.php/tickets/lookup" name="query"
            autofocus size="30" value="<?php echo Format::htmlchars($_REQUEST['query'], true); ?>"
            autocomplete="off" autocorrect="off" autocapitalize="off"  placeholder="Search Tickets">
          <button type="submit" class="button"><i class="icon-search"></i>
          </button>
       
        <a href="#" onclick="javascript:
            $.dialog('ajax.php/tickets/search', 201);"
            >[<?php echo __('advanced'); ?>]</a>
            <i class="help-tip icon-question-sign" href="#advanced"></i>
        </form>
      </li>  
        </ul>
      </div>
    
  </nav>
  
  
  
  
  



