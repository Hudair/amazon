<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
{{--<script src="{{ mix("js/highchart.js") }}" charset=utf-8></script>--}}
<script src="{{ mix("js/chartjs.js") }}" charset=utf-8></script>

<script type="text/javascript">
    var generate;
    var methodGenerate;
    var statusGenerate;
    var salesCtx;
    var paymentMethodChart;
    var paymentStatusChart;
    var startDate;
    var endDate;
    ;(function ($, window, document) {
        // var sorter = $('#sortable').rowSorter({
        var startDate;
        var endDate;
        var jsonData = '<?php echo json_encode($chartDataArray);?>';
        var chartData =  JSON.parse(jsonData);
        var paymentStatusJson = '<?php echo json_encode($paymentStatus);?>';
        var paymentStatus =  JSON.parse(paymentStatusJson);
        var paymentMethodJson = '<?php echo json_encode($paymentMethod);?>';
        var paymentMethod =  JSON.parse(paymentMethodJson);
        var chartFormatData = chartDataFormat(chartData);
        var paymentMethodData = paymentMethodPie(paymentMethod);
        var paymentStatusData = paymentStatusPie(paymentStatus);

         salesCtx = document.getElementById('salesReport').getContext('2d');
         paymentMethodChart = document.getElementById('paymentMethodChart').getContext('2d');
         paymentStatusChart = document.getElementById('paymentStatusChart').getContext('2d');

         generate = new Chart(salesCtx, chartFormatData);
         methodGenerate = new Chart(paymentMethodChart, paymentMethodData);
         statusGenerate = new Chart(paymentStatusChart, paymentStatusData);

        //let dataString = "fromDate=&toDate=";
        //dateToDateSearch(dataString);
        $(document).ready(function () {
            $('#daterangepicker').daterangepicker(
                {
                    startDate: moment().subtract('days', 6),
                    endDate: moment(),
                    showDropdowns: false,
                    showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 30,
                    timePicker12Hour: false,
                    ranges: {
                        '{{ trans('app.today') }}': [moment(), moment()],
                        '{{ trans('app.yesterday') }}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '{{ trans('app.last_7_days') }}': [moment().subtract(6, 'days'), moment()],
                        '{{ trans('app.last_30_day') }}': [moment().subtract(29, 'days'), moment()],
                        '{{ trans('app.this_month') }}': [moment().startOf('month'), moment()],
                        '{{ trans('app.last_month') }}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        '{{trans('app.last_12_month')}}': [moment().startOf('month').subtract(12, 'month'), moment().endOf('month')],
                        '{{trans('app.this_year')}}': [moment().startOf('year'), moment()],
                        '{{trans('app.last_year')}}': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                    },
                    opens: 'left',
                    buttonClasses: ['btn btn-default'],
                    cancelClass: 'btn-small',
                    format: 'DD/MM/YYYY',
                    separator: ' to ',
                },
                function (start, end) {
                    //console.log("Callback has been called!");
                    $('#daterangepicker span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                    startDate = start.format('YYYY-MM-DD');
                    endDate = end.format('YYYY-MM-DD');
                    $('#getFromDate').val(start.format('YYYY-MM-DD'));
                    $('#getToDate').val(end.format('YYYY-MM-DD'));
                    //console.log(window.location.hostname)
                    //Get Filter Value:
                    let paymentMethod = $('#paymentMethod').val();
                    let paymentStatus= $('#paymentStatus').val();
                    let dataString = "fromDate=" + startDate + "&toDate=" + endDate+"&paymentMethod=" + paymentMethod+"&paymentStatus=" + paymentStatus;
                    //Data Table Reset After Ajax:
                    //Get Chart Data Via Ajax:
                    let ajaxUrl = '{{route('admin.sales.payments.getMoreForChart')}}';
                    ajaxFire(ajaxUrl, dataString, function (output){
                        generate.clear();
                        generate.destroy();
                        chartFormatData = chartDataFormat(output);
                        generate = new Chart(salesCtx, chartFormatData);
                    });
                    let urlMethod = '{{route('admin.sales.payments.getMethod')}}';
                    ajaxFire(urlMethod, dataString, function (output){
                        methodGenerate.clear();
                        methodGenerate.destroy();
                        paymentMethodData = paymentMethodPie(output);
                        methodGenerate = new Chart(paymentMethodChart, paymentMethodData);

                    });
                    let urlStatus = '{{route('admin.sales.payments.getStatus')}}';
                    ajaxFire(urlStatus, dataString, function (output){
                        statusGenerate.clear();
                        statusGenerate.destroy();
                        paymentStatusData = paymentStatusPie(output);
                        statusGenerate = new Chart(paymentStatusChart, paymentStatusData);

                    });

                }
            );
            //Set the initial state of the picker label
            $('#daterangepicker span').html(moment().subtract('days', 29).format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
            $('#getFromDate').val(moment().subtract('days', 7).format('YYYY-MM-DD'));
            $('#getToDate').val(moment().format('YYYY-MM-DD'));
        });
        ///Calling Chart Function to manipulate:

    }(window.jQuery, window, document));

    //This function Will Return Data Configuration:
    function chartDataFormat(chartData){

        let chartCount = chartData.length;
        let labelData = [];
        let pending = [];
        let paid = [];
        let refunded = [];

        for(let i = 0; i < chartData.length; i++){
            labelData.push( chartData[i].date);if(i < chartCount -1 ){','}
            pending.push( chartData[i].pending);if(i < chartCount -1 ){','}
            paid.push( chartData[i].paid);if(i < chartCount -1 ){','}
            refunded.push( chartData[i].refunded);if(i < chartCount -1 ){','}
        }

        let saleReport = {
            type: 'line',
            data: {
                labels: labelData,
                datasets: [
                    {
                        label: 'Unpaid',
                        fill: true,
                        backgroundColor: "rgba(0,0,255, 0.6)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(232,105,90, 6)",
                        hoverBorderColor: "orange",
                        data: pending,
                    },
                    {
                        label: 'Paid',
                        fill: true,
                        backgroundColor: "rgba(0,128,0, 0.6)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(232,105,90,0.6)",
                        hoverBorderColor: "orange",
                        data: paid,
                    },
                    {
                        label: 'Refunded',
                        fill: true,
                        backgroundColor: "rgba(255,0,0, 0.6)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(232,105,90,0.6)",
                        hoverBorderColor: "orange",
                        data: refunded,
                    }
                ]
            },
            options: {
                responsive: true, // Instruct chart js to respond nicely.
                maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height
                legend: {
                    display: true,
                },
            }
        };
        return saleReport;
    }

    function paymentMethodPie(chartData){

        let chartCount = chartData.length;
        let labelData = [];
        let mainData = [];

        for(let i = 0; i < chartData.length; i++){
            labelData.push( chartData[i].name);if(i < chartCount -1 ){','}
            mainData.push( chartData[i].total);if(i < chartCount -1 ){','}
        }

        var colorArray = [
            window.chartColors.green,
            window.chartColors.gray,
            window.chartColors.red,
            window.chartColors.yellow,
            window.chartColors.black,
            window.chartColors.white,
            window.chartColors.blue,
            window.chartColors.orange
        ];
        let config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: mainData,
                    backgroundColor: colorArray,
                    label: 'Payment Method Wise Transaction'
                }],
                labels: labelData,
            },
            options: {
                responsive: true,
                legend: {
                    labels: {
                        // This more specific font property overrides the global property
                        fontSize:15,
                    },
                },
            },
        };
        return config;

    }

    function paymentStatusPie(chartData){

        let pending = 0;
        let paid = 0;
        let refunded = 0;

        for(let i = 0; i < chartData.length; i++) {
            pending += parseFloat(chartData[i].pending);
            paid += parseFloat(chartData[i].paid);
            refunded += parseFloat(chartData[i].refunded);
        }

        let labelData = ['Unpaid', 'Paid', 'Refunded'];
        let mainData = [pending, paid, refunded];

        var colorArray = [
            window.chartColors.yellow,
            window.chartColors.green,
            window.chartColors.red,
        ];
        let config = {
            type: 'pie',
            data: {
                datasets: [
                    {
                    data: mainData,
                    backgroundColor: colorArray,
                    label: 'Payment Status Wise Revenue'
                }
                ],
                labels: labelData,
            },
            options: {
                responsive: true,
                legend: {
                    labels: {
                        // This more specific font property overrides the global property
                        fontSize:15,
                    },
                },
            },
        };
        return config;

    }

    //ajaxFire:
    function ajaxFire(ajaxUrl, params,  handleData){

        $.ajax({
            url:ajaxUrl+'/?'+params,
            method:'get',
            contentType: 'application/json',
            success:function (response){
                //console.log(response)
                handleData(response.data);
            }
        });

    }

    //Clear All Filter:
    function clearAllFilter(){
        $('#paymentMethod').val("");
        $('#paymentStatus').val("");
    }

    function fireEventOnFilter(str) {

        let paymentMethod =  $('#paymentMethod').val();
        let paymentStatus =  $('#paymentStatus').val();
        let fromDate = $('#getFromDate').val();
        let toDate = $('#getToDate').val();

        let dataString = "fromDate=" + fromDate + "&toDate=" + toDate + "&paymentMethod=" + paymentMethod+"&paymentStatus=" + paymentStatus;
        //Data Table Reset After Ajax:
        //Get Chart Data Via Ajax:
        let ajaxUrl = '{{route('admin.sales.payments.getMoreForChart')}}';
        ajaxFire(ajaxUrl, dataString, function (output){
            generate.clear();
            generate.destroy();
            chartFormatData = chartDataFormat(output);
            generate = new Chart(salesCtx, chartFormatData);
        });
        let urlMethod = '{{route('admin.sales.payments.getMethod')}}';
        ajaxFire(urlMethod, dataString, function (output){
            methodGenerate.clear();
            methodGenerate.destroy();
            paymentMethodData = paymentMethodPie(output);
            methodGenerate = new Chart(paymentMethodChart, paymentMethodData);

        });
        let urlStatus = '{{route('admin.sales.payments.getStatus')}}';
        ajaxFire(urlStatus, dataString, function (output){
            statusGenerate.clear();
            statusGenerate.destroy();
            paymentStatusData = paymentStatusPie(output);
            statusGenerate = new Chart(paymentStatusChart, paymentStatusData);

        });

    }



</script>