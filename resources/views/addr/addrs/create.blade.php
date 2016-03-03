@extends('navbarerp')

@section('main')
    <h1>添加地址</h1>
    <hr/>
    
    {!! Form::open(['url' => 'addr/addrs']) !!}
        @include('addr.addrs._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
<!--     <form> -->
<!--         <div class="form-group"> -->
<!--             <lablel for="province_id">Select Province:</lablel> -->
<!--             <select name="province_id" id="province" class="form-control"> -->
<!--                 <option value="0">--请选择--</option> -->
<!--                 @foreach($provinceList as $province) -->
<!--                     <option>{{ $province }}</option> -->
<!--                 @endforeach -->
<!--             </select> -->
<!--         </div> -->
        
<!--         <div class="form-group"> -->
<!--             <lablel for="city_id">Select City:</lablel> -->
<!--             <select name="city_id" id="city" class="form-control"> -->
                
<!--             </select> -->
<!--         </div> -->
<!--     </form> -->
    
<!--     <script type="text/javascript">
    var region = {
 		   getProvinces: function() {
 	 		   region_info.cid = 0;
 	 		   $.get('{{url('common/ajax/provinces')}}', function(data) {
 	 	 		   if (data) {
 	 	 	 		   region_info.provinces = data;
 	 	 		   }
 	 		   });
 		   },
 		   getCities: function(pid) {
 	 		   region_info.rid = 0;
 	 		   $.get('{{url('common/ajax/cities')}}', {pid: pid}, function(data) {
 	 	 		   if (data) {
 	 	 	 		   region_info.cities = data;
 	 	 		   }
 	 		   });
 		   }
    };
    

</script>
     -->
    

    
<!--     <script type="text/javascript"> 
    jQuery(document).ready(function($) {
        $('#make').change(function() {
            $.get("{{ url('api/dropdown') }}", 
                    {option: $(this).val() },
                    function(data) {
                        var model = $('#model');
                        model.empty();

                        $.each(data, function(index, element) {
                            model.append("<option value='" + element.id + "'>" + element.name + "</option>");
                        });
                    });
        });
    });
</script>-->
    
    {{-- 
    <form>
        <div class="form-group">
            <label for="province">Select Province:</label>
                {!! Form::select('province_id', array('0' => '--请选择--') + json_decode($provinceList, true), null, ['class' => 'form-control', 'id' => 'province']) !!} 
            </select>
        </div>
        
        <div class="form-group">
            <label for="city">Select City:</label>
            {!! Form::select('city_id', array(), null, ['class' => 'form-control', 'id' => 'city']) !!}
        </div>
    </form>
    --}}

            
       
    </script>
    
<!--     <script type="text/javascript"> 
    $(document).on('change', '#province', function (e) {

        // empty the select with previous cities if we have.
        $('#city').empty();            

        $.ajax({
                type: "POST",
                dataType: "json",
                // actions is a controller
                // cities is a method of actions controller
                url : "{{ URL::to('actions/cities') }}",
                //here we set the data for the post based in our form 
                data : $('#MyFormID').serialize(),
                success:function(data){                    
                        if(data.error === 0 ){ // all was ok                                

                        for (var i = 0; i < data.cities.length; i++) { 
                            $('#city').append("<option value='"+data.cities[i].id+"'>"+data.cities[i].city_name+"</option>")
                        }

                        }else{
                            alert(data);
                        }
                },
                timeout:10000
        });                         
    });
</script>-->
    
    @include('errors.list')
@stop

@section('script')
    @include('addr.addrs._form_js', [])

{{--
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#province').change(function(){
//             alert($(this).val());
            $.get("{{ url('api/dropdown') }}", { option: $(this).val() }, function(data) {
                var city=$('#city');
                city.empty();
                $.each(data, function(index, element) {
//                     alert(element.name);
                    city.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                });
            });
//             $.get("{{ url('api/dropdown') }}"));
//             $.get("{{ url('api/dropdown') }}"), function(data)
//                     {
//                 $.each(data, function(index, element) {
//                     alert(element.name);
//                 });
//             $.get("{{ url('api/dropdown') }}",
//             { option: $(this).val() },
//             function(data) {
//             var city = $('#city');
//             city.empty();
//             $.each(data, function(index,element) {
//                 city.append("<option value='"+ element.id +"'>" + element.name + "</option>");
//             });
//             });
        });
    }); 
<!--

//-->
</script>
--}}

    <script type="text/javascript">
//         jQuery(document).ready(function($){
//             alert('aaa');
//             $('#province').change(function(){
//                 $.get("{{ url('api/dropdown')}}",
//                 { option: $(this).val() },
//                 function(data) {
//                 var city = $('#city');
//                 city.empty();
//                 $.each(data, function(index,element) {
//                     city.append("<option value='"+ element.id +"'>" + element.city + "</option>");
//                 });
//                 });
//             });
//             $('#city').change(function(){
//                 $.get("{{ url('api/dropdowndist')}}",
//                 { stateoption: $(this).val() },
//                 function(data) {
//                     var district = $('#district');
//                     district.empty();
//                     $.each(data, function(index,element) {
//                         district.append("<option value='"+ element.id +"'>" + element.district + "</option>");
//                 });
//                 });
//             });
//         });    
@stop