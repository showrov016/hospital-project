<footer>
    <?php
    /**
     * Footer content hidden message / action variable block
     *
     */
    if (isset($csrf)) {
        echo '<input type="hidden" id="csrf_token_name" value="' . $csrf['name'] . '">';
        echo '<input type="hidden" id="csrf_token_hash" value="' . $csrf['hash'] . '">';
    }

    /**
     * Fetch server success/error messages if any
     */
    if ($isLoggedIn) {
        $msg = $this->session->flashdata('msg_success');
        echo '<input type="hidden" id="msg_success" value="' . $msg . '">';

        $msg = $this->session->flashdata('msg_error');
        echo '<input type="hidden" id="msg_error" value="' . $msg . '">';
        
        $msg = $this->session->flashdata('msg_popup');
        echo '<input type="hidden" id="msg_popup" value="' . $msg . '">';
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center footer-text">
                Copyright © <?= date('Y') ?><a href="#"> </a> | All rights reserved
            </div>
        </div>
    </div>
</footer>

