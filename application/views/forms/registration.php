<div class="container">
    <?php if(!empty($this->session->flashdata('error-msg'))):?>
        <?=$this->session->flashdata('error-msg')?>
    <?php endif;?>
    <?php if(!empty($this->session->flashdata('success-msg'))):?>
        <?=$this->session->flashdata('success-msg')?>
    <?php endif;?>
    <?= form_open('Auth/userRegistration') ?>
    <h2 class="text-center">Consultant Registration</h2>
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="fname">First Name</label>
            <input type="text" class="form-control" id="fname" name="fname" placeholder="Firstname">

            <label for="fname">Last Name</label>
            <input type="text" class="form-control" id="fname" name="lname" placeholder="Firstname">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            <input type="submit" name="submit" value="Register" class="btn btn-success mt-1">
        </div>
    </div>
    <?= form_close() ?>
</div>
