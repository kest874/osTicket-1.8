<?php
if(!defined('OSTSTAFFINC') || !$thisstaff) die('Access Denied');

?>

    <div class="has_bottom_border" style="margin-bottom:5px; padding-top:5px;">
        <div class="pull-left">
            <h2><?php echo __('Reports');?></h2>
        </div>
        <div class="clear"></div>
    </div>
<div>
<div>
<br>



<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border:none;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:12px 10px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:12px 10px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
.tg .tg-3we0{background-color:#ffffff;vertical-align:top}
</style>

<?php $DeptId = Dept::getParentId($thisstaff->dept_id);?>
<?php $TeamId = $thisstaff->dept_id;?>

<table class="tg" width="100%">

    <tr class="tg-3we0">
        <td width="220px">
            <strong>
                <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_AssociateSummary&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
                Associate Suggestion Summary</a>
            </strong>
        </td>
        <td>
            <em>Report showing associate activity by month.</em>
        </td>
    </tr>
        
    <tr class="tg-3we0">
        <td width="220px">
            <strong>
            <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_Summary&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
            Team Suggestion Summary</a>
            </strong>
        </td>
        <td>
            <em>Report showing team activity by month.</em>
        </td>
    </tr>
    <tr class="tg-3we0">
        <td width="220px">
            <strong>
                <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_OpenSuggesitonAge&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
                Suggestions Open Age</a>
            </strong>
        </td>
        <td>
            <em>Report showing Suggestions By Age</em>
        </td>     
    </tr>
        <tr class="tg-3we0">
        <td width="220px">
            <strong>
                <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_Suggestions&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
                Suggestions</a>
            </strong>
        </td>
        <td>
            <em>Report showing Suggestions with filters (Catagory, Status)</em>
        </td>     
    </tr>
        </tr>
        <tr class="tg-3we0">
        <td width="220px"> 
            <strong>
                <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_Statistics&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
                Suggestions Statistics</a>
            </strong>
        </td>
        <td>
            <em>Report showing Suggestions Statistics</em>
        </td>     
    </tr>
 
</table>
<br><br><br>
<hr>
<div>
</div>


