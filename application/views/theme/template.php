<?php
header('Expires: Sun, 01 Jan 2019 00:00:00 GMT');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ob_start(); // Start Output Buffering
?>
<!DOCTYPE html>
<html>
    <?php
    /**
     * Site Header Template
     */
    $this->load->view('theme/include/header');
    ?>
    <body>

        
        <?php
        /**
         * Site Sidebar Template 
         */
        $this->load->view('theme/include/sidebar');

        /**
         * Site Navigation-bar Template
         */
        $this->load->view('theme/include/navbar');
        ?>
        <div id="main_content" <?= $isLoggedIn ? 'class="expand"' : 'class="no-margin"'; ?>>
            <div id="siteLoader"><div></div></div>
                    <?php
                    /**
                     * Site Body Content Template
                     */
                    $this->load->view('theme/include/content');
                    ?>

        </div>
        <?php
        /**
         * Site Footer Template
         */
        $this->load->view('theme/include/footer');
        ?>
    </body>
</html>
<?php
ob_flush(); // Flush Output Buffer

