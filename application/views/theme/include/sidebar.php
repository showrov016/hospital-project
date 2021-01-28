<?php if ($this->session->userdata('type') != 'consultant' && $this->session->has_userdata('userId')) : ?>
    <aside id="sidebar" class="expand">
        <div class="sidebar-header">
            <h3>
                Admin Panel
                <a class="btn btn-warning side-menu-toggle pull-right" href="#" style="margin-left: 15px;"><span class="glyphicon glyphicon-align-justify"></span></a>
            </h3>
        </div>
        <div class="sidebar-menu">
            <div class="menu-items list-group panel">

                <?php if ($this->session->userdata('type') != 'super_user') : ?>
                    <a href="<?= base_url('Auth/login') ?>" class="list-group-item">
                        <i class="glyphicon glyphicon-dashboard"></i> Dashboard
                    </a>
                <?php endif; ?>

                <?php if ($this->session->userdata('type') != 'super_user' && $this->session->userdata('type') != 'facility_location_manager' && $this->session->userdata('type') != 'facility_project_manager') : ?>
                    <a href="#modules" class="list-group-item list-group-item-success" data-toggle="collapse">
                        <i class="fa fa-users"></i> Modules
                    </a>
                    <div class="<?= $activeMenu == 'tl' ? '' : 'collapse' ?>" id="modules">
                        <a href="<?= base_url('admin/Features/moduleList') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>List</a>
                        <a href="<?= base_url('admin/Features/addModule') ?>" class="list-group-item"><i class="fa fa-plus"></i>Add Module</a>
                    </div>

                    <a href="#dept" class="list-group-item list-group-item-success" data-toggle="collapse">
                        <i class="fa fa-users"></i> Departments
                    </a>
                    <div class="<?= $activeMenu == 'tl' ? '' : 'collapse' ?>" id="dept">
                        <a href="<?= base_url('admin/Features/deptList') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>List</a>
                    </div>
                    <a href="<?= base_url('admin/Features/consultants') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Consultant List</a>
                <?php endif; ?>


                <?php if ($this->session->userdata('type') == 'app_admin' || $this->session->userdata('type') == 'project_manager') : ?>
                    <a href="#facilities" class="list-group-item list-group-item-success" data-toggle="collapse">
                        <i class="fa fa-users"></i> Facilities
                    </a>
                    <div class="<?= $activeMenu == 'tl' ? '' : 'collapse' ?>" id="facilities">
                        <a href="<?= base_url('admin/Features/facility') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>List</a>
                        <a href="<?= base_url('admin/Features/locations') ?>" class="list-group-item"><i class="fa fa-plus"></i>Locations</a>
                    </div>


                    <a href="<?= base_url('admin/Features/facilityLocationManager') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Facility Location Managers</a>
                    <a href="<?= base_url('admin/Features/superUsers') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Super Users</a>
                    <a href="<?= base_url('admin/Features/teamLead') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Team Lead</a>

                <?php endif; ?>


                <?php if ($this->session->userdata('type') == 'facility_location_manager') : ?>
                    <a href="<?= base_url('admin/Features/superUsers') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Super Users</a>
                <?php endif; ?>


                <a href="<?= base_url('admin/Features/shifts') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Shifts</a>
                <a href="<?= base_url('admin/Features/changeUserPassword') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Change Password</a>

                <?php if ($this->session->userdata('type') == 'app_admin') : ?>
                    <a href="<?= base_url('admin/Features/appAdmin') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>App Admins</a>
                    <a href="<?= base_url('admin/Features/facilityProjectManager') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Facility Project Managers</a>
                <?php endif; ?>


                <!--                    # facilities, super users, locations, facility location managership-->
                <?php if ($this->session->userdata('type') == 'facility_project_manager') : ?>
                    <a href="#facilities" class="list-group-item list-group-item-success" data-toggle="collapse">
                        <i class="fa fa-users"></i> Facilities
                    </a>
                    <div class="<?= $activeMenu == 'tl' ? '' : 'collapse' ?>" id="facilities">
                        <a href="<?= base_url('admin/Features/facility') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>List</a>
                        <a href="<?= base_url('admin/Features/locations') ?>" class="list-group-item"><i class="fa fa-plus"></i>Locations</a>
                    </div>
                    <a href="<?= base_url('admin/Features/superUsers') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Super Users</a>
                    <a href="<?= base_url('admin/Features/locations') ?>" class="list-group-item"><i class="fa fa-plus"></i>Locations</a>
                    <a href="<?= base_url('admin/Features/facilityLocationManager') ?>" class="list-group-item"><i class="fa fa-briefcase"></i>Facility Location Managers</a>
                <?php endif; ?>

            </div>
        </div>
    </aside>

<?php endif; ?>