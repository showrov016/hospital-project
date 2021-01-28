<div class="container">
    <?php $this->load->view('admin/add-super-users'); ?>
    <br>
    <?php if ($totalRow > 0): ?>
        <table class="table table-striped" id="dataTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Location/Shift</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($susers as $s): ?>
                    <tr>
                        <td><?= $s->first_name ?></td>
                        <td><?= $s->username ?></td>
                        <td>
                            <select>
                                <?php foreach ($locations as $l): ?>
                                    <option value="<?= $l->location_id ?>" <?= $l->location_id == $s->location_id ? "selected" : "" ?>><?= $l->name ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="shift">
                                <option value="day" <?= $s->shift == 'day' ? 'selected' : '' ?>>day</option>
                                <option value="night" <?= $s->shift == 'night' ? 'selected' : '' ?>>night</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-info update_su" data-id="<?= $s->user_id ?>">Update</button>
                            <a href="<?= base_url('admin/features/deleteSuperUser/' . $s->user_id) ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>



        </table>
    <?php else: ?>
        <h2>No data found</h2>
    <?php endif; ?>
</div>
