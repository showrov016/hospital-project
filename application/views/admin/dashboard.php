<div class="row">
    <div class="col-md-12">
        <h1 class="login-title margin-bottom-mid text-center">
            Welcome to Admin Panel
        </h1>
        <hr>
        <?php
        $successMsg = $this->session->flashdata('success-msg');
        if ($successMsg) {
            echo '<div class="alert alert-success alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong>';
            echo ' ' . $successMsg;
            echo '</div>';
        }
        $errorMsg = $this->session->flashdata('error-msg');
        if (isset($errorMsg)) {
            echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!</strong>';
            echo ' ' . $errorMsg;
            echo '</div>';
        }
        ?>
        <div class="container-fluid">
            <div class="row">

                <select class="form-control" id="facility">
                    <option value="" selected disabled>Select Facility</option>
                    <?php foreach ($facilities as $f) : ?>
                        <option value="<?= $f->fac_id ?>"><?= $f->name ?></option>
                    <?php endforeach ?>
                </select>

                <table class="table table-bordered col-7" id="dataTable">
                    <thead>
                        <tr>
                            <th>Location</th>
                            <th class="text-success">Complete</th>
                            <th class="text-warning">Partial Day</th>
                            <th class="text-warning">Partial Night</th>
                            <th class="text-danger">Unscheduled Day</th>
                            <th class="text-danger">Unscheduled Night</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="shift_info">
                    </tbody>
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tfoot>
                </table>



                <div class="col-5">
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                </div>

            </div>
        </div>

    </div>
</div>


<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    $(document).ready(function() {
        $("#facility").val($("#facility")[0].options[1].value).change();
        //$('#fac_details').empty();
        var datapoints = <?= $dataPoints ?>;
        // for (var i = 0; i < datapoints.length; i++) {
        //     $('#fac_details').append("<td>" + datapoints[i].y + "</td>");
        // }

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Employee Assignment Details"
            },
            subtitles: [{
                text: ""
            }],
            data: [{
                type: "pie",
                yValueFormatString: "",
                indexLabel: "{label} ({y})",
                dataPoints: datapoints
            }]
        });
        chart.render();
        $(".canvasjs-chart-credit").hide();
    });




    $('#facility').on('change', function() {
        $('#dataTable').DataTable().clear().destroy();
        $.ajax({
            url: base_url + 'ajax-call/features/getDashboardInfo',
            type: 'POST',
            data: {
                facility: $('#facility').val()
            },
            success: function(dataPoints) {
                //console.log(dataPoints.events[0]);
               
                for ($i = 0; $i < dataPoints.events.length; $i++) {
                    var total = dataPoints.events[$i].completed +
                        dataPoints.events[$i].partial_day +
                        dataPoints.events[$i].partial_night +
                        dataPoints.events[$i].unscheduled_day +
                        dataPoints.events[$i].unscheduled_night;

                    $('#dataTable').DataTable().row.add([
                        "<a href='" + base_url + "admin/Features/shifts/" + dataPoints.events[$i].location_id + "'>" + dataPoints.events[$i].location_name + "</a>",
                        dataPoints.events[$i].completed, dataPoints.events[$i].partial_day,
                        dataPoints.events[$i].partial_night,
                        dataPoints.events[$i].unscheduled_day,
                        dataPoints.events[$i].unscheduled_night,
                        total
                    ]).draw();
                }
                $('#dataTable').DataTable().destroy();
                $('#dataTable').DataTable({
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        // converting to interger to find total
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        var complete = api
                            .column(1)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var pDay = api
                            .column(2)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var pNight = api
                            .column(3)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var uDay = api
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var uNight = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var total = api
                            .column(6)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        $(api.column(0).footer()).html('Total');
                        $(api.column(1).footer()).html(complete);
                        $(api.column(2).footer()).html(pDay);
                        $(api.column(3).footer()).html(pNight);
                        $(api.column(4).footer()).html(uDay);
                        $(api.column(5).footer()).html(uNight);
                        $(api.column(6).footer()).html(total);
                    }


                });

            }
        })
    });
</script>