<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#province').change(function(){
            $.get("{{ url('api/dropdown') }}", { option: $(this).val() }, function(data) {
                var city=$('#city');
                city.empty();
                $.each(data, function(index, element) {
                    city.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                });
            });
        });
    }); 
<!--

//-->
</script>