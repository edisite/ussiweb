<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Laporan</h3>
            </div>
          
                    <form action="#" method="get" class="sidebar-form">
                        <div class="box-body">
                        
                          <!-- Date -->
                          <div class="form-group">
                             <div class="col-xs-12 col-sm-4 col-md-4">
                                <label>Date:</label>
                                <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                                </div>   
                             </div>
                          </div>
                         <div class="form-group">
                                  <div class="col-xs-12 col-sm-4 col-md-4">
                                  <label>Keyword : </label>
<!--                                  <select class="select2_single form-control" tabindex="-1" style="width: 100%;" name="_k">
                                      -->
                                       <select class="select2_group form-control">
                                    <?php 
//                                        if($result_kwod) :
//                                            if($keyword == "all")
//                                            {
                                                echo '<option selected="selected" value="all">All</option>';
//                                            }else{
//                                                echo '<option value="all">All</option>';
//                                            }
//                                            foreach ($result_kwod as $v) {
//                                                
//                                                if($keyword == $v->group_id){
//                                                    echo '<option value="'.$v->group_id.'" selected>'.$v->k.'</option>';
//                                                }
//                                                else{
//                                                    echo '<option value="'.$v->group_id.'">'.$v->k.'</option>';
//                                                }
//                                            }
//                                        endif;
                                    ?>
                                  </select>
                                    </div>                            
                          </div>                        
                          <div class="form-group">
                              <div class="col-xs-12 col-sm-4 col-md-4">
                                  <label>Subgroup    : </label>
                                  <select class="form-control select2" style="width: 100%;" name="_sg">
                
                                    <option selected="selected" value="all">All</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="100">100</option>
                                    <option value="130">130</option>
                                    <option value="140">140</option>
                                    <option value="150">150</option>
                                  </select>
                              </div>
                          </div> 
                                        
                    
                        </div> 
                 
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                  <label></label>
                                  <div class="clearfix"></div>
                                  <br>
                                  <button type="submit" class="btn btn-primary">Search</button>

                            </div>
                        </div> 
                    </div>
                    </form>
        <div class="clearfix"></div>
        <br>
          <div class="box-body">                
              <div class="clearfix"></div>
                <div class="content">
                    <div class="container" id="container">
                        <?php echo @$contentside?>
                        <div class="clearfix"></div>
                    </div>
                </div>
          </div>
      </div>
    </div>
</div>


<!--<script>
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Laporan Penjualan Per Bulan'
    }, 
    subtitle: {
        text: 'Source: WorldClimate.com'
    },
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
            'Dec',
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
    yAxis: {
        min: 0,
        title: {
            text: 'Rainfall (mm)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm1</b></td></tr>',
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
    series: [{
        name: 'Tokyo',
        data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4,49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

    }, {
        name: 'New York',
        data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3,49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

    }, {
        name: 'London',
        data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2,49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

    }, {
        name: 'Berlin',
        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1,49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

    }]
});
</script>-->
<script type="text/javascript">
$(function() {

    var start = moment().subtract(350, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    
});
</script>
        
        <!-- Include Required Prerequisites -->
<!--<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>-->
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!--<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
 -->
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
 