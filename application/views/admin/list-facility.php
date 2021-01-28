<div class="container">
    <?php $this->load->view('admin/add-facility');?>
    <br>
    <?php if ($totalRow > 0): ?>
        <table class="table table-striped">
            <tr>
                <th>Facility Name</th>
            </tr>
            <?php foreach ($facilities as $f): ?>
                <tr>
                    <td><?= $f->name ?></td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <h2>No data found</h2>
    <?php endif; ?>
</div>
