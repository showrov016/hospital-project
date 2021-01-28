<div class="container">
    <?php $this->load->view('admin/add-dept');?>
    <br>
    <?php if ($totalRow > 0): ?>
        <table class="table table-striped">
            <tr>
                <th>Department Type</th>
                <th>Action</th>
            </tr>
            <?php foreach ($depts as $a): ?>
                <tr>
                    <td><?= $a->type ?></td>
                    <td><a href="<?= base_url('admin/features/deleteDept/'.$a->dept_id)?>" class="btn btn-danger">Delete</a></td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <h2>No data found</h2>
    <?php endif; ?>
</div>
