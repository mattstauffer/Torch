<ul class="pagination">
    <!-- Previous Page Link -->
    <?php if ($paginator->onFirstPage()) : ?>
        <li class="disabled"><span>&laquo;</span></li>
    <?php else : ?>
        <li><a href="<?php echo $paginator->previousPageUrl(); ?>" rel="prev">&laquo;</a></li>
    <?php endif; ?>

    <!-- Pagination Elements -->
    <?php foreach ($elements as $element) : ?>
        <!-- "Three Dots" Separator -->
        <?php if (is_string($element)) : ?>
            <li class="disabled"><span><?php echo $element;?></span></li>
        <?php endif; ?>

        <!-- Array Of Links -->
        <?php if (is_array($element)) : ?>
            <?php foreach ($element as $page => $url) : ?>
                <?php if ($page == $paginator->currentPage()) : ?>
                    <li class="active"><span><?php echo $page; ?></span></li>
                <?php else : ?>
                    <li><a href="<?php echo $url; ?>"><?php echo $page; ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Next Page Link -->
    <?php if ($paginator->hasMorePages()) : ?>
        <li><a href="<?php echo $paginator->nextPageUrl(); ?>" rel="next">&raquo;</a></li>
    <?php else : ?>
        <li class="disabled"><span>&raquo;</span></li>
    <?php endif; ?>
</ul>