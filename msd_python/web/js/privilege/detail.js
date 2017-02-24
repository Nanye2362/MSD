KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="content1"]', {
				resizeType : 1,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						'source','|','fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'link',]
			});
});

$(function(){
	$('input').prop('checked',false);
	
	$('.json').each(function(){
		var arr;
		if($(this).val().length>0){
			arr=eval('('+$(this).val()+')');
			console.log($(this).parent('td').find('div input'));
			$(this).parent('td').find('div input').each(function(){
				console.log($(this).val());
				console.log(arr);
				if($.inArray($(this).val(),arr)>=0){
					$(this).prop('checked',true);
				}
			})
		}
	})
	
	
		
	$('th input').click(function(){
		console.log($(this).prop('checked'));
		$('.td'+$(this).attr('lang')+' input').prop('checked',$(this).prop('checked'));
	})
})