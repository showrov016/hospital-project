<div class="container">
    <h2 class="text-center">Change Password</h2>
    <?php if (!empty($this->session->flashdata('error-msg'))): ?>
        <?= $this->session->flashdata('error-msg') ?>
    <?php endif; ?>
    <?php if (!empty($this->session->flashdata('success-msg'))): ?>
        <?= $this->session->flashdata('success-msg') ?>
    <?php endif; ?>
    <?= form_open('admin/Features/updatePassword') ?>
    <input type="text" name="prev_pass" placeholder="Previous Password" class="form-control">
    <input type="text" name="new_pass" placeholder="New Password" class="form-control">
    <input type="text" name="c_new_pass" placeholder="Verify New Password" class="form-control">
    <input type="submit" class="btn btn-success mt-1" name="submit" value="Change">
    <?= form_close() ?>
</div>