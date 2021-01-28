<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <?php if ($this->session->userdata('type') != 'super_user' && $this->session->userdata('type') != 'facility_location_manager') : ?>
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#inputShift">Input Shift</button>
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#copyShift">Copy Shift</button>

            <?php endif; ?>
            <h2 class="text-center"><?= !empty($facility_name) ? $facility_name->location : "" ?></h2>
        </div>

        <div class="col-7 mt-5" id="calendar" data-location='<?= $location_id ?>'></div>

        <div class="col-2 mt-5">
            <?php if ($this->session->userdata('type') != 'super_user') : ?>
                <?php foreach ($events as $e) : ?>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" id="menu1" type="button" data-toggle="dropdown"><?= $e->facility_name ?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                            <?php
                            $locations = explode(",", $e->location_names);
                            $location_ids = explode(",", $e->location_ids);
                            ?>
                            <?php for ($i = 0; $i < count($locations); $i++) : ?>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= base_url('admin/Features/shifts/' . $location_ids[$i]) ?>"><?= $locations[$i] ?></a></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="col-3">
            <table class="table table-bordered">
                <tr class="text-success">
                    <th>Complete</th>
                    <td><?= count($green) ?></td>
                </tr>
                <tr class="text-warning">
                    <th>Partial</th>
                    <td><?= count($yellow) ?></td>
                </tr>
                <tr class="text-danger">
                    <th>UnScheduled</th>
                    <td><?= count($red) ?></td>
                </tr>
                <tr>
                    <th>Total Shifts</th>
                    <td><?= $total ?></td>
                </tr>
            </table>
        </div>
        <?php if ($this->session->userdata('type') != 'super_user' && $this->session->userdata('type') != 'facility_location_manager') : ?>
            <div class="col-4 mt-3">
                <h1 class="text-success text-center">Completed</h1>
                <table class="table table-bordered dataTable">
                    <thead>
                        <tr>
                            <th>Start/End</th>
                            <th>Shift</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($green as $g) : ?>
                            <tr>
                                <th><?= $g->start_time . " - " . $g->end_time ?></th>
                                <th><?= $g->shift ?></th>
                                <td><input type="checkbox" value="<?= $g->event_id ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
            <div class="col-4 mt-3">
                <h1 class="text-danger text-center">Unscheduled</h1>
                <table class="table table-bordered dataTable">
                    <thead>
                        <tr>
                            <th>Start/End</th>
                            <th>Shift</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($red as $g) : ?>
                            <tr>
                                <th><?= $g->start_time . " - " . $g->end_time ?></th>
                                <th><?= $g->shift ?></th>
                                <td><input type="checkbox" value="<?= $g->event_id ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
            <div class="col-4 mt-3">
                <h1 class="text-center text-warning">Partial</h1>
                <table class="table table-bordered dataTable">
                    <thead>
                        <tr>
                            <th>Start/End</th>
                            <th>Shift</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($yellow as $g) : ?>
                            <tr>
                                <th><?= $g->start_time . " - " . $g->end_time ?></th>
                                <th><?= $g->shift ?></th>
                                <td><input type="checkbox" value="<?= $g->event_id ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <div class="col-12 mt-3">
                <h1 class="text-center text-info">All Events</h1>
                <br>
                <a href="<?=base_url('admin/Features/exportShift')?>" class="btn btn-success float-right">Export Shifts as CSV</a>
                <button class="btn btn-danger float-right" id="shift_delete">Delete</button>
                <!-- <button class="btn btn-info float-right" id="add_support">Add support</button> -->
                <br>
                <table class="table table-striped text-center mt-5" id="dataTable">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Last</th>
                            <th>First</th>
                            <th>Type</th>
                            <th>Shift</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shifts as $s) : ?>
                            <tr>
                                <td><?= $s->name ?></td>
                                <td><?= $s->last_name ?></td>
                                <td><?= $s->first_name ?></td>
                                <td><?= $s->type ?></td>
                                <td><?= $s->shift ?></td>
                                <td><?= date_format(date_create($s->start_time), "m/d/Y H:i:s") ?></td>
                                <td><?= date_format(date_create($s->end_time), "m/d/Y H:i:s") ?></td>
                                <td>
                                    <input type="checkbox" name="shifts[]" value="<?= $s->event_id ?>">
                                    <!-- <a href="<?= base_url('admin/features/deleteShift/' . $s->event_id) ?>" class="btn btn-danger">Delete</a> -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


        <?php endif; ?>

    </div>
</div>

<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Shift Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <?= form_open('admin/Features/updateEvent') ?>
                <label for="consultants">Consultants</label>
                <select class="form-control select2" id="consultants" name="consultants[]" data-selType="consultant" <?= $this->session->userdata('type') == 'super_user' ? 'disabled' : '' ?> data-placeholder="Consultants" multiple="true" style="width: 100%">
                </select>
                <label for="super_users">Super Users</label>
                <select class="form-control select2" id="super_users" name="super_users[]" data-selType="super_users" <?= $this->session->userdata('type') == 'super_user' ? 'disabled' : '' ?> data-placeholder="Super Users" multiple="true" style="width: 100%">
                </select>

                <input type="hidden" id="event_id" value="" name="event_id">
                <button type="button" class="btn btn-danger mt-1" data-dismiss="modal">Close</button>
                <?php if ($this->session->userdata('type') != 'super_user') : ?>
                    <input type="submit" name="update_event" value="Update" class="btn btn-info mt-1">
                <?php endif; ?>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="inputShift">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Shift Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <?= form_open('admin/Features/addEvent') ?>
                <label>Select Facility</label>
                <select name="facility_id" id="fac_id" class="form-control" required>
                    <option disabled selected value="">Select Facility</option>
                    <?php foreach ($facs as $f) : ?>
                        <option value="<?= $f->fac_id ?>"><?= $f->name ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Select Location</label>
                <select name="location" class="form-control" id="locations" required>
                    <option disabled selected value="">Select Location</option>
                    <?php if (!empty($locations)) : ?>
                        <?php foreach ($locations as $l) : ?>
                            <option value="<?= $l->location_id ?>"><?= $l->name ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <label>#Consultants</label>
                <input type="number" placeholder="Consultant" id="consultant_num" min="0" class="form-control" name="num_of_const" value="0">
                <label>Shifts</label>
                <select name="shift" class="form-control" required>
                    <option disabled selected>Select Shift</option>
                    <option value="day">day</option>
                    <option value="night">night</option>
                </select>
                <label class="consultant_dependent">Module</label>
                <select class="consultant_dependent form-control" name="pref_module1">
                    <option disabled selected>Select Preferred Module</option>
                    <?php foreach ($modules as $m) : ?>
                        <option value="<?= $m->module_id ?>"><?= $m->name ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="consultant_dependent">Pref_skill_1</label>
                <input type="text" placeholder="Skills needed higher than" class="consultant_dependent form-control" name="pref_skill1">
                <label class="consultant_dependent">Pref_module</label>
                <select class="consultant_dependent form-control" name="pref_module2">
                    <option disabled selected>Select Preferred Module</option>
                    <?php foreach ($modules as $m) : ?>
                        <option value="<?= $m->module_id ?>"><?= $m->name ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="consultant_dependent">Pref_skill_2</label>
                <input type="text" placeholder="Skills needed higher than" class="consultant_dependent form-control" name="pref_skill2">
                <label class="consultant_dependent">demeanor</label>
                <input type="text" placeholder="Demeanor higher than" class="consultant_dependent form-control" name="demeanor">
                <label>start_date</label>
                <input type="date" placeholder="Start Date" class="form-control" name="start_date" required>
                <label>start_time</label>
                <input type="time" placeholder="Start Time" class="form-control" name="start_time" required>

                <label>Hours</label>
                <!-- <input type="time" placeholder="End Time" class="form-control" name="end_time" required> -->
                <select name="end_time" class="form-control">
                    <option value="0" disabled selected>Select Hour</option>
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
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
                <label>Occurance</label>
                <select name="occurance" class="form-control" id="occurance">
                    <option value="0" disabled selected>Select Number of Occurrence</option>
                    <?php for ($i = 1; $i <= 21; $i++) : ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <label>Number of SuperUser(s)</label>
                <div id="susers" class="mt-1"></div>
                <input type="submit" value="submit" class="btn btn-success" name="submit">
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="copyShift" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make a copy of your shifts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open('admin/Features/copyEvents', ['id' => 'copy_events']) ?>
                <label for="copy_from">Copy From</label>
                <select name="copy_from" id="copy_from" class="form-control" required>
                    <option value="0" disabled selected>Select Location</option>
                    <?php foreach ($locations_list as $l) : ?>
                        <option value="<?= $l->location_id ?>"><?= $l->name ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="copy_from">Copy To</label>
                <select name="copy_to" id="copy_to" class="form-control" required>
                    <option value="0" disabled selected>Select Location</option>
                    <?php foreach ($locations_list as $l) : ?>
                        <option value="<?= $l->location_id ?>"><?= $l->name ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="days">Days</label>
                <input type="checkbox" name="days" id="days">
                <label for="nights">Nights</label>
                <input type="checkbox" name="nights" id="nights"><br>
                <label for="start_time">Start Time</label>
                <input type="time" id="start_time" name="start_time" class="form_control">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" class="form_control">
                <?= form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="$('#copy_events').submit()">Copy Events</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="addSupportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Support</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <label for="support">Support</label>
                <select name="support" id="support" class="form-control">
                    <option value="" disabled selected>Select Support</option>
                    <option value="consultant">consultant</option>
                    <option value="superuser">superuser</option>
                </select>
                <label for="shift">Shift</label>
                <select name="shift" id="shift" class="form-control">
                    <option value="" disabled selected>Select shift</option>
                    <option value="day">day</option>
                    <option value="night">night</option>
                </select>
                <label for="location">Location</label>
                <select name="location" id="location" class="form-control">
                    <option value="0" disabled selected>Select Location</option>
                    <?php foreach ($locations_list as $l) : ?>
                        <option value="<?= $l->location_id ?>"><?= $l->name ?></option>
                    <?php endforeach; ?>
                </select> -->
                <label for="names">Users List</label>
                <select name="names[]" id="names" class="select2" multiple style="width: 100%;">
                    <option value="0" disabled>Select Names</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" disabled>Add support</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.dataTable').dataTable();
        $('#susers').append('<select name="num_of_su[]">\n\
                <option value="0">0</option>\n\
                <option value="1">1</option>\n\
                <option value="2">2</option>\n\
                <option value="3">3</option>\n\
                <option value="4">4</option>\n\
                <option value="5">5</option>\n\
                <option value="6">6</option>\n\
                <option value="7">7</option>\n\
                <option value="8">8</option>\n\
                <option value="9">9</option>\n\
                <option value="10">10</option>\n\
                <select>');
    });
    $('#occurance').on('change', function() {
        $('#susers').empty();
        if ($('#occurance').val() != 0) {
            for (i = 0; i < $('#occurance').val(); i++) {
                $('#susers').append('<select name="num_of_su[]">\n\
                <option value="0">0</option>\n\
                <option value="1">1</option>\n\
                <option value="2">2</option>\n\
                <option value="3">3</option>\n\
                <option value="4">4</option>\n\
                <option value="5">5</option>\n\
                <option value="6">6</option>\n\
                <option value="7">7</option>\n\
                <option value="8">8</option>\n\
                <option value="9">9</option>\n\
                <option value="10">10</option>\n\
                <select>');
            }
        }


    });

    $('#add_support').on('click', function() {
        var shift = $("input[name='shifts[]']:checked").map(function() {
            return $(this).val();
        }).get();

        if (shift.length == 1) {
            $.ajax({
                url: base_url + 'ajax-call/features/getSupportNames',
                type: 'POST',
                data: {
                    'shift': shift,
                },
                success: function(response) {
                    $('#names').empty();
                    $('#names').select2({
                        maximumSelectionLength: response.max_user

                    }).change();
                    console.log(response.max_user);
                    var selected = '';
                    var users = [];

                    for (var i = 0; i < response.selected_users.length; i++) {
                        users.push(response.selected_users[i].user_id);
                    }

                    for (var i = 0; i < response.data.length; i++) {
                        if (users.includes(response.data[i].user_id)) {
                            selected = 'selected';
                        } else {
                            selected = '';
                        }
                        $('#names').append("<option value='" +
                            response.data[i].user_id +
                            "'" + selected + ">" +
                            response.data[i].first_name +
                            ' ' +
                            response.data[i].last_name +
                            "</option>");
                    }

                    $('#addSupportModal').modal('show');
                }
            });
        } else {
            alert('select only one shift');
        }
    });
</script>
<?php if ($jump_date != null) : ?>
    <script>
        $(document).ready(function() {
            $("#calendar").fullCalendar('gotoDate', <?= "'" . $jump_date . "'" ?>);
        });
    </script>
<?php endif; ?>