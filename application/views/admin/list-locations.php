<div class="container">
    <?php $this->load->view('admin/add-locations'); ?>
    <br>
    <?php if ($totalRow > 0) : ?>
        <table class="table table-striped" id="dataTable">
            <thead>
                <tr>
                    <th>Facility Name</th>
                    <th>Location Name</th>
                    <th>Department Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $l) : ?>
                    <tr>
                        <td><?= $l->fac_name ?></td>
                        <td><?= $l->name ?></td>
                        <td><?= $l->dept_type ?></td>
                        <td><a href="<?= base_url('admin/features/deleteLocation/' . $l->location_id) ?>" class="btn btn-danger">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    <?php else : ?>
        <h2>No data found</h2>
    <?php endif; ?>
</div>