function removequest(object)
{
	var objectparent = $(object).parent();
	objectparent = $(objectparent).parent();
	$(objectparent).find('.field_text').attr('value', '');
	$(objectparent).hide();
	return false;
}

$(document).ready(function(){
	$("#quest").tableDnD({
		onDragClass:'tabledrag',
		onDrop:function(table,row) {
			var rows = table.tBodies[0].rows;
			for (var i=0; i<rows.length; i++)
			{
				$(rows[i])
				.find('.field_number').attr('value', i);
			}
		}
	});
	$("#quest_new").hide();
	$("#questbefore").show();
	$("#tester_selector").find('select').change(function () {
		var change =$(this).attr('value');
		if(change==1)
		{
			$('#surveytester').show();
			$('#surveytester').find('input').attr('value', 10);
		}
		else
		{
			$('#surveytester').hide();
			$('#surveytester').find('input').attr('value', 0);
		}

	});

	$(".field_type").change(function () {
		var objectparent = $(this).parent();
		objectparent = $(objectparent).parent();
		var valid =  $(this).attr('value');
		var vtext =  $("#help"+valid).html();
		$(objectparent).find('.desc').html(vtext);
		$(objectparent).find('.desc').attr('title',vtext);
		$(objectparent).find('.field_choices').attr('title',vtext);
		if (valid==6)
		{
			$(objectparent).find('.field_choices').attr('disabled', 'disabled');
			$(objectparent).find('.field_right').attr('disabled', 'disabled');
		}
		else
		{
			$(objectparent).find('.field_choices').removeAttr("disabled");
			$(objectparent).find('.field_right').removeAttr("disabled");
		}
	});

	$("#addoption").click(function () {
		var object = $('#quest_new').clone().attr("id", 'quest_'+ident);
		$(object).find('.field_number').attr('value', ident);

		$(object).find('.field_number').attr('name', 'field_number['+qid+']');
		$(object).find('.field_id').attr('name', 'field_id['+qid+']');
		$(object).find('.field_text').attr('name', 'field_text['+qid+']');
		$(object).find('.field_req').attr('name', 'field_req['+qid+']');
		$(object).find('.field_type').attr('name', 'field_type['+qid+']');
		$(object).find('.field_choices').attr('name', 'field_choices['+qid+']');
		$(object).find('.field_right').attr('name', 'field_right['+qid+']');

		$(object).find('.qid').html('');

		$(object).find('.field_type').change(function () {
			var valid =  $(this).attr('value');
			var vtext =  $("#help"+valid).html();
			$(object).find('.field_choices').attr('title',vtext);
			if (valid==6)
			{
				$(object).find('.field_choices').attr('disabled', 'disabled');
				$(object).find('.field_right').attr('disabled', 'disabled');
			}
			else
			{
				$(object).find('.field_choices').removeAttr("disabled");
				$(object).find('.field_right').removeAttr("disabled");
			}
		});

		$(object).insertBefore('#questbefore').show();
		ident++;
		qid++;
		$("#quest").tableDnDUpdate();
	});
        
	$(".field_type").change();
	if(ident < 2)
	{
		$("#addoption").click();
	}
	$("#tester_selector").show();

	if ($('#surveytester').find('input').attr('value') == 0)
	{
		$('#surveytester').hide();
	}
	else {
		$('#surveytester').show();
		$("#tester_selector").find('select').attr('value', '1');
	}

});