<nav aria-label="Page navigation">
    <ul class="pagination justify-content-start">
        <?php // first page ?>
        <?php if($page_current - $page_delta > 1) {
            $_get_params = $get_params;
            $_get_params['page'] = 1; ?>
            <li class="page-item">
                <a class="page-link" href="?<?= http_build_query($_get_params) ?>" aria-label="Первая">
                    <span aria-hidden="true">1</span>
                    <span class="sr-only">Первая</span>
                </a>
            </li>
        <?php } ?>

        <?php // previous page ?>
        <?php if($page_current > 1) {
            $_get_params = $get_params;
            $_get_params['page'] = $page_current - 1; ?>
            <li class="page-item">
                <a class="page-link" href="?<?= http_build_query($_get_params) ?>" aria-label="Предыдущая">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Предыдущая</span>
                </a>
            </li>
        <?php } ?>

        <?php // delta - pages ?>
        <?php if($page_current > 1) {
            $_get_params = $get_params;
            for($i=$page_delta;$i>0;$i--) {
                if($page_current - $i > 0) {
                    $_get_params['page'] = $page_current - $i; ?>
            <li class="page-item">
                <a class="page-link" href="?<?= http_build_query($_get_params) ?>" aria-label="<?= $_get_params['page'] ?>">
                    <span aria-hidden="true"><?= $_get_params['page'] ?></span>
                    <span class="sr-only"><?= $_get_params['page'] ?></span>
                </a>
            </li>
        <?php   }
            }
        } ?>

        <?php // intermediate pages ?>
            <li class="page-item active">
                <a class="page-link current" href="#" onclick="javascript: return false;"><?= $page_current ?></a>
            </li>

        <?php // delta + pages ?>
        <?php if($page_current < $pages_cnt) {
            $_get_params = $get_params;
            for($i=1;$i<=$page_delta;$i++) {
                if($page_current + $i <= $pages_cnt) {
                    $_get_params['page'] = $page_current + $i; ?>
            <li class="page-item">
                <a class="page-link" href="?<?= http_build_query($_get_params) ?>" aria-label="<?= $_get_params['page'] ?>">
                    <span aria-hidden="true"><?= $_get_params['page'] ?></span>
                    <span class="sr-only"><?= $_get_params['page'] ?></span>
                </a>
            </li>
        <?php   }
            }
        } ?>

        <?php // next page ?>
        <?php if($page_current + 1 <= $pages_cnt) {
            $_get_params = $get_params;
            $_get_params['page'] = $page_current + 1; ?>
            <li class="page-item">
                <a class="page-link" href="?<?= http_build_query($_get_params) ?>" aria-label="Следущая">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Следущая</span>
                </a>
            </li>
        <?php } ?>

        <?php // last page ?>
        <?php if($page_current + $page_delta < $pages_cnt) {
            $_get_params = $get_params;
            $_get_params['page'] = $pages_cnt; ?>
            <li class="page-item">
                <a class="page-link" href="?<?= http_build_query($_get_params) ?>" aria-label="Последняя">
                    <span aria-hidden="true"><?= $pages_cnt ?></span>
                    <span class="sr-only">Последняя</span>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>
