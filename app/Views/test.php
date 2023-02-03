<!doctype html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>

        <title>Test Pitjarus</title>

    </head>

    <body>
        <div style="width:100%;height:100%;">
            <div class="d-flex justify-content-around mt-4">
                <div>
                    <select id="store_area" class="" name="store_area[]" multiple="multiple" style="width:200px;">
                        <option selected value="">Select Area</option>
                        <?php foreach ($store_area as $area) : ?>
                        <option value="<?= $area['area_id'] ?>"><?= $area['area_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ml-2">
                    <select style="height:38px;" id="date_from" name="date_form" name="state" onchange="GetDateTo()">
                        <option value="">Select Date From</option>
                        <?php foreach ($report_product as $report) : ?>
                        <option value="<?= $report['tanggal'] ?>"><?= $report['tanggal'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ml-2">
                    <select id="date_to" style="height:38px;" id="date_to" name="date_to" name="state">
                        <option value="">Select Date To</option>
                    </select>
                </div>
                <div class="ml-2"></div>
                <button class="btn btn-primary btn-sm" style="width:100px;" onclick="showChartAndTable()">View</button>
            </div>
            <div class="d-flex justify-content-center mt-5">
                <figure class="highcharts-figure">
                    <div id="container"></div>
                </figure>
            </div>
            <div class="d-flex justify-content-center" id="dynamic_table">
            </div>
        </div>


        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        $('#store_area').select2();

        function GetDateTo() {
            let date_from = $('#date_from').val();
            $.ajax({
                type: 'post',
                url: "<?= base_url(); ?>/get_date_to",
                async: true,
                dataType: "json",
                data: {
                    date_from: date_from,
                },
                success: function(data) {
                    $('#date_to').html('');
                    $('#date_to').html(data[0]);
                }

            })
        }

        function showChartAndTable() {

            $("table thead tr").append("<th>Brand</th>");

            let area_id = $("[name='store_area[]']").val();
            let date_from = $('#date_from').val();
            let date_to = $('#date_to').val();
            if (date_from == '' || date_to == '' || area_id[0] == '') {
                alert('Data Select From,Select To dan Select Area Tidak Boleh Kosong');
            } else {
                $.ajax({
                    type: 'post',
                    url: "<?= base_url(); ?>/get_chart_table",
                    async: true,
                    dataType: "json",
                    data: {
                        area_id: area_id,
                        date_from: date_from,
                        date_to: date_to
                    },
                    success: function(data) {
                        console.info(data)

                        // Data retrieved from https://gs.statcounter.com/browser-market-share#monthly-202201-202201-bar

                        // Create the chart
                        Highcharts.chart('container', {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                align: 'left',
                                text: 'Browser market shares. January, 2022'
                            },
                            subtitle: {
                                align: 'left',
                                text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
                            },
                            accessibility: {
                                announceNewData: {
                                    enabled: true
                                }
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: 'Total percent market share'
                                }

                            },
                            legend: {
                                enabled: false
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true,
                                        format: '{point.y:.1f}%'
                                    }
                                }
                            },

                            tooltip: {
                                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
                            },

                            series: [{
                                name: 'Browsers',
                                colorByPoint: true,
                                data: data[0]
                            }],
                        });
                        $('#dynamic_table').html('')
                        $('#dynamic_table').html(data[1])
                    }

                })

            }
        }
        </script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    </body>

</html>