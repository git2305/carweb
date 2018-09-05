<div class="content-page">
<div class="content">
<div class="container">
<!-- Page-Title -->
						<div class="row">
							<div class="col-sm-12">
                                <!--<div class="btn-group pull-right m-t-15">
                                    <button type="button" class="btn btn-white dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                                    <ul class="dropdown-menu drop-menu-right dropdown-menu-animate" role="menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div>-->

								<h4 class="page-title">Admin Panel</h4>
								<ol class="breadcrumb">
									<li class="active">
										Home
									</li>
									<li>
										<a href="#">Car Menu</a>
									</li>
								</ol>
							</div>
						</div>
						
						
						<div class="row">
							<div class="col-sm-12">
								<div class="card-box">
									<div class="row">
										<div class="col-md-6">
											<div id="containerchart"></div>
										</div>
										<div class="col-md-6">
											<div id="containerchartchf"></div>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div> <!-- container -->
                </div> <!-- content -->


            <!-- Start Footer -->
            <?php echo $this->element('admin/footer'); ?>
            <!-- /End Footer -->

            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


	<!-- Right Chatbar -->
		<?php echo $this->element('admin/chatbar'); ?>
	<!-- /Right Chatbar -->			
</div>
</div>
</div>
<?php 
			
	for ($i = 0; $i < 12; $i++) {
		
		$months[] = date("'M-y'", strtotime( date( 'Y-m-01' )." - $i months"));
		$month[] = date("m-Y", strtotime( date( 'Y-m-01' )." - $i months"));
	}
	$monthData =  implode(',',array_reverse($months));
	//pr($month);
	$monthArr = array();
	$countArr = array();
	
	foreach($vehicleChart as $k => $v)
	{
		$monthArr[$k] = $v[0]['month'];
		$countArr[$v[0]['month']] = $v[0]['count'];
	}
	
	$finalCount = '';
	$finalArr = array();
	foreach($month as $key => $val)
	{
		
		if(in_array($val,$monthArr))
		{
			$finalArr[$months[$key]] = $countArr[$val];
		}else{
			$finalArr[$months[$key]] = '0';
		}
	}
	
	$chartMonth = implode(',',array_keys(array_reverse($finalArr)));
	$vehicleReg = implode(',',array_reverse($finalArr));
	
	$chfArr = array();
	for($j=500;$j<100000;$j++){
		$chfArr[$j] = $j;
	}
	$chf = implode(',',$chfArr);
	//pr($chf);die;
	
	$monthArr1 = array();
	$countArr1 = array();
	
	foreach($vehiclesellChart as $k => $v)
	{
		$monthArr1[$k] = $v[0]['month'];
		$countArr1[$v[0]['month']] = $v[0]['CHF'];
	}
	
	$finalCount = '';
	$finalArr1 = array();
	foreach($month as $key => $val)
	{
		
		if(in_array($val,$monthArr1))
		{
			$finalArr1[$months[$key]] = $countArr1[$val];
		}else{
			$finalArr1[$months[$key]] = '0';
		}
	}
	
	$chartMonth1 = implode(',',array_keys(array_reverse($finalArr1)));
	$vehicleSell = implode(',',array_reverse($finalArr1));
	
	
?>
<script>
$(function () {
    var chart = Highcharts.chart('containerchart', {

        chart: {
            type: 'column'
        },

        title: {
            text: 'Vehicle Registration'
        },

        legend: {
            align: 'right',
            verticalAlign: 'middle',
            layout: 'vertical'
        },

        xAxis: {
            categories: [<?php echo $chartMonth; ?>],
            title: {
	            text: 'Months'
	        },
        },

        yAxis: {
        	allowDecimals : false,
            type: 'linear',
            title: {
	            text: 'Vehicles'
	        },
        },

        series: [{
            data: [<?php echo $vehicleReg; ?>],
            showInLegend: false,
            name:'Vehicles'
        }]
    });
    
    var chartchf = Highcharts.chart('containerchartchf', {

        chart: {
            type: 'column'
        },

        title: {
            text: 'Vehicle Sold'
        },

        legend: {
            align: 'right',
            verticalAlign: 'middle',
            layout: 'vertical'
        },

        xAxis: {
            categories: [<?php echo $chartMonth1; ?>],
            title: {
	            text: 'Months'
	        },
        },

        yAxis: {
        	allowDecimals : false,
            type: 'linear',
            title: {
	            text: 'CHF'
	        },
        },

        series: [{
            data: [<?php echo $vehicleSell; ?>],
            showInLegend: false,
            name:'CHF'
        }]
    });
});
</script>
