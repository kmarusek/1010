(function($){
    $(function(){
        new BWPostsGrid({
            element: $('.fl-module-bw-posts-grid.fl-node-<?php echo $id;?>'),
            paginationEnabled: <?php echo $module->paginationIsEnabled() ? 'true' : 'false'; ?>,
            dataSource: <?php echo json_encode( $module->getPaginationDataSource() );?>,
            pageSize: <?php echo $module->getPostsPerPage(); ?>
        });
    });
})(jQuery);