<?php
$config = PluginUihacksForcechoiceconfig::getConfigValues();

if($config['is_activated'] &&
      isset($_SERVER['HTTP_REFERER']) &&
      preg_match("/^([^\/]*\/)*(helpdesk\.public\.php\?create_ticket=1|tracking\.injector\.php|ticket\.form\.php(?!(\?id=\d+))).*/", $_SERVER['HTTP_REFERER']) ) {
?>

//<script>
$(function() {
   var initialized = false;
   $(document).ajaxComplete(forceChoice);
   forceChoice();

   function forceChoice() {
      if(initialized) return;
      var form = $('form[name="form_ticket"], form[name="helpdeskform"]');
      if(form.length === 0) return;
      initialized = true;

      var dd_type = form.find('select[name="type"]');
      var dd_urgency = form.find('select[name="urgency"]');
      var uihState = parseQuery().uihstate || '00';

      form.submit(uihacksSubmit);

      if(uihState.charAt(0) === '0') {
         dd_type.removeAttr('onchange');
         html = '<option value="-1" selected="">-----</option>'+dd_type.html().replace('selected=""', '');
         dd_type
            .html(html)
            .change();
            .change(function(){
               refreshSubmitAction();
               this.form.submit();
            });

         form.find('input[name="itilcategories_id"]').closest('td').html('<?php echo addslashes($config['category_msg']);?>');

      }

      if(uihState.charAt(1) === '0') {
         html = '<option value="-1" selected="">-----</option>'+dd_urgency.html().replace('selected=""', '');
         dd_urgency
            .html(html)
            .change();
            .change(refreshSubmitAction);
      }

      refreshSubmitAction();


      /****************************
       *      Fonctions         *
       ****************************/

      function makeUihacksState() {
         return ((dd_type.val() == -1)?'0':'1') + ((dd_urgency.val() == -1)?'0':'1');
      }

      var savedAction = form.attr('action') + (/\?/.test(form.action) ? '&' : '?');
      function refreshSubmitAction() {
         form.attr('action', savedAction + 'uihstate=' + makeUihacksState());
      }

      function uihacksSubmit() {
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
   }


});
//</script>
<?php
}
?>