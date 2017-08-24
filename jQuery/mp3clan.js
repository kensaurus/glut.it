// ==UserScript==
// @name         mp3clan bitrate checker & cleaner
// @namespace    http://glut.it
// @version      0.1
// @description  remove ads, check bitrates.
// @author       kenjisakuramoto
// @match        http://mp3clan.com/*
// @grant        none
// ==/UserScript==

(function() {
    'use strict';
    //$('div.mp3list-bitrate').click();
    //$('div.mp3list-bitrate').on('click', function(){});
    $("*").unbind("click");
    //$("[onclick]").not('.mp3list-bitrate').removeAttr("onclick");
    $('div.mp3list-bitrate').each(function(index){
        $(this).click();
    });
    $('.mp3list-ringtone').hide();
    $('.dropin-btn-status1').hide();
    $('.clan-bar').hide();
    $('.social-share').hide();
    $('.clan-tabs').hide();
        $('.footer').hide();
        $('.banner-roll').hide();
        $('#banner-album').hide();
        $('#banner-pub').hide();
})();
