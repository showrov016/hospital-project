<div class="container">
    <h2 class="text-center">Add Manager</h2>
    <?php if (!empty($this->session->flashdata('error-msg'))): ?>
        <?= $this->session->flashdata('error-msg') ?>
    <?php endif; ?>
    <?php if (!empty($this->session->flashdata('success-msg'))): ?>
        <?= $this->session->flashdata('success-msg') ?>
    <?php endif; ?>
    <?= form_open('admin/Features/addFacilityLocationManager') ?>
        <input type="text" name="first_name" placeholder="Firstname">
        <input type="text" name="last_name" placeholder="Lastname">
        <input type="text" name="email" placeholder="E-mail">
        <input type="password" name="password" placeholder="Password">
        <input type="text" name="phone" placeholder="Phone">
        <select name="facility_id">
            <option disabled selected>Select Facility</option>
            <?php foreach ($facilities as $f):?>
            <option value="<?=$f->fac_id?>"><?=$f->name?></option>
            <?php endforeach;?>
        </select>
        <input type="submit" class="btn btn-success mt-1" name="submit" value="Add Manager">
    <?= form_close() ?>
</div>