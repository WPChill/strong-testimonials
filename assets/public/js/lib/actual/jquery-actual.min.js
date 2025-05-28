/*! Copyright 2012, Ben Lin (http://dreamerslab.com/)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Version: 1.0.19
 *
 * Requires: jQuery >= 1.2.3
 */
!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t(jQuery)}((function(t){t.fn.addBack=t.fn.addBack||t.fn.andSelf,t.fn.extend({actual:function(e,n){if(!this[e])throw'$.actual => The jQuery method "'+e+'" you called does not exist';var i,a,o=t.extend({absolute:!1,clone:!1,includeMargin:!1,display:"block"},n),d=this.eq(0);if(!0===o.clone)i=function(){d=d.clone().attr("style","position: absolute !important; top: -1000 !important; ").appendTo("body")},a=function(){d.remove()};else{var r,l=[],s="";i=function(){r=d.parents().addBack().filter(":hidden"),s+="visibility: hidden !important; display: "+o.display+" !important; ",!0===o.absolute&&(s+="position: absolute !important; "),r.each((function(){var e=t(this),n=e.attr("style");l.push(n),e.attr("style",n?n+";"+s:s)}))},a=function(){r.each((function(e){var n=t(this),i=l[e];void 0===i?n.removeAttr("style"):n.attr("style",i)}))}}i();var u=/(outer)/.test(e)?d[e](o.includeMargin):d[e]();return a(),u}})}));