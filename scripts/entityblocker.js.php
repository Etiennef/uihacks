<?php
$replacement = PluginUihacksEntityblockerrule::getApplicableReplacement();

if($replacement !== false) {
?>

//<script>

$(function() {
   var initialized=false;
   $(document).ajaxComplete(maskForm);
   maskForm();

   function maskForm() {
      if(initialized) return;
      var form = $('form[name="form_ticket"], form[name="helpdeskform"]');
      if(form.length === 0) return;
      initialized = true;

      form.children().remove();
      form.append('<?php echo addslashes($replacement);?>');
   }
});
// </script>

<?php
}
?>