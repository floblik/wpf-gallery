function deleteImageHandler () {
	$(".jg-entry").off("click");
    $(".jg-entry").on('click', '.js-delete-image', (function(e) {        
		e.preventDefault();		
        var image_id = $(this).attr("data-image-id");
		var formData = {
			"imageId": image_id
		};
		var eythis = $(this);
        $.ajax({
            url: "process_delete.php", // Url to which the request is send
            type: "POST", // Type of request to be send, called as method
            data: {
				'imageId': image_id
			}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            cache: false, // To unable request pages to be cached
            success: function(data) // A function to be called if request succeeds
                {
					$(eythis).parent().fadeOut(function () {
						$(eythis).parent().remove();
						$(".grid").justifiedGallery("destroy");
						$(".grid").justifiedGallery({
							rowHeight: 150
						});		
					});
								
                }

        })
    }));
}

$(document).ready(function() {
	
    $(".grid").justifiedGallery({
        rowHeight: 150
    });

    $(".avatarForm").on('submit', (function(e) {
        e.preventDefault();

        if (!$('#avatar').val()) {
            $(".upload-msg").html("<span class='msg-error'>Bitte ein Bild auswählen.</span>");
            return false;
        }

        var ext = $('#avatar').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            $(".upload-msg").html("<span class='msg-error'>Es sind nur die Formate .jpeg, .jpg, .gif und .png erlaubt.</span>");
            return false;
        }

        $('#avatarbutton').prop('disabled', true).val('Loading...');

        $.ajax({
            url: "process_upload.php", // Url to which the request is send
            type: "POST", // Type of request to be send, called as method
            data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false, // To send DOMDocument or non processed data file it is set to false
            success: function(data) // A function to be called if request succeeds
                {

                    $('#avatarbutton').prop('disabled', false).val('Edit Avatar');

                    $('.upload-msg').html('');
                    $('.avatarForm').each(function() {
                        this.reset();
                    });

                    if (data.indexOf("Error") > -1) {
                        $('.upload-msg').html(data);
                    } else {
                        $('#uploadavatar').hide().html(data).fadeIn("slow");
						deleteImageHandler();

                    }

                }

        })
    }));
    
        $("#avatar").change(function() {
        $(".upload-msg").empty();
    });


    $(".uploadForm").on('submit', (function(e) {
        e.preventDefault();

        if (!$('#image').val()) {
            $(".upload-msg").html("<span class='msg-error'>Bitte ein Bild auswählen.</span>");
            return false;
        }

        var ext = $('#image').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            $(".upload-msg").html("<span class='msg-error'>Es sind nur die Formate .jpeg, .jpg, .gif und .png erlaubt.</span>");
            return false;
        }
        $(".upload-msg").html('<div id="progressBar"><div id="status"></div><div id="percentage">0%</div></div>');

        $.ajax({
            url: "process_upload.php", // Url to which the request is send
            type: "POST", // Type of request to be send, called as method
            data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            xhr: function() {
                myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    $('#progressBar').show();
                    $('#upload').prop('disabled', true).val('Loading...');
                    myXhr.upload.addEventListener('progress', function(ev) {
                        if (ev.lengthComputable) {
                            var percentComplete = Math.round((ev.loaded / ev.total) * 100);
                            $('#percentage').text(percentComplete + '%');
                            $('#status').css('width', percentComplete + '%');
                        }
                    }, false);
                }
                return myXhr;

            }, 
            success: function(data) // A function to be called if request succeeds
                {

                    $('.upload-msg').html('');
                    $('.uploadForm').each(function() {
                        this.reset();
                    });
                    $(".img-preview").html('');

                    $('#upload').prop('disabled', false).val('Upload');



                    if (data.indexOf("Error") > -1) {
                        $('.upload-msg').html(data);
                    } else if (!data) {
                        $('.upload-msg').html("<span id='error' class='bg-danger'>Es gab einen Serverfehler beim Upload.<br />Die Datei war wahrscheinlich zu groß.</span>");
                    } else {
                        $(data).hide().prependTo(".grid").fadeIn("slow");
                        $(".grid").justifiedGallery("destroy");
                        $(".grid").justifiedGallery({
                            rowHeight: 150
                        });
                        var $number = $(".count");
                        $number.html((parseInt($number.html(), 10) || 0) + 1)
                    }
					deleteImageHandler();

                }

        })
    }));

	
    $(".jg-entry").on('click', '.js-delete-image', (function(e) {
        
		e.preventDefault();
		
        var image_id = $(this).attr("data-image-id");
		var formData = {
			"imageId": image_id
		};
		var eythis = $(this);
        $.ajax({
            url: "process_delete.php", // Url to which the request is send
            type: "POST", // Type of request to be send, called as method
            data: {
				'imageId': image_id
			}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            cache: false, // To unable request pages to be cached
            success: function(data) // A function to be called if request succeeds
                {
					$(eythis).parent().fadeOut(function () {
						$(eythis).parent().remove();
						$(".grid").justifiedGallery("destroy");
						$(".grid").justifiedGallery({
							rowHeight: 150
						});		
					});
								
                }

        })
    }));

	deleteImageHandler();
	
    $("#image").change(function() {
        $(".upload-msg").empty();
        var ext = $('#image').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            $(".upload-msg").html("<span class='msg-error'>Es sind nur die Formate .jpeg, .jpg, .gif und .png erlaubt.</span>");
            return false;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            $(".img-preview").fadeIn().html('<div class="abcd"><img id="previewimg" src="' + e.target.result + '" alt="" /></div><div class="form-group"><input type="text" name="title" class="form-control input-sm margintop" placeholder="Titel (optional)" /> <input type="text" name="description" class="form-control input-sm margintop" placeholder="Beschreibung (optional)" /></div>');
        };
        reader.readAsDataURL(this.files[0]);

    });

	// prevent login form from closing
    $('.stop-propagation').on('click', function(e) {
        e.stopPropagation();
    });


    // Setzt active class auf li basierend auf url
    var url = window.location;

    $('ul.nav a[href="' + url + '"]').parent().addClass('active');

    $('ul.nav a').filter(function() {

        return this.href == url;
    }).parent().addClass('active');

    $('#blueimp-gallery').on('slide', function(event, index, slide) {
        var src = $(slide).find("img").attr("src")
        var link = $("a").filter(function() {
            return this.href === src
        })
        $(this).children('.description')
            .text(link.data('description'));
    });


});