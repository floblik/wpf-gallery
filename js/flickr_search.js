$(function () {
	var split = location.search.replace('?', '').split('=')
	'use strict';
									  $('.loading').html('Loading...');
									$.ajax({
										url: 'https://api.flickr.com/services/rest/',
										data: {
											format: 'json',
											method: 'flickr.photos.search',
											api_key: '7617adae70159d09ba78cfec73c13be3',
											text: split[1],
										},
										dataType: 'jsonp',
										jsonp: 'jsoncallback'
									}).done(function (result) {
										$('.loading').html('');
										var linksContainer = $('#links-search'),
											baseUrl;
												
										$.each(result.photos.photo, function (index, photo) {
											baseUrl = 'https://farm' + photo.farm + '.static.flickr.com/' +
												photo.server + '/' + photo.id + '_' + photo.secret;
											$('<a/>')
												.append($('<img>').prop('src', baseUrl + '_q.jpg').attr('class','img_abstand'))
												.prop('href', baseUrl + '_b.jpg')
												.prop('title', photo.title)
												.attr('data-gallery', '#blueimp-gallery-flickr')
												.attr('data-description',' ')
												.appendTo(linksContainer);
										});
									});
								
							});