(function(){
    var ele = document.querySelectorAll(".fl-module-bw-logo-list.fl-node-<?php echo $id; ?>");
    new LogoList({
        element: ele[0]
    });
})();