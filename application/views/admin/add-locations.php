<div class="container">
    <h2 class="text-center">Add Locations</h2>
    <?php if (!empty($this->session->flashdata('error-msg'))): ?>
        <?= $this->session->flashdata('error-msg') ?>
    <?php endif; ?>
    <?php if (!empty($this->session->flashdata('success-msg'))): ?>
        <?= $this->session->flashdata('success-msg') ?>
    <?php endif; ?>
    <?= form_open('admin/Features/addLocations') ?>
    <select name="fac_id" class="form-control" id="facility_list">
        <option value="" disabled selected>Select a Facility</option>
        <?php foreach ($facs as $f):?>
            <option value="<?=$f->fac_id?>"><?=$f->name?></option>
        <?php endforeach;?>
    </select>
    <input type="text" name="location_name" placeholder="Enter Location Name" class="form-control" id="location_name">
    <select name="location_type" class="form-control">
        <option disabled selected value="">Select Department Type</option>
        <?php foreach ($depts as $d):?>
            <option value="<?=$d->dept_id?>"><?=$d->type?></option>
        <?php endforeach;?>
    </select>
    
    <select name="project_manager" class="form-control" id="project_manager">
        <option disabled selected value="">Select Facility Location Manager</option>
    </select>
    <input type="submit" class="btn btn-success mt-1" name="submit" value="Add Location" id="add_location">
    <span class="text-danger" id="err"></span>
    <?= form_close() ?>
</div>