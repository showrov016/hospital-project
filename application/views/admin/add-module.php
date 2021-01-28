<div class="container">

    <h2 class="text-center">Add Module</h2>
    <?php if (!empty($this->session->flashdata('error-msg'))): ?>
        <?= $this->session->flashdata('error-msg') ?>
    <?php endif; ?>
    <?php if (!empty($this->session->flashdata('success-msg'))): ?>
        <?= $this->session->flashdata('success-msg') ?>
    <?php endif; ?>
    <?= form_open('admin/Features/addModule') ?>
    <input type="text" name="module_name" placeholder="Enter Module Name" class="form-control">
    
    <input type="submit" class="btn btn-success mt-1" name="submit">
    <?= form_close() ?>
</div>