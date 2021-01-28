<div class="container">
    <?php $this->load->view('admin/add-team-lead'); ?>
    <br>
    <?php if ($totalRow > 0) : ?>
        <table class="table table-striped">
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Phone</th>
            </tr>
            <?php foreach ($team_lead as $a) : ?>
                <tr>
                    <td><?= $a->first_name . " " . $a->last_name ?></td>
                    <td><?= $a->username ?></td>
                    <td><?= $a->phone ?></td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else : ?>
        <h2>No data found</h2>
    <?php endif; ?>
</div>