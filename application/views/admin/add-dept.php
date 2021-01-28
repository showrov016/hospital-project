<div class="container">
    <h2 class="text-center">Add Departments</h2>
    <?php if (!empty($this->session->flashdata('error-msg'))): ?>
        <?= $this->session->flashdata('error-msg') ?>
    <?php endif; ?>
    <?php if (!empty($this->session->flashdata('success-msg'))): ?>
        <?= $this->session->flashdata('success-msg') ?>
    <?php endif; ?>
    <?= form_open('admin/Features/addDept') ?>
    <input type="text" name="dept_name" placeholder="Enter Department Name" class="form-control">
    <input type="submit" class="btn btn-success mt-1" name="submit" value="Add department">
    <?= form_close() ?>
</div>