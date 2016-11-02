$(document).ready(function(){
	$('.btn-delete').click(function(e)
	{
		e.preventDefault();

		//obtener el padre del elemento en que estamos 

		var row = $(this).parents('tr');

		//con el metodo data vamos a recuperar el id de tr
		var id = row.data('id');
	//			alert(id);	
		//obtenemos el form 
		var form = $('#form-delete');

		//enviara  ruta correspondiente
		var url = form.attr('action').replace(':USER_ID',id);

		//hay que serializar el form para enviar todo el form como un post 
		var data = form.serialize();

		//alert(data);	
		//enviar datos al controlador

		bootbox.confirm(message, function(res)
		{
			// darle click al boton ok res = true
			if(res == true)
			{
				$('#delete-progress').removeClass('hidden');
				$.post(url, data, function(result)
				{

				$('#delete-progress').addClass('hidden');

					if (result.removed == 1) 
					{
						row.fadeOut();		
							//eliminamos la clase hiddeb para que aparezca la clase
							//$('#message').removeClass('hidden');
							//seleccionamos el id user message para colocar el mensaje		
							toastr.success('Usuario eliminado', 'Alerta');
							//$('#user-message').text(result.message);
							//variable total user para manejar el total de los usuarios
							var totalUsers = $('#total').text();
							//actualizando total
							if($.isNumeric(totalUsers))
							{
								$('#total').text(totalUsers - 1);
							}
							else
							{
								$('#total').text(result.countUsers);
							}
						}																
						else
						{
							//mostrando la clase
							$('message-danger').removeClass('hidden');							
						   //mostrando el mensaje de error
						   // Display an error toast, with a title
							//toastr.error('I do not think that word means what you think it means.', 'Inconceivable!')
							toastr.error('El usuario no se pudo eliminar', 'Es administrador');
							//$('#user-message-danger').text(result.message);
						}
					}).fail(function(data)
					{
						alert("ERROR");
						row.show();
					});
				}
			});
});
});