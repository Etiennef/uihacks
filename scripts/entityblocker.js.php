<?php
include ("../../../inc/includes.php");
header("Content-type: application/javascript");


if(isset($_SERVER['HTTP_REFERER']) &&
        preg_match("/^([^\/]*\/)*(helpdesk\.public\.php\?create_ticket=1|tracking\.injector\.php|ticket\.form\.php(?!(\?id=\d+))).*/", $_SERVER['HTTP_REFERER'])) {
    $replacement = PluginUihacksEntityblockerrule::getApplicableReplacement();

    if($replacement !== false) {
    ?>

    //<script>

    $(function() {
        var form = $('form[name="form_ticket"], form[name="helpdeskform"]');

        // Replace form with message
        var div = $('<div/>');
        div.html('<?php echo addslashes($replacement);?>');
        form.replaceWith(div);

    });

    // </script>

    <?php
    }
}
?>