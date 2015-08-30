/*
 * blueimp Gallery jQuery plugin 1.2.2
 * https://github.com/blueimp/Gallery
 *
 * Copyright 2013, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global define, window, document */
$(function () {
    'use strict';
      $('.loading').html('Loading...');
    $.ajax({
        url: 'https://api.flickr.com/services/rest/',
        data: {
            format: 'json',
            method: 'flickr.interestingness.getList',
            api_key: '7617adae70159d09ba78cfec73c13be3' 
        },
        dataType: 'jsonp',
        jsonp: 'jsoncallback'
    }).done(function (result) {
	    $('.loading').html('');
        var linksContainer = $('#links'),
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