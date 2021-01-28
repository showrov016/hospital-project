<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            
            <?php if ($this->session->has_userdata('userId')): ?>
                <li class="nav-item">
                    <a class="nav-link float-right" href="<?= base_url('Auth/logout') ?>">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link float-right" href="<?= base_url('Auth/login') ?>">Log in</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link float-right" href="<?= base_url('Auth/userRegistration') ?>">Register</a>
                </li>
            <?php endif; ?>
        </ul>

    </div>
</nav>
