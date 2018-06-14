<script src="<?php echo ROOT_PATH; ?>scp/js/highcharts.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/highcharts-3d.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/exporting.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/export-data.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/pareto.js"></script>



<div class="subnav">

    <div class="float-left subnavtitle">
                          
    <?php echo __('IT Dashboard');?>                        
    
    </div>
    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
   &nbsp;
      </div>   
   <div class="clearfix"></div> 
</div> 


<div class="row">
    <div class="col-lg-6">
        <div class="portlet" id="IncidentLocationbyType" ><!-- /primary heading -->
            
        </div>
    </div>
     <div class="col-lg-6">
        <div class="portlet" id="IncidentTypebyLocation" ><!-- /primary heading -->
            
        </div>
    </div>
    
    
</div>

<script>

<?php
    $sql="select distinct topic from (SELECT count(ticket_id) as incidents, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic) a";
        
    $topics = db_query($sql);
    
    $sql="select distinct location from (SELECT count(ticket_id) as incidents, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic) a";
        
    $locs = db_query($sql);
    

    $sql="SELECT count(ticket_id) as COUNT, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic";
        
    $topicsdata = db_query($sql);
    

?>

$(function () {
Highcharts.chart('IncidentLocationbyType', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Incident Location by Type',
        style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
    },
    credits: false,
    xAxis: {
        categories: [
        <?php
  foreach ($topics as $ctopics) {
             
             echo "'".preg_replace('/\s+/', ' ', $ctopics["topic"])."',";
   }   
   ?>]
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Incidents'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
            }
        }
    },
    series: [<?php
        foreach ($locs as $loc) { ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            data: [<?php foreach ($topicsdata as $topicdata) {

                if ($topicdata["location"] == $loc["location"]) echo $topicdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>]
 });
}); 



<?php
    $sql="select distinct topic from (SELECT count(ticket_id) as incidents, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic) a";
        
    $topics = db_query($sql);
    
    $sql="select distinct location from (SELECT count(ticket_id) as incidents, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic) a";
        
    $locs = db_query($sql);
    

    $sql="SELECT count(ticket_id) as COUNT, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic";
        
    $topicsdata = db_query($sql);
    

?>

$(function () {
Highcharts.chart('IncidentTypebyLocation', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Incident Type by Location',
        style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
    },
    credits: false,
    xAxis: {
        categories: [
        <?php
  foreach ($locs as $cloc) {
             
             echo "'".preg_replace('/\s+/', ' ', $cloc["location"])."',";
   }   
   ?>]
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Incidents'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
            }
        }
    },
    series: [<?php
        foreach ($topics as $topic) { ?>
        
        {
            name: '<?php echo $topic["topic"]?>',
            data: [<?php foreach ($topicsdata as $topicdata) {

                if ($topicdata["topic"] == $topic["topic"]) echo $topicdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>]
 });
});      

<?php $sql="SELECT * FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id

where fev.field_id = 148 /*or field_id = 149*/ and fev.value is not null and length(fev.value) > 7 " ?>
     
</script>

