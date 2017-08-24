// ==UserScript==
// @name        soundcloudDownloader
// @namespace   http://glut.it
// @version	1
// @description Creates download links beneath soundcloud tracks to mp3clan
// @author      kenjisakuramoto
// @include     http://soundcloud.com/*
// @include     https://soundcloud.com/*
// @include     http://www.soundcloud.com/*
// @include     https://www.soundcloud.com/*
// @require     https://code.jquery.com/jquery-1.11.1.min.js
// @run-at      document-start
// ==/UserScript==
/*
 * arrive.js
 * v2.3.1
 * https://github.com/uzairfarooq/arrive
 * MIT licensed
 * Copyright (c) 2014-2016 Uzair Farooq
 *
 */
var Arrive = function(a, b, c) {
    "use strict";

    function l(a, b, c) {
        e.addMethod(b, c, a.unbindEvent), e.addMethod(b, c, a.unbindEventWithSelectorOrCallback), e.addMethod(b, c, a.unbindEventWithSelectorAndCallback)
    }

    function m(a) {
        a.arrive = j.bindEvent, l(j, a, "unbindArrive"), a.leave = k.bindEvent, l(k, a, "unbindLeave")
    }
    if (a.MutationObserver && "undefined" != typeof HTMLElement) {
        var d = 0,
            e = function() {
                var b = HTMLElement.prototype.matches || HTMLElement.prototype.webkitMatchesSelector || HTMLElement.prototype.mozMatchesSelector || HTMLElement.prototype.msMatchesSelector;
                return {
                    matchesSelector: function(a, c) {
                        return a instanceof HTMLElement && b.call(a, c)
                    },
                    addMethod: function(a, b, c) {
                        var d = a[b];
                        a[b] = function() {
                            return c.length == arguments.length ? c.apply(this, arguments) : "function" == typeof d ? d.apply(this, arguments) : void 0
                        }
                    },
                    callCallbacks: function(a) {
                        for (var c, b = 0; c = a[b]; b++) c.callback.call(c.elem)
                    },
                    checkChildNodesRecursively: function(a, b, c, d) {
                        for (var g, f = 0; g = a[f]; f++) c(g, b, d) && d.push({
                            callback: b.callback,
                            elem: g
                        }), g.childNodes.length > 0 && e.checkChildNodesRecursively(g.childNodes, b, c, d)
                    },
                    mergeArrays: function(a, b) {
                        var d, c = {};
                        for (d in a) c[d] = a[d];
                        for (d in b) c[d] = b[d];
                        return c
                    },
                    toElementsArray: function(b) {
                        return "undefined" == typeof b || "number" == typeof b.length && b !== a || (b = [b]), b
                    }
                }
            }(),
            f = function() {
                var a = function() {
                    this._eventsBucket = [], this._beforeAdding = null, this._beforeRemoving = null
                };
                return a.prototype.addEvent = function(a, b, c, d) {
                    var e = {
                        target: a,
                        selector: b,
                        options: c,
                        callback: d,
                        firedElems: []
                    };
                    return this._beforeAdding && this._beforeAdding(e), this._eventsBucket.push(e), e
                }, a.prototype.removeEvent = function(a) {
                    for (var c, b = this._eventsBucket.length - 1; c = this._eventsBucket[b]; b--) a(c) && (this._beforeRemoving && this._beforeRemoving(c), this._eventsBucket.splice(b, 1))
                }, a.prototype.beforeAdding = function(a) {
                    this._beforeAdding = a
                }, a.prototype.beforeRemoving = function(a) {
                    this._beforeRemoving = a
                }, a
            }(),
            g = function(b, d) {
                var g = new f,
                    h = this,
                    i = {
                        fireOnAttributesModification: !1
                    };
                return g.beforeAdding(function(c) {
                    var i, e = c.target;
                    c.selector, c.callback;
                    (e === a.document || e === a) && (e = document.getElementsByTagName("html")[0]), i = new MutationObserver(function(a) {
                        d.call(this, a, c)
                    });
                    var j = b(c.options);
                    i.observe(e, j), c.observer = i, c.me = h
                }), g.beforeRemoving(function(a) {
                    a.observer.disconnect()
                }), this.bindEvent = function(a, b, c) {
                    b = e.mergeArrays(i, b);
                    for (var d = e.toElementsArray(this), f = 0; f < d.length; f++) g.addEvent(d[f], a, b, c)
                }, this.unbindEvent = function() {
                    var a = e.toElementsArray(this);
                    g.removeEvent(function(b) {
                        for (var d = 0; d < a.length; d++)
                            if (this === c || b.target === a[d]) return !0;
                        return !1
                    })
                }, this.unbindEventWithSelectorOrCallback = function(a) {
                    var f, b = e.toElementsArray(this),
                        d = a;
                    f = "function" == typeof a ? function(a) {
                        for (var e = 0; e < b.length; e++)
                            if ((this === c || a.target === b[e]) && a.callback === d) return !0;
                        return !1
                    } : function(d) {
                        for (var e = 0; e < b.length; e++)
                            if ((this === c || d.target === b[e]) && d.selector === a) return !0;
                        return !1
                    }, g.removeEvent(f)
                }, this.unbindEventWithSelectorAndCallback = function(a, b) {
                    var d = e.toElementsArray(this);
                    g.removeEvent(function(e) {
                        for (var f = 0; f < d.length; f++)
                            if ((this === c || e.target === d[f]) && e.selector === a && e.callback === b) return !0;
                        return !1
                    })
                }, this
            },
            h = function() {
                function h(a) {
                    var b = {
                        attributes: !1,
                        childList: !0,
                        subtree: !0
                    };
                    return a.fireOnAttributesModification && (b.attributes = !0), b
                }

                function i(a, b) {
                    a.forEach(function(a) {
                        var c = a.addedNodes,
                            d = a.target,
                            f = [];
                        null !== c && c.length > 0 ? e.checkChildNodesRecursively(c, b, k, f) : "attributes" === a.type && k(d, b, f) && f.push({
                            callback: b.callback,
                            elem: node
                        }), e.callCallbacks(f)
                    })
                }

                function k(a, b, f) {
                    if (e.matchesSelector(a, b.selector) && (a._id === c && (a._id = d++), -1 == b.firedElems.indexOf(a._id))) {
                        if (b.options.onceOnly) {
                            if (0 !== b.firedElems.length) return;
                            b.me.unbindEventWithSelectorAndCallback.call(b.target, b.selector, b.callback)
                        }
                        b.firedElems.push(a._id), f.push({
                            callback: b.callback,
                            elem: a
                        })
                    }
                }
                var f = {
                    fireOnAttributesModification: !1,
                    onceOnly: !1,
                    existing: !1
                };
                j = new g(h, i);
                var l = j.bindEvent;
                return j.bindEvent = function(a, b, c) {
                    "undefined" == typeof c ? (c = b, b = f) : b = e.mergeArrays(f, b);
                    var d = e.toElementsArray(this);
                    if (b.existing) {
                        for (var g = [], h = 0; h < d.length; h++)
                            for (var i = d[h].querySelectorAll(a), j = 0; j < i.length; j++) g.push({
                                callback: c,
                                elem: i[j]
                            });
                        if (b.onceOnly && g.length) return c.call(g[0].elem);
                        setTimeout(e.callCallbacks, 1, g)
                    }
                    l.call(this, a, b, c)
                }, j
            },
            i = function() {
                function d(a) {
                    var b = {
                        childList: !0,
                        subtree: !0
                    };
                    return b
                }

                function f(a, b) {
                    a.forEach(function(a) {
                        var c = a.removedNodes,
                            f = (a.target, []);
                        null !== c && c.length > 0 && e.checkChildNodesRecursively(c, b, h, f), e.callCallbacks(f)
                    })
                }

                function h(a, b) {
                    return e.matchesSelector(a, b.selector)
                }
                var c = {};
                k = new g(d, f);
                var i = k.bindEvent;
                return k.bindEvent = function(a, b, d) {
                    "undefined" == typeof d ? (d = b, b = c) : b = e.mergeArrays(c, b), i.call(this, a, b, d)
                }, k
            },
            j = new h,
            k = new i;
        b && m(b.fn), m(HTMLElement.prototype), m(NodeList.prototype), m(HTMLCollection.prototype), m(HTMLDocument.prototype), m(Window.prototype);
        var n = {};
        return l(j, n, "unbindAllArrive"), l(k, n, "unbindAllLeave"), n
    }
}(window, "undefined" == typeof jQuery ? null : jQuery, void 0);
/* End arrive.js */

console.info('Thanks for using SoundTake! :)');
(function(document) {

    'use strict';

    var versi = '0.6',
        //Remove control of $ variable
        jQ = jQuery.noConflict(true); 
    var options = {
        //Arrive event will only fire for newly created elements. (Not on existing elements)
        fireOnAttributesModification: false 
    };

	//Creates a similiar looking DL button to soundcloud, appends to after every jQ(e) 
    function DownloadButton(e) {
		//If more than 0 elements in .sc-button-download and no element in .sc-sndtk-btn, delete .sc-button-download
		//Removes the original download button
        if (jQ(e).find('.sc-button-download').length > 0 && jQ(e).find('.sc-sndtk-btn').length === 0) {
            jQ(e).find('.sc-button-download').remove();
        }
		//Finds the "Download" button size based on the soundcloud website
		//div.sc-button-group is the row of buttons beneath the track
		//Ternary operator, if true set button-size to sc-button-medium, if false set button-size to sc-button-small
        var button_size = jQ(e).find('div.sc-button-group').hasClass('sc-button-group-medium') || jQ(e).find('div.sc-button-group').find('button').eq(0).hasClass('sc-button-medium') ? 'sc-button-medium' : 'sc-button-small',
            //Set d to the first song title found on the page
			d = jQ(e).find('.soundTitle__title').eq(0),
            link = null,
			//div.sc-button-group is the row of buttons beneath the track
			//Set btngroup object to the first row of buttons beneath the track
            btngroup = jQ(e).find('div.sc-button-group').eq(0);
			//Ternary operator, if href attribute of the song title is undefined, set link to current url, else set to href attribute of the song title
			link = (d.attr('href') === undefined) ? document.location.href : 'https://soundcloud.com' + d.attr('href');
		
		//Appends "DL" button to the track/playlist
		//Different conditions -> playlist, profile, single track, etc.
        if (jQ(e).hasClass('userNetwork__share')) {
			//Set link to the current url
            link = document.location.href;
			//Append button
            jQ(e).append('<button style="margin-left:5px;" class=\'sc-sndtk-btn sc-button sc-button-download ' + button_size + ' sc-button-responsive\' title=\'Download this list with SoundTake\' onclick=\'window.open("http://soundtake.net/?utm_source=likes&utm_campaign=download_btn&utm_medium=soundcloud&ver=' + versi + '#' + link + '", "_blank")\'>1) Download This List </button>');
		//Download from profile - Download all tracks
        } else if (jQ(e).hasClass('userInfoBar__buttons') || jQ(e).hasClass('userDropbar__buttons')) {
            link = document.location.href;
			//Append button
            btngroup.append('<button class=\'sc-sndtk-btn sc-button sc-button-download ' + button_size + ' sc-button-responsive\' title=\'Download all tracks with SoundTake\' onclick=\'window.open("http://soundtake.net/?utm_source=sc_user&utm_campaign=download_btn&utm_medium=soundcloud&ver=' + versi + '#' + link + '", "_blank")\'>2) Download All Tracks </button>');
        //Download from playlist
		} else if (jQ(e).hasClass('playlist') || jQ(e).find('.sc-button-like[title=\'Like Playlist\']').length > 0) {
			//Append button
            btngroup.append('<button class=\'sc-sndtk-btn sc-button sc-button-download ' + button_size + ' sc-button-responsive\' title=\'Download this playlist with SoundTake\' onclick=\'window.open("http://soundtake.net/?utm_source=playlist&utm_campaign=download_btn&utm_medium=soundcloud&ver=' + versi + '#' + link + '", "_blank")\'>3) Download Playlist </button>');
        } else if (jQ(e).find('.trackItem__content').length > 0) {
            link = 'https://soundcloud.com' + jQ(e).find('.trackItem__trackTitle').eq(0).attr('href');
			//Append button
            btngroup.append('<button class=\'sc-sndtk-btn sc-button sc-button-download ' + button_size + ' sc-button-responsive sc-button-icon\' title=\'Download this track with SoundTake\' onclick=\'window.open("http://soundtake.net/?utm_source=tracklist&utm_campaign=download_btn&utm_medium=soundcloud&ver=' + versi + '#' + link + '", "_blank")\'>4) Download </button>');
        } else if (jQ(e).hasClass('soundBadge')) {
			//Append button
            btngroup.append('<button class=\'sc-sndtk-btn sc-button sc-button-download ' + button_size + ' sc-button-responsive sc-button-icon\' title=\'Download this track with SoundTake\' onclick=\'window.open("http://soundtake.net/?utm_source=soundbadge&utm_campaign=download_btn&utm_medium=soundcloud&ver=' + versi + '#' + link + '", "_blank")\'>5) Download </button>');
		//Download from single tracks
        } else {
            //btngroup.append('<button class=\'sc-sndtk-btn sc-button sc-button-download ' + button_size + ' sc-button-responsive\' title=\'Download this track with SoundTake\' onclick=\'window.open("http://soundtake.net/?utm_source=track&utm_campaign=download_btn&utm_medium=soundcloud&ver=' + versi + '#' + link + '", "_blank")\'>6) Download </button>');
			//Customized button
			link = d.find('span').text();
			btngroup.append('<button class=\'sc-sndtk-btn sc-button sc-button-download ' + button_size + ' sc-button-responsive\' title=\'Download this track with 
			\' onclick=\'window.open("http://mp3clan.com/mp3/' + link + '.html' + '", "_blank")\'>6) Download </button>');
        }
        jQ(e).leave('.sc-button-download', function() {
            jQ(e).unbindLeave();
            DownloadButton(e);
        });
    }

    function eksekusi() {
		//Creates a list of elements on soundcloud where the DL button will be placed after
        var elements = ['.trackItem', '.l-listen-engagement', '.listenEngagement__actions', '.soundBadge', '.userNetwork__share', '.userInfoBar__buttons', '.userDropbar__buttons', '.chartTracks__item'];

        jQ('.sound').each(function(a, b) {
            DownloadButton(b);
        });
		//Usage of arrive.js
        jQ(document).arrive('.sound', options, function() {
            DownloadButton(this);
        });
		//Reiterate through the elements and assign a download button after each of the element
        for (var i = 0; i <= elements.length; i++) {
            DownloadButton(jQ(elements[i]));
			//Usage of arrive.js
            jQ(document).arrive(elements[i], options, function() {
                DownloadButton(this);
            });
        }
    }
    eksekusi();
})(document);
