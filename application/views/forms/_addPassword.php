<div class="container">
    <?php if(!empty($this->session->flashdata('error-msg'))):?>
        <?=$this->session->flashdata('error-msg')?>
    <?php endif;?>
    <?php if(!empty($this->session->flashdata('success-msg'))):?>
        <?=$this->session->flashdata('success-msg')?>
    <?php endif;?>
    <?= form_open('Auth/setPassword') ?>
    <h2 class="text-center">Consultant Registration</h2>
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="fname">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>

            <label for="fname">Confirm Password</label>
            <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" required>
            <input type="hidden" value="<?=$username?>" name="username">
            <input type="submit" name="submit" value="Set Password" class="btn btn-success mt-1">
        </div>
    </div>
    <?= form_close() ?>
</div>
