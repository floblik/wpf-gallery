var abc = 0; //Declaring and defining global increement variable

$( document ).ready(function() {
	
	
	//To add new input file field dynamically, on click of "Add More Files" button below function will be executed
    $('#add_more').click(function(e) {
	    e.preventDefault();
	     $("#filediv").append(
        '<li><br />'
     +  '<input name="image[]" id="image" type="file" class="new_image" accept="image/jpg,image/png,image/jpeg,image/gif" /> '
      + '<a href="#" class="remove_image"><img class="cross_upload" src="./img/x.png" /></a>'
      + '</li>');
});
    
    $('#filediv').on('click', '.remove_image', function(e) {
    e.preventDefault();

    $(this).parent().remove();
});
    

//following function will executes on change event of file input to select different file	
$('body').on('change', '#image', function(){
            if (this.files && this.files[0]) {
                 abc += 1; //increementing global variable by 1
				
				var z = abc - 1;
                var x = $(this).parent().find('#previewimg' + z).remove();
                $(this).before("<div id='abcd"+ abc +"' class='abcd'><img id='previewimg" + abc + "' src=''/></div>");
                $(this).after('<div class="form-group"><input type="text" name="title[]" class="form-control input-sm margintop" placeholder="Titel" /> <input type="text" name="description[]" class="form-control input-sm margintop" placeholder="Beschreibung" /></div>');
               
			    var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
               
				$(this).parent().find('.remove_image').hide();
			    $(this).hide();
			    
                $("#abcd"+ abc).append($("<img/>", {id: 'img', src: './img/x.png', alt: 'delete', class: 'imgdelete'}).click(function() {
                $(this).parent().parent().remove();
                }));
            }
        });

//To preview image     
    function imageIsLoaded(e) {
        $('#previewimg' + abc).attr('src', e.target.result);
    };
	
	$('.stop-propagation').on('click', function (e) {
    e.stopPropagation();
	});
	
// Setzt active class auf li basierend auf url
	var url = window.location;

$('ul.nav a[href="'+ url +'"]').parent().addClass('active');

$('ul.nav a').filter(function() {
	
    return this.href == url;
}).parent().addClass('active');




});
