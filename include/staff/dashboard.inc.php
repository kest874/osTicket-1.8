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
        <div class="portlet" id="IncidentsbyLocation" ><!-- /primary heading -->
            
        </div>
    </div>
    <div class="col-lg-6">
        <div class="portlet" id="RecordablesbyLocation" ><!-- /primary heading -->
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="portlet" id="DartbyLocation"><!-- /primary heading -->
            
        </div>
    </div>
    <div class="col-lg-6">
        <div class="portlet" id="" ><!-- /primary heading -->
            
        </div>
    </div>
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

<div class="row">
    <div class="col-lg-6">
        <div class="portlet" id="injurytypebylocation" ><!-- /primary heading -->
            
        </div>
    </div>
     <div class="col-lg-6">
        <div class="portlet" id="locationbyinjurytype" ><!-- /primary heading -->
            
        </div>
    </div>
    
    
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="portlet" id="bodypartbylocation" ><!-- /primary heading -->
            
        </div>
    </div>
     <div class="col-lg-6">
        <div class="portlet" id="locationbybodypart" ><!-- /primary heading -->
            
        </div>
    </div>
    
    
</div>

<script>

<?php

$sql="select distinct concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by cat, location order by CALENDARYEAR, CALENDARWEEK";

$periods = db_query($sql);

$sql="select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by location order by location, CALENDARYEAR, CALENDARWEEK";

$locs = db_query($sql);

$sql="select sum(COUNT) as COUNT, concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 order by CALENDARYEAR, CALENDARWEEK)a

union all 
select 0 as COUNT,CALENDARWEEK,CALENDARYEAR, location from (select distinct CALENDARWEEK,CALENDARYEAR from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by CALENDARWEEK,CALENDARYEAR, location order by CALENDARYEAR, CALENDARWEEK)a join 

(select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by location order by location, CALENDARYEAR, CALENDARWEEK) b on 1= 1



)b

group by cat, location order by location, CALENDARYEAR, CALENDARWEEK";


$locsdata = db_query($sql);

?>

$(function () {
    Highcharts.chart('IncidentsbyLocation', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Incidents by location',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        xAxis: {
            categories: [<?php
      foreach ($periods as $period) {
                 
                 echo "'".preg_replace('/\s+/', ' ', $period["cat"])."',";
       }   
       ?>]
        },
        yAxis: {
            min: 0,
            title: {
                    text: 'Number of Incidents'
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
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
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
                formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        
        series: [
        
         <?php
        foreach ($locs as $loc) { ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            data: [<?php foreach ($locsdata as $locdata) {
                if ($locdata["location"] == $loc["location"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]
    });
});      


<?php
$sql="select distinct concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isrecordable = 1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by cat, location order by CALENDARYEAR, CALENDARWEEK";

$periods = db_query($sql);

$sql="select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isrecordable = 1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by location order by location, CALENDARYEAR, CALENDARWEEK";

$locs = db_query($sql);

$sql="select sum(COUNT) as COUNT, concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isrecordable = 1 order by CALENDARYEAR, CALENDARWEEK)a

union all 
select 0 as COUNT,CALENDARWEEK,CALENDARYEAR, location from (select distinct CALENDARWEEK,CALENDARYEAR from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isrecordable = 1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by CALENDARWEEK,CALENDARYEAR, location order by CALENDARYEAR, CALENDARWEEK)a join 

(select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isrecordable = 1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by location order by location, CALENDARYEAR, CALENDARWEEK) b on 1= 1



)b

group by cat, location order by location, CALENDARYEAR, CALENDARWEEK";


$locsdata = db_query($sql);

?>

$(function () {
    Highcharts.chart('RecordablesbyLocation', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Recordables by location',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        xAxis: {
            categories: [<?php
      foreach ($periods as $period) {
                 
                 echo "'".preg_replace('/\s+/', ' ', $period["cat"])."',";
       }   
       ?>]
        },
        yAxis: {
            title: {
                text: 'Number of Recordables'
            }
        ,
            stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
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
                formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
            }
        }
    },
        
               series: [
        
         <?php
        foreach ($locs as $loc) { ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            data: [<?php foreach ($locsdata as $locdata) {
                if ($locdata["location"] == $loc["location"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]
    });
});   

<?php
$sql="select distinct concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isdart = 1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by cat, location order by CALENDARYEAR, CALENDARWEEK";

$periods = db_query($sql);

$sql="select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isdart = 1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by location order by location, CALENDARYEAR, CALENDARWEEK";

$locs = db_query($sql);

$sql="select sum(COUNT) as COUNT, concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isdart = 1 order by CALENDARYEAR, CALENDARWEEK)a

union all 
select 0 as COUNT,CALENDARWEEK,CALENDARYEAR, location from (select distinct CALENDARWEEK,CALENDARYEAR from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isdart = 1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by CALENDARWEEK,CALENDARYEAR, location order by CALENDARYEAR, CALENDARWEEK)a join 

(select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (

select d.name as location ,month(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(tc.dateofincident) - MOD(TO_DAYS(tc.dateofincident) - 2, 7))) AS CALENDARYEAR

from

ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isdart = 1 order by CALENDARYEAR, CALENDARWEEK)a)b

group by location order by location, CALENDARYEAR, CALENDARWEEK) b on 1= 1



)b

group by cat, location order by location, CALENDARYEAR, CALENDARWEEK";


$locsdata = db_query($sql);

?>

$(function () {
    Highcharts.chart('DartbyLocation', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Days Away Restricted or Transferred by location',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        xAxis: {
            categories: [<?php
      foreach ($periods as $period) {
                 
                 echo "'".preg_replace('/\s+/', ' ', $period["cat"])."',";
       }   
       ?>]
        },
        yAxis: {
            title: {
                text: ''
            }
        ,
            stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
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
                formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
            }
        }
    },
        
               series: [
        
         <?php
        foreach ($locs as $loc) { ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            data: [<?php foreach ($locsdata as $locdata) {
                if ($locdata["location"] == $loc["location"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]
    });
});

<?php
    $sql="select distinct topic from (SELECT count(ticket_id) as incidents, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic) a";
        
    $topics = db_query($sql);
    
    $sql="select distinct location from (SELECT count(ticket_id) as incidents, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic) a order by location";
        
    $locs = db_query($sql);
    

    $sql="select sum(count) as COUNT, location, topic from (SELECT count(ticket_id) as COUNT, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic 
     
     union all 
     
    select 0 as COUNT, d.location,  ht.topic from ost_help_topic ht join (
    
    select distinct location from (SELECT count(ticket_id) as incidents, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic) a
    
    ) d on 1=1)data
    
    group by location, topic";
        
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
        align: 'center',
        verticalAlign: 'bottom',
        x: 0,
        y: 0,
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
                formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
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
    group by d.name, ht.topic) a order by topic";
        
    $topics = db_query($sql);
    
    $sql="select distinct location from (SELECT count(ticket_id) as incidents, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic) a order by location";
        
    $locs = db_query($sql);
    

    $sql="select sum(count) as COUNT, location, topic from (SELECT count(ticket_id) as COUNT, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic 
     
     union all 
     
    select 0 as COUNT, d.location,  ht.topic from ost_help_topic ht join (
    
    select distinct location from (SELECT count(ticket_id) as incidents, d.name as location, ht.topic   
    FROM ost_ticket t join ost_department d on t.dept_id = d.id join ost_help_topic ht on ht.topic_id = t.topic_id
    group by d.name, ht.topic) a
    
    ) d on 1=1)data
    
    group by location, topic";
        
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
            formatter: function(){
        var val = this.total;
        if (val > 0) {
            return val;
        }
        return '';
    },
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
       align: 'center',
        verticalAlign: 'bottom',
        x: 0,
        y: 0,
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
                  formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                
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

<?php 

$sql="select distinct name as location from (
SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )a";

$locs = db_query($sql);

$sql="select distinct value as injurytype  from (
SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )a order by injurytype";

$injurytypes = db_query($sql);


$sql="select sum(COUNT) as COUNT, injurytype, location from 
	(select count(value) as COUNT, value as injurytype, name as location from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )a

	group by location, value 

	union

	select 0 as COUNT, injurytype, location  from 
	(select distinct name as location from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )l)loc join


	(select distinct value as injurytype  from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )b )bod on  1=1) data
    
	group by injurytype, location order by location, injurytype
";

$locsdata = db_query($sql);

 ?>


$(function () {
Highcharts.chart('injurytypebylocation', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Injury Type by Location',
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
  foreach ($injurytypes as $injurytype) {
             
             echo "'".preg_replace('/\s+/', ' ', $injurytype["injurytype"])."',";
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
            formatter: function(){
        var val = this.total;
        if (val > 0) {
            return val;
        }
        return '';
    },
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
       align: 'center',
        verticalAlign: 'bottom',
        x: 0,
        y: 0,
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
                  formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                
            }
        }
    },
    series: [<?php
        foreach ($locs as $loc) { ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            data: [<?php foreach ($locsdata as $locdata) {

                if ($loc["location"] == $locdata["location"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>]
 });
});      


<?php 

$sql="select distinct name as location from (
SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )a order by location";

$locs = db_query($sql);

$sql="select distinct value as injurytype  from (
SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )a order by injurytype";

$injurytypes = db_query($sql);


$sql="select sum(COUNT) as COUNT, injurytype, location from 
	(select count(value) as COUNT, value as injurytype, name as location from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )a

	group by location, value 

	union

	select 0 as COUNT, injurytype, location  from 
	(select distinct name as location from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )l)loc join


	(select distinct value as injurytype  from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 149 and fev.value is not null and length(fev.value) > 7 )b )bod on  1=1) data
    
	group by injurytype, location order by location, injurytype
";

$locsdata = db_query($sql);

 ?>


$(function () {
Highcharts.chart('locationbyinjurytype', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Location by Injury Type',
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
  foreach ($locs as $loc) {
             
             echo "'".preg_replace('/\s+/', ' ', $loc["location"])."',";
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
            formatter: function(){
        var val = this.total;
        if (val > 0) {
            return val;
        }
        return '';
    },
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
       align: 'center',
        verticalAlign: 'bottom',
        x: 0,
        y: 0,
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
                  formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                
            }
        }
    },
    series: [<?php
        foreach ($injurytypes as $injurytype) { ?>
        
        {
            name: '<?php echo $injurytype["injurytype"]?>',
            data: [<?php foreach ($locsdata as $locdata) {

                if ($injurytype["injurytype"] == $locdata["injurytype"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>]
 });
}); 




<?php 

$sql="select distinct name as location from (
SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )a";

$locs = db_query($sql);

$sql="select distinct value as bodypart  from (
SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )a order by bodypart";

$bodyparts = db_query($sql);


$sql="select sum(COUNT) as COUNT, bodypart, location from 
	(select count(value) as COUNT, value as bodypart, name as location from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )a

	group by location, value 

	union

	select 0 as COUNT, bodypart, location  from 
	(select distinct name as location from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )l)loc join


	(select distinct value as bodypart  from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )b )bod on  1=1) data
    
	group by bodypart, location order by location, bodypart
";

$locsdata = db_query($sql);

 ?>


$(function () {
Highcharts.chart('bodypartbylocation', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Primary Body Part by Location',
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
  foreach ($bodyparts as $bodypart) {
             
             echo "'".preg_replace('/\s+/', ' ', $bodypart["bodypart"])."',";
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
            formatter: function(){
        var val = this.total;
        if (val > 0) {
            return val;
        }
        return '';
    },
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
       align: 'center',
        verticalAlign: 'bottom',
        x: 0,
        y: 0,
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
                  formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                
            }
        }
    },
    series: [<?php
        foreach ($locs as $loc) { ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            data: [<?php foreach ($locsdata as $locdata) {

                if ($loc["location"] == $locdata["location"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>]
 });
});      


<?php 

$sql="select distinct name as location from (
SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )a order by location";

$locs = db_query($sql);

$sql="select distinct value as bodypart  from (
SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )a order by bodypart";

$bodyparts = db_query($sql);


$sql="select sum(COUNT) as COUNT, bodypart, location from 
	(select count(value) as COUNT, value as bodypart, name as location from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )a

	group by location, value 

	union

	select 0 as COUNT, bodypart, location  from 
	(select distinct name as location from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )l)loc join


	(select distinct value as bodypart  from (
	SELECT left(right(fev.value,length(fev.value) - instr(fev.value,':')-1),length(right(fev.value,length(fev.value) - instr(fev.value,':')-1))-2) as value, d.name
	FROM ost_form_entry_values fev  join ost_form_entry fe on fe.id = fev.entry_id join ost_ticket t on fe.object_id = t.ticket_id join ost_department d on t.dept_id = d.id

	where fev.field_id = 148 and fev.value is not null and length(fev.value) > 7 )b )bod on  1=1) data
    
	group by bodypart, location order by location, bodypart
";

$locsdata = db_query($sql);

 ?>


$(function () {
Highcharts.chart('locationbybodypart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Location by Primary Body Part',
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
  foreach ($locs as $loc) {
             
             echo "'".preg_replace('/\s+/', ' ', $loc["location"])."',";
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
            formatter: function(){
        var val = this.total;
        if (val > 0) {
            return val;
        }
        return '';
    },
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
       align: 'center',
        verticalAlign: 'bottom',
        x: 0,
        y: 0,
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
                  formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                
            }
        }
    },
    series: [<?php
        foreach ($bodyparts as $bodypart) { ?>
        
        {
            name: '<?php echo $bodypart["bodypart"]?>',
            data: [<?php foreach ($locsdata as $locdata) {

                if ($bodypart["bodypart"] == $locdata["bodypart"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>]
 });
});           
</script>

