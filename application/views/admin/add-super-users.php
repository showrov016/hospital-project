<div class="container">
    <h2 class="text-center">Add Super Users</h2>
    <?php if (!empty($this->session->flashdata('error-msg'))): ?>
        <?= $this->session->flashdata('error-msg') ?>
    <?php endif; ?>
    <?php if (!empty($this->session->flashdata('success-msg'))): ?>
        <?= $this->session->flashdata('success-msg') ?>
    <?php endif; ?>
    <?= form_open('admin/Features/addSuperUser') ?>
    <input type="text" name="first_name" placeholder="First Name" id="s_fname">
    <input type="text" name="last_name" placeholder="Last Name" id="s_lname">
    <input type="text" name="username" placeholder="Username" readonly id="s_uname">
    <select name="shift">
        <option disabled selected>Select Shift</option>
        <option value="day">day</option>
        <option value="night">night</option>
    </select>
    <input type="password" name="password" placeholder="Password">
    <select name="location">
        <option disabled selected value="">Select Location</option>
        <?php foreach ($locations as $l): ?>
            <option value="<?= $l->location_id ?>"><?= $l->name ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" class="btn btn-success mt-1" name="submit" value="Add Super User">
    <?= form_close() ?>
</div>