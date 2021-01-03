<div id="target-content">loading...</div>
<div class="loading-div loader">
    <div class="loader-content">
        <img src="image/loader.gif" style="text-align: center" width=""/>
    </div>
</div>
<div class="clearfix">
    <div class="pagination">
        <ul>
            <?php
            if (!empty($total_pages)) {

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == 1) {
                        ?><li class="pageitem active" id="<?php echo $i; ?>"><a href="JavaScript:Void(0);" data-id="<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
                        <?php
                    } else {
                        ?>
                        <li class="pageitem" id="<?php echo $i; ?>"><a href="JavaScript:Void(0);" class="page-link" data-id="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
    </div>
</div>