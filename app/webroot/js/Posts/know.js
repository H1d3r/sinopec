$(document).ready(function() {

	$('#PostDocId').change(getList);
	function getList(){
		var treaid = $('#PostDocId').val();
		var formData = {
				'treaid':treaid
		};
		//alert(treaid);
		var formUrl = '/admin/Posts/doctype';
		$.ajax({
			type: 'POST',
			url: formUrl,
			data: formData,
			success: function(data,textStatus,xhr){
				//alert(data.getdata);
				//alert('操作成功');
				$("#KnowledgesfessionsId").html(data);
			},
			error: function(xhr,textStatus,error){
				alert(textStatus);
			}
		});
		return false;
	}

} );