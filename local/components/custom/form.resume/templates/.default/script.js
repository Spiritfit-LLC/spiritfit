$( document ).ready(function() {
	$(".file-selector input").change(function(e) {
		var p = $(this).parents(".file-selector").find("span").eq(1);
		var f = e.target.files;
		var t = '';
		len = f.length;
		for (var i=0; i < len; i++) {
			t += f[i].name + ' ';
		}
		if( t !== '' ) {
			p.text(t);
		} else {
			p.text('Прикрепить резюме');
		}
	});
});