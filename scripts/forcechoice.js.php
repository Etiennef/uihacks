<?php
include ("../../../inc/includes.php");
header("Content-type: application/javascript");
$config = PluginUihacksForcechoiceconfig::getConfigValues();

if($config['is_activated_for_type'] || $config['is_activated_for_urgency']) {
?>

//<script>
Ext.onReady(function() {
    var config = {
          forcetype : <?php echo $config['is_activated_for_type'] ? 'true' : 'false' ?>,
          forceurgency : <?php echo $config['is_activated_for_urgency'] ? 'true' : 'false' ?>,
      };
    var form = $('form[name="form_ticket"], form[name="helpdeskform"]');
    var dd_type = form.find('select[name="type"]');
    var dd_urgency = form.find('select[name="urgency"]');
    var uihState = parseQuery().uihstate || '00';

    if(uihState.charAt(0) === '0' && config.forcetype) {
        dd_type.find('[selected]').removeAttr('selected');
        dd_type.prepend('<option value="-1" selected="">-----</option>');

        form.find('select[name="itilcategories_id"]').parent().parent()
                .html('<?php echo addslashes($config['category_msg']);?>');
    }

    if(uihState.charAt(1) === '0' && config.forceurgency) {
        dd_urgency.find('[selected]').removeAttr('selected');
        dd_urgency.prepend('<option value="-1" selected="">-----</option>');
    }

    form.find('[onchange="submit()"]')
        .removeAttr('onchange')
        .change(uihacksOnchange)
        .attr('onchange', 'submit()');

    form.submit(uihacksSubmit);

    /****************************
     *        Fonctions            *
     ****************************/

    function makeUihacksState() {
        return ((dd_type.val() == -1 && config.forcetype)?'0':'1') +
        ((dd_urgency.val() == -1 && config.forceurgency)?'0':'1');
    }

    function uihacksOnchange() {
        //console.log('uihacksOnchange()');
        form.attr('action', form.attr('action') + (/\?/.test(form.action) ? '&' : '?') + 'uihstate=' + makeUihacksState());
    }

    function uihacksSubmit() {
        //console.log('uihacksSubmit()');
        if(makeUihacksState() === '11') {
            return true;
        } else {
            alert('<?php echo addslashes($config['bad_submit_msg']);?>');
            return false;
        }
    }

    function parseQuery() {
        var result = {},
        tmp = [];
        location.search.substr(1).split("&").forEach(function (item) {
            tmp = item.split("=");
            result[tmp[0]] = tmp[1];
        });
        return result;
    }
});
//</script>
<?php
}
?>