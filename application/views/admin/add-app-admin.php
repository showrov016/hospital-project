<div class="container">
    <h2 class="text-center">Add App Admin</h2>
    <?php if (!empty($this->session->flashdata('error-msg'))): ?>
        <?= $this->session->flashdata('error-msg') ?>
    <?php endif; ?>
    <?php if (!empty($this->session->flashdata('success-msg'))): ?>
        <?= $this->session->flashdata('success-msg') ?>
    <?php endif; ?>
    <?= form_open('admin/Features/addAppadmin') ?>
    <input type="text" name="first_name" placeholder="First Name" >
    <input type="text" name="last_name" placeholder="Last Name" >
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <input type="text" name="phone" placeholder="Phone">
    <input type="submit" class="btn btn-success mt-1" name="submit" value="Add App Admin">
    <?= form_close() ?>
</div>