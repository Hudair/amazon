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
        var chartFormatData = chartDataFormat(chartData);
        salesCtx = document.getElementById('payoutReport').getContext('2d');
        generate = new Chart(salesCtx, chartFormatData);

        $(document).ready(function () {
            $('#daterangepicker').daterangepicker(
                {
                    startDate: moment().subtract('days', 6),
                    endDate: moment(),
                    showDropdowns: false,
                    showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 7,
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

                    fireEventOnFilter();
                }
            );
            //Set the initial state of the picker label
            $('#daterangepicker span').html(moment().subtract('days', 6).format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
            $('#getFromDate').val(moment().subtract('days', 7).format('YYYY-MM-DD'));
            $('#getToDate').val(moment().format('YYYY-MM-DD'));
        });
        ///Calling Chart Function to manipulate:

    }(window.jQuery, window, document));

    //This function Will Return Data Configuration:
    function chartDataFormat(chartData){

        let chartCount = chartData.length;
        let labelData = [];
        let deposit = [];
        let withdraw = [];

        for(let i = 0; i < chartData.length; i++){
            labelData.push( chartData[i].date);if(i < chartCount -1 ){','}
            deposit.push( chartData[i].deposit);if(i < chartCount -1 ){','}
            withdraw.push( chartData[i].withdraw);if(i < chartCount -1 ){','}
        }

        let saleReport = {
            type: 'line',
            data: {
                labels: labelData,
                datasets: [
                    {
                        label: 'Deposit',
                        fill: true,
                        backgroundColor: "rgba(0,0,255, 0.6)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(232,105,90, 6)",
                        hoverBorderColor: "orange",
                        data: deposit,
                    },
                    {
                        label: 'Withdraw',
                        fill: true,
                        backgroundColor: "rgba(255,0,0, 0.6)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(232,105,90,0.6)",
                        hoverBorderColor: "orange",
                        data: withdraw,
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

    //Data Table Reset and manipulate new Data
    function dataTableResetting(dataString) {


        console.log(dataString)
        var table = $('.table-no-sort');
        if ($.fn.dataTable.isDataTable(table)) {
            table.DataTable().destroy();
            //table.clear();
        }
        let url = '{{route('admin.wallet.payout.report.getMore')}}';

        table.DataTable({
            "responsive": true,
            "iDisplayLength": {{ getPaginationValue() }},
            "ajax": url + '/?' + dataString,
            "columns": [
                {
                    'data': 'date',
                    'name': 'date',
                    'orderable': true,
                    'searchable': true,
                    'exportable': true,
                    'printable': true
                },
                {
                    'data': 'shop',
                    'name': 'shop',
                    'orderable': true,
                    'searchable': true,
                    'exportable': true,
                    'printable': true
                },
                {
                    'data': 'type',
                    'name': 'type',
                    'orderable': true,
                    'searchable': true,
                    'exportable': true,
                    'printable': true
                },
                {
                    'data': 'description',
                    'name': 'description',
                    'orderable': true,
                    'searchable': true,
                    'exportable': true,
                    'printable': true
                },
                {
                    'data': 'balance',
                    'name': 'balance',
                    'orderable': true,
                    'searchable': true,
                    'exportable': true,
                    'printable': true
                },
                {
                    'data': 'amount',
                    'name': 'amount',
                    'orderable': true,
                    'searchable': true,
                    'exportable': true,
                    'printable': true
                }

            ],
            "oLanguage": {
                "sInfo": "_START_ to _END_ of _TOTAL_ entries",
                "sLengthMenu": "Show _MENU_",
                "sSearch": "",
                "sEmptyTable": "No data found!",
                "oPaginate": {
                    "sNext": '<i class="fa fa-hand-o-right"></i>',
                    "sPrevious": '<i class="fa fa-hand-o-left"></i>',
                },
            },
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [-1]
            }],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
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
        $('#payoutType').val("");
        $('#status').val("");

        fireEventOnFilter();
    }

    function fireEventOnFilter(str) {

        let payoutType =  $('#payoutType').val();
        let status =  $('#status').val();
        let fromDate = $('#getFromDate').val();
        let toDate = $('#getToDate').val();

        let dataString = "fromDate=" + fromDate + "&toDate=" + toDate + "&payoutType=" + payoutType + "&status=" + status;
        //Data Table Reset After Ajax:
        dataTableResetting(dataString);
        //Get Chart Data Via Ajax:
        let chartUrl = '{{route('admin.wallet.payout.report.getMoreChart')}}';
        ajaxFire(chartUrl, dataString, function (output){
            generate.clear();
            generate.destroy();
            chartFormatData = chartDataFormat(output);
            generate = new Chart(salesCtx, chartFormatData);
        });
    }


</script>