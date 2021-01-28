<div class="container">
    <?php if (!empty($this->session->flashdata('error-msg'))): ?>
        <?= $this->session->flashdata('error-msg') ?>
    <?php endif; ?>
    <?php if (!empty($this->session->flashdata('success-msg'))): ?>
        <?= $this->session->flashdata('success-msg') ?>
    <?php endif; ?>
    <?= form_open('Auth/saveConsultantInfo') ?>
    <h2 class="text-center">Consultant Registration</h2>
    <div class="form-row">
        <div class="form-group col-md-12">
            <div class="profile-info">
                <div class="image text-center">
                    <?php if (!empty($user)): ?>
                        <?php if ($user->propic != null): ?>
                            <img clas="img img-responsive img-circle" style="height:100px" src="<?= base_url('uploads/profile/' . $user->propic) ?>">
                        <?php else: ?>
                            <img clas="img img-responsive img-circle" style="height:100px" src="<?= base_url('resources/images/placeholder.png') ?>">
                        <?php endif; ?>
                    <?php else: ?>
                        <img clas="img img-responsive img-circle" style="height:100px" src="<?= base_url('resources/images/placeholder.png') ?>">
                    <?php endif; ?>

                    <div id="profile_upload">
                        Upload Photo
                    </div>
                    <input type="file" id="my_propic" name="my_propic" style="display:none">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="name" class="mt-1">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="name" value="<?= (!empty($user)) ? $user->first_name . " " . $user->last_name : "" ?>" readonly>
                </div>
                <div class="col">
                    <label for="mail" class="mt-1">E-mail</label>
                    <input type="text" class="form-control" id="mail" name="email" placeholder="mail" value="<?= (!empty($user)) ? $user->username : "" ?>" readonly>
                </div>
                <div class="col">
                    <label for="address" class="mt-1">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="phone" value="<?= (!empty($user)) ? $user->phone : "" ?>">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="address" class="mt-1">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?= (!empty($consultant) && $consultant->address != null) ? $consultant->address : "" ?>">
                </div>
                <div class="col">
                    <label for="city" class="mt-1">City</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?= (!empty($consultant) && $consultant->city != null) ? $consultant->city : "" ?>">
                </div>
                <div class="col">
                    <label for="state" class="mt-1">State</label>
                    <select class="form-control" name="state">
                        <?php foreach ($states as $s):?>
                        <option value="<?=$s->states?>" <?php if(isset($consultant))
                        {if($s->states==$consultant->state)
                        {echo 'selected';}}?>><?=$s->states?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="col">
                    <label for="zip" class="mt-1">Zip</label>
                    <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" value="<?= (!empty($consultant) && $consultant->zip != null) ? $consultant->zip : "" ?>">
                </div>
            </div>


            <div class="row">
                <label for="shift" class="mt-1">Shift</label>

                <select class="form-control" name="shift" id="shift">
                    <option value="day" <?= !empty($consultant) && $consultant->shift != null && $consultant->shift == 'day' ? 'selected' : "" ?>>Day</option>
                    <option value="night" <?= !empty($consultant) && $consultant->shift != null && $consultant->shift == 'night' ? 'selected' : "" ?>>Night</option>
                    <option value="no preference" <?= !empty($consultant) && $consultant->shift != null && $consultant->shift == 'no preference' ? 'selected' : "" ?>>No Preference</option>
                </select>

                <label for="dept" class="mt-1">Departments</label>

                <select class="form-control" id="dept" data-placeholder="Select Departments" required name="dept">
                    <?php if (!empty($depts)): ?>
                        <?php foreach ($depts as $d): ?>
                            <option value="<?= $d->dept_id ?>" <?php
                            if ($d->dept_id == $selected_dept) {
                                echo "selected";
                            }
                            ?>><?= $d->name ?></option>
    <?php endforeach; ?>
<?php endif; ?>
                </select>
                <label for="module1" class="mt-1">Module 1</label>
                <select class="form-control mt-1" id="module1" data-placeholder="Select Modules" required name="module1">
                    <option value="" disabled selected>Select First Module</option>>
                    <?php foreach ($modules as $m): ?>
                        <option value="<?= $m->name ?>" <?php
                        if ($module1 == $m->name) {
                            echo "selected";
                        }
                        ?>><?= $m->name ?></option>
<?php endforeach; ?>

                </select>
                <label for="module2" class="mt-1">Module 2</label>
                <select class="form-control mt-1" id="module2" data-placeholder="Select Modules" name="module2">
                    <option value="" disabled selected>Select Second Module</option>>
                    <?php foreach ($module2List as $m): ?>
                        <option value="<?= $m->name ?>" <?php
                        if ($module2 == $m->name) {
                            echo "selected";
                        }
                        ?>><?= $m->name ?></option>
<?php endforeach; ?>
                </select>

                <label for="module3" class="mt-1">Module 3</label>
                <select class="form-control mt-1" id="module3" data-placeholder="Select Modules" name="module3">
                    <option value="" disabled selected>Select Third Module</option>>
                    <?php foreach ($module3List as $m): ?>
                        <option value="<?= $m->name ?>" <?php
                        if ($module3 == $m->name) {
                            echo "selected";
                        }
                        ?>><?= $m->name ?></option>
<?php endforeach; ?>
                </select>

                <label for="comment" class="mt-1">Comment</label>
                <input type="text" class="form-control" id="comment" name="comment" placeholder="comment"  value="<?= !empty($consultant) && $consultant->comment != null ? $consultant->comment : "" ?>">
                <input type="hidden" value="<?= $user_id ?>" name="user_id">
                <input type="submit" name="submit" value="Save Information" class="btn btn-success mt-1">
            </div>


        </div>
    </div>
<?= form_close() ?>
</div>


<!-- Modal -->
<div class="modal fade" id="preview_image" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <img class="preview" src="" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="upload_image();">Upload</button>
            </div>
        </div>

    </div>
</div>

<style>

    #profile_upload:hover {
        opacity: 0.5;
    }

    #profile_upload {
        position: absolute;
        width: 100px;
        height: 100px;
        line-height: 100px;
        background: #333;
        cursor: pointer;
        opacity: 0;
        border-radius: 50%;
        right: 0;
        margin: 0 auto;
        top: 0;
        left: 0px;
        color: white;
    }
    img{
        max-width: 100%
    }
</style>
