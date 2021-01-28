<div class="container-fluid">
    <h1 class="login-title margin-bottom-mid mb-1">
        <span><?= $totalRow; ?> rows found</span>
    </h1>
    <?php if ($totalRow > 0) : ?>
        <table class="table table-striped">
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Shift</th>
                <th>Department</th>
                <th>Module1</th>
                <th>Rating1</th>
                <th>Module2</th>
                <th>Rating2</th>
                <th>Module3</th>
                <th>Rating3</th>
                <th>team lead</th>
                <th>Comment</th>
                <th>Demeanor Rating</th>
            </tr>
            <?php foreach ($consultants as $a) : ?>
                <tr>
                    <td><?= $a->first_name ?></td>
                    <td><?= $a->last_name ?></td>
                    <td><?= $a->username ?></td>
                    <td><?= $a->address ?></td>
                    <td><?= $a->shift ?></td>
                    <td><?= $a->department ?></td>
                    <td><?= $a->module1 ?></td>
                    <td><input type="number" value="<?= $a->rating1 ?>" class="form-control" onkeyup="updateRating(1,<?= $a->user_id ?>,$(this).val())"></td>
                    <td><?= $a->module2 ?></td>
                    <td><input type="number" value="<?= $a->rating2 ?>" class="form-control" onkeyup="updateRating(2,<?= $a->user_id ?>,$(this).val())"></td>
                    <td><?= $a->module3 ?></td>
                    <td><input type="number" value="<?= $a->rating3 ?>" class="form-control" onkeyup="updateRating(3,<?= $a->user_id ?>,$(this).val())"></td>
                    <td>
                        <select name="team_lead" id="team_lead" onchange="updateTeamLead(<?= $a->user_id ?>,$(this).val())">
                            <option value="" disabled selected>Select Team Lead</option>
                            <?php foreach ($team_leads as $t) : ?>
                                <option value="<?= $t->user_id ?>" <?= $a->team_lead_uid == $t->user_id ? 'selected' : '' ?>><?= $t->first_name . " " . $t->last_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><?= $a->comment ?></td>
                    <td>
                        <select data-user_id="<?= $a->user_id ?>">
                            <option <?= $a->demeanor == 1 ? 'selected' : '' ?>>1</option>
                            <option <?= $a->demeanor == 2 ? 'selected' : '' ?>>2</option>
                            <option <?= $a->demeanor == 3 ? 'selected' : '' ?>>3</option>
                            <option <?= $a->demeanor == 4 ? 'selected' : '' ?>>4</option>
                            <option <?= $a->demeanor == 5 ? 'selected' : '' ?>>5</option>
                        </select>
                        <button class="btn btn-success" onclick="saveDemeanor(<?= $a->user_id ?>)">Save</button>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else : ?>
        <h2>No data found</h2>
    <?php endif; ?>
</div>