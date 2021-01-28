<div class="container">
    <h1 class="login-title margin-bottom-mid mb-1">
        <span><?= $totalRow; ?> rows found</span>
    </h1>
    <?php if ($totalRow > 0): ?>
        <table class="table table-striped">
            <tr>
                <th>Module Name</th>
                
                
            </tr>
            <?php foreach ($modules as $a): ?>
                <tr>
                    <td><?= $a->name ?></td>
                    
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <h2>No data found</h2>
    <?php endif; ?>
</div>
