<script src="<?php echo ROOT_PATH; ?>scp/js/highcharts.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/highcharts-3d.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/exporting.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/export-data.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/pareto.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/no-data-to-display.js"></script>

<?php TicketForm::ensureDynamicDataView(); 
$year = $_GET['y'];

if (isset($year)) {}else{$year= date("Y");}

$sql = "SELECT distinct year FROM ost_hours";
$syears = db_query($sql);
$cyear=0;
foreach ($syears as $syear) {
	
	if ($syear['year']==date("Y")) $cyear=1;
}

?>

<div class="subnav">

    <div class="float-left subnavtitle">
                          
    <?php echo __('IT Dashboard');   ?>                        
    
    </div>
    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
		
		<?php foreach ($syears as $syear) { 
		
		if ($year == $syear['year']){$class = 'btn-success';} else {$class = 'btn-secondary';}
		?>
				<a class="btn btn-icon waves-effect waves-light <?php echo $class;?>" id="btn<?php echo $syear['year'];?>" data-placement="bottom" data-toggle="tooltip" title="" href="/scp/dashboard.php?y=<?php echo $syear['year'];?>" data-original-title="Year"><?php echo $syear['year'];?></i></a>
		
		<?php }?>
		
		
		<?php if ($cyear ==0){ 
			if ($year == date("Y")){$class = 'btn-success';} else {$class = 'btn-secondary';}?>
		<a class="btn btn-icon waves-effect waves-light <?php echo $class;?>" id="btn<?php echo date("Y");?>" data-placement="bottom" data-toggle="tooltip" title="" href="/scp/dashboard.php?y=<?php echo date("Y");?>" data-original-title="Year"><?php echo date("Y");?></i></a>
		
		<?php } ?>
		
      </div>   
   <div class="clearfix"></div> 
</div> 
<div class="row">
    <div class="col-lg-12">		
	
	    
        <div class="portlet" id="IncidentRate" ><!-- /primary heading -->
            
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="portlet" id="DartRate" ><!-- /primary heading -->
            
        </div>
    </div>
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
<div class="row">
    <div class="col-lg-12">
        <div class="portlet" id="associateincidents" ><!-- /primary heading -->
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="portlet" id="associaterecordables" ><!-- /primary heading -->
            
        </div>
    </div>
</div>

<script>
<?php
$sitecolor = array(
"BRY"=>"#ff5252",
"CAN"=>"rgb(241, 92, 128)",
"IND"=>"#e040fb",
"MEX"=>"#7c4dff",
"NTC"=>"rgb(43, 144, 143)",
"OH"=>"rgb(67, 67, 72)",
"PAU"=>"#40c4ff",
"RTA"=>"#18ffff",
"RVC"=>"rgb(247, 163, 92)",
"TNN1"=>"#69f0ae",
"TNN2"=>"rgb(124, 181, 236)",
"TNS"=>"#eeff41",
"YTD"=>"#c30000");
$sql="select distinct concat(DATE_FORMAT(STR_TO_DATE(CALENDARWEEK, '%m'), '%b'),' ',CALENDARYEAR) as cat from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by cat, location order by CALENDARYEAR, CALENDARWEEK";
$periods = db_query($sql);
$sql="select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by location order by location, CALENDARYEAR, CALENDARWEEK";
$locs = db_query($sql);
$sql="select sum(COUNT) as COUNT, concat(DATE_FORMAT(STR_TO_DATE(CALENDARWEEK, '%m'), '%b'),' ',CALENDARYEAR) as cat, location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a
union all 
select 0 as COUNT,CALENDARWEEK,CALENDARYEAR, location from (select distinct CALENDARWEEK,CALENDARYEAR from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by CALENDARWEEK,CALENDARYEAR, location order by CALENDARYEAR, CALENDARWEEK)a join 
(select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by location order by location, CALENDARYEAR, CALENDARWEEK) b on 1= 1)b
group by cat, location order by location, CALENDARYEAR, CALENDARWEEK";
$locsdata = db_query($sql);
$sql = "select sum(count) as COUNT,CALENDARWEEK,cat from(select sum(COUNT) as COUNT, concat(DATE_FORMAT(STR_TO_DATE(CALENDARWEEK, '%m'), '%b'),' ',CALENDARYEAR) as cat, location,CALENDARWEEK from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a
union all 
select 0 as COUNT,CALENDARWEEK,CALENDARYEAR, location from (select distinct CALENDARWEEK,CALENDARYEAR from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by CALENDARWEEK,CALENDARYEAR, location order by CALENDARYEAR, CALENDARWEEK)a join 
(select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by location order by location, CALENDARYEAR, CALENDARWEEK) b on 1= 1)b
group by cat, location order by CALENDARWEEK,CALENDARYEAR )tot
group by cat order by CALENDARWEEK";
$monthtotals = db_query($sql);
?>
$(function () {
    Highcharts.chart('IncidentsbyLocation', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Incidents by location (<?php echo $year;?>)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        xAxis: {
            categories: [<?php
      foreach ($periods as $period) {
                 
                 echo "'".preg_replace('/\s+/', ' ', $period["cat"])."',";
       }   
       ?>]
        },
        yAxis: [{
            min: 0,
            title: {
                    text: 'Number of Incidents YTD'
                },
                 opposite: true,
            stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
        },{ // Secondary yAxis
        title: {
            text: 'Number of Incidents',
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        },
        labels: {
            style: {
                 color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        },
       
    }],
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
           
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
        foreach ($locs as $loc) { 
        
        $key = $loc["location"];
        $color = $sitecolor[$key];
        ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            yAxis: 1,
            color: '<?php echo $color; ?>',
            data: [<?php foreach ($locsdata as $locdata) {
                if ($locdata["location"] == $loc["location"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        
        {
            name: 'YTD',
            type: 'spline',
            color: 'red',           
            data: [
             <?php
        $p=0;
        foreach ($monthtotals as $monthtotal) { 
            $c = $monthtotal["COUNT"];
            $cu= $p+$c;
            echo $cu.',';
            $p = $cu;
        } ?>]
            
        }
        ]
    });
});      
<?php
$sql="select distinct concat(DATE_FORMAT(STR_TO_DATE(CALENDARWEEK, '%m'), '%b'),' ',CALENDARYEAR) as cat from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isrecordable = 1  and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by cat, location order by CALENDARYEAR, CALENDARWEEK";
$periods = db_query($sql);
$sql="select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isrecordable = 1  and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."'  order by CALENDARYEAR, CALENDARWEEK)a)b
group by location order by location, CALENDARYEAR, CALENDARWEEK";
$locs = db_query($sql);
$sql="select sum(COUNT) as COUNT, concat(DATE_FORMAT(STR_TO_DATE(CALENDARWEEK, '%m'), '%b'),' ',CALENDARYEAR) as cat, location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isrecordable = 1  and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."'  order by CALENDARYEAR, CALENDARWEEK)a
union all 
select 0 as COUNT,CALENDARWEEK,CALENDARYEAR, location from (select distinct CALENDARWEEK,CALENDARYEAR from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isrecordable = 1  and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."'  order by CALENDARYEAR, CALENDARWEEK)a)b
group by CALENDARWEEK,CALENDARYEAR, location order by CALENDARYEAR, CALENDARWEEK)a join 
(select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isrecordable = 1  and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."'  order by CALENDARYEAR, CALENDARWEEK)a)b
group by location order by location, CALENDARYEAR, CALENDARWEEK) b on 1= 1)b
group by cat, location order by location, CALENDARYEAR, CALENDARWEEK";
$locsdata = db_query($sql);
$sql = "select sum(count) as COUNT,CALENDARWEEK,cat from(select sum(COUNT) as COUNT, concat(DATE_FORMAT(STR_TO_DATE(CALENDARWEEK, '%m'), '%b'),' ',CALENDARYEAR) as cat, location,CALENDARWEEK from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isrecordable = 1  and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."'  order by CALENDARYEAR, CALENDARWEEK)a
union all 
select 0 as COUNT,CALENDARWEEK,CALENDARYEAR, location from (select distinct CALENDARWEEK,CALENDARYEAR from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isrecordable = 1  and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."'  order by CALENDARYEAR, CALENDARWEEK)a)b
group by CALENDARWEEK,CALENDARYEAR, location order by CALENDARYEAR, CALENDARWEEK)a join 
(select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isrecordable = 1  and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."'  order by CALENDARYEAR, CALENDARWEEK)a)b
group by location order by location, CALENDARYEAR, CALENDARWEEK) b on 1= 1)b
group by cat, location order by location, CALENDARYEAR, CALENDARWEEK )tot
group by cat order by CALENDARWEEK";
$monthtotals = db_query($sql);
?>
$(function () {
    Highcharts.chart('RecordablesbyLocation', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Recordables by location (<?php echo $year;?>)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        xAxis: {
            categories: [<?php
      foreach ($periods as $period) {
                 
                 echo "'".preg_replace('/\s+/', ' ', $period["cat"])."',";
       }   
       ?>]
        },
        yAxis: [{
            title: {
                text: 'Number of Recordables YTD'
            },
            opposite: true,
            stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
        },{ // Secondary yAxis
        title: {
            text: 'Number of Recordables',
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        },
        labels: {
            style: {
                 color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        },
       
        }],
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
           
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
                    if (val < 1) {
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
        foreach ($locs as $loc) {  
        
        $key = $loc["location"];
        $color = $sitecolor[$key];
        
        ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            yAxis: 1,
            color: '<?php echo $color; ?>',
            data: [<?php foreach ($locsdata as $locdata) {
                if ($locdata["location"] == $loc["location"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        
        {
            name: 'YTD',
            type: 'spline',
            color: 'red',           
            data: [
             <?php
        $p=0;
        foreach ($monthtotals as $monthtotal) { 
            $c = $monthtotal["COUNT"];
            $cu= $p+$c;
            echo $cu.',';
            $p = $cu;
        } ?>]
            
        }
        ]
    });
});   
<?php
$sql="select distinct concat(DATE_FORMAT(STR_TO_DATE(CALENDARWEEK, '%m'), '%b'),' ',CALENDARYEAR) as cat from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isdart = 1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by cat, location order by CALENDARYEAR, CALENDARWEEK";
$periods = db_query($sql);
$sql="select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isdart = 1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by location order by location, CALENDARYEAR, CALENDARWEEK";
$locs = db_query($sql);
$sql="select sum(COUNT) as COUNT, concat(DATE_FORMAT(STR_TO_DATE(CALENDARWEEK, '%m'), '%b'),' ',CALENDARYEAR) as cat, location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isdart = 1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a
union all 
select 0 as COUNT,CALENDARWEEK,CALENDARYEAR, location from (select distinct CALENDARWEEK,CALENDARYEAR from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isdart = 1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by CALENDARWEEK,CALENDARYEAR, location order by CALENDARYEAR, CALENDARWEEK)a join 
(select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isdart = 1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by location order by location, CALENDARYEAR, CALENDARWEEK) b on 1= 1)b
group by cat, location order by location, CALENDARYEAR, CALENDARWEEK";
$locsdata = db_query($sql);
$sql = "select sum(count) as COUNT,CALENDARWEEK,cat from(select sum(COUNT) as COUNT, concat(DATE_FORMAT(STR_TO_DATE(CALENDARWEEK, '%m'), '%b'),' ',CALENDARYEAR) as cat, location,CALENDARWEEK from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isdart = 1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a
union all 
select 0 as COUNT,CALENDARWEEK,CALENDARYEAR, location from (select distinct CALENDARWEEK,CALENDARYEAR from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1 and t.isdart = 1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by CALENDARWEEK,CALENDARYEAR, location order by CALENDARYEAR, CALENDARWEEK)a join 
(select distinct location from
(select 1 as COUNT, CALENDARWEEK, CALENDARYEAR, location from (
select d.name as location ,month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARWEEK,YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR
from
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id where length(tc.dateofincident)>1  and t.isdart = 1 and YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = '".$year."' order by CALENDARYEAR, CALENDARWEEK)a)b
group by location order by location, CALENDARYEAR, CALENDARWEEK) b on 1= 1)b
group by cat, location order by location, CALENDARYEAR, CALENDARWEEK)tot
group by cat order by CALENDARWEEK";
$monthtotals = db_query($sql);
?>
$(function () {
    Highcharts.chart('DartbyLocation', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Days Away Restricted or Transfered by location (<?php echo $year;?>)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        xAxis: {
            categories: [<?php
      foreach ($periods as $period) {
                 
                 echo "'".preg_replace('/\s+/', ' ', $period["cat"])."',";
       }   
       ?>]
        },
        yAxis: [{
            title: {
                text: 'Number of Days Away Restricted or Transfered YTD'
            },
            opposite: true,
            stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
        },{ // Secondary yAxis
        title: {
            text: 'Number of Days Away Restricted or Transfered',
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        },
        labels: {
            style: {
                 color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        },
       
        }],
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
           
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
                    if (val < 1) {
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
        foreach ($locs as $loc) { 
        
        $key = $loc["location"];
        $color = $sitecolor[$key];
        
        ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            yAxis: 1,
            color: '<?php echo $color; ?>',
            data: [<?php foreach ($locsdata as $locdata) {
                if ($locdata["location"] == $loc["location"]) echo $locdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        
        {
            name: 'YTD',
            type: 'spline',
            color: 'red',           
            data: [
             <?php
        $p=0;
        foreach ($monthtotals as $monthtotal) { 
            $c = $monthtotal["COUNT"];
            $cu= $p+$c;
            echo $cu.',';
            $p = $cu;
        } ?>]
            
        }
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
        foreach ($locs as $loc) { 
        
        $key = $loc["location"];
        $color = $sitecolor[$key];
        
        ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            color: '<?php echo $color; ?>',
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
        foreach ($locs as $loc) { 
        
        $key = $loc["location"];
        $color = $sitecolor[$key];
        
        ?>
        
        {
            name: '<?php echo $loc["location"]?>',
            color: '<?php echo $color; ?>',
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
        foreach ($locs as $loc) { 
        
        $key = $loc["location"];
        $color = $sitecolor[$key];
       
        ?>
        
        {
            name: '<?php echo $loc["location"]?>', 
            color: '<?php echo $color; ?>',
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
<?php
$sql="select *,
case

when location = 'BRY' then '#ff5252'
when location = 'CAN' then 'rgb(241 92 128)'
when location = 'IND' then '#e040fb'
when location = 'MEX' then '#7c4dff'
when location = 'NTC' then 'rgb(43 144 143)'
when location = 'OH' then 'rgb(67 67 72)'
when location = 'PAU' then '#40c4ff'
when location = 'RTA' then '#18ffff'
when location = 'RVC' then 'rgb(247 163 92)'
when location = 'TNN1' then '#69f0ae'
when location = 'TNN2' then 'rgb(124 181 236)'
when location = 'TNS' then '#eeff41'
when location = 'YTD' then '#c30000'
end as color
 from  (
	select sum(count) as COUNT, lastname, location from (

	select  count(topic) as COUNT, topic, value as lastname, location from (

		SELECT ht.topic, concat(left(left(right(a.value,length(a.value) - instr(a.value,':')),length(right(a.value,length(a.value) - instr(a.value,':')))),1),'. ',
		 left(right(b.value,length(b.value) - instr(b.value,':')),length(right(b.value,length(b.value) - instr(b.value,':'))))) as value, d.name as LOCATION
		 FROM ost_form_entry_values a join ost_form_entry_values b on a.entry_id = b.entry_id and a.field_id = 38 and b.field_id = 329 
		 join ost_form_entry e on a.entry_id = e.id join ost_ticket t on e.object_id = t.ticket_id join ost_help_topic ht on t.topic_id = ht.topic_id join ost_department d on t.dept_id = d.id where t.isrecordable = 1
	 
		)a
	 group by topic, lastname, location
	 
	 )a  
	  group by lastname, location 
)c where count >1  order by location,count desc";
$tresults = db_query($sql); 
?>
$(function() {        
 Highcharts.chart('associaterecordables', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Recordables by Associate > 1',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
    },
    tooltip: {
            formatter: function () {
                
                if (this.point.location !== undefined ){
                    return  '<b>'+this.point.name +'<br>'+ ' Total: <b>' + this.point.y + '<br>Location: <b>' + this.point.location;
                }
            }
    },
    plotOptions: {
        area: {
                events: {
                legendItemClick: function () {
                    return false; 
                }
            }
        },
        allowPointSelect: false,
    },
    xAxis: {
        categories: [<?php foreach ($tresults as $tresult) {echo "\"".str_replace("'","\'",$tresult['lastname'])."\",";}?>]
    },
    yAxis: {
        title: {
            text: 'Number of Recordables'
            }
        },
        minPadding: 0,
        maxPadding: 0,
        max: 100,
        min: 0,
        opposite: true,
        labels: {
            format: "{value}%"
        }
    ,
    credits: false,
    series: [{        name: 'Recordables',
        type: 'column',
        data: [<?php foreach ($tresults as $tresult) {?>
            
            {y: <?php echo $tresult['COUNT']; ?>,
            name: '<?php echo str_replace("'","\'",$tresult["lastname"])?>',
            color: '<?php echo $tresult["color"]?>',
            location: '<?php echo $tresult["location"];?>',
            },
            
                <?php } ?>]
    },{type: 'area',
           name: 'BRY',
           color: '#ff5252'},{type: 'area',
           name: 'CAN',
           color: 'rgb(241, 92, 128)'},{type: 'area',
           name: 'IND',
           color: '#e040fb'},{type: 'area',
           name: 'MEX',
           color: '#7c4dff'},{type: 'area',
           name: 'NTC',
           color: 'rgb(43, 144, 143)'},{type: 'area',
           name: 'OH',
           color: 'rgb(67, 67, 72)'},{type: 'area',
           name: 'PAU',
           color: '#40c4ff'},{type: 'area',
           name: 'RTA',
           color: '#18ffff'},{type: 'area',
           name: 'RVC',
           color: 'rgb(247, 163, 92)'},{type: 'area',
           name: 'TNN1',
           color: '#69f0ae'},{type: 'area',
           name: 'TNN2',
           color: 'rgb(124, 181, 236)'},{type: 'area',
           name: 'TNS',
           color: '#eeff41'}]
});
});      
 
<?php

$sql="select sum(COUNT) as COUNT, topic, lastname, location from(select  count(topic) as COUNT, 
case

when topic = 'Injury/Illness' then 'injuryillness'
when topic = 'Near-miss' then 'nearmiss'
when topic = 'Property Damage' then 'propertydamage'
when topic = 'Spill' then 'spill'

end as topic

, value as lastname, location from (

		SELECT ht.topic, concat(left(left(right(a.value,length(a.value) - instr(a.value,':')),length(right(a.value,length(a.value) - instr(a.value,':')))),1),'. ',
		 left(right(b.value,length(b.value) - instr(b.value,':')),length(right(b.value,length(b.value) - instr(b.value,':'))))) as value, d.name as location
		 FROM ost_form_entry_values a join ost_form_entry_values b on a.entry_id = b.entry_id and a.field_id = 38 and b.field_id = 329 
		 join ost_form_entry e on a.entry_id = e.id join ost_ticket t on e.object_id = t.ticket_id join ost_help_topic ht on t.topic_id = ht.topic_id join ost_department d on t.dept_id = d.id
	 
		)a group by topic, lastname, location
        
union all

select 0 as COUNT,topic, value as lastname, location from (SELECT distinct concat(left(left(right(a.value,length(a.value) - instr(a.value,':')),length(right(a.value,length(a.value) - instr(a.value,':')))),1),'. ',
		 left(right(b.value,length(b.value) - instr(b.value,':')),length(right(b.value,length(b.value) - instr(b.value,':'))))) as value, d.name as location
		 FROM ost_form_entry_values a join ost_form_entry_values b on a.entry_id = b.entry_id and a.field_id = 38 and b.field_id = 329 
		 join ost_form_entry e on a.entry_id = e.id join ost_ticket t on e.object_id = t.ticket_id join ost_help_topic ht on t.topic_id = ht.topic_id join ost_department d on t.dept_id = d.id) a
         
join (         
         
SELECT distinct case

when topic = 'Injury/Illness' then 'injuryillness'
when topic = 'Near-miss' then 'nearmiss'
when topic = 'Property Damage' then 'propertydamage'
when topic = 'Spill' then 'spill'

end as topic
		 FROM ost_form_entry_values a join ost_form_entry_values b on a.entry_id = b.entry_id and a.field_id = 38 and b.field_id = 329 
		 join ost_form_entry e on a.entry_id = e.id join ost_ticket t on e.object_id = t.ticket_id join ost_help_topic ht on t.topic_id = ht.topic_id join ost_department d on t.dept_id = d.id) b on 1=1    )data
         group by topic, lastname, location";

$topics = db_query($sql); 

$sql="select *,
case

when location = 'BRY' then '#ff5252'
when location = 'CAN' then 'rgb(241 92 128)'
when location = 'IND' then '#e040fb'
when location = 'MEX' then '#7c4dff'
when location = 'NTC' then 'rgb(43 144 143)'
when location = 'OH' then 'rgb(67 67 72)'
when location = 'PAU' then '#40c4ff'
when location = 'RTA' then '#18ffff'
when location = 'RVC' then 'rgb(247 163 92)'
when location = 'TNN1' then '#69f0ae'
when location = 'TNN2' then 'rgb(124 181 236)'
when location = 'TNS' then '#eeff41'
when location = 'YTD' then '#c30000'
end as color
 from  (
	select sum(count) as COUNT, lastname, location from (

	select  count(topic) as COUNT, topic, value as lastname, location from (

		SELECT ht.topic, concat(left(left(right(a.value,length(a.value) - instr(a.value,':')),length(right(a.value,length(a.value) - instr(a.value,':')))),1),'. ',
		 left(right(b.value,length(b.value) - instr(b.value,':')),length(right(b.value,length(b.value) - instr(b.value,':'))))) as value, d.name as LOCATION
		 FROM ost_form_entry_values a join ost_form_entry_values b on a.entry_id = b.entry_id and a.field_id = 38 and b.field_id = 329 
		 join ost_form_entry e on a.entry_id = e.id join ost_ticket t on e.object_id = t.ticket_id join ost_help_topic ht on t.topic_id = ht.topic_id join ost_department d on t.dept_id = d.id
	 
		)a
	 group by topic, lastname, location
	 
	 )a  
	  group by lastname, location 
)c where count >1  order by location,count desc";
$tresults = db_query($sql); 
?>
$(function() {        
     Highcharts.chart('associateincidents', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Incidents/Observations by Associate > 1',
                style: {
                color: '#797979',
                fontSize: '14px',
                fontWeight: '600',
                }
        },
        tooltip: {
            formatter: function () {
                
                if (this.point.location !== undefined ){
                    return  '<b>'+this.point.name +'<br>'+ ' Total: <b>' + this.point.y + '<br>Location: <b>' + this.point.location + '<br>Injury/Illness: <b>' + this.point.injuryillness+ '<br>Near-Miss: <b>' + this.point.nearmiss+ '<br>Propery Damage: <b>' + this.point.propertydamage+ '<br>Spill: <b>' + this.point.spill;
                }
            }
        },
        // legend: {
        // useHTML: true,
        // labelFormatter: function () {
            // return '<span title="' + this.color + '">' + this.color + '</span>';
        // }
        // },
        plotOptions: {
        area: {
                events: {
                legendItemClick: function () {
                    return false; 
                }
            }
        },
        allowPointSelect: false,
        },
        xAxis: {
            categories: [<?php foreach ($tresults as $tresult) {echo "\"".str_replace("'","\'",$tresult['lastname'])."\",";}?>]
        },
        yAxis: {
            title: {
                text: 'Number of Incidents'
                }
            },
            minPadding: 0,
            maxPadding: 0,
            max: 100,
            min: 0,
            opposite: true,
            labels: {
                format: "{value}%"
            }
        ,
        credits: false,
        series: [{        
            showInLegend: false,
            name: 'Incidents',
            type: 'column',
            data: [
            <?php foreach ($tresults as $tresult) {?>
            
            {y: <?php echo $tresult['COUNT']; ?>,
            name: '<?php echo str_replace("'","\'",$tresult["lastname"])?>',
            color: '<?php echo $tresult["color"]?>',
            location: '<?php echo $tresult["location"];?>',
            <?php foreach ($topics as $topic) {
             if ($tresult['lastname'] == $topic["lastname"] && $tresult['location'] == $topic["location"]) {   
                  echo $topic['topic'].": '".$topic['COUNT']."',";
             }
            } ?>   
            
            },
            
                <?php } ?>]
        },{type: 'area',
           name: 'BRY',
           color: '#ff5252'},{type: 'area',
           name: 'CAN',
           color: 'rgb(241, 92, 128)'},{type: 'area',
           name: 'IND',
           color: '#e040fb'},{type: 'area',
           name: 'MEX',
           color: '#7c4dff'},{type: 'area',
           name: 'NTC',
           color: 'rgb(43, 144, 143)'},{type: 'area',
           name: 'OH',
           color: 'rgb(67, 67, 72)'},{type: 'area',
           name: 'PAU',
           color: '#40c4ff'},{type: 'area',
           name: 'RTA',
           color: '#18ffff'},{type: 'area',
           name: 'RVC',
           color: 'rgb(247, 163, 92)'},{type: 'area',
           name: 'TNN1',
           color: '#69f0ae'},{type: 'area',
           name: 'TNN2',
           color: 'rgb(124, 181, 236)'},{type: 'area',
           name: 'TNS',
           color: '#eeff41'}]
    });
});     
<?php

$sql="select distinct d.name as location
from 
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id  join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

where isrecordable = 1 or isdart = 1 order by d.name";

$locs = db_query($sql);

$sql="select sum(data.recordables) as recordables, h.hours, data.location, data.MONTHNAME,data.MONTHNUM,data.CALENDARYEAR from (

	select sum(isrecordable)as recordables,0 as hours, d.name as location,
	DATE_FORMAT(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'),'%b') AS MONTHNAME,
	month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS MONTHNUM,
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR 
	from 
	ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id  join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

	where isrecordable = 1

	group by d.name, month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')),YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'))

	union

	select 0 as recordables,0 as hours, a.location, mon.MONTHNAME, mon.MONTH as MONTHNUM,  '".$year."' as CALENDARYEAR from (select distinct d.name as location
	from 
	ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

	where isrecordable = 1 order by d.name)a

	join 

	(SELECT 1 AS MONTH, 'Jan' as MONTHNAME
	   UNION SELECT 2 AS MONTH, 'Feb' as MONTHNAME
	   UNION SELECT 3 AS MONTH, 'Mar' as MONTHNAME
	   UNION SELECT 4 AS MONTH, 'Apr' as MONTHNAME
	   UNION SELECT 5 AS MONTH, 'May' as MONTHNAME
	   UNION SELECT 6 AS MONTH, 'Jun' as MONTHNAME
	   UNION SELECT 7 AS MONTH, 'Jul' as MONTHNAME
	   UNION SELECT 8 AS MONTH, 'Aug' as MONTHNAME
	   UNION SELECT 9 AS MONTH, 'Sep' as MONTHNAME
	   UNION SELECT 10 AS MONTH, 'Oct' as MONTHNAME
	   UNION SELECT 11 AS MONTH, 'Nov' as MONTHNAME
	   UNION SELECT 12 AS MONTH, 'Dec' as MONTHNAME)mon on 1=1
	
	) data
join ost_hours h on data.location = h.location and data.MONTHNUM = h.month and '".$year."'=h.year
group by data.location, data.MONTHNAME,data.MONTHNUM,data.CALENDARYEAR order by data.location,data.MONTHNUM,data.CALENDARYEAR

";

$locsdata = db_query($sql); 

$sql="select sum(data.recordables) as recordables, h.hours, data.MONTHNAME,data.MONTHNUM,data.CALENDARYEAR from (

	select sum(isrecordable)as recordables,0 as hours, d.name as location,
	DATE_FORMAT(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'),'%b') AS MONTHNAME,
	month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS MONTHNUM,
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR 
	from 
	ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id  join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

	where isrecordable = 1

	group by d.name, month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')),YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'))

	union

	select 0 as recordables,0 as hours, a.location, mon.MONTHNAME, mon.MONTH as MONTHNUM,  '".$year."' as CALENDARYEAR from (select distinct d.name as location
	from 
	ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

	where isrecordable = 1 order by d.name)a

	join 

	(SELECT 1 AS MONTH, 'Jan' as MONTHNAME
	   UNION SELECT 2 AS MONTH, 'Feb' as MONTHNAME
	   UNION SELECT 3 AS MONTH, 'Mar' as MONTHNAME
	   UNION SELECT 4 AS MONTH, 'Apr' as MONTHNAME
	   UNION SELECT 5 AS MONTH, 'May' as MONTHNAME
	   UNION SELECT 6 AS MONTH, 'Jun' as MONTHNAME
	   UNION SELECT 7 AS MONTH, 'Jul' as MONTHNAME
	   UNION SELECT 8 AS MONTH, 'Aug' as MONTHNAME
	   UNION SELECT 9 AS MONTH, 'Sep' as MONTHNAME
	   UNION SELECT 10 AS MONTH, 'Oct' as MONTHNAME
	   UNION SELECT 11 AS MONTH, 'Nov' as MONTHNAME
	   UNION SELECT 12 AS MONTH, 'Dec' as MONTHNAME)mon on 1=1
	
	) data
join (SELECT sum(hours) as hours, month, year FROM ost_hours where '".$year."'=year group by month, year)h on h.month = data.MONTHNUM and h.year = data.CALENDARYEAR
group by data.MONTHNAME,data.MONTHNUM,data.CALENDARYEAR order by data.MONTHNUM,data.CALENDARYEAR

";

$totals = db_query($sql);
?>
$(function() {
    Highcharts.chart('IncidentRate', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Incident Rates (<?php echo $year;?>)'
        },
         // subtitle: {
             // text: 'Recordables'
         // },
        xAxis: {
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        credits: false,
        yAxis: {
            min: 0,
            title: {
                text: 'Incident Rate'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px"><b>{point.key}</b></span><br><table>',
            // pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                // '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
        <?php foreach ($locs as $loc){ 
            $key = $loc["location"];
            $color = $sitecolor[$key];
        ?>
        {
            name: '<?php echo $loc['location']; ?>',
            color: '<?php echo $color; ?>',
            data: [<?php foreach ($locsdata as $locdata){

                    if ($loc['location'] == $locdata['location'] ) {
                        
                        $r = $locdata['recordables'] + $r;
                        $h = $locdata['hours'] +$h;
                       if ($h !=0){ 
                       echo ROUND($r*200000/$h,2).',';
                       }else{
                        echo '0,';   
                       } 
                    }
         } ?>]

        },
         <?php  $h = null;
                $r = null;
            } ?>
        {
            name: 'Total',
            type: 'line',
            color: "#c30000",
            data: [<?php foreach ($totals as $total){
            
                        $r = $total['recordables'] + $r;
                        $h = $total['hours'] +$h;
                       if ($h !=0){ 
                       echo ROUND($r*200000/$h,2).',';
                       }else{
                        echo '0,';   
                       } 
            }?>
            ]
            
        },
        {
            name: 'IR Goal',
            type: 'line',
            data: [3.38,3.38,3.38,3.38,3.38,3.38,3.38,3.38,3.38,3.38,3.38,3.38],
        },
        {
            name: 'PMA Rate',
            type: 'line',
            data: [5.20,5.20,5.20,5.20,5.20,5.20,5.20,5.20,5.20,5.20,5.20,5.20],
        },
        {
            name: 'IR (2016: 336370)',
            type: 'line',
            data: [6.30,6.30,6.30,6.30,6.30,6.30,6.30,6.30,6.30,6.30,6.30,6.30],
        },
        {
            name: 'IR (2016: 336360)',
            type: 'line',
            data: [4.20,4.20,4.20,4.20,4.20,4.20,4.20,4.20,4.20,4.20,4.20,4.20],
        },
        ]
    });
}); 


<?php

$sql="select distinct d.name as location
from 
ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id  join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

where isrecordable = 1 or isdart = 1 order by d.name";

$locs = db_query($sql);

$sql="select sum(data.dart) as dart, h.hours, data.location, data.MONTHNAME,data.MONTHNUM,data.CALENDARYEAR from (

	select sum(isdart)as dart,0 as hours, d.name as location,
	DATE_FORMAT(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'),'%b') AS MONTHNAME,
	month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS MONTHNUM,
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR 
	from 
	ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id  join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

	where isdart = 1

	group by d.name, month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')),YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'))

	union

	select 0 as dart,0 as hours, a.location, mon.MONTHNAME, mon.MONTH as MONTHNUM,  ".$year." as CALENDARYEAR from (select distinct d.name as location
	from 
	ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

	where isdart = 1 order by d.name)a

	join 

	(SELECT 1 AS MONTH, 'Jan' as MONTHNAME
	   UNION SELECT 2 AS MONTH, 'Feb' as MONTHNAME
	   UNION SELECT 3 AS MONTH, 'Mar' as MONTHNAME
	   UNION SELECT 4 AS MONTH, 'Apr' as MONTHNAME
	   UNION SELECT 5 AS MONTH, 'May' as MONTHNAME
	   UNION SELECT 6 AS MONTH, 'Jun' as MONTHNAME
	   UNION SELECT 7 AS MONTH, 'Jul' as MONTHNAME
	   UNION SELECT 8 AS MONTH, 'Aug' as MONTHNAME
	   UNION SELECT 9 AS MONTH, 'Sep' as MONTHNAME
	   UNION SELECT 10 AS MONTH, 'Oct' as MONTHNAME
	   UNION SELECT 11 AS MONTH, 'Nov' as MONTHNAME
	   UNION SELECT 12 AS MONTH, 'Dec' as MONTHNAME)mon on 1=1
	
	) data
join ost_hours h on data.location = h.location and data.MONTHNUM = h.month and ".$year."=h.year
group by data.location, data.MONTHNAME,data.MONTHNUM,data.CALENDARYEAR order by data.location,data.MONTHNUM,data.CALENDARYEAR

";

$locsdata = db_query($sql); 

$sql="select sum(data.dart) as dart, h.hours, data.MONTHNAME,data.MONTHNUM,data.CALENDARYEAR from (

	select sum(isdart)as dart,0 as hours, d.name as location,
	DATE_FORMAT(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'),'%b') AS MONTHNAME,
	month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS MONTHNUM,
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) AS CALENDARYEAR 
	from 
	ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id  join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

	where isdart = 1

	group by d.name, month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')),YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'))

	union

	select 0 as dart,0 as hours, a.location, mon.MONTHNAME, mon.MONTH as MONTHNUM,  ".$year." as CALENDARYEAR from (select distinct d.name as location
	from 
	ost_ticket t join ost_department d on t.dept_id = d.id join ost_ticket__cdata tc on tc.ticket_id = t.ticket_id join ost_hours h on h.location = d.name and month(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.month and 
	YEAR(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = h.year

	where isdart = 1 order by d.name)a

	join 

	(SELECT 1 AS MONTH, 'Jan' as MONTHNAME
	   UNION SELECT 2 AS MONTH, 'Feb' as MONTHNAME
	   UNION SELECT 3 AS MONTH, 'Mar' as MONTHNAME
	   UNION SELECT 4 AS MONTH, 'Apr' as MONTHNAME
	   UNION SELECT 5 AS MONTH, 'May' as MONTHNAME
	   UNION SELECT 6 AS MONTH, 'Jun' as MONTHNAME
	   UNION SELECT 7 AS MONTH, 'Jul' as MONTHNAME
	   UNION SELECT 8 AS MONTH, 'Aug' as MONTHNAME
	   UNION SELECT 9 AS MONTH, 'Sep' as MONTHNAME
	   UNION SELECT 10 AS MONTH, 'Oct' as MONTHNAME
	   UNION SELECT 11 AS MONTH, 'Nov' as MONTHNAME
	   UNION SELECT 12 AS MONTH, 'Dec' as MONTHNAME)mon on 1=1
	
	) data
join (SELECT sum(hours) as hours, month, year FROM ost_hours where ".$year."=year group by month, year)h on h.month = data.MONTHNUM and h.year = data.CALENDARYEAR
group by data.MONTHNAME,data.MONTHNUM,data.CALENDARYEAR order by data.MONTHNUM,data.CALENDARYEAR

";

$totals = db_query($sql);


?>
$(function() {
    Highcharts.chart('DartRate', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'DART Rates (<?php echo $year;?>)'
        },
         // subtitle: {
             // text: 'Recordables DART'
         // },
        xAxis: {
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        credits: false,
        yAxis: {
            min: 0,
            title: {
                text: 'DART Rate'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px"><b>{point.key}</b></span><br><table>',
            // pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                // '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
        <?php foreach ($locs as $loc){ 
            $key = $loc["location"];
            $color = $sitecolor[$key];
        ?>
        {
            name: '<?php echo $loc['location']; ?>',
            color: '<?php echo $color; ?>',
            data: [<?php foreach ($locsdata as $locdata){

                    if ($loc['location'] == $locdata['location'] ) {
                        
                        $r = $locdata['dart'] + $r;
                        $h = $locdata['hours'] +$h;
                       if ($h !=0){ 
                       echo ROUND($r*200000/$h,2).',';
                       }else{
                        echo '0,';   
                       } 
                    }
         } ?>]

        },
         <?php  $h = null;
                $r = null;
            } ?>
         {
            name: 'Total',
            type: 'line',
            color: "#c30000",
            data: [<?php foreach ($totals as $total){
            
                        $r = $total['dart'] + $r;
                        $h = $total['hours'] +$h;
                       if ($h !=0){ 
                       echo ROUND($r*200000/$h,2).',';
                       }else{
                        echo '0,';   
                       } 
            }?>
            ]
            
        },
        {
            name: 'DR (2016: 336370)',
            type: 'line',
            data: [2.90,2.90,2.90,2.90,2.90,2.90,2.90,2.90,2.90,2.90,2.90,2.90],
        },
        {
            name: 'DR (2016: 336360)',
            type: 'line',
            data: [2.80,2.80,2.80,2.80,2.80,2.80,2.80,2.80,2.80,2.80,2.80,2.80],
        },        
        ]
    });
}); 
</script>

