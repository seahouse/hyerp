@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($item, ['method' => 'PATCH', 'action' => ['Product\ItemsController@update', $item->id], 'class' => 'form-horizontal']) !!}
        @include('product.items._form', ['submitButtonText' => '保存', 'marketprice' => $item->marketprice, 'itemId' => $item->id])
    {!! Form::close() !!}
    
 {{--     <ul class="nav nav-tabs">
        {{ URL('items/edit_charass') }}
        <li role="presentation" class="active"><a href="/items/edit_charass">Home</a></li>
        <li role="presentation"><a href="#">Profile</a></li>
        <li role="presentation"><a href="#">Messages</a></li>
    </ul> --}}
    
    @include('product.items.edit_charass')    


    @include('errors.list')
@endsection


@section('script')
<script type="text/javascript">
    jQuery(document).ready(function(e) {
        $("#btnAddCharass").bind("click", function() {
     	    $.ajax({
         	    type:"POST",
         	    url:"{{ url('product/charasses/addrecord') }}",
         	    data: $("form#formCharass").serialize(),
          	    contentType:"application/x-www-form-urlencoded",
         	    error:function(xhr, ajaxOptions, thrownError){
             	    alert($("form#formCharass").serialize());
             	    alert('error');
					alert(xhr.status);
					alert(xhr.responseText);
					alert(ajaxOptions);
					alert(thrownError);
             	},
             	success:function(msg){
             	    alert('添加成功:' + msg);
              	    $('#exampleModal').modal('toggle');
					$.getJSON("{{ url('product/charasses/getCharassesByTargetId/I/' . $item->id) }}", 
							function(data) {
								var listItem = '';
								$.each(data, function(i, field){
									listItem += '<tr>'
										+ '<td>' + field.char_name + '</td>'
										+ '<td>' + field.value + '</td>'
										+ '<td>' + field.default_str + '</td>'
										+ '<td>' + field.price + '</td>'
										+ '<td><button type="button" id="btnRemoveCharass" class="glyphicon glyphicon-remove" onclick="removecharass(' + field.id + ')"></button></td>'
										+ '</tr>';
								});		
						        
						        $("#charassTable tbody").empty().append(listItem);
							});
                },
         	});
  	  });


        
    });

    function removecharass(id) {
	    $.ajax({
	    type:"GET",
	    url:"{{ url('product/charasses/removerecord/') }}" + "/" + id,
// 	    data:"id=" . id,
	    error:function(xhr, ajaxOptions, thrownError){
 	    alert('error');
			alert(xhr.status);
			alert(xhr.responseText);
			alert(ajaxOptions);
			alert(thrownError);
		},
     	success:function(msg){
     	    alert('删除成功.');
    		$.getJSON("{{ url('product/charasses/getCharassesByTargetId/I/' . $item->id) }}", 
    				function(data) {
    					var listItem = '';
    					$.each(data, function(i, field){
    						listItem += '<tr>'
    							+ '<td>' + field.char_name + '</td>'
    							+ '<td>' + field.value + '</td>'
    							+ '<td>' + field.default_str + '</td>'
    							+ '<td>' + field.price + '</td>'
    							+ '<td><button type="button" id="btnRemoveCharass" class="glyphicon glyphicon-remove" onclick="removecharass(' + field.id + ')"></button></td>'
    							+ '</tr>';
    					});		
    			        
    			        $("#charassTable tbody").empty().append(listItem);
    				});
        },
	});
    };
    
    $('#exampleModal').on('shown.bs.modal', function (event) {
    	  var button = $(event.relatedTarget) // Button that triggered the modal
    	  var recipient = button.data('whatever') // Extract info from data-* attributes
    	  
    	  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).

    	  
    	  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    	  var modal = $(this)
//     	  modal.find('.modal-title').text('New message to ' + recipient)
//     	  modal.find('.modal-body input').val(recipient)
    	});

//     $('#exampleModal').on('hide.bs.modal', function (event) {
//     	location.reload();
//     	});
</script>

@endsection
