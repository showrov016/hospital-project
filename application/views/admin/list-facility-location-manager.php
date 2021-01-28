<div class="container">
    <?php $this->load->view('admin/add-facility-location-managers'); ?>
    <br>
    <?php if ($totalRow > 0) : ?>
        <table class="table table-striped">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>E-mail</th>
                <th>Phone</th>
                <th>Facilities</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
            <?php foreach ($managers as $m) : ?>
                <?php
                $su_locations = explode(',', $m->locations);
                ?>
                <tr>
                    <td><?= $m->first_name ?></td>
                    <td><?= $m->last_name ?></td>
                    <td><?= $m->username ?></td>
                    <td><input type="text" class="phone" value="<?= $m->phone ?>"></td>
                    <td>
                        <select class="facility">
                            <option value="" disabled>Select Facility</option>
                            <?php foreach ($facilities as $f) : ?>
                                <option value="<?=$f->fac_id?>" <?= $f->fac_id == $m->fac_id ? "selected" : "" ?>><?= $f->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select class="select2 location" multiple>
                            <?php foreach ($locations as $l) : ?>
                                <option value="<?= $l->location_id ?>" <?= in_array($l->location_id, $su_locations) ? "selected" : "" ?>><?= $l->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-info updateFacLocMangr" data-id="<?= $m->user_id ?>">Update</button>
                        <a href="<?= base_url('admin/features/deleteFacLocationManager/' . $m->user_id) ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else : ?>
        <h2>No data found</h2>
    <?php endif; ?>
</div>