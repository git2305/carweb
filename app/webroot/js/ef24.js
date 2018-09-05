/* This notice must be untouched at all times.
 
 wz_tooltip.js	 v. 4.12
 
 The latest version is available at
 http://www.walterzorn.com
 or http://www.devira.com
 or http://www.walterzorn.de
 
 Copyright (c) 2002-2007 Walter Zorn. All rights reserved.
 Created 1.12.2002 by Walter Zorn (Web: http://www.walterzorn.com )
 Last modified: 13.7.2007
 
 Easy-to-use cross-browser tooltips.
 Just include the script at the beginning of the <body> section, and invoke
 Tip('Tooltip text') from within the desired HTML onmouseover eventhandlers.
 No container DIV, no onmouseouts required.
 By default, width of tooltips is automatically adapted to content.
 Is even capable of dynamically converting arbitrary HTML elements to tooltips
 by calling TagToTip('ID_of_HTML_element_to_be_converted') instead of Tip(),
 which means you can put important, search-engine-relevant stuff into tooltips.
 Appearance of tooltips can be individually configured
 via commands passed to Tip() or TagToTip().
 
 Tab Width: 4
 LICENSE: LGPL
 
 This library is free software; you can redistribute it and/or
 modify it under the terms of the GNU Lesser General Public
 License (LGPL) as published by the Free Software Foundation; either
 version 2.1 of the License, or (at your option) any later version.
 
 This library is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 
 For more details on the GNU Lesser General Public License,
 see http://www.gnu.org/copyleft/lesser.html
 */

var config = new Object();

//===================  GLOBAL TOOPTIP CONFIGURATION  =========================//
var tt_Debug = false		// false or true - recommended: false once you release your page to the public
var tt_Enabled = true		// Allows to (temporarily) suppress tooltips, e.g. by providing the user with a button that sets this global variable to false
var TagsToTip = true		// false or true - if true, the script is capable of converting HTML elements to tooltips

// For each of the following config variables there exists a command, which is
// just the variablename in uppercase, to be passed to Tip() or TagToTip() to
// configure tooltips individually. Individual commands override global
// configuration. Order of commands is arbitrary.
// Example: onmouseover="Tip('Tooltip text', LEFT, true, BGCOLOR, '#FF9900', FADEIN, 400)"

config.Above = false 	// false or true - tooltip above mousepointer?
config.BgColor = '#E4E7FF' // Background color
config.BgImg = ''		// Path to background image, none if empty string ''
config.BorderColor = '#002299'
config.BorderStyle = 'solid'	// Any permitted CSS value, but I recommend 'solid', 'dotted' or 'dashed'
config.BorderWidth = 1
config.CenterMouse = false 	// false or true - center the tip horizontally below (or above) the mousepointer
config.ClickClose = false 	// false or true - close tooltip if the user clicks somewhere
config.CloseBtn = false 	// false or true - closebutton in titlebar
config.CloseBtnColors = ['#990000', '#FFFFFF', '#DD3333', '#FFFFFF']	  // [Background, text, hovered background, hovered text] - use empty strings '' to inherit title colors
config.CloseBtnText = '&nbsp;X&nbsp;'	// Close button text (may also be an image tag)
config.CopyContent = true		// When converting a HTML element to a tooltip, copy only the element's content, rather than converting the element by its own
config.Delay = 0			// Time span in ms until tooltip shows up
config.Duration = 0 		// Time span in ms after which the tooltip disappears; 0 for infinite duration
config.FadeIn = 100 		// Fade-in duration in ms, e.g. 400; 0 for no animation
config.FadeOut = 100
config.FadeInterval = 30		// Duration of each fade step in ms (recommended: 30) - shorter is smoother but causes more CPU-load
//var divh = 67;
var divh = 100;
if (document.getElementById("SuperBanner")) {
    setheight();

}
function setheight()
{
    divh += document.getElementById('SuperBanner').offsetHeight + 7;
}
config.Fix = [410, divh]		// Fixated position - x- an y-oordinates in brackets, e.g. [210, 480], or null for no fixation
config.FollowMouse = true		// false or true - tooltip follows the mouse
config.FontColor = '#FFFFFF'
config.FontFace = 'Arial, Verdana, Helvetica, sans-serif'
config.FontSize = '10pt' 	// E.g. '9pt' or '12px' - unit is mandatory
config.FontWeight = 'normal'	// 'normal' or 'bold';
config.Left = false 	// false or true - tooltip on the left of the mouse
config.OffsetX = 0		// Horizontal offset of left-top corner from mousepointer
config.OffsetY = 0		// Vertical offset
config.Opacity = 100		// Integer between 0 and 100 - opacity of tooltip in percent
config.Padding = 3 		// Spacing between border and content
config.Shadow = false 	// false or true
config.ShadowColor = '#C0C0C0'
config.ShadowWidth = 5
config.Sticky = false 	// Do NOT hide tooltip on mouseout? false or true
config.TextAlign = 'left'	// 'left', 'right' or 'justify'
config.Title = ''		// Default title text applied to all tips (no default title: empty string '')
config.TitleAlign = 'left'	// 'left' or 'right' - text alignment inside the title bar
config.TitleBgColor = ''		// If empty string '', BorderColor will be used
config.TitleFontColor = '#ffffff'	// Color of title text - if '', BgColor (of tooltip body) will be used
config.TitleFontFace = ''		// If '' use FontFace (boldified)
config.TitleFontSize = ''		// If '' use FontSize
config.Width = 0 		// Tooltip width; 0 for automatic adaption to tooltip content
//=======  END OF TOOLTIP CONFIG, DO NOT CHANGE ANYTHING BELOW  ==============//




//======================  PUBLIC  ============================================//
function Tip()
{
    tt_Tip(arguments, null);
}
function TagToTip()
{
    if (TagsToTip)
    {
        var t2t = tt_GetElt(arguments[0]);
        if (t2t)
            tt_Tip(arguments, t2t);
    }
}

//==================  PUBLIC EXTENSION API	==================================//
// Extension eventhandlers currently supported:
// OnLoadConfig, OnCreateContentString, OnSubDivsCreated, OnShow, OnMoveBefore,
// OnMoveAfter, OnHideInit, OnHide, OnKill

var tt_aElt = new Array(10), // Container DIV, outer title & body DIVs, inner title & body TDs, closebutton SPAN, shadow DIVs, and IFRAME to cover windowed elements in IE
        tt_aV = new Array(), // Caches and enumerates config data for currently active tooltip
        tt_sContent, // Inner tooltip text or HTML
        tt_scrlX = 0, tt_scrlY = 0,
        tt_musX, tt_musY,
        tt_over,
        tt_x, tt_y, tt_w, tt_h; // Position, width and height of currently displayed tooltip

function tt_Extension()
{
    tt_ExtCmdEnum();
    tt_aExt[tt_aExt.length] = this;
    return this;
}
function tt_SetTipPos(x, y)
{
    var css = tt_aElt[0].style;

    tt_x = x;
    tt_y = y;
    css.left = x + "px";
    css.top = y + "px";
    if (tt_ie56)
    {
        var ifrm = tt_aElt[tt_aElt.length - 1];
        if (ifrm)
        {
            ifrm.style.left = css.left;
            ifrm.style.top = css.top;
        }
    }
}
function tt_Hide()
{
    if (tt_db && tt_iState)
    {
        if (tt_iState & 0x2)
        {
            tt_aElt[0].style.visibility = "hidden";
            tt_ExtCallFncs(0, "Hide");
        }
        tt_tShow.EndTimer();
        tt_tHide.EndTimer();
        tt_tDurt.EndTimer();
        tt_tFade.EndTimer();
        if (!tt_op && !tt_ie)
        {
            tt_tWaitMov.EndTimer();
            tt_bWait = false;
        }
        if (tt_aV[CLICKCLOSE])
            tt_RemEvtFnc(document, "mouseup", tt_HideInit);
        tt_AddRemOutFnc(false);
        tt_ExtCallFncs(0, "Kill");
        // In case of a TagToTip tooltip, hide converted DOM node and
        // re-insert it into document
        if (tt_t2t && !tt_aV[COPYCONTENT])
        {
            tt_t2t.style.display = "none";
            tt_MovDomNode(tt_t2t, tt_aElt[6], tt_t2tDad);
        }
        tt_iState = 0;
        tt_over = null;
        tt_ResetMainDiv();
        if (tt_aElt[tt_aElt.length - 1])
            tt_aElt[tt_aElt.length - 1].style.display = "none";
    }
}
function tt_GetElt(id)
{
    return(document.getElementById ? document.getElementById(id)
            : document.all ? document.all[id]
            : null);
}
function tt_GetDivW(el)
{
    return(el ? (el.offsetWidth || el.style.pixelWidth || 0) : 0);
}
function tt_GetDivH(el)
{
    return(el ? (el.offsetHeight || el.style.pixelHeight || 0) : 0);
}
function tt_GetScrollX()
{
    return(window.pageXOffset || (tt_db ? (tt_db.scrollLeft || 0) : 0));
}
function tt_GetScrollY()
{
    return(window.pageYOffset || (tt_db ? (tt_db.scrollTop || 0) : 0));
}
function tt_GetClientW()
{
    return(document.body && (typeof (document.body.clientWidth) != tt_u) ? document.body.clientWidth
            : (typeof (window.innerWidth) != tt_u) ? window.innerWidth
            : tt_db ? (tt_db.clientWidth || 0)
            : 0);
}
function tt_GetClientH()
{
    // Exactly this order seems to yield correct values in all major browsers
    return(document.body && (typeof (document.body.clientHeight) != tt_u) ? document.body.clientHeight
            : (typeof (window.innerHeight) != tt_u) ? window.innerHeight
            : tt_db ? (tt_db.clientHeight || 0)
            : 0);
}
function tt_GetEvtX(e)
{
    return (e ? ((typeof (e.pageX) != tt_u) ? e.pageX : (e.clientX + tt_scrlX)) : 0);
}
function tt_GetEvtY(e)
{
    return (e ? ((typeof (e.pageY) != tt_u) ? e.pageY : (e.clientY + tt_scrlY)) : 0);
}
function tt_AddEvtFnc(el, sEvt, PFnc)
{
    if (el)
    {
        if (el.addEventListener)
            el.addEventListener(sEvt, PFnc, false);
        else
            el.attachEvent("on" + sEvt, PFnc);
    }
}
function tt_RemEvtFnc(el, sEvt, PFnc)
{
    if (el)
    {
        if (el.removeEventListener)
            el.removeEventListener(sEvt, PFnc, false);
        else
            el.detachEvent("on" + sEvt, PFnc);
    }
}

//======================  PRIVATE  ===========================================//
var tt_aExt = new Array(), // Array of extension objects

        tt_db, tt_op, tt_ie, tt_ie56, tt_bBoxOld, // Browser flags
        tt_body,
        tt_flagOpa, // Opacity support: 1=IE, 2=Khtml, 3=KHTML, 4=Moz, 5=W3C
        tt_maxPosX, tt_maxPosY,
        tt_iState = 0, // Tooltip active |= 1, shown |= 2, move with mouse |= 4
        tt_opa, // Currently applied opacity
        tt_bJmpVert, // Tip above mouse (or ABOVE tip below mouse)
        tt_t2t, tt_t2tDad, // Tag converted to tip, and its parent element in the document
        tt_elDeHref, // The tag from which Opera has removed the href attribute
// Timer
        tt_tShow = new Number(0), tt_tHide = new Number(0), tt_tDurt = new Number(0),
        tt_tFade = new Number(0), tt_tWaitMov = new Number(0),
        tt_bWait = false,
        tt_u = "undefined";


function tt_Init()
{
    tt_MkCmdEnum();
    // Send old browsers instantly to hell
    if (!tt_Browser() || !tt_MkMainDiv())
        return;
    tt_IsW3cBox();
    tt_OpaSupport();
    tt_AddEvtFnc(document, "mousemove", tt_Move);
    // In Debug mode we search for TagToTip() calls in order to notify
    // the user if they've forgotten to set the TagsToTip config flag
    if (TagsToTip || tt_Debug)
        tt_SetOnloadFnc();
    tt_AddEvtFnc(window, "scroll",
            function ()
            {
                tt_scrlX = tt_GetScrollX();
                tt_scrlY = tt_GetScrollY();
                if (tt_iState && !(tt_aV[STICKY] && (tt_iState & 2)))
                    tt_HideInit();
            });
    // Ensure the tip be hidden when the page unloads
    tt_AddEvtFnc(window, "unload", tt_Hide);
    tt_Hide();
}
// Creates command names by translating config variable names to upper case
function tt_MkCmdEnum()
{
    var n = 0;
    for (var i in config)
        eval("window." + i.toString().toUpperCase() + " = " + n++);
    tt_aV.length = n;
}
function tt_Browser()
{
    var n, nv, n6, w3c;

    n = navigator.userAgent.toLowerCase(),
            nv = navigator.appVersion;
    tt_op = (document.defaultView && typeof (eval("w" + "indow" + "." + "o" + "p" + "er" + "a")) != tt_u);
    tt_ie = n.indexOf("msie") != -1 && document.all && !tt_op;
    if (tt_ie)
    {
        var ieOld = (!document.compatMode || document.compatMode == "BackCompat");
        tt_db = !ieOld ? document.documentElement : (document.body || null);
        if (tt_db)
            tt_ie56 = parseFloat(nv.substring(nv.indexOf("MSIE") + 5)) >= 5.5
                    && typeof document.body.style.maxHeight == tt_u;
    }
    else
    {
        tt_db = document.documentElement || document.body ||
                (document.getElementsByTagName ? document.getElementsByTagName("body")[0]
                        : null);
        if (!tt_op)
        {
            n6 = document.defaultView && typeof document.defaultView.getComputedStyle != tt_u;
            w3c = !n6 && document.getElementById;
        }
    }
    tt_body = (document.getElementsByTagName ? document.getElementsByTagName("body")[0]
            : (document.body || null));
    if (tt_ie || n6 || tt_op || w3c)
    {
        if (tt_body && tt_db)
        {
            if (document.attachEvent || document.addEventListener)
                return true;
        }
        else
            tt_Err("wz_tooltip.js must be included INSIDE the body section,"
                    + " immediately after the opening <body> tag.");
    }
    tt_db = null;
    return false;
}
function tt_MkMainDiv()
{
    // Create the tooltip DIV
    if (tt_body.insertAdjacentHTML)
        tt_body.insertAdjacentHTML("afterBegin", tt_MkMainDivHtm());
    else if (typeof tt_body.innerHTML != tt_u && document.createElement && tt_body.appendChild)
        tt_body.appendChild(tt_MkMainDivDom());
    // FireFox Alzheimer bug
    if (window.tt_GetMainDivRefs && tt_GetMainDivRefs())
        return true;
    tt_db = null;
    return false;
}
function tt_MkMainDivHtm()
{
    return('<div id="WzTtDiV"></div>' +
            (tt_ie56 ? ('<iframe id="WzTtIfRm" src="javascript:false" scrolling="no" frameborder="0" style="filter:Alpha(opacity=0);position:absolute;top:0px;left:0px;display:none;"></iframe>')
                    : ''));
}
function tt_MkMainDivDom()
{
    var el = document.createElement("div");
    if (el)
        el.id = "WzTtDiV";
    return el;
}
function tt_GetMainDivRefs()
{
    tt_aElt[0] = tt_GetElt("WzTtDiV");
    if (tt_ie56 && tt_aElt[0])
    {
        tt_aElt[tt_aElt.length - 1] = tt_GetElt("WzTtIfRm");
        if (!tt_aElt[tt_aElt.length - 1])
            tt_aElt[0] = null;
    }
    if (tt_aElt[0])
    {
        var css = tt_aElt[0].style;

        css.visibility = "hidden";
        css.position = "absolute";
        css.overflow = "hidden";
        return true;
    }
    return false;
}
function tt_ResetMainDiv()
{
    var w = (window.screen && screen.width) ? screen.width : 10000;

    tt_SetTipPos(-w, 0);
    tt_aElt[0].innerHTML = "";
    tt_aElt[0].style.width = (w - 1) + "px";
}
function tt_IsW3cBox()
{
    var css = tt_aElt[0].style;

    css.padding = "10px";
    css.width = "40px";
    tt_bBoxOld = (tt_GetDivW(tt_aElt[0]) == 40);
    css.padding = "0px";
    tt_ResetMainDiv();
}
function tt_OpaSupport()
{
    var css = tt_body.style;

    tt_flagOpa = (typeof (css.filter) != tt_u) ? 1
            : (typeof (css.KhtmlOpacity) != tt_u) ? 2
            : (typeof (css.KHTMLOpacity) != tt_u) ? 3
            : (typeof (css.MozOpacity) != tt_u) ? 4
            : (typeof (css.opacity) != tt_u) ? 5
            : 0;
}
// Ported from http://dean.edwards.name/weblog/2006/06/again/
// (Dean Edwards et al.)
function tt_SetOnloadFnc()
{
    tt_AddEvtFnc(document, "DOMContentLoaded", tt_HideSrcTags);
    tt_AddEvtFnc(window, "load", tt_HideSrcTags);
    if (tt_body.attachEvent)
        tt_body.attachEvent("onreadystatechange",
                function () {
                    if (tt_body.readyState == "complete")
                        tt_HideSrcTags();
                });
    if (/WebKit|KHTML/i.test(navigator.userAgent))
    {
        var t = setInterval(function () {
            if (/loaded|complete/.test(document.readyState))
            {
                clearInterval(t);
                tt_HideSrcTags();
            }
        }, 10);
    }
}
function tt_HideSrcTags()
{
    if (!window.tt_HideSrcTags || window.tt_HideSrcTags.done)
        return;
    window.tt_HideSrcTags.done = true;
    if (!tt_HideSrcTagsRecurs(tt_body))
        tt_Err("To enable the capability to convert HTML elements to tooltips,"
                + " you must set TagsToTip in the global tooltip configuration"
                + " to true.");
}
function tt_HideSrcTagsRecurs(dad)
{
    var a, ovr, asT2t;

    // Walk the DOM tree for tags that have an onmouseover attribute
    // containing a TagToTip('...') call.
    // (.childNodes first since .children is bugous in Safari)
    a = dad.childNodes || dad.children || null;
    for (var i = a ? a.length : 0; i; )
    {
        --i;
        if (!tt_HideSrcTagsRecurs(a[i]))
            return false;
        ovr = a[i].getAttribute ? a[i].getAttribute("onmouseover")
                : (typeof a[i].onmouseover == "function") ? a[i].onmouseover
                : null;
        if (ovr)
        {
            asT2t = ovr.toString().match(/TagToTip\s*\(\s*'[^'.]+'\s*[\),]/);
            if (asT2t && asT2t.length)
            {
                if (!tt_HideSrcTag(asT2t[0]))
                    return false;
            }
        }
    }
    return true;
}
function tt_HideSrcTag(sT2t)
{
    var id, el;

    // The ID passed to the found TagToTip() call identifies an HTML element
    // to be converted to a tooltip, so hide that element
    id = sT2t.replace(/.+'([^'.]+)'.+/, "$1");
    el = tt_GetElt(id);
    if (el)
    {
        if (tt_Debug && !TagsToTip)
            return false;
        else
            el.style.display = "none";
    }
    else
        tt_Err("Invalid ID\n'" + id + "'\npassed to TagToTip()."
                + " There exists no HTML element with that ID.");
    return true;
}
function tt_Tip(arg, t2t)
{
    if (!tt_db)
        return;
    if (tt_iState)
        tt_Hide();
    if (!tt_Enabled)
        return;
    tt_t2t = t2t;
    if (!tt_ReadCmds(arg))
        return;
    tt_iState = 0x1 | 0x4;
    tt_AdaptConfig1();
    tt_MkTipContent(arg);
    tt_MkTipSubDivs();
    tt_FormatTip();
    tt_bJmpVert = false;
    tt_maxPosX = tt_GetClientW() + tt_scrlX - tt_w - 1;
    tt_maxPosY = tt_GetClientH() + tt_scrlY - tt_h - 1;
    tt_AdaptConfig2();
    // We must fake the first mousemove in order to ensure the tip
    // be immediately shown and positioned
    tt_Move();
    tt_ShowInit();
}
function tt_ReadCmds(a)
{
    var i;

    // First load the global config values, to initialize also values
    // for which no command has been passed
    i = 0;
    for (var j in config)
        tt_aV[i++] = config[j];
    // Then replace each cached config value for which a command has been
    // passed (ensure the # of command args plus value args be even)
    if (a.length & 1)
    {
        for (i = a.length - 1; i > 0; i -= 2)
            tt_aV[a[i - 1]] = a[i];
        return true;
    }
    tt_Err("Incorrect call of Tip() or TagToTip().\n"
            + "Each command must be followed by a value.");
    return false;
}
function tt_AdaptConfig1()
{
    tt_ExtCallFncs(0, "LoadConfig");
    // Inherit unspecified title formattings from body
    if (!tt_aV[TITLEBGCOLOR].length)
        tt_aV[TITLEBGCOLOR] = tt_aV[BORDERCOLOR];
    if (!tt_aV[TITLEFONTCOLOR].length)
        tt_aV[TITLEFONTCOLOR] = tt_aV[BGCOLOR];
    if (!tt_aV[TITLEFONTFACE].length)
        tt_aV[TITLEFONTFACE] = tt_aV[FONTFACE];
    if (!tt_aV[TITLEFONTSIZE].length)
        tt_aV[TITLEFONTSIZE] = tt_aV[FONTSIZE];
    if (tt_aV[CLOSEBTN])
    {
        // Use title colors for non-specified closebutton colors
        if (!tt_aV[CLOSEBTNCOLORS])
            tt_aV[CLOSEBTNCOLORS] = new Array("", "", "", "");
        for (var i = 4; i; )
        {
            --i;
            if (!tt_aV[CLOSEBTNCOLORS][i].length)
                tt_aV[CLOSEBTNCOLORS][i] = (i & 1) ? tt_aV[TITLEFONTCOLOR] : tt_aV[TITLEBGCOLOR];
        }
        // Enforce titlebar be shown
        if (!tt_aV[TITLE].length)
            tt_aV[TITLE] = " ";
    }
    // Circumvents broken display of images and fade-in flicker in Geckos < 1.8
    if (tt_aV[OPACITY] == 100 && typeof tt_aElt[0].style.MozOpacity != tt_u && !Array.every)
        tt_aV[OPACITY] = 99;
    // Smartly shorten the delay for fade-in tooltips
    if (tt_aV[FADEIN] && tt_flagOpa && tt_aV[DELAY] > 100)
        tt_aV[DELAY] = Math.max(tt_aV[DELAY] - tt_aV[FADEIN], 100);
}
function tt_AdaptConfig2()
{
    if (tt_aV[CENTERMOUSE])
        tt_aV[OFFSETX] -= ((tt_w - (tt_aV[SHADOW] ? tt_aV[SHADOWWIDTH] : 0)) >> 1);
}
// Expose content globally so extensions can modify it
function tt_MkTipContent(a)
{
    if (tt_t2t)
    {
        if (tt_aV[COPYCONTENT])
            tt_sContent = tt_t2t.innerHTML;
        else
            tt_sContent = "";
    }
    else
        tt_sContent = a[0];
    tt_ExtCallFncs(0, "CreateContentString");
}
function tt_MkTipSubDivs()
{
    var sCss = 'position:relative;margin:0px;padding:0px;border-width:0px;left:0px;top:0px;line-height:normal;width:auto;',
            sTbTrTd = ' cellspacing=0 cellpadding=0 border=0 style="' + sCss + '"><tbody style="' + sCss + '"><tr><td ';

    tt_aElt[0].innerHTML =
            (''
                    + (tt_aV[TITLE].length ?
                            ('<div id="WzTiTl" style="position:relative;z-index:1;">'
                                    + '<table id="WzTiTlTb"' + sTbTrTd + 'id="WzTiTlI" style="' + sCss + '">'
                                    + tt_aV[TITLE]
                                    + '</td>'
                                    + (tt_aV[CLOSEBTN] ?
                                            ('<td align="right" style="' + sCss
                                                    + 'text-align:right;">'
                                                    + '<span id="WzClOsE" style="padding-left:2px;padding-right:2px;'
                                                    + 'cursor:' + (tt_ie ? 'hand' : 'pointer')
                                                    + ';" onmouseover="tt_OnCloseBtnOver(1)" onmouseout="tt_OnCloseBtnOver(0)" onclick="tt_HideInit()">'
                                                    + tt_aV[CLOSEBTNTEXT]
                                                    + '</span></td>')
                                            : '')
                                    + '</tr></tbody></table></div>')
                            : '')
                    + '<div id="WzBoDy" style="position:relative;z-index:0;">'
                    + '<table' + sTbTrTd + 'id="WzBoDyI" style="' + sCss + '">'
                    + tt_sContent
                    + '</td></tr></tbody></table></div>'
                    + (tt_aV[SHADOW]
                            ? ('<div id="WzTtShDwR" style="position:absolute;overflow:hidden;"></div>'
                                    + '<div id="WzTtShDwB" style="position:relative;overflow:hidden;"></div>')
                            : '')
                    );
    tt_GetSubDivRefs();
    // Convert DOM node to tip
    if (tt_t2t && !tt_aV[COPYCONTENT])
    {
        // Store the tag's parent element so we can restore that DOM branch
        // once the tooltip is hidden
        tt_t2tDad = tt_t2t.parentNode || tt_t2t.parentElement || tt_t2t.offsetParent || null;
        if (tt_t2tDad)
        {
            tt_MovDomNode(tt_t2t, tt_t2tDad, tt_aElt[6]);
            tt_t2t.style.display = "block";
        }
    }
    tt_ExtCallFncs(0, "SubDivsCreated");
}
function tt_GetSubDivRefs()
{
    var aId = new Array("WzTiTl", "WzTiTlTb", "WzTiTlI", "WzClOsE", "WzBoDy", "WzBoDyI", "WzTtShDwB", "WzTtShDwR");

    for (var i = aId.length; i; --i)
        tt_aElt[i] = tt_GetElt(aId[i - 1]);
}
function tt_FormatTip()
{
    var css, w, iOffY, iOffSh;

    //--------- Title DIV ----------
    if (tt_aV[TITLE].length)
    {
        css = tt_aElt[1].style;
        css.background = tt_aV[TITLEBGCOLOR];
        css.paddingTop = (tt_aV[CLOSEBTN] ? 2 : 0) + "px";
        css.paddingBottom = "1px";
        css.paddingLeft = css.paddingRight = tt_aV[PADDING] + "px";
        css = tt_aElt[3].style;
        css.color = tt_aV[TITLEFONTCOLOR];
        css.fontFamily = tt_aV[TITLEFONTFACE];
        css.fontSize = tt_aV[TITLEFONTSIZE];
        css.fontWeight = "bold";
        css.textAlign = tt_aV[TITLEALIGN];
        // Close button DIV
        if (tt_aElt[4])
        {
            css.paddingRight = (tt_aV[PADDING] << 1) + "px";
            css = tt_aElt[4].style;
            css.background = tt_aV[CLOSEBTNCOLORS][0];
            css.color = tt_aV[CLOSEBTNCOLORS][1];
            css.fontFamily = tt_aV[TITLEFONTFACE];
            css.fontSize = tt_aV[TITLEFONTSIZE];
            css.fontWeight = "bold";
        }
        if (tt_aV[WIDTH] > 0)
            tt_w = tt_aV[WIDTH] + ((tt_aV[PADDING] + tt_aV[BORDERWIDTH]) << 1);
        else
        {
            tt_w = tt_GetDivW(tt_aElt[3]) + tt_GetDivW(tt_aElt[4]);
            // Some spacing between title DIV and closebutton
            if (tt_aElt[4])
                tt_w += tt_aV[PADDING];
        }
        // Ensure the top border of the body DIV be covered by the title DIV
        iOffY = -tt_aV[BORDERWIDTH];
    }
    else
    {
        tt_w = 0;
        iOffY = 0;
    }

    //-------- Body DIV ------------
    css = tt_aElt[5].style;
    css.top = iOffY + "px";
    if (tt_aV[BORDERWIDTH])
    {
        css.borderColor = tt_aV[BORDERCOLOR];
        css.borderStyle = tt_aV[BORDERSTYLE];
        css.borderWidth = tt_aV[BORDERWIDTH] + "px";
    }
    if (tt_aV[BGCOLOR].length)
        css.background = tt_aV[BGCOLOR];
    if (tt_aV[BGIMG].length)
        css.backgroundImage = "url(" + tt_aV[BGIMG] + ")";
    css.padding = tt_aV[PADDING] + "px";
    css.textAlign = tt_aV[TEXTALIGN];
    // TD inside body DIV
    css = tt_aElt[6].style;
    css.color = tt_aV[FONTCOLOR];
    css.fontFamily = tt_aV[FONTFACE];
    css.fontSize = tt_aV[FONTSIZE];
    css.fontWeight = tt_aV[FONTWEIGHT];
    css.background = "";
    css.textAlign = tt_aV[TEXTALIGN];
    if (tt_aV[WIDTH] > 0)
        w = tt_aV[WIDTH] + ((tt_aV[PADDING] + tt_aV[BORDERWIDTH]) << 1);
    else
        // We measure the width of the body's inner TD, because some browsers
        // expand the width of the container and outer body DIV to 100%
        w = tt_GetDivW(tt_aElt[6]) + ((tt_aV[PADDING] + tt_aV[BORDERWIDTH]) << 1);
    if (w > tt_w)
        tt_w = w;

    //--------- Shadow DIVs ------------
    if (tt_aV[SHADOW])
    {
        tt_w += tt_aV[SHADOWWIDTH];
        iOffSh = Math.floor((tt_aV[SHADOWWIDTH] * 4) / 3);
        // Bottom shadow
        css = tt_aElt[7].style;
        css.top = iOffY + "px";
        css.left = iOffSh + "px";
        css.width = (tt_w - iOffSh - tt_aV[SHADOWWIDTH]) + "px";
        css.height = tt_aV[SHADOWWIDTH] + "px";
        css.background = tt_aV[SHADOWCOLOR];
        // Right shadow
        css = tt_aElt[8].style;
        css.top = iOffSh + "px";
        css.left = (tt_w - tt_aV[SHADOWWIDTH]) + "px";
        css.width = tt_aV[SHADOWWIDTH] + "px";
        css.background = tt_aV[SHADOWCOLOR];
    }
    else
        iOffSh = 0;

    //-------- Container DIV -------
    tt_SetTipOpa(tt_aV[FADEIN] ? 0 : tt_aV[OPACITY]);
    tt_FixSize(iOffY, iOffSh);
}
// Fixate the size so it can't dynamically change while the tooltip is moving.
function tt_FixSize(iOffY, iOffSh)
{
    var wIn, wOut, i;

    tt_aElt[0].style.width = tt_w + "px";
    tt_aElt[0].style.pixelWidth = tt_w;
    wOut = tt_w - ((tt_aV[SHADOW]) ? tt_aV[SHADOWWIDTH] : 0);
    // Body
    wIn = wOut;
    if (!tt_bBoxOld)
        wIn -= ((tt_aV[PADDING] + tt_aV[BORDERWIDTH]) << 1);
    tt_aElt[5].style.width = wIn + "px";
    // Title
    if (tt_aElt[1])
    {
        wIn = wOut - (tt_aV[PADDING] << 1);
        if (!tt_bBoxOld)
            wOut = wIn;
        tt_aElt[1].style.width = wOut + "px";
        tt_aElt[2].style.width = wIn + "px";
    }
    tt_h = tt_GetDivH(tt_aElt[0]) + iOffY;
    // Right shadow
    if (tt_aElt[8])
        tt_aElt[8].style.height = (tt_h - iOffSh) + "px";
    i = tt_aElt.length - 1;
    if (tt_aElt[i])
    {
        tt_aElt[i].style.width = tt_w + "px";
        tt_aElt[i].style.height = tt_h + "px";
    }
}
function tt_DeAlt(el)
{
    var aKid;

    if (el.alt)
        el.alt = "";
    if (el.title)
        el.title = "";
    aKid = el.childNodes || el.children || null;
    if (aKid)
    {
        for (var i = aKid.length; i; )
            tt_DeAlt(aKid[--i]);
    }
}
// This hack removes the annoying native tooltips over links in Opera
function tt_OpDeHref(el)
{
    if (!tt_op)
        return;
    if (tt_elDeHref)
        tt_OpReHref();
    while (el)
    {
        if (el.hasAttribute("href"))
        {
            el.t_href = el.getAttribute("href");
            el.t_stats = window.status;
            el.removeAttribute("href");
            el.style.cursor = "hand";
            tt_AddEvtFnc(el, "mousedown", tt_OpReHref);
            window.status = el.t_href;
            tt_elDeHref = el;
            break;
        }
        el = el.parentElement;
    }
}
function tt_ShowInit()
{
    tt_tShow.Timer("tt_Show()", tt_aV[DELAY], true);
    if (tt_aV[CLICKCLOSE])
        tt_AddEvtFnc(document, "mouseup", tt_HideInit);
}
function tt_OverInit(e)
{
    tt_over = e.target || e.srcElement;
    tt_DeAlt(tt_over);
    tt_OpDeHref(tt_over);
    tt_AddRemOutFnc(true);
}
function tt_Show()
{
    var css = tt_aElt[0].style;

    // Override the z-index of the topmost wz_dragdrop.js D&D item
    css.zIndex = Math.max((window.dd && dd.z) ? (dd.z + 2) : 0, 1010);
    if (tt_aV[STICKY] || !tt_aV[FOLLOWMOUSE])
        tt_iState &= ~0x4;
    if (tt_aV[DURATION] > 0)
        tt_tDurt.Timer("tt_HideInit()", tt_aV[DURATION], true);
    tt_ExtCallFncs(0, "Show")
    css.visibility = "visible";
    tt_iState |= 0x2;
    if (tt_aV[FADEIN])
        tt_Fade(0, 0, tt_aV[OPACITY], Math.round(tt_aV[FADEIN] / tt_aV[FADEINTERVAL]));
    tt_ShowIfrm();
}
function tt_ShowIfrm()
{
    if (tt_ie56)
    {
        var ifrm = tt_aElt[tt_aElt.length - 1];
        if (ifrm)
        {
            var css = ifrm.style;
            css.zIndex = tt_aElt[0].style.zIndex - 1;
            css.display = "block";
        }
    }
}
function tt_Move(e)
{
    e = window.event || e;
    if (e)
    {
        tt_musX = tt_GetEvtX(e);
        tt_musY = tt_GetEvtY(e);
    }
    if (tt_iState)
    {
        if (!tt_over && e)
            tt_OverInit(e);
        if (tt_iState & 0x4)
        {
            // Protect some browsers against jam of mousemove events
            if (!tt_op && !tt_ie)
            {
                if (tt_bWait)
                    return;
                tt_bWait = true;
                tt_tWaitMov.Timer("tt_bWait = false;", 1, true);
            }
            if (tt_aV[FIX])
            {
                tt_iState &= ~0x4;
                tt_SetTipPos(tt_aV[FIX][0], tt_aV[FIX][1]);
            }
            else if (!tt_ExtCallFncs(e, "MoveBefore"))
                tt_SetTipPos(tt_PosX(), tt_PosY());
            tt_ExtCallFncs([tt_musX, tt_musY], "MoveAfter")
        }
    }
}
function tt_PosX()
{
    var x;

    x = tt_musX;
    if (tt_aV[LEFT])
        x -= tt_w + tt_aV[OFFSETX] - (tt_aV[SHADOW] ? tt_aV[SHADOWWIDTH] : 0);
    else
        x += tt_aV[OFFSETX];
    // Prevent tip from extending past right/left clientarea boundary
    if (x > tt_maxPosX)
        x = tt_maxPosX;
    return((x < tt_scrlX) ? tt_scrlX : x);
}
function tt_PosY()
{
    var y;

    // Apply some hysteresis after the tip has snapped to the other side of the
    // mouse. In case of insufficient space above and below the mouse, we place
    // the tip below.
    if (tt_aV[ABOVE] && (!tt_bJmpVert || tt_CalcPosYAbove() >= tt_scrlY + 16))
        y = tt_DoPosYAbove();
    else if (!tt_aV[ABOVE] && tt_bJmpVert && tt_CalcPosYBelow() > tt_maxPosY - 16)
        y = tt_DoPosYAbove();
    else
        y = tt_DoPosYBelow();
    // Snap to other side of mouse if tip would extend past window boundary
    if (y > tt_maxPosY)
        y = tt_DoPosYAbove();
    if (y < tt_scrlY)
        y = tt_DoPosYBelow();
    return y;
}
function tt_DoPosYBelow()
{
    tt_bJmpVert = tt_aV[ABOVE];
    return tt_CalcPosYBelow();
}
function tt_DoPosYAbove()
{
    tt_bJmpVert = !tt_aV[ABOVE];
    return tt_CalcPosYAbove();
}
function tt_CalcPosYBelow()
{
    return(tt_musY + tt_aV[OFFSETY]);
}
function tt_CalcPosYAbove()
{
    var dy = tt_aV[OFFSETY] - (tt_aV[SHADOW] ? tt_aV[SHADOWWIDTH] : 0);
    if (tt_aV[OFFSETY] > 0 && dy <= 0)
        dy = 1;
    return(tt_musY - tt_h - dy);
}
function tt_OnOut()
{
    tt_AddRemOutFnc(false);
    if (!(tt_aV[STICKY] && (tt_iState & 0x2)))
        tt_HideInit();
}
function tt_HideInit()
{
    tt_ExtCallFncs(0, "HideInit");
    tt_iState &= ~0x4;
    if (tt_flagOpa && tt_aV[FADEOUT])
    {
        tt_tFade.EndTimer();
        if (tt_opa)
        {
            var n = Math.round(tt_aV[FADEOUT] / (tt_aV[FADEINTERVAL] * (tt_aV[OPACITY] / tt_opa)));
            tt_Fade(tt_opa, tt_opa, 0, n);
            return;
        }
    }
    tt_tHide.Timer("tt_Hide();", 1, false);
}
function tt_OpReHref()
{
    if (tt_elDeHref)
    {
        tt_elDeHref.setAttribute("href", tt_elDeHref.t_href);
        tt_RemEvtFnc(tt_elDeHref, "mousedown", tt_OpReHref);
        window.status = tt_elDeHref.t_stats;
        tt_elDeHref = null;
    }
}
function tt_Fade(a, now, z, n)
{
    if (n)
    {
        now += Math.round((z - now) / n);
        if ((z > a) ? (now >= z) : (now <= z))
            now = z;
        else
            tt_tFade.Timer("tt_Fade("
                    + a + "," + now + "," + z + "," + (n - 1)
                    + ")",
                    tt_aV[FADEINTERVAL],
                    true);
    }
    now ? tt_SetTipOpa(now) : tt_Hide();
}
// To circumvent the opacity nesting flaws of IE, we set the opacity
// for each sub-DIV separately, rather than for the container DIV.
function tt_SetTipOpa(opa)
{
    tt_SetOpa(tt_aElt[5].style, opa);
    if (tt_aElt[1])
        tt_SetOpa(tt_aElt[1].style, opa);
    if (tt_aV[SHADOW])
    {
        opa = Math.round(opa * 0.8);
        tt_SetOpa(tt_aElt[7].style, opa);
        tt_SetOpa(tt_aElt[8].style, opa);
    }
}
function tt_OnCloseBtnOver(iOver)
{
    var css = tt_aElt[4].style;

    iOver <<= 1;
    css.background = tt_aV[CLOSEBTNCOLORS][iOver];
    css.color = tt_aV[CLOSEBTNCOLORS][iOver + 1];
}
function tt_Int(x)
{
    var y;

    return(isNaN(y = parseInt(x)) ? 0 : y);
}
// Adds or removes the document.mousemove or HoveredElem.mouseout handler
// conveniently. Keeps track of those handlers to prevent them from being
// set or removed redundantly.
function tt_AddRemOutFnc(bAdd)
{
    var PSet = bAdd ? tt_AddEvtFnc : tt_RemEvtFnc;

    if (bAdd != tt_AddRemOutFnc.bOn)
    {
        PSet(tt_over, "mouseout", tt_OnOut);
        tt_AddRemOutFnc.bOn = bAdd;
        if (!bAdd)
            tt_OpReHref();
    }
}
tt_AddRemOutFnc.bOn = false;
Number.prototype.Timer = function (s, iT, bUrge)
{
    if (!this.value || bUrge)
        this.value = window.setTimeout(s, iT);
}
Number.prototype.EndTimer = function ()
{
    if (this.value)
    {
        window.clearTimeout(this.value);
        this.value = 0;
    }
}
function tt_SetOpa(css, opa)
{
    tt_opa = opa;
    if (tt_flagOpa == 1)
    {
        // Hack for bugs of IE:
        // A DIV cannot be made visible in a single step if an opacity < 100
        // has been applied while the DIV was hidden.
        // Moreover, in IE6, applying an opacity < 100 has no effect if the
        // concerned element has no layout (position, size, zoom, ...).
        if (opa < 100)
        {
            var bVis = css.visibility != "hidden";
            css.zoom = "100%";
            if (!bVis)
                css.visibility = "visible";
            css.filter = "alpha(opacity=" + opa + ")";
            if (!bVis)
                css.visibility = "hidden";
        }
        else
            css.filter = "";
    }
    else
    {
        opa /= 100.0;
        switch (tt_flagOpa)
        {
            case 2:
                css.KhtmlOpacity = opa;
                break;
            case 3:
                css.KHTMLOpacity = opa;
                break;
            case 4:
                css.MozOpacity = opa;
                break;
            case 5:
                css.opacity = opa;
                break;
        }
    }
}
function tt_MovDomNode(el, dadFrom, dadTo)
{
    if (dadFrom)
        dadFrom.removeChild(el);
    if (dadTo)
        dadTo.appendChild(el);
}
function tt_Err(sErr)
{
    if (tt_Debug)
        alert("Tooltip Script Error Message:\n\n" + sErr);
}

//===========  DEALING WITH EXTENSIONS	==============//
function tt_ExtCmdEnum()
{
    var s;

    // Add new command(s) to the commands enum
    for (var i in config)
    {
        s = "window." + i.toString().toUpperCase();
        if (eval("typeof(" + s + ") == tt_u"))
        {
            eval(s + " = " + tt_aV.length);
            tt_aV[tt_aV.length] = null;
        }
    }
}
function tt_ExtCallFncs(arg, sFnc)
{
    var b = false;
    for (var i = tt_aExt.length; i; )
    {
        --i;
        var fnc = tt_aExt[i]["On" + sFnc];
        // Call the method the extension has defined for this event
        if (fnc && fnc(arg))
            b = true;
    }
    return b;
}

tt_Init();

/* * jQuery JavaScript Library v1.3.1 * http://jquery.com/ * * Copyright (c) 2009 John Resig * Dual licensed under the MIT and GPL licenses. * http://docs.jquery.com/License * * Date: 2009-01-21 20:42:16 -0500 (Wed, 21 Jan 2009) * Revision: 6158 */(function () {
    var l = this, g, y = l.jQuery, p = l.$, o = l.jQuery = l.$ = function (E, F) {
        return new o.fn.init(E, F)
    }, D = /^[^<]*(<(.|\s)+>)[^>]*$|^#([\w-]+)$/, f = /^.[^:#\[\.,]*$/;
    o.fn = o.prototype = {init: function (E, H) {
            E = E || document;
            if (E.nodeType) {
                this[0] = E;
                this.length = 1;
                this.context = E;
                return this
            }
            if (typeof E === "string") {
                var G = D.exec(E);
                if (G && (G[1] || !H)) {
                    if (G[1]) {
                        E = o.clean([G[1]], H)
                    } else {
                        var I = document.getElementById(G[3]);
                        if (I && I.id != G[3]) {
                            return o().find(E)
                        }
                        var F = o(I || []);
                        F.context = document;
                        F.selector = E;
                        return F
                    }
                } else {
                    return o(H).find(E)
                }
            } else {
                if (o.isFunction(E)) {
                    return o(document).ready(E)
                }
            }
            if (E.selector && E.context) {
                this.selector = E.selector;
                this.context = E.context
            }
            return this.setArray(o.makeArray(E))
        }, selector: "", jquery: "1.3.1", size: function () {
            return this.length
        }, get: function (E) {
            return E === g ? o.makeArray(this) : this[E]
        }, pushStack: function (F, H, E) {
            var G = o(F);
            G.prevObject = this;
            G.context = this.context;
            if (H === "find") {
                G.selector = this.selector + (this.selector ? " " : "") + E
            } else {
                if (H) {
                    G.selector = this.selector + "." + H + "(" + E + ")"
                }
            }
            return G
        }, setArray: function (E) {
            this.length = 0;
            Array.prototype.push.apply(this, E);
            return this
        }, each: function (F, E) {
            return o.each(this, F, E)
        }, index: function (E) {
            return o.inArray(E && E.jquery ? E[0] : E, this)
        }, attr: function (F, H, G) {
            var E = F;
            if (typeof F === "string") {
                if (H === g) {
                    return this[0] && o[G || "attr"](this[0], F)
                } else {
                    E = {};
                    E[F] = H
                }
            }
            return this.each(function (I) {
                for (F in E) {
                    o.attr(G ? this.style : this, F, o.prop(this, E[F], G, I, F))
                }
            })
        }, css: function (E, F) {
            if ((E == "width" || E == "height") && parseFloat(F) < 0) {
                F = g
            }
            return this.attr(E, F, "curCSS")
        }, text: function (F) {
            if (typeof F !== "object" && F != null) {
                return this.empty().append((this[0] && this[0].ownerDocument || document).createTextNode(F))
            }
            var E = "";
            o.each(F || this, function () {
                o.each(this.childNodes, function () {
                    if (this.nodeType != 8) {
                        E += this.nodeType != 1 ? this.nodeValue : o.fn.text([this])
                    }
                })
            });
            return E
        }, wrapAll: function (E) {
            if (this[0]) {
                var F = o(E, this[0].ownerDocument).clone();
                if (this[0].parentNode) {
                    F.insertBefore(this[0])
                }
                F.map(function () {
                    var G = this;
                    while (G.firstChild) {
                        G = G.firstChild
                    }
                    return G
                }).append(this)
            }
            return this
        }, wrapInner: function (E) {
            return this.each(function () {
                o(this).contents().wrapAll(E)
            })
        }, wrap: function (E) {
            return this.each(function () {
                o(this).wrapAll(E)
            })
        }, append: function () {
            return this.domManip(arguments, true, function (E) {
                if (this.nodeType == 1) {
                    this.appendChild(E)
                }
            })
        }, prepend: function () {
            return this.domManip(arguments, true, function (E) {
                if (this.nodeType == 1) {
                    this.insertBefore(E, this.firstChild)
                }
            })
        }, before: function () {
            return this.domManip(arguments, false, function (E) {
                this.parentNode.insertBefore(E, this)
            })
        }, after: function () {
            return this.domManip(arguments, false, function (E) {
                this.parentNode.insertBefore(E, this.nextSibling)
            })
        }, end: function () {
            return this.prevObject || o([])
        }, push: [].push, find: function (E) {
            if (this.length === 1 && !/,/.test(E)) {
                var G = this.pushStack([], "find", E);
                G.length = 0;
                o.find(E, this[0], G);
                return G
            } else {
                var F = o.map(this, function (H) {
                    return o.find(E, H)
                });
                return this.pushStack(/[^+>] [^+>]/.test(E) ? o.unique(F) : F, "find", E)
            }
        }, clone: function (F) {
            var E = this.map(function () {
                if (!o.support.noCloneEvent && !o.isXMLDoc(this)) {
                    var I = this.cloneNode(true), H = document.createElement("div");
                    H.appendChild(I);
                    return o.clean([H.innerHTML])[0]
                } else {
                    return this.cloneNode(true)
                }
            });
            var G = E.find("*").andSelf().each(function () {
                if (this[h] !== g) {
                    this[h] = null
                }
            });
            if (F === true) {
                this.find("*").andSelf().each(function (I) {
                    if (this.nodeType == 3) {
                        return
                    }
                    var H = o.data(this, "events");
                    for (var K in H) {
                        for (var J in H[K]) {
                            o.event.add(G[I], K, H[K][J], H[K][J].data)
                        }
                    }
                })
            }
            return E
        }, filter: function (E) {
            return this.pushStack(o.isFunction(E) && o.grep(this, function (G, F) {
                return E.call(G, F)
            }) || o.multiFilter(E, o.grep(this, function (F) {
                return F.nodeType === 1
            })), "filter", E)
        }, closest: function (E) {
            var F = o.expr.match.POS.test(E) ? o(E) : null;
            return this.map(function () {
                var G = this;
                while (G && G.ownerDocument) {
                    if (F ? F.index(G) > -1 : o(G).is(E)) {
                        return G
                    }
                    G = G.parentNode
                }
            })
        }, not: function (E) {
            if (typeof E === "string") {
                if (f.test(E)) {
                    return this.pushStack(o.multiFilter(E, this, true), "not", E)
                } else {
                    E = o.multiFilter(E, this)
                }
            }
            var F = E.length && E[E.length - 1] !== g && !E.nodeType;
            return this.filter(function () {
                return F ? o.inArray(this, E) < 0 : this != E
            })
        }, add: function (E) {
            return this.pushStack(o.unique(o.merge(this.get(), typeof E === "string" ? o(E) : o.makeArray(E))))
        }, is: function (E) {
            return !!E && o.multiFilter(E, this).length > 0
        }, hasClass: function (E) {
            return !!E && this.is("." + E)
        }, val: function (K) {
            if (K === g) {
                var E = this[0];
                if (E) {
                    if (o.nodeName(E, "option")) {
                        return(E.attributes.value || {}).specified ? E.value : E.text
                    }
                    if (o.nodeName(E, "select")) {
                        var I = E.selectedIndex, L = [], M = E.options, H = E.type == "select-one";
                        if (I < 0) {
                            return null
                        }
                        for (var F = H ? I : 0, J = H ? I + 1 : M.length; F < J; F++) {
                            var G = M[F];
                            if (G.selected) {
                                K = o(G).val();
                                if (H) {
                                    return K
                                }
                                L.push(K)
                            }
                        }
                        return L
                    }
                    return(E.value || "").replace(/\r/g, "")
                }
                return g
            }
            if (typeof K === "number") {
                K += ""
            }
            return this.each(function () {
                if (this.nodeType != 1) {
                    return
                }
                if (o.isArray(K) && /radio|checkbox/.test(this.type)) {
                    this.checked = (o.inArray(this.value, K) >= 0 || o.inArray(this.name, K) >= 0)
                } else {
                    if (o.nodeName(this, "select")) {
                        var N = o.makeArray(K);
                        o("option", this).each(function () {
                            this.selected = (o.inArray(this.value, N) >= 0 || o.inArray(this.text, N) >= 0)
                        });
                        if (!N.length) {
                            this.selectedIndex = -1
                        }
                    } else {
                        this.value = K
                    }
                }
            })
        }, html: function (E) {
            return E === g ? (this[0] ? this[0].innerHTML : null) : this.empty().append(E)
        }, replaceWith: function (E) {
            return this.after(E).remove()
        }, eq: function (E) {
            return this.slice(E, +E + 1)
        }, slice: function () {
            return this.pushStack(Array.prototype.slice.apply(this, arguments), "slice", Array.prototype.slice.call(arguments).join(","))
        }, map: function (E) {
            return this.pushStack(o.map(this, function (G, F) {
                return E.call(G, F, G)
            }))
        }, andSelf: function () {
            return this.add(this.prevObject)
        }, domManip: function (K, N, M) {
            if (this[0]) {
                var J = (this[0].ownerDocument || this[0]).createDocumentFragment(), G = o.clean(K, (this[0].ownerDocument || this[0]), J), I = J.firstChild, E = this.length > 1 ? J.cloneNode(true) : J;
                if (I) {
                    for (var H = 0, F = this.length; H < F; H++) {
                        M.call(L(this[H], I), H > 0 ? E.cloneNode(true) : J)
                    }
                }
                if (G) {
                    o.each(G, z)
                }
            }
            return this;
            function L(O, P) {
                return N && o.nodeName(O, "table") && o.nodeName(P, "tr") ? (O.getElementsByTagName("tbody")[0] || O.appendChild(O.ownerDocument.createElement("tbody"))) : O
            }}
    };
    o.fn.init.prototype = o.fn;
    function z(E, F) {
        if (F.src) {
            o.ajax({url: F.src, async: false, dataType: "script"})
        } else {
            o.globalEval(F.text || F.textContent || F.innerHTML || "")
        }
        if (F.parentNode) {
            F.parentNode.removeChild(F)
        }
    }
    function e() {
        return +new Date
    }
    o.extend = o.fn.extend = function () {
        var J = arguments[0] || {}, H = 1, I = arguments.length, E = false, G;
        if (typeof J === "boolean") {
            E = J;
            J = arguments[1] || {};
            H = 2
        }
        if (typeof J !== "object" && !o.isFunction(J)) {
            J = {}
        }
        if (I == H) {
            J = this;
            --H
        }
        for (; H < I; H++) {
            if ((G = arguments[H]) != null) {
                for (var F in G) {
                    var K = J[F], L = G[F];
                    if (J === L) {
                        continue
                    }
                    if (E && L && typeof L === "object" && !L.nodeType) {
                        J[F] = o.extend(E, K || (L.length != null ? [] : {}), L)
                    } else {
                        if (L !== g) {
                            J[F] = L
                        }
                    }
                }
            }
        }
        return J
    };
    var b = /z-?index|font-?weight|opacity|zoom|line-?height/i, q = document.defaultView || {}, s = Object.prototype.toString;
    o.extend({noConflict: function (E) {
            l.$ = p;
            if (E) {
                l.jQuery = y
            }
            return o
        }, isFunction: function (E) {
            return s.call(E) === "[object Function]"
        }, isArray: function (E) {
            return s.call(E) === "[object Array]"
        }, isXMLDoc: function (E) {
            return E.nodeType === 9 && E.documentElement.nodeName !== "HTML" || !!E.ownerDocument && o.isXMLDoc(E.ownerDocument)
        }, globalEval: function (G) {
            G = o.trim(G);
            if (G) {
                var F = document.getElementsByTagName("head")[0] || document.documentElement, E = document.createElement("script");
                E.type = "text/javascript";
                if (o.support.scriptEval) {
                    E.appendChild(document.createTextNode(G))
                } else {
                    E.text = G
                }
                F.insertBefore(E, F.firstChild);
                F.removeChild(E)
            }
        }, nodeName: function (F, E) {
            return F.nodeName && F.nodeName.toUpperCase() == E.toUpperCase()
        }, each: function (G, K, F) {
            var E, H = 0, I = G.length;
            if (F) {
                if (I === g) {
                    for (E in G) {
                        if (K.apply(G[E], F) === false) {
                            break
                        }
                    }
                } else {
                    for (; H < I; ) {
                        if (K.apply(G[H++], F) === false) {
                            break
                        }
                    }
                }
            } else {
                if (I === g) {
                    for (E in G) {
                        if (K.call(G[E], E, G[E]) === false) {
                            break
                        }
                    }
                } else {
                    for (var J = G[0]; H < I && K.call(J, H, J) !== false; J = G[++H]) {
                    }
                }
            }
            return G
        }, prop: function (H, I, G, F, E) {
            if (o.isFunction(I)) {
                I = I.call(H, F)
            }
            return typeof I === "number" && G == "curCSS" && !b.test(E) ? I + "px" : I
        }, className: {add: function (E, F) {
                o.each((F || "").split(/\s+/), function (G, H) {
                    if (E.nodeType == 1 && !o.className.has(E.className, H)) {
                        E.className += (E.className ? " " : "") + H
                    }
                })
            }, remove: function (E, F) {
                if (E.nodeType == 1) {
                    E.className = F !== g ? o.grep(E.className.split(/\s+/), function (G) {
                        return !o.className.has(F, G)
                    }).join(" ") : ""
                }
            }, has: function (F, E) {
                return F && o.inArray(E, (F.className || F).toString().split(/\s+/)) > -1
            }}, swap: function (H, G, I) {
            var E = {};
            for (var F in G) {
                E[F] = H.style[F];
                H.style[F] = G[F]
            }
            I.call(H);
            for (var F in G) {
                H.style[F] = E[F]
            }
        }, css: function (G, E, I) {
            if (E == "width" || E == "height") {
                var K, F = {position: "absolute", visibility: "hidden", display: "block"}, J = E == "width" ? ["Left", "Right"] : ["Top", "Bottom"];
                function H() {
                    K = E == "width" ? G.offsetWidth : G.offsetHeight;
                    var M = 0, L = 0;
                    o.each(J, function () {
                        M += parseFloat(o.curCSS(G, "padding" + this, true)) || 0;
                        L += parseFloat(o.curCSS(G, "border" + this + "Width", true)) || 0
                    });
                    K -= Math.round(M + L)
                }
                if (o(G).is(":visible")) {
                    H()
                } else {
                    o.swap(G, F, H)
                }
                return Math.max(0, K)
            }
            return o.curCSS(G, E, I)
        }, curCSS: function (I, F, G) {
            var L, E = I.style;
            if (F == "opacity" && !o.support.opacity) {
                L = o.attr(E, "opacity");
                return L == "" ? "1" : L
            }
            if (F.match(/float/i)) {
                F = w
            }
            if (!G && E && E[F]) {
                L = E[F]
            } else {
                if (q.getComputedStyle) {
                    if (F.match(/float/i)) {
                        F = "float"
                    }
                    F = F.replace(/([A-Z])/g, "-$1").toLowerCase();
                    var M = q.getComputedStyle(I, null);
                    if (M) {
                        L = M.getPropertyValue(F)
                    }
                    if (F == "opacity" && L == "") {
                        L = "1"
                    }
                } else {
                    if (I.currentStyle) {
                        var J = F.replace(/\-(\w)/g, function (N, O) {
                            return O.toUpperCase()
                        });
                        L = I.currentStyle[F] || I.currentStyle[J];
                        if (!/^\d+(px)?$/i.test(L) && /^\d/.test(L)) {
                            var H = E.left, K = I.runtimeStyle.left;
                            I.runtimeStyle.left = I.currentStyle.left;
                            E.left = L || 0;
                            L = E.pixelLeft + "px";
                            E.left = H;
                            I.runtimeStyle.left = K
                        }
                    }
                }
            }
            return L
        }, clean: function (F, K, I) {
            K = K || document;
            if (typeof K.createElement === "undefined") {
                K = K.ownerDocument || K[0] && K[0].ownerDocument || document
            }
            if (!I && F.length === 1 && typeof F[0] === "string") {
                var H = /^<(\w+)\s*\/?>$/.exec(F[0]);
                if (H) {
                    return[K.createElement(H[1])]
                }
            }
            var G = [], E = [], L = K.createElement("div");
            o.each(F, function (P, R) {
                if (typeof R === "number") {
                    R += ""
                }
                if (!R) {
                    return
                }
                if (typeof R === "string") {
                    R = R.replace(/(<(\w+)[^>]*?)\/>/g, function (T, U, S) {
                        return S.match(/^(abbr|br|col|img|input|link|meta|param|hr|area|embed)$/i) ? T : U + "></" + S + ">"
                    });
                    var O = o.trim(R).toLowerCase();
                    var Q = !O.indexOf("<opt") && [1, "<select multiple='multiple'>", "</select>"] || !O.indexOf("<leg") && [1, "<fieldset>", "</fieldset>"] || O.match(/^<(thead|tbody|tfoot|colg|cap)/) && [1, "<table>", "</table>"] || !O.indexOf("<tr") && [2, "<table><tbody>", "</tbody></table>"] || (!O.indexOf("<td") || !O.indexOf("<th")) && [3, "<table><tbody><tr>", "</tr></tbody></table>"] || !O.indexOf("<col") && [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"] || !o.support.htmlSerialize && [1, "div<div>", "</div>"] || [0, "", ""];
                    L.innerHTML = Q[1] + R + Q[2];
                    while (Q[0]--) {
                        L = L.lastChild
                    }
                    if (!o.support.tbody) {
                        var N = !O.indexOf("<table") && O.indexOf("<tbody") < 0 ? L.firstChild && L.firstChild.childNodes : Q[1] == "<table>" && O.indexOf("<tbody") < 0 ? L.childNodes : [];
                        for (var M = N.length - 1; M >= 0; --M) {
                            if (o.nodeName(N[M], "tbody") && !N[M].childNodes.length) {
                                N[M].parentNode.removeChild(N[M])
                            }
                        }
                    }
                    if (!o.support.leadingWhitespace && /^\s/.test(R)) {
                        L.insertBefore(K.createTextNode(R.match(/^\s*/)[0]), L.firstChild)
                    }
                    R = o.makeArray(L.childNodes)
                }
                if (R.nodeType) {
                    G.push(R)
                } else {
                    G = o.merge(G, R)
                }
            });
            if (I) {
                for (var J = 0; G[J]; J++) {
                    if (o.nodeName(G[J], "script") && (!G[J].type || G[J].type.toLowerCase() === "text/javascript")) {
                        E.push(G[J].parentNode ? G[J].parentNode.removeChild(G[J]) : G[J])
                    } else {
                        if (G[J].nodeType === 1) {
                            G.splice.apply(G, [J + 1, 0].concat(o.makeArray(G[J].getElementsByTagName("script"))))
                        }
                        I.appendChild(G[J])
                    }
                }
                return E
            }
            return G
        }, attr: function (J, G, K) {
            if (!J || J.nodeType == 3 || J.nodeType == 8) {
                return g
            }
            var H = !o.isXMLDoc(J), L = K !== g;
            G = H && o.props[G] || G;
            if (J.tagName) {
                var F = /href|src|style/.test(G);
                if (G == "selected" && J.parentNode) {
                    J.parentNode.selectedIndex
                }
                if (G in J && H && !F) {
                    if (L) {
                        if (G == "type" && o.nodeName(J, "input") && J.parentNode) {
                            throw"type property can't be changed"
                        }
                        J[G] = K
                    }
                    if (o.nodeName(J, "form") && J.getAttributeNode(G)) {
                        return J.getAttributeNode(G).nodeValue
                    }
                    if (G == "tabIndex") {
                        var I = J.getAttributeNode("tabIndex");
                        return I && I.specified ? I.value : J.nodeName.match(/(button|input|object|select|textarea)/i) ? 0 : J.nodeName.match(/^(a|area)$/i) && J.href ? 0 : g
                    }
                    return J[G]
                }
                if (!o.support.style && H && G == "style") {
                    return o.attr(J.style, "cssText", K)
                }
                if (L) {
                    J.setAttribute(G, "" + K)
                }
                var E = !o.support.hrefNormalized && H && F ? J.getAttribute(G, 2) : J.getAttribute(G);
                return E === null ? g : E
            }
            if (!o.support.opacity && G == "opacity") {
                if (L) {
                    J.zoom = 1;
                    J.filter = (J.filter || "").replace(/alpha\([^)]*\)/, "") + (parseInt(K) + "" == "NaN" ? "" : "alpha(opacity=" + K * 100 + ")")
                }
                return J.filter && J.filter.indexOf("opacity=") >= 0 ? (parseFloat(J.filter.match(/opacity=([^)]*)/)[1]) / 100) + "" : ""
            }
            G = G.replace(/-([a-z])/ig, function (M, N) {
                return N.toUpperCase()
            });
            if (L) {
                J[G] = K
            }
            return J[G]
        }, trim: function (E) {
            return(E || "").replace(/^\s+|\s+$/g, "")
        }, makeArray: function (G) {
            var E = [];
            if (G != null) {
                var F = G.length;
                if (F == null || typeof G === "string" || o.isFunction(G) || G.setInterval) {
                    E[0] = G
                } else {
                    while (F) {
                        E[--F] = G[F]
                    }
                }
            }
            return E
        }, inArray: function (G, H) {
            for (var E = 0, F = H.length; E < F; E++) {
                if (H[E] === G) {
                    return E
                }
            }
            return -1
        }, merge: function (H, E) {
            var F = 0, G, I = H.length;
            if (!o.support.getAll) {
                while ((G = E[F++]) != null) {
                    if (G.nodeType != 8) {
                        H[I++] = G
                    }
                }
            } else {
                while ((G = E[F++]) != null) {
                    H[I++] = G
                }
            }
            return H
        }, unique: function (K) {
            var F = [], E = {};
            try {
                for (var G = 0, H = K.length; G < H; G++) {
                    var J = o.data(K[G]);
                    if (!E[J]) {
                        E[J] = true;
                        F.push(K[G])
                    }
                }
            } catch (I) {
                F = K
            }
            return F
        }, grep: function (F, J, E) {
            var G = [];
            for (var H = 0, I = F.length; H < I; H++) {
                if (!E != !J(F[H], H)) {
                    G.push(F[H])
                }
            }
            return G
        }, map: function (E, J) {
            var F = [];
            for (var G = 0, H = E.length; G < H; G++) {
                var I = J(E[G], G);
                if (I != null) {
                    F[F.length] = I
                }
            }
            return F.concat.apply([], F)
        }});
    var C = navigator.userAgent.toLowerCase();
    o.browser = {version: (C.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [0, "0"])[1], safari: /webkit/.test(C), opera: /opera/.test(C), msie: /msie/.test(C) && !/opera/.test(C), mozilla: /mozilla/.test(C) && !/(compatible|webkit)/.test(C)};
    o.each({parent: function (E) {
            return E.parentNode
        }, parents: function (E) {
            return o.dir(E, "parentNode")
        }, next: function (E) {
            return o.nth(E, 2, "nextSibling")
        }, prev: function (E) {
            return o.nth(E, 2, "previousSibling")
        }, nextAll: function (E) {
            return o.dir(E, "nextSibling")
        }, prevAll: function (E) {
            return o.dir(E, "previousSibling")
        }, siblings: function (E) {
            return o.sibling(E.parentNode.firstChild, E)
        }, children: function (E) {
            return o.sibling(E.firstChild)
        }, contents: function (E) {
            return o.nodeName(E, "iframe") ? E.contentDocument || E.contentWindow.document : o.makeArray(E.childNodes)
        }}, function (E, F) {
        o.fn[E] = function (G) {
            var H = o.map(this, F);
            if (G && typeof G == "string") {
                H = o.multiFilter(G, H)
            }
            return this.pushStack(o.unique(H), E, G)
        }
    });
    o.each({appendTo: "append", prependTo: "prepend", insertBefore: "before", insertAfter: "after", replaceAll: "replaceWith"}, function (E, F) {
        o.fn[E] = function () {
            var G = arguments;
            return this.each(function () {
                for (var H = 0, I = G.length; H < I; H++) {
                    o(G[H])[F](this)
                }
            })
        }
    });
    o.each({removeAttr: function (E) {
            o.attr(this, E, "");
            if (this.nodeType == 1) {
                this.removeAttribute(E)
            }
        }, addClass: function (E) {
            o.className.add(this, E)
        }, removeClass: function (E) {
            o.className.remove(this, E)
        }, toggleClass: function (F, E) {
            if (typeof E !== "boolean") {
                E = !o.className.has(this, F)
            }
            o.className[E ? "add" : "remove"](this, F)
        }, remove: function (E) {
            if (!E || o.filter(E, [this]).length) {
                o("*", this).add([this]).each(function () {
                    o.event.remove(this);
                    o.removeData(this)
                });
                if (this.parentNode) {
                    this.parentNode.removeChild(this)
                }
            }
        }, empty: function () {
            o(">*", this).remove();
            while (this.firstChild) {
                this.removeChild(this.firstChild)
            }
        }}, function (E, F) {
        o.fn[E] = function () {
            return this.each(F, arguments)
        }
    });
    function j(E, F) {
        return E[0] && parseInt(o.curCSS(E[0], F, true), 10) || 0
    }
    var h = "jQuery" + e(), v = 0, A = {};
    o.extend({cache: {}, data: function (F, E, G) {
            F = F == l ? A : F;
            var H = F[h];
            if (!H) {
                H = F[h] = ++v
            }
            if (E && !o.cache[H]) {
                o.cache[H] = {}
            }
            if (G !== g) {
                o.cache[H][E] = G
            }
            return E ? o.cache[H][E] : H
        }, removeData: function (F, E) {
            F = F == l ? A : F;
            var H = F[h];
            if (E) {
                if (o.cache[H]) {
                    delete o.cache[H][E];
                    E = "";
                    for (E in o.cache[H]) {
                        break
                    }
                    if (!E) {
                        o.removeData(F)
                    }
                }
            } else {
                try {
                    delete F[h]
                } catch (G) {
                    if (F.removeAttribute) {
                        F.removeAttribute(h)
                    }
                }
                delete o.cache[H]
            }
        }, queue: function (F, E, H) {
            if (F) {
                E = (E || "fx") + "queue";
                var G = o.data(F, E);
                if (!G || o.isArray(H)) {
                    G = o.data(F, E, o.makeArray(H))
                } else {
                    if (H) {
                        G.push(H)
                    }
                }
            }
            return G
        }, dequeue: function (H, G) {
            var E = o.queue(H, G), F = E.shift();
            if (!G || G === "fx") {
                F = E[0]
            }
            if (F !== g) {
                F.call(H)
            }
        }});
    o.fn.extend({data: function (E, G) {
            var H = E.split(".");
            H[1] = H[1] ? "." + H[1] : "";
            if (G === g) {
                var F = this.triggerHandler("getData" + H[1] + "!", [H[0]]);
                if (F === g && this.length) {
                    F = o.data(this[0], E)
                }
                return F === g && H[1] ? this.data(H[0]) : F
            } else {
                return this.trigger("setData" + H[1] + "!", [H[0], G]).each(function () {
                    o.data(this, E, G)
                })
            }
        }, removeData: function (E) {
            return this.each(function () {
                o.removeData(this, E)
            })
        }, queue: function (E, F) {
            if (typeof E !== "string") {
                F = E;
                E = "fx"
            }
            if (F === g) {
                return o.queue(this[0], E)
            }
            return this.each(function () {
                var G = o.queue(this, E, F);
                if (E == "fx" && G.length == 1) {
                    G[0].call(this)
                }
            })
        }, dequeue: function (E) {
            return this.each(function () {
                o.dequeue(this, E)
            })
        }});
    /* * Sizzle CSS Selector Engine - v0.9.3 *  Copyright 2009, The Dojo Foundation *  Released under the MIT, BSD, and GPL Licenses. *  More information: http://sizzlejs.com/ */(function () {
        var Q = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^[\]]*\]|['"][^'"]+['"]|[^[\]'"]+)+\]|\\.|[^ >+~,(\[]+)+|[>+~])(\s*,\s*)?/g, K = 0, G = Object.prototype.toString;
        var F = function (X, T, aa, ab) {
            aa = aa || [];
            T = T || document;
            if (T.nodeType !== 1 && T.nodeType !== 9) {
                return[]
            }
            if (!X || typeof X !== "string") {
                return aa
            }
            var Y = [], V, ae, ah, S, ac, U, W = true;
            Q.lastIndex = 0;
            while ((V = Q.exec(X)) !== null) {
                Y.push(V[1]);
                if (V[2]) {
                    U = RegExp.rightContext;
                    break
                }
            }
            if (Y.length > 1 && L.exec(X)) {
                if (Y.length === 2 && H.relative[Y[0]]) {
                    ae = I(Y[0] + Y[1], T)
                } else {
                    ae = H.relative[Y[0]] ? [T] : F(Y.shift(), T);
                    while (Y.length) {
                        X = Y.shift();
                        if (H.relative[X]) {
                            X += Y.shift()
                        }
                        ae = I(X, ae)
                    }
                }
            } else {
                var ad = ab ? {expr: Y.pop(), set: E(ab)} : F.find(Y.pop(), Y.length === 1 && T.parentNode ? T.parentNode : T, P(T));
                ae = F.filter(ad.expr, ad.set);
                if (Y.length > 0) {
                    ah = E(ae)
                } else {
                    W = false
                }
                while (Y.length) {
                    var ag = Y.pop(), af = ag;
                    if (!H.relative[ag]) {
                        ag = ""
                    } else {
                        af = Y.pop()
                    }
                    if (af == null) {
                        af = T
                    }
                    H.relative[ag](ah, af, P(T))
                }
            }
            if (!ah) {
                ah = ae
            }
            if (!ah) {
                throw"Syntax error, unrecognized expression: " + (ag || X)
            }
            if (G.call(ah) === "[object Array]") {
                if (!W) {
                    aa.push.apply(aa, ah)
                } else {
                    if (T.nodeType === 1) {
                        for (var Z = 0; ah[Z] != null; Z++) {
                            if (ah[Z] && (ah[Z] === true || ah[Z].nodeType === 1 && J(T, ah[Z]))) {
                                aa.push(ae[Z])
                            }
                        }
                    } else {
                        for (var Z = 0; ah[Z] != null; Z++) {
                            if (ah[Z] && ah[Z].nodeType === 1) {
                                aa.push(ae[Z])
                            }
                        }
                    }
                }
            } else {
                E(ah, aa)
            }
            if (U) {
                F(U, T, aa, ab)
            }
            return aa
        };
        F.matches = function (S, T) {
            return F(S, null, null, T)
        };
        F.find = function (Z, S, aa) {
            var Y, W;
            if (!Z) {
                return[]
            }
            for (var V = 0, U = H.order.length; V < U; V++) {
                var X = H.order[V], W;
                if ((W = H.match[X].exec(Z))) {
                    var T = RegExp.leftContext;
                    if (T.substr(T.length - 1) !== "\\") {
                        W[1] = (W[1] || "").replace(/\\/g, "");
                        Y = H.find[X](W, S, aa);
                        if (Y != null) {
                            Z = Z.replace(H.match[X], "");
                            break
                        }
                    }
                }
            }
            if (!Y) {
                Y = S.getElementsByTagName("*")
            }
            return{set: Y, expr: Z}
        };
        F.filter = function (ab, aa, ae, V) {
            var U = ab, ag = [], Y = aa, X, S;
            while (ab && aa.length) {
                for (var Z in H.filter) {
                    if ((X = H.match[Z].exec(ab)) != null) {
                        var T = H.filter[Z], af, ad;
                        S = false;
                        if (Y == ag) {
                            ag = []
                        }
                        if (H.preFilter[Z]) {
                            X = H.preFilter[Z](X, Y, ae, ag, V);
                            if (!X) {
                                S = af = true
                            } else {
                                if (X === true) {
                                    continue
                                }
                            }
                        }
                        if (X) {
                            for (var W = 0; (ad = Y[W]) != null; W++) {
                                if (ad) {
                                    af = T(ad, X, W, Y);
                                    var ac = V ^ !!af;
                                    if (ae && af != null) {
                                        if (ac) {
                                            S = true
                                        } else {
                                            Y[W] = false
                                        }
                                    } else {
                                        if (ac) {
                                            ag.push(ad);
                                            S = true
                                        }
                                    }
                                }
                            }
                        }
                        if (af !== g) {
                            if (!ae) {
                                Y = ag
                            }
                            ab = ab.replace(H.match[Z], "");
                            if (!S) {
                                return[]
                            }
                            break
                        }
                    }
                }
                ab = ab.replace(/\s*,\s*/, "");
                if (ab == U) {
                    if (S == null) {
                        throw"Syntax error, unrecognized expression: " + ab
                    } else {
                        break
                    }
                }
                U = ab
            }
            return Y
        };
        var H = F.selectors = {order: ["ID", "NAME", "TAG"], match: {ID: /#((?:[\w\u00c0-\uFFFF_-]|\\.)+)/, CLASS: /\.((?:[\w\u00c0-\uFFFF_-]|\\.)+)/, NAME: /\[name=['"]*((?:[\w\u00c0-\uFFFF_-]|\\.)+)['"]*\]/, ATTR: /\[\s*((?:[\w\u00c0-\uFFFF_-]|\\.)+)\s*(?:(\S?=)\s*(['"]*)(.*?)\3|)\s*\]/, TAG: /^((?:[\w\u00c0-\uFFFF\*_-]|\\.)+)/, CHILD: /:(only|nth|last|first)-child(?:\((even|odd|[\dn+-]*)\))?/, POS: /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^-]|$)/, PSEUDO: /:((?:[\w\u00c0-\uFFFF_-]|\\.)+)(?:\((['"]*)((?:\([^\)]+\)|[^\2\(\)]*)+)\2\))?/}, attrMap: {"class": "className", "for": "htmlFor"}, attrHandle: {href: function (S) {
                    return S.getAttribute("href")
                }}, relative: {"+": function (W, T) {
                    for (var U = 0, S = W.length; U < S; U++) {
                        var V = W[U];
                        if (V) {
                            var X = V.previousSibling;
                            while (X && X.nodeType !== 1) {
                                X = X.previousSibling
                            }
                            W[U] = typeof T === "string" ? X || false : X === T
                        }
                    }
                    if (typeof T === "string") {
                        F.filter(T, W, true)
                    }
                }, ">": function (X, T, Y) {
                    if (typeof T === "string" && !/\W/.test(T)) {
                        T = Y ? T : T.toUpperCase();
                        for (var U = 0, S = X.length; U < S; U++) {
                            var W = X[U];
                            if (W) {
                                var V = W.parentNode;
                                X[U] = V.nodeName === T ? V : false
                            }
                        }
                    } else {
                        for (var U = 0, S = X.length; U < S; U++) {
                            var W = X[U];
                            if (W) {
                                X[U] = typeof T === "string" ? W.parentNode : W.parentNode === T
                            }
                        }
                        if (typeof T === "string") {
                            F.filter(T, X, true)
                        }
                    }
                }, "": function (V, T, X) {
                    var U = "done" + (K++), S = R;
                    if (!T.match(/\W/)) {
                        var W = T = X ? T : T.toUpperCase();
                        S = O
                    }
                    S("parentNode", T, U, V, W, X)
                }, "~": function (V, T, X) {
                    var U = "done" + (K++), S = R;
                    if (typeof T === "string" && !T.match(/\W/)) {
                        var W = T = X ? T : T.toUpperCase();
                        S = O
                    }
                    S("previousSibling", T, U, V, W, X)
                }}, find: {ID: function (T, U, V) {
                    if (typeof U.getElementById !== "undefined" && !V) {
                        var S = U.getElementById(T[1]);
                        return S ? [S] : []
                    }
                }, NAME: function (S, T, U) {
                    if (typeof T.getElementsByName !== "undefined" && !U) {
                        return T.getElementsByName(S[1])
                    }
                }, TAG: function (S, T) {
                    return T.getElementsByTagName(S[1])
                }}, preFilter: {CLASS: function (V, T, U, S, Y) {
                    V = " " + V[1].replace(/\\/g, "") + " ";
                    var X;
                    for (var W = 0; (X = T[W]) != null; W++) {
                        if (X) {
                            if (Y ^ (" " + X.className + " ").indexOf(V) >= 0) {
                                if (!U) {
                                    S.push(X)
                                }
                            } else {
                                if (U) {
                                    T[W] = false
                                }
                            }
                        }
                    }
                    return false
                }, ID: function (S) {
                    return S[1].replace(/\\/g, "")
                }, TAG: function (T, S) {
                    for (var U = 0; S[U] === false; U++) {
                    }
                    return S[U] && P(S[U]) ? T[1] : T[1].toUpperCase()
                }, CHILD: function (S) {
                    if (S[1] == "nth") {
                        var T = /(-?)(\d*)n((?:\+|-)?\d*)/.exec(S[2] == "even" && "2n" || S[2] == "odd" && "2n+1" || !/\D/.test(S[2]) && "0n+" + S[2] || S[2]);
                        S[2] = (T[1] + (T[2] || 1)) - 0;
                        S[3] = T[3] - 0
                    }
                    S[0] = "done" + (K++);
                    return S
                }, ATTR: function (T) {
                    var S = T[1].replace(/\\/g, "");
                    if (H.attrMap[S]) {
                        T[1] = H.attrMap[S]
                    }
                    if (T[2] === "~=") {
                        T[4] = " " + T[4] + " "
                    }
                    return T
                }, PSEUDO: function (W, T, U, S, X) {
                    if (W[1] === "not") {
                        if (W[3].match(Q).length > 1) {
                            W[3] = F(W[3], null, null, T)
                        } else {
                            var V = F.filter(W[3], T, U, true ^ X);
                            if (!U) {
                                S.push.apply(S, V)
                            }
                            return false
                        }
                    } else {
                        if (H.match.POS.test(W[0])) {
                            return true
                        }
                    }
                    return W
                }, POS: function (S) {
                    S.unshift(true);
                    return S
                }}, filters: {enabled: function (S) {
                    return S.disabled === false && S.type !== "hidden"
                }, disabled: function (S) {
                    return S.disabled === true
                }, checked: function (S) {
                    return S.checked === true
                }, selected: function (S) {
                    S.parentNode.selectedIndex;
                    return S.selected === true
                }, parent: function (S) {
                    return !!S.firstChild
                }, empty: function (S) {
                    return !S.firstChild
                }, has: function (U, T, S) {
                    return !!F(S[3], U).length
                }, header: function (S) {
                    return/h\d/i.test(S.nodeName)
                }, text: function (S) {
                    return"text" === S.type
                }, radio: function (S) {
                    return"radio" === S.type
                }, checkbox: function (S) {
                    return"checkbox" === S.type
                }, file: function (S) {
                    return"file" === S.type
                }, password: function (S) {
                    return"password" === S.type
                }, submit: function (S) {
                    return"submit" === S.type
                }, image: function (S) {
                    return"image" === S.type
                }, reset: function (S) {
                    return"reset" === S.type
                }, button: function (S) {
                    return"button" === S.type || S.nodeName.toUpperCase() === "BUTTON"
                }, input: function (S) {
                    return/input|select|textarea|button/i.test(S.nodeName)
                }}, setFilters: {first: function (T, S) {
                    return S === 0
                }, last: function (U, T, S, V) {
                    return T === V.length - 1
                }, even: function (T, S) {
                    return S % 2 === 0
                }, odd: function (T, S) {
                    return S % 2 === 1
                }, lt: function (U, T, S) {
                    return T < S[3] - 0
                }, gt: function (U, T, S) {
                    return T > S[3] - 0
                }, nth: function (U, T, S) {
                    return S[3] - 0 == T
                }, eq: function (U, T, S) {
                    return S[3] - 0 == T
                }}, filter: {CHILD: function (S, V) {
                    var Y = V[1], Z = S.parentNode;
                    var X = V[0];
                    if (Z && (!Z[X] || !S.nodeIndex)) {
                        var W = 1;
                        for (var T = Z.firstChild; T; T = T.nextSibling) {
                            if (T.nodeType == 1) {
                                T.nodeIndex = W++
                            }
                        }
                        Z[X] = W - 1
                    }
                    if (Y == "first") {
                        return S.nodeIndex == 1
                    } else {
                        if (Y == "last") {
                            return S.nodeIndex == Z[X]
                        } else {
                            if (Y == "only") {
                                return Z[X] == 1
                            } else {
                                if (Y == "nth") {
                                    var ab = false, U = V[2], aa = V[3];
                                    if (U == 1 && aa == 0) {
                                        return true
                                    }
                                    if (U == 0) {
                                        if (S.nodeIndex == aa) {
                                            ab = true
                                        }
                                    } else {
                                        if ((S.nodeIndex - aa) % U == 0 && (S.nodeIndex - aa) / U >= 0) {
                                            ab = true
                                        }
                                    }
                                    return ab
                                }
                            }
                        }
                    }
                }, PSEUDO: function (Y, U, V, Z) {
                    var T = U[1], W = H.filters[T];
                    if (W) {
                        return W(Y, V, U, Z)
                    } else {
                        if (T === "contains") {
                            return(Y.textContent || Y.innerText || "").indexOf(U[3]) >= 0
                        } else {
                            if (T === "not") {
                                var X = U[3];
                                for (var V = 0, S = X.length; V < S; V++) {
                                    if (X[V] === Y) {
                                        return false
                                    }
                                }
                                return true
                            }
                        }
                    }
                }, ID: function (T, S) {
                    return T.nodeType === 1 && T.getAttribute("id") === S
                }, TAG: function (T, S) {
                    return(S === "*" && T.nodeType === 1) || T.nodeName === S
                }, CLASS: function (T, S) {
                    return S.test(T.className)
                }, ATTR: function (W, U) {
                    var S = H.attrHandle[U[1]] ? H.attrHandle[U[1]](W) : W[U[1]] || W.getAttribute(U[1]), X = S + "", V = U[2], T = U[4];
                    return S == null ? V === "!=" : V === "=" ? X === T : V === "*=" ? X.indexOf(T) >= 0 : V === "~=" ? (" " + X + " ").indexOf(T) >= 0 : !U[4] ? S : V === "!=" ? X != T : V === "^=" ? X.indexOf(T) === 0 : V === "$=" ? X.substr(X.length - T.length) === T : V === "|=" ? X === T || X.substr(0, T.length + 1) === T + "-" : false
                }, POS: function (W, T, U, X) {
                    var S = T[2], V = H.setFilters[S];
                    if (V) {
                        return V(W, U, T, X)
                    }
                }}};
        var L = H.match.POS;
        for (var N in H.match) {
            H.match[N] = RegExp(H.match[N].source + /(?![^\[]*\])(?![^\(]*\))/.source)
        }
        var E = function (T, S) {
            T = Array.prototype.slice.call(T);
            if (S) {
                S.push.apply(S, T);
                return S
            }
            return T
        };
        try {
            Array.prototype.slice.call(document.documentElement.childNodes)
        } catch (M) {
            E = function (W, V) {
                var T = V || [];
                if (G.call(W) === "[object Array]") {
                    Array.prototype.push.apply(T, W)
                } else {
                    if (typeof W.length === "number") {
                        for (var U = 0, S = W.length; U < S; U++) {
                            T.push(W[U])
                        }
                    } else {
                        for (var U = 0; W[U]; U++) {
                            T.push(W[U])
                        }
                    }
                }
                return T
            }
        }
        (function () {
            var T = document.createElement("form"), U = "script" + (new Date).getTime();
            T.innerHTML = "<input name='" + U + "'/>";
            var S = document.documentElement;
            S.insertBefore(T, S.firstChild);
            if (!!document.getElementById(U)) {
                H.find.ID = function (W, X, Y) {
                    if (typeof X.getElementById !== "undefined" && !Y) {
                        var V = X.getElementById(W[1]);
                        return V ? V.id === W[1] || typeof V.getAttributeNode !== "undefined" && V.getAttributeNode("id").nodeValue === W[1] ? [V] : g : []
                    }
                };
                H.filter.ID = function (X, V) {
                    var W = typeof X.getAttributeNode !== "undefined" && X.getAttributeNode("id");
                    return X.nodeType === 1 && W && W.nodeValue === V
                }
            }
            S.removeChild(T)
        })();
        (function () {
            var S = document.createElement("div");
            S.appendChild(document.createComment(""));
            if (S.getElementsByTagName("*").length > 0) {
                H.find.TAG = function (T, X) {
                    var W = X.getElementsByTagName(T[1]);
                    if (T[1] === "*") {
                        var V = [];
                        for (var U = 0; W[U]; U++) {
                            if (W[U].nodeType === 1) {
                                V.push(W[U])
                            }
                        }
                        W = V
                    }
                    return W
                }
            }
            S.innerHTML = "<a href='#'></a>";
            if (S.firstChild && S.firstChild.getAttribute("href") !== "#") {
                H.attrHandle.href = function (T) {
                    return T.getAttribute("href", 2)
                }
            }
        })();
        if (document.querySelectorAll) {
            (function () {
                var S = F, T = document.createElement("div");
                T.innerHTML = "<p class='TEST'></p>";
                if (T.querySelectorAll && T.querySelectorAll(".TEST").length === 0) {
                    return
                }
                F = function (X, W, U, V) {
                    W = W || document;
                    if (!V && W.nodeType === 9 && !P(W)) {
                        try {
                            return E(W.querySelectorAll(X), U)
                        } catch (Y) {
                        }
                    }
                    return S(X, W, U, V)
                };
                F.find = S.find;
                F.filter = S.filter;
                F.selectors = S.selectors;
                F.matches = S.matches
            })()
        }
        if (document.getElementsByClassName && document.documentElement.getElementsByClassName) {
            H.order.splice(1, 0, "CLASS");
            H.find.CLASS = function (S, T) {
                return T.getElementsByClassName(S[1])
            }
        }
        function O(T, Z, Y, ac, aa, ab) {
            for (var W = 0, U = ac.length; W < U; W++) {
                var S = ac[W];
                if (S) {
                    S = S[T];
                    var X = false;
                    while (S && S.nodeType) {
                        var V = S[Y];
                        if (V) {
                            X = ac[V];
                            break
                        }
                        if (S.nodeType === 1 && !ab) {
                            S[Y] = W
                        }
                        if (S.nodeName === Z) {
                            X = S;
                            break
                        }
                        S = S[T]
                    }
                    ac[W] = X
                }
            }
        }
        function R(T, Y, X, ab, Z, aa) {
            for (var V = 0, U = ab.length; V < U; V++) {
                var S = ab[V];
                if (S) {
                    S = S[T];
                    var W = false;
                    while (S && S.nodeType) {
                        if (S[X]) {
                            W = ab[S[X]];
                            break
                        }
                        if (S.nodeType === 1) {
                            if (!aa) {
                                S[X] = V
                            }
                            if (typeof Y !== "string") {
                                if (S === Y) {
                                    W = true;
                                    break
                                }
                            } else {
                                if (F.filter(Y, [S]).length > 0) {
                                    W = S;
                                    break
                                }
                            }
                        }
                        S = S[T]
                    }
                    ab[V] = W
                }
            }
        }
        var J = document.compareDocumentPosition ? function (T, S) {
            return T.compareDocumentPosition(S) & 16
        } : function (T, S) {
            return T !== S && (T.contains ? T.contains(S) : true)
        };
        var P = function (S) {
            return S.nodeType === 9 && S.documentElement.nodeName !== "HTML" || !!S.ownerDocument && P(S.ownerDocument)
        };
        var I = function (S, Z) {
            var V = [], W = "", X, U = Z.nodeType ? [Z] : Z;
            while ((X = H.match.PSEUDO.exec(S))) {
                W += X[0];
                S = S.replace(H.match.PSEUDO, "")
            }
            S = H.relative[S] ? S + "*" : S;
            for (var Y = 0, T = U.length; Y < T; Y++) {
                F(S, U[Y], V)
            }
            return F.filter(W, V)
        };
        o.find = F;
        o.filter = F.filter;
        o.expr = F.selectors;
        o.expr[":"] = o.expr.filters;
        F.selectors.filters.hidden = function (S) {
            return"hidden" === S.type || o.css(S, "display") === "none" || o.css(S, "visibility") === "hidden"
        };
        F.selectors.filters.visible = function (S) {
            return"hidden" !== S.type && o.css(S, "display") !== "none" && o.css(S, "visibility") !== "hidden"
        };
        F.selectors.filters.animated = function (S) {
            return o.grep(o.timers, function (T) {
                return S === T.elem
            }).length
        };
        o.multiFilter = function (U, S, T) {
            if (T) {
                U = ":not(" + U + ")"
            }
            return F.matches(U, S)
        };
        o.dir = function (U, T) {
            var S = [], V = U[T];
            while (V && V != document) {
                if (V.nodeType == 1) {
                    S.push(V)
                }
                V = V[T]
            }
            return S
        };
        o.nth = function (W, S, U, V) {
            S = S || 1;
            var T = 0;
            for (; W; W = W[U]) {
                if (W.nodeType == 1 && ++T == S) {
                    break
                }
            }
            return W
        };
        o.sibling = function (U, T) {
            var S = [];
            for (; U; U = U.nextSibling) {
                if (U.nodeType == 1 && U != T) {
                    S.push(U)
                }
            }
            return S
        };
        return;
        l.Sizzle = F
    })();
    o.event = {add: function (I, F, H, K) {
            if (I.nodeType == 3 || I.nodeType == 8) {
                return
            }
            if (I.setInterval && I != l) {
                I = l
            }
            if (!H.guid) {
                H.guid = this.guid++
            }
            if (K !== g) {
                var G = H;
                H = this.proxy(G);
                H.data = K
            }
            var E = o.data(I, "events") || o.data(I, "events", {}), J = o.data(I, "handle") || o.data(I, "handle", function () {
                return typeof o !== "undefined" && !o.event.triggered ? o.event.handle.apply(arguments.callee.elem, arguments) : g
            });
            J.elem = I;
            o.each(F.split(/\s+/), function (M, N) {
                var O = N.split(".");
                N = O.shift();
                H.type = O.slice().sort().join(".");
                var L = E[N];
                if (o.event.specialAll[N]) {
                    o.event.specialAll[N].setup.call(I, K, O)
                }
                if (!L) {
                    L = E[N] = {};
                    if (!o.event.special[N] || o.event.special[N].setup.call(I, K, O) === false) {
                        if (I.addEventListener) {
                            I.addEventListener(N, J, false)
                        } else {
                            if (I.attachEvent) {
                                I.attachEvent("on" + N, J)
                            }
                        }
                    }
                }
                L[H.guid] = H;
                o.event.global[N] = true
            });
            I = null
        }, guid: 1, global: {}, remove: function (K, H, J) {
            if (K.nodeType == 3 || K.nodeType == 8) {
                return
            }
            var G = o.data(K, "events"), F, E;
            if (G) {
                if (H === g || (typeof H === "string" && H.charAt(0) == ".")) {
                    for (var I in G) {
                        this.remove(K, I + (H || ""))
                    }
                } else {
                    if (H.type) {
                        J = H.handler;
                        H = H.type
                    }
                    o.each(H.split(/\s+/), function (M, O) {
                        var Q = O.split(".");
                        O = Q.shift();
                        var N = RegExp("(^|\\.)" + Q.slice().sort().join(".*\\.") + "(\\.|$)");
                        if (G[O]) {
                            if (J) {
                                delete G[O][J.guid]
                            } else {
                                for (var P in G[O]) {
                                    if (N.test(G[O][P].type)) {
                                        delete G[O][P]
                                    }
                                }
                            }
                            if (o.event.specialAll[O]) {
                                o.event.specialAll[O].teardown.call(K, Q)
                            }
                            for (F in G[O]) {
                                break
                            }
                            if (!F) {
                                if (!o.event.special[O] || o.event.special[O].teardown.call(K, Q) === false) {
                                    if (K.removeEventListener) {
                                        K.removeEventListener(O, o.data(K, "handle"), false)
                                    } else {
                                        if (K.detachEvent) {
                                            K.detachEvent("on" + O, o.data(K, "handle"))
                                        }
                                    }
                                }
                                F = null;
                                delete G[O]
                            }
                        }
                    })
                }
                for (F in G) {
                    break
                }
                if (!F) {
                    var L = o.data(K, "handle");
                    if (L) {
                        L.elem = null
                    }
                    o.removeData(K, "events");
                    o.removeData(K, "handle")
                }
            }
        }, trigger: function (I, K, H, E) {
            var G = I.type || I;
            if (!E) {
                I = typeof I === "object" ? I[h] ? I : o.extend(o.Event(G), I) : o.Event(G);
                if (G.indexOf("!") >= 0) {
                    I.type = G = G.slice(0, -1);
                    I.exclusive = true
                }
                if (!H) {
                    I.stopPropagation();
                    if (this.global[G]) {
                        o.each(o.cache, function () {
                            if (this.events && this.events[G]) {
                                o.event.trigger(I, K, this.handle.elem)
                            }
                        })
                    }
                }
                if (!H || H.nodeType == 3 || H.nodeType == 8) {
                    return g
                }
                I.result = g;
                I.target = H;
                K = o.makeArray(K);
                K.unshift(I)
            }
            I.currentTarget = H;
            var J = o.data(H, "handle");
            if (J) {
                J.apply(H, K)
            }
            if ((!H[G] || (o.nodeName(H, "a") && G == "click")) && H["on" + G] && H["on" + G].apply(H, K) === false) {
                I.result = false
            }
            if (!E && H[G] && !I.isDefaultPrevented() && !(o.nodeName(H, "a") && G == "click")) {
                this.triggered = true;
                try {
                    H[G]()
                } catch (L) {
                }
            }
            this.triggered = false;
            if (!I.isPropagationStopped()) {
                var F = H.parentNode || H.ownerDocument;
                if (F) {
                    o.event.trigger(I, K, F, true)
                }
            }
        }, handle: function (K) {
            var J, E;
            K = arguments[0] = o.event.fix(K || l.event);
            var L = K.type.split(".");
            K.type = L.shift();
            J = !L.length && !K.exclusive;
            var I = RegExp("(^|\\.)" + L.slice().sort().join(".*\\.") + "(\\.|$)");
            E = (o.data(this, "events") || {})[K.type];
            for (var G in E) {
                var H = E[G];
                if (J || I.test(H.type)) {
                    K.handler = H;
                    K.data = H.data;
                    var F = H.apply(this, arguments);
                    if (F !== g) {
                        K.result = F;
                        if (F === false) {
                            K.preventDefault();
                            K.stopPropagation()
                        }
                    }
                    if (K.isImmediatePropagationStopped()) {
                        break
                    }
                }
            }
        }, props: "altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode metaKey newValue originalTarget pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which".split(" "), fix: function (H) {
            if (H[h]) {
                return H
            }
            var F = H;
            H = o.Event(F);
            for (var G = this.props.length, J; G; ) {
                J = this.props[--G];
                H[J] = F[J]
            }
            if (!H.target) {
                H.target = H.srcElement || document
            }
            if (H.target.nodeType == 3) {
                H.target = H.target.parentNode
            }
            if (!H.relatedTarget && H.fromElement) {
                H.relatedTarget = H.fromElement == H.target ? H.toElement : H.fromElement
            }
            if (H.pageX == null && H.clientX != null) {
                var I = document.documentElement, E = document.body;
                H.pageX = H.clientX + (I && I.scrollLeft || E && E.scrollLeft || 0) - (I.clientLeft || 0);
                H.pageY = H.clientY + (I && I.scrollTop || E && E.scrollTop || 0) - (I.clientTop || 0)
            }
            if (!H.which && ((H.charCode || H.charCode === 0) ? H.charCode : H.keyCode)) {
                H.which = H.charCode || H.keyCode
            }
            if (!H.metaKey && H.ctrlKey) {
                H.metaKey = H.ctrlKey
            }
            if (!H.which && H.button) {
                H.which = (H.button & 1 ? 1 : (H.button & 2 ? 3 : (H.button & 4 ? 2 : 0)))
            }
            return H
        }, proxy: function (F, E) {
            E = E || function () {
                return F.apply(this, arguments)
            };
            E.guid = F.guid = F.guid || E.guid || this.guid++;
            return E
        }, special: {ready: {setup: B, teardown: function () {
                }}}, specialAll: {live: {setup: function (E, F) {
                    o.event.add(this, F[0], c)
                }, teardown: function (G) {
                    if (G.length) {
                        var E = 0, F = RegExp("(^|\\.)" + G[0] + "(\\.|$)");
                        o.each((o.data(this, "events").live || {}), function () {
                            if (F.test(this.type)) {
                                E++
                            }
                        });
                        if (E < 1) {
                            o.event.remove(this, G[0], c)
                        }
                    }
                }}}};
    o.Event = function (E) {
        if (!this.preventDefault) {
            return new o.Event(E)
        }
        if (E && E.type) {
            this.originalEvent = E;
            this.type = E.type
        } else {
            this.type = E
        }
        this.timeStamp = e();
        this[h] = true
    };
    function k() {
        return false
    }
    function u() {
        return true
    }
    o.Event.prototype = {preventDefault: function () {
            this.isDefaultPrevented = u;
            var E = this.originalEvent;
            if (!E) {
                return
            }
            if (E.preventDefault) {
                E.preventDefault()
            }
            E.returnValue = false
        }, stopPropagation: function () {
            this.isPropagationStopped = u;
            var E = this.originalEvent;
            if (!E) {
                return
            }
            if (E.stopPropagation) {
                E.stopPropagation()
            }
            E.cancelBubble = true
        }, stopImmediatePropagation: function () {
            this.isImmediatePropagationStopped = u;
            this.stopPropagation()
        }, isDefaultPrevented: k, isPropagationStopped: k, isImmediatePropagationStopped: k};
    var a = function (F) {
        var E = F.relatedTarget;
        while (E && E != this) {
            try {
                E = E.parentNode
            } catch (G) {
                E = this
            }
        }
        if (E != this) {
            F.type = F.data;
            o.event.handle.apply(this, arguments)
        }
    };
    o.each({mouseover: "mouseenter", mouseout: "mouseleave"}, function (F, E) {
        o.event.special[E] = {setup: function () {
                o.event.add(this, F, a, E)
            }, teardown: function () {
                o.event.remove(this, F, a)
            }}
    });
    o.fn.extend({bind: function (F, G, E) {
            return F == "unload" ? this.one(F, G, E) : this.each(function () {
                o.event.add(this, F, E || G, E && G)
            })
        }, one: function (G, H, F) {
            var E = o.event.proxy(F || H, function (I) {
                o(this).unbind(I, E);
                return(F || H).apply(this, arguments)
            });
            return this.each(function () {
                o.event.add(this, G, E, F && H)
            })
        }, unbind: function (F, E) {
            return this.each(function () {
                o.event.remove(this, F, E)
            })
        }, trigger: function (E, F) {
            return this.each(function () {
                o.event.trigger(E, F, this)
            })
        }, triggerHandler: function (E, G) {
            if (this[0]) {
                var F = o.Event(E);
                F.preventDefault();
                F.stopPropagation();
                o.event.trigger(F, G, this[0]);
                return F.result
            }
        }, toggle: function (G) {
            var E = arguments, F = 1;
            while (F < E.length) {
                o.event.proxy(G, E[F++])
            }
            return this.click(o.event.proxy(G, function (H) {
                this.lastToggle = (this.lastToggle || 0) % F;
                H.preventDefault();
                return E[this.lastToggle++].apply(this, arguments) || false
            }))
        }, hover: function (E, F) {
            return this.mouseenter(E).mouseleave(F)
        }, ready: function (E) {
            B();
            if (o.isReady) {
                E.call(document, o)
            } else {
                o.readyList.push(E)
            }
            return this
        }, live: function (G, F) {
            var E = o.event.proxy(F);
            E.guid += this.selector + G;
            o(document).bind(i(G, this.selector), this.selector, E);
            return this
        }, die: function (F, E) {
            o(document).unbind(i(F, this.selector), E ? {guid: E.guid + this.selector + F} : null);
            return this
        }});
    function c(H) {
        var E = RegExp("(^|\\.)" + H.type + "(\\.|$)"), G = true, F = [];
        o.each(o.data(this, "events").live || [], function (I, J) {
            if (E.test(J.type)) {
                var K = o(H.target).closest(J.data)[0];
                if (K) {
                    F.push({elem: K, fn: J})
                }
            }
        });
        o.each(F, function () {
            if (this.fn.call(this.elem, H, this.fn.data) === false) {
                G = false
            }
        });
        return G
    }
    function i(F, E) {
        return["live", F, E.replace(/\./g, "`").replace(/ /g, "|")].join(".")
    }
    o.extend({isReady: false, readyList: [], ready: function () {
            if (!o.isReady) {
                o.isReady = true;
                if (o.readyList) {
                    o.each(o.readyList, function () {
                        this.call(document, o)
                    });
                    o.readyList = null
                }
                o(document).triggerHandler("ready")
            }
        }});
    var x = false;
    function B() {
        if (x) {
            return
        }
        x = true;
        if (document.addEventListener) {
            document.addEventListener("DOMContentLoaded", function () {
                document.removeEventListener("DOMContentLoaded", arguments.callee, false);
                o.ready()
            }, false)
        } else {
            if (document.attachEvent) {
                document.attachEvent("onreadystatechange", function () {
                    if (document.readyState === "complete") {
                        document.detachEvent("onreadystatechange", arguments.callee);
                        o.ready()
                    }
                });
                if (document.documentElement.doScroll && typeof l.frameElement === "undefined") {
                    (function () {
                        if (o.isReady) {
                            return
                        }
                        try {
                            document.documentElement.doScroll("left")
                        } catch (E) {
                            setTimeout(arguments.callee, 0);
                            return
                        }
                        o.ready()
                    })()
                }
            }
        }
        o.event.add(l, "load", o.ready)
    }
    o.each(("blur,focus,load,resize,scroll,unload,click,dblclick,mousedown,mouseup,mousemove,mouseover,mouseout,mouseenter,mouseleave,change,select,submit,keydown,keypress,keyup,error").split(","), function (F, E) {
        o.fn[E] = function (G) {
            return G ? this.bind(E, G) : this.trigger(E)
        }
    });
    o(l).bind("unload", function () {
        for (var E in o.cache) {
            if (E != 1 && o.cache[E].handle) {
                o.event.remove(o.cache[E].handle.elem)
            }
        }
    });
    (function () {
        o.support = {};
        var F = document.documentElement, G = document.createElement("script"), K = document.createElement("div"), J = "script" + (new Date).getTime();
        K.style.display = "none";
        K.innerHTML = '   <link/><table></table><a href="/a" style="color:red;float:left;opacity:.5;">a</a><select><option>text</option></select><object><param/></object>';
        var H = K.getElementsByTagName("*"), E = K.getElementsByTagName("a")[0];
        if (!H || !H.length || !E) {
            return
        }
        o.support = {leadingWhitespace: K.firstChild.nodeType == 3, tbody: !K.getElementsByTagName("tbody").length, objectAll: !!K.getElementsByTagName("object")[0].getElementsByTagName("*").length, htmlSerialize: !!K.getElementsByTagName("link").length, style: /red/.test(E.getAttribute("style")), hrefNormalized: E.getAttribute("href") === "/a", opacity: E.style.opacity === "0.5", cssFloat: !!E.style.cssFloat, scriptEval: false, noCloneEvent: true, boxModel: null};
        G.type = "text/javascript";
        try {
            G.appendChild(document.createTextNode("window." + J + "=1;"))
        } catch (I) {
        }
        F.insertBefore(G, F.firstChild);
        if (l[J]) {
            o.support.scriptEval = true;
            delete l[J]
        }
        F.removeChild(G);
        if (K.attachEvent && K.fireEvent) {
            K.attachEvent("onclick", function () {
                o.support.noCloneEvent = false;
                K.detachEvent("onclick", arguments.callee)
            });
            K.cloneNode(true).fireEvent("onclick")
        }
        o(function () {
            var L = document.createElement("div");
            L.style.width = "1px";
            L.style.paddingLeft = "1px";
            document.body.appendChild(L);
            o.boxModel = o.support.boxModel = L.offsetWidth === 2;
            document.body.removeChild(L)
        })
    })();
    var w = o.support.cssFloat ? "cssFloat" : "styleFloat";
    o.props = {"for": "htmlFor", "class": "className", "float": w, cssFloat: w, styleFloat: w, readonly: "readOnly", maxlength: "maxLength", cellspacing: "cellSpacing", rowspan: "rowSpan", tabindex: "tabIndex"};
    o.fn.extend({_load: o.fn.load, load: function (G, J, K) {
            if (typeof G !== "string") {
                return this._load(G)
            }
            var I = G.indexOf(" ");
            if (I >= 0) {
                var E = G.slice(I, G.length);
                G = G.slice(0, I)
            }
            var H = "GET";
            if (J) {
                if (o.isFunction(J)) {
                    K = J;
                    J = null
                } else {
                    if (typeof J === "object") {
                        J = o.param(J);
                        H = "POST"
                    }
                }
            }
            var F = this;
            o.ajax({url: G, type: H, dataType: "html", data: J, complete: function (M, L) {
                    if (L == "success" || L == "notmodified") {
                        F.html(E ? o("<div/>").append(M.responseText.replace(/<script(.|\s)*?\/script>/g, "")).find(E) : M.responseText)
                    }
                    if (K) {
                        F.each(K, [M.responseText, L, M])
                    }
                }});
            return this
        }, serialize: function () {
            return o.param(this.serializeArray())
        }, serializeArray: function () {
            return this.map(function () {
                return this.elements ? o.makeArray(this.elements) : this
            }).filter(function () {
                return this.name && !this.disabled && (this.checked || /select|textarea/i.test(this.nodeName) || /text|hidden|password/i.test(this.type))
            }).map(function (E, F) {
                var G = o(this).val();
                return G == null ? null : o.isArray(G) ? o.map(G, function (I, H) {
                    return{name: F.name, value: I}
                }) : {name: F.name, value: G}
            }).get()
        }});
    o.each("ajaxStart,ajaxStop,ajaxComplete,ajaxError,ajaxSuccess,ajaxSend".split(","), function (E, F) {
        o.fn[F] = function (G) {
            return this.bind(F, G)
        }
    });
    var r = e();
    o.extend({get: function (E, G, H, F) {
            if (o.isFunction(G)) {
                H = G;
                G = null
            }
            return o.ajax({type: "GET", url: E, data: G, success: H, dataType: F})
        }, getScript: function (E, F) {
            return o.get(E, null, F, "script")
        }, getJSON: function (E, F, G) {
            return o.get(E, F, G, "json")
        }, post: function (E, G, H, F) {
            if (o.isFunction(G)) {
                H = G;
                G = {}
            }
            return o.ajax({type: "POST", url: E, data: G, success: H, dataType: F})
        }, ajaxSetup: function (E) {
            o.extend(o.ajaxSettings, E)
        }, ajaxSettings: {url: location.href, global: true, type: "GET", contentType: "application/x-www-form-urlencoded", processData: true, async: true, xhr: function () {
                return l.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest()
            }, accepts: {xml: "application/xml, text/xml", html: "text/html", script: "text/javascript, application/javascript", json: "application/json, text/javascript", text: "text/plain", _default: "*/*"}}, lastModified: {}, ajax: function (M) {
            M = o.extend(true, M, o.extend(true, {}, o.ajaxSettings, M));
            var W, F = /=\?(&|$)/g, R, V, G = M.type.toUpperCase();
            if (M.data && M.processData && typeof M.data !== "string") {
                M.data = o.param(M.data)
            }
            if (M.dataType == "jsonp") {
                if (G == "GET") {
                    if (!M.url.match(F)) {
                        M.url += (M.url.match(/\?/) ? "&" : "?") + (M.jsonp || "callback") + "=?"
                    }
                } else {
                    if (!M.data || !M.data.match(F)) {
                        M.data = (M.data ? M.data + "&" : "") + (M.jsonp || "callback") + "=?"
                    }
                }
                M.dataType = "json"
            }
            if (M.dataType == "json" && (M.data && M.data.match(F) || M.url.match(F))) {
                W = "jsonp" + r++;
                if (M.data) {
                    M.data = (M.data + "").replace(F, "=" + W + "$1")
                }
                M.url = M.url.replace(F, "=" + W + "$1");
                M.dataType = "script";
                l[W] = function (X) {
                    V = X;
                    I();
                    L();
                    l[W] = g;
                    try {
                        delete l[W]
                    } catch (Y) {
                    }
                    if (H) {
                        H.removeChild(T)
                    }
                }
            }
            if (M.dataType == "script" && M.cache == null) {
                M.cache = false
            }
            if (M.cache === false && G == "GET") {
                var E = e();
                var U = M.url.replace(/(\?|&)_=.*?(&|$)/, "$1_=" + E + "$2");
                M.url = U + ((U == M.url) ? (M.url.match(/\?/) ? "&" : "?") + "_=" + E : "")
            }
            if (M.data && G == "GET") {
                M.url += (M.url.match(/\?/) ? "&" : "?") + M.data;
                M.data = null
            }
            if (M.global && !o.active++) {
                o.event.trigger("ajaxStart")
            }
            var Q = /^(\w+:)?\/\/([^\/?#]+)/.exec(M.url);
            if (M.dataType == "script" && G == "GET" && Q && (Q[1] && Q[1] != location.protocol || Q[2] != location.host)) {
                var H = document.getElementsByTagName("head")[0];
                var T = document.createElement("script");
                T.src = M.url;
                if (M.scriptCharset) {
                    T.charset = M.scriptCharset
                }
                if (!W) {
                    var O = false;
                    T.onload = T.onreadystatechange = function () {
                        if (!O && (!this.readyState || this.readyState == "loaded" || this.readyState == "complete")) {
                            O = true;
                            I();
                            L();
                            H.removeChild(T)
                        }
                    }
                }
                H.appendChild(T);
                return g
            }
            var K = false;
            var J = M.xhr();
            if (M.username) {
                J.open(G, M.url, M.async, M.username, M.password)
            } else {
                J.open(G, M.url, M.async)
            }
            try {
                if (M.data) {
                    J.setRequestHeader("Content-Type", M.contentType)
                }
                if (M.ifModified) {
                    J.setRequestHeader("If-Modified-Since", o.lastModified[M.url] || "Thu, 01 Jan 1970 00:00:00 GMT")
                }
                J.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                J.setRequestHeader("Accept", M.dataType && M.accepts[M.dataType] ? M.accepts[M.dataType] + ", */*" : M.accepts._default)
            } catch (S) {
            }
            if (M.beforeSend && M.beforeSend(J, M) === false) {
                if (M.global && !--o.active) {
                    o.event.trigger("ajaxStop")
                }
                J.abort();
                return false
            }
            if (M.global) {
                o.event.trigger("ajaxSend", [J, M])
            }
            var N = function (X) {
                if (J.readyState == 0) {
                    if (P) {
                        clearInterval(P);
                        P = null;
                        if (M.global && !--o.active) {
                            o.event.trigger("ajaxStop")
                        }
                    }
                } else {
                    if (!K && J && (J.readyState == 4 || X == "timeout")) {
                        K = true;
                        if (P) {
                            clearInterval(P);
                            P = null
                        }
                        R = X == "timeout" ? "timeout" : !o.httpSuccess(J) ? "error" : M.ifModified && o.httpNotModified(J, M.url) ? "notmodified" : "success";
                        if (R == "success") {
                            try {
                                V = o.httpData(J, M.dataType, M)
                            } catch (Z) {
                                R = "parsererror"
                            }
                        }
                        if (R == "success") {
                            var Y;
                            try {
                                Y = J.getResponseHeader("Last-Modified")
                            } catch (Z) {
                            }
                            if (M.ifModified && Y) {
                                o.lastModified[M.url] = Y
                            }
                            if (!W) {
                                I()
                            }
                        } else {
                            o.handleError(M, J, R)
                        }
                        L();
                        if (X) {
                            J.abort()
                        }
                        if (M.async) {
                            J = null
                        }
                    }
                }
            };
            if (M.async) {
                var P = setInterval(N, 13);
                if (M.timeout > 0) {
                    setTimeout(function () {
                        if (J && !K) {
                            N("timeout")
                        }
                    }, M.timeout)
                }
            }
            try {
                J.send(M.data)
            } catch (S) {
                o.handleError(M, J, null, S)
            }
            if (!M.async) {
                N()
            }
            function I() {
                if (M.success) {
                    M.success(V, R)
                }
                if (M.global) {
                    o.event.trigger("ajaxSuccess", [J, M])
                }
            }
            function L() {
                if (M.complete) {
                    M.complete(J, R)
                }
                if (M.global) {
                    o.event.trigger("ajaxComplete", [J, M])
                }
                if (M.global && !--o.active) {
                    o.event.trigger("ajaxStop")
                }
            }
            return J
        }, handleError: function (F, H, E, G) {
            if (F.error) {
                F.error(H, E, G)
            }
            if (F.global) {
                o.event.trigger("ajaxError", [H, F, G])
            }
        }, active: 0, httpSuccess: function (F) {
            try {
                return !F.status && location.protocol == "file:" || (F.status >= 200 && F.status < 300) || F.status == 304 || F.status == 1223
            } catch (E) {
            }
            return false
        }, httpNotModified: function (G, E) {
            try {
                var H = G.getResponseHeader("Last-Modified");
                return G.status == 304 || H == o.lastModified[E]
            } catch (F) {
            }
            return false
        }, httpData: function (J, H, G) {
            var F = J.getResponseHeader("content-type"), E = H == "xml" || !H && F && F.indexOf("xml") >= 0, I = E ? J.responseXML : J.responseText;
            if (E && I.documentElement.tagName == "parsererror") {
                throw"parsererror"
            }
            if (G && G.dataFilter) {
                I = G.dataFilter(I, H)
            }
            if (typeof I === "string") {
                if (H == "script") {
                    o.globalEval(I)
                }
                if (H == "json") {
                    I = l["eval"]("(" + I + ")")
                }
            }
            return I
        }, param: function (E) {
            var G = [];
            function H(I, J) {
                G[G.length] = encodeURIComponent(I) + "=" + encodeURIComponent(J)
            }
            if (o.isArray(E) || E.jquery) {
                o.each(E, function () {
                    H(this.name, this.value)
                })
            } else {
                for (var F in E) {
                    if (o.isArray(E[F])) {
                        o.each(E[F], function () {
                            H(F, this)
                        })
                    } else {
                        H(F, o.isFunction(E[F]) ? E[F]() : E[F])
                    }
                }
            }
            return G.join("&").replace(/%20/g, "+")
        }});
    var m = {}, n, d = [["height", "marginTop", "marginBottom", "paddingTop", "paddingBottom"], ["width", "marginLeft", "marginRight", "paddingLeft", "paddingRight"], ["opacity"]];
    function t(F, E) {
        var G = {};
        o.each(d.concat.apply([], d.slice(0, E)), function () {
            G[this] = F
        });
        return G
    }
    o.fn.extend({show: function (J, L) {
            if (J) {
                return this.animate(t("show", 3), J, L)
            } else {
                for (var H = 0, F = this.length; H < F; H++) {
                    var E = o.data(this[H], "olddisplay");
                    this[H].style.display = E || "";
                    if (o.css(this[H], "display") === "none") {
                        var G = this[H].tagName, K;
                        if (m[G]) {
                            K = m[G]
                        } else {
                            var I = o("<" + G + " />").appendTo("body");
                            K = I.css("display");
                            if (K === "none") {
                                K = "block"
                            }
                            I.remove();
                            m[G] = K
                        }
                        this[H].style.display = o.data(this[H], "olddisplay", K)
                    }
                }
                return this
            }
        }, hide: function (H, I) {
            if (H) {
                return this.animate(t("hide", 3), H, I)
            } else {
                for (var G = 0, F = this.length; G < F; G++) {
                    var E = o.data(this[G], "olddisplay");
                    if (!E && E !== "none") {
                        o.data(this[G], "olddisplay", o.css(this[G], "display"))
                    }
                    this[G].style.display = "none"
                }
                return this
            }
        }, _toggle: o.fn.toggle, toggle: function (G, F) {
            var E = typeof G === "boolean";
            return o.isFunction(G) && o.isFunction(F) ? this._toggle.apply(this, arguments) : G == null || E ? this.each(function () {
                var H = E ? G : o(this).is(":hidden");
                o(this)[H ? "show" : "hide"]()
            }) : this.animate(t("toggle", 3), G, F)
        }, fadeTo: function (E, G, F) {
            return this.animate({opacity: G}, E, F)
        }, animate: function (I, F, H, G) {
            var E = o.speed(F, H, G);
            return this[E.queue === false ? "each" : "queue"](function () {
                var K = o.extend({}, E), M, L = this.nodeType == 1 && o(this).is(":hidden"), J = this;
                for (M in I) {
                    if (I[M] == "hide" && L || I[M] == "show" && !L) {
                        return K.complete.call(this)
                    }
                    if ((M == "height" || M == "width") && this.style) {
                        K.display = o.css(this, "display");
                        K.overflow = this.style.overflow
                    }
                }
                if (K.overflow != null) {
                    this.style.overflow = "hidden"
                }
                K.curAnim = o.extend({}, I);
                o.each(I, function (O, S) {
                    var R = new o.fx(J, K, O);
                    if (/toggle|show|hide/.test(S)) {
                        R[S == "toggle" ? L ? "show" : "hide" : S](I)
                    } else {
                        var Q = S.toString().match(/^([+-]=)?([\d+-.]+)(.*)$/), T = R.cur(true) || 0;
                        if (Q) {
                            var N = parseFloat(Q[2]), P = Q[3] || "px";
                            if (P != "px") {
                                J.style[O] = (N || 1) + P;
                                T = ((N || 1) / R.cur(true)) * T;
                                J.style[O] = T + P
                            }
                            if (Q[1]) {
                                N = ((Q[1] == "-=" ? -1 : 1) * N) + T
                            }
                            R.custom(T, N, P)
                        } else {
                            R.custom(T, S, "")
                        }
                    }
                });
                return true
            })
        }, stop: function (F, E) {
            var G = o.timers;
            if (F) {
                this.queue([])
            }
            this.each(function () {
                for (var H = G.length - 1; H >= 0; H--) {
                    if (G[H].elem == this) {
                        if (E) {
                            G[H](true)
                        }
                        G.splice(H, 1)
                    }
                }
            });
            if (!E) {
                this.dequeue()
            }
            return this
        }});
    o.each({slideDown: t("show", 1), slideUp: t("hide", 1), slideToggle: t("toggle", 1), fadeIn: {opacity: "show"}, fadeOut: {opacity: "hide"}}, function (E, F) {
        o.fn[E] = function (G, H) {
            return this.animate(F, G, H)
        }
    });
    o.extend({speed: function (G, H, F) {
            var E = typeof G === "object" ? G : {complete: F || !F && H || o.isFunction(G) && G, duration: G, easing: F && H || H && !o.isFunction(H) && H};
            E.duration = o.fx.off ? 0 : typeof E.duration === "number" ? E.duration : o.fx.speeds[E.duration] || o.fx.speeds._default;
            E.old = E.complete;
            E.complete = function () {
                if (E.queue !== false) {
                    o(this).dequeue()
                }
                if (o.isFunction(E.old)) {
                    E.old.call(this)
                }
            };
            return E
        }, easing: {linear: function (G, H, E, F) {
                return E + F * G
            }, swing: function (G, H, E, F) {
                return((-Math.cos(G * Math.PI) / 2) + 0.5) * F + E
            }}, timers: [], fx: function (F, E, G) {
            this.options = E;
            this.elem = F;
            this.prop = G;
            if (!E.orig) {
                E.orig = {}
            }
        }});
    o.fx.prototype = {update: function () {
            if (this.options.step) {
                this.options.step.call(this.elem, this.now, this)
            }
            (o.fx.step[this.prop] || o.fx.step._default)(this);
            if ((this.prop == "height" || this.prop == "width") && this.elem.style) {
                this.elem.style.display = "block"
            }
        }, cur: function (F) {
            if (this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null)) {
                return this.elem[this.prop]
            }
            var E = parseFloat(o.css(this.elem, this.prop, F));
            return E && E > -10000 ? E : parseFloat(o.curCSS(this.elem, this.prop)) || 0
        }, custom: function (I, H, G) {
            this.startTime = e();
            this.start = I;
            this.end = H;
            this.unit = G || this.unit || "px";
            this.now = this.start;
            this.pos = this.state = 0;
            var E = this;
            function F(J) {
                return E.step(J)
            }
            F.elem = this.elem;
            if (F() && o.timers.push(F) == 1) {
                n = setInterval(function () {
                    var K = o.timers;
                    for (var J = 0; J < K.length; J++) {
                        if (!K[J]()) {
                            K.splice(J--, 1)
                        }
                    }
                    if (!K.length) {
                        clearInterval(n)
                    }
                }, 13)
            }
        }, show: function () {
            this.options.orig[this.prop] = o.attr(this.elem.style, this.prop);
            this.options.show = true;
            this.custom(this.prop == "width" || this.prop == "height" ? 1 : 0, this.cur());
            o(this.elem).show()
        }, hide: function () {
            this.options.orig[this.prop] = o.attr(this.elem.style, this.prop);
            this.options.hide = true;
            this.custom(this.cur(), 0)
        }, step: function (H) {
            var G = e();
            if (H || G >= this.options.duration + this.startTime) {
                this.now = this.end;
                this.pos = this.state = 1;
                this.update();
                this.options.curAnim[this.prop] = true;
                var E = true;
                for (var F in this.options.curAnim) {
                    if (this.options.curAnim[F] !== true) {
                        E = false
                    }
                }
                if (E) {
                    if (this.options.display != null) {
                        this.elem.style.overflow = this.options.overflow;
                        this.elem.style.display = this.options.display;
                        if (o.css(this.elem, "display") == "none") {
                            this.elem.style.display = "block"
                        }
                    }
                    if (this.options.hide) {
                        o(this.elem).hide()
                    }
                    if (this.options.hide || this.options.show) {
                        for (var I in this.options.curAnim) {
                            o.attr(this.elem.style, I, this.options.orig[I])
                        }
                    }
                    this.options.complete.call(this.elem)
                }
                return false
            } else {
                var J = G - this.startTime;
                this.state = J / this.options.duration;
                this.pos = o.easing[this.options.easing || (o.easing.swing ? "swing" : "linear")](this.state, J, 0, 1, this.options.duration);
                this.now = this.start + ((this.end - this.start) * this.pos);
                this.update()
            }
            return true
        }};
    o.extend(o.fx, {speeds: {slow: 600, fast: 200, _default: 400}, step: {opacity: function (E) {
                o.attr(E.elem.style, "opacity", E.now)
            }, _default: function (E) {
                if (E.elem.style && E.elem.style[E.prop] != null) {
                    E.elem.style[E.prop] = E.now + E.unit
                } else {
                    E.elem[E.prop] = E.now
                }
            }}});
    if (document.documentElement.getBoundingClientRect) {
        o.fn.offset = function () {
            if (!this[0]) {
                return{top: 0, left: 0}
            }
            if (this[0] === this[0].ownerDocument.body) {
                return o.offset.bodyOffset(this[0])
            }
            var G = this[0].getBoundingClientRect(), J = this[0].ownerDocument, F = J.body, E = J.documentElement, L = E.clientTop || F.clientTop || 0, K = E.clientLeft || F.clientLeft || 0, I = G.top + (self.pageYOffset || o.boxModel && E.scrollTop || F.scrollTop) - L, H = G.left + (self.pageXOffset || o.boxModel && E.scrollLeft || F.scrollLeft) - K;
            return{top: I, left: H}
        }
    } else {
        o.fn.offset = function () {
            if (!this[0]) {
                return{top: 0, left: 0}
            }
            if (this[0] === this[0].ownerDocument.body) {
                return o.offset.bodyOffset(this[0])
            }
            o.offset.initialized || o.offset.initialize();
            var J = this[0], G = J.offsetParent, F = J, O = J.ownerDocument, M, H = O.documentElement, K = O.body, L = O.defaultView, E = L.getComputedStyle(J, null), N = J.offsetTop, I = J.offsetLeft;
            while ((J = J.parentNode) && J !== K && J !== H) {
                M = L.getComputedStyle(J, null);
                N -= J.scrollTop, I -= J.scrollLeft;
                if (J === G) {
                    N += J.offsetTop, I += J.offsetLeft;
                    if (o.offset.doesNotAddBorder && !(o.offset.doesAddBorderForTableAndCells && /^t(able|d|h)$/i.test(J.tagName))) {
                        N += parseInt(M.borderTopWidth, 10) || 0, I += parseInt(M.borderLeftWidth, 10) || 0
                    }
                    F = G, G = J.offsetParent
                }
                if (o.offset.subtractsBorderForOverflowNotVisible && M.overflow !== "visible") {
                    N += parseInt(M.borderTopWidth, 10) || 0, I += parseInt(M.borderLeftWidth, 10) || 0
                }
                E = M
            }
            if (E.position === "relative" || E.position === "static") {
                N += K.offsetTop, I += K.offsetLeft
            }
            if (E.position === "fixed") {
                N += Math.max(H.scrollTop, K.scrollTop), I += Math.max(H.scrollLeft, K.scrollLeft)
            }
            return{top: N, left: I}
        }
    }
    o.offset = {initialize: function () {
            if (this.initialized) {
                return
            }
            var L = document.body, F = document.createElement("div"), H, G, N, I, M, E, J = L.style.marginTop, K = '<div style="position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;"><div></div></div><table style="position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;" cellpadding="0" cellspacing="0"><tr><td></td></tr></table>';
            M = {position: "absolute", top: 0, left: 0, margin: 0, border: 0, width: "1px", height: "1px", visibility: "hidden"};
            for (E in M) {
                F.style[E] = M[E]
            }
            F.innerHTML = K;
            L.insertBefore(F, L.firstChild);
            H = F.firstChild, G = H.firstChild, I = H.nextSibling.firstChild.firstChild;
            this.doesNotAddBorder = (G.offsetTop !== 5);
            this.doesAddBorderForTableAndCells = (I.offsetTop === 5);
            H.style.overflow = "hidden", H.style.position = "relative";
            this.subtractsBorderForOverflowNotVisible = (G.offsetTop === -5);
            L.style.marginTop = "1px";
            this.doesNotIncludeMarginInBodyOffset = (L.offsetTop === 0);
            L.style.marginTop = J;
            L.removeChild(F);
            this.initialized = true
        }, bodyOffset: function (E) {
            o.offset.initialized || o.offset.initialize();
            var G = E.offsetTop, F = E.offsetLeft;
            if (o.offset.doesNotIncludeMarginInBodyOffset) {
                G += parseInt(o.curCSS(E, "marginTop", true), 10) || 0, F += parseInt(o.curCSS(E, "marginLeft", true), 10) || 0
            }
            return{top: G, left: F}
        }};
    o.fn.extend({position: function () {
            var I = 0, H = 0, F;
            if (this[0]) {
                var G = this.offsetParent(), J = this.offset(), E = /^body|html$/i.test(G[0].tagName) ? {top: 0, left: 0} : G.offset();
                J.top -= j(this, "marginTop");
                J.left -= j(this, "marginLeft");
                E.top += j(G, "borderTopWidth");
                E.left += j(G, "borderLeftWidth");
                F = {top: J.top - E.top, left: J.left - E.left}
            }
            return F
        }, offsetParent: function () {
            var E = this[0].offsetParent || document.body;
            while (E && (!/^body|html$/i.test(E.tagName) && o.css(E, "position") == "static")) {
                E = E.offsetParent
            }
            return o(E)
        }});
    o.each(["Left", "Top"], function (F, E) {
        var G = "scroll" + E;
        o.fn[G] = function (H) {
            if (!this[0]) {
                return null
            }
            return H !== g ? this.each(function () {
                this == l || this == document ? l.scrollTo(!F ? H : o(l).scrollLeft(), F ? H : o(l).scrollTop()) : this[G] = H
            }) : this[0] == l || this[0] == document ? self[F ? "pageYOffset" : "pageXOffset"] || o.boxModel && document.documentElement[G] || document.body[G] : this[0][G]
        }
    });
    o.each(["Height", "Width"], function (H, F) {
        var E = H ? "Left" : "Top", G = H ? "Right" : "Bottom";
        o.fn["inner" + F] = function () {
            return this[F.toLowerCase()]() + j(this, "padding" + E) + j(this, "padding" + G)
        };
        o.fn["outer" + F] = function (J) {
            return this["inner" + F]() + j(this, "border" + E + "Width") + j(this, "border" + G + "Width") + (J ? j(this, "margin" + E) + j(this, "margin" + G) : 0)
        };
        var I = F.toLowerCase();
        o.fn[I] = function (J) {
            return this[0] == l ? document.compatMode == "CSS1Compat" && document.documentElement["client" + F] || document.body["client" + F] : this[0] == document ? Math.max(document.documentElement["client" + F], document.body["scroll" + F], document.documentElement["scroll" + F], document.body["offset" + F], document.documentElement["offset" + F]) : J === g ? (this.length ? o.css(this[0], I) : null) : this.css(I, typeof J === "string" ? J : J + "px")
        }
    })
})();
// -----------------------------------------------------------------------------
// Code Added for Htaccess- CHG-00000034063 on Feb 02/2011 
// -----------------------------------------------------------------------------


// -----------------------------------------------------------------------------
// Code END for Htaccess- CHG-00000034063 on Feb 02/2011 
// -----------------------------------------------------------------------------
function clearField(field)
{
    document.getElementById(field).value = '';
}

function fillField(field, text)
{
    if (document.getElementById(field).value == '') {
        document.getElementById(field).value = text;
    }
}

// Read Parameters from Querystring
function get_query(name) {
    var value = null;
    var query = window.location.search;
    if (query != "") {
        var kk = query.indexOf(name + "=");
        if (kk >= 0) {
            kk = kk + name.length + 1;
            var ll = query.indexOf("&", kk);
            if (ll < 0)
                ll = query.length;
            value = query.substring(kk, ll);
            for (kk = 0; kk < value.length; kk++) {
                if (value.charAt(kk) == '+') {
                    value = value.substring(0, kk) + " " + value.substring(kk + 1, value.length);
                }
            }
            value = unescape(value);
        }
    }
    // -----------------------------------------------------------------------------
// Code Added for Htaccess- CHG-00000034063 on Feb 02/2011 
// -----------------------------------------------------------------------------
    var coun_mkt = 'CH';

    if (coun_mkt == "UK")
    {
        if (value == null && name == "sess")
        {
            pathArray = window.location.pathname.split('/');

            if (pathArray == ',index.php' || pathArray.length <= 2)
            {
                //value = '';
            }
            else
            {
                value = pathArray[2].substring(0, 35);
            }
        }
    }

    if (value == null && name == "UCvtype")
    {

        value = '';
    }

    if (value == null && name == "plate")
    {

        value = '-1';

    }

    if (value == null && name == "UCmake")
    {

        value = '';
    }

    if (value == null && name == "UCmodel")
    {

        value = '';
    }
    if (value == null && name == "UCfueltype")
    {

        value = '';
    }
    if (value == null && name == "UCbodytype")
    {

        value = '';
    }
    if (value == null && name == "UCdoors")
    {

        value = '';
    }
    if (value == null && name == "UCTrim")
    {

        value = '';
    }
    if (value == null && name == "natcode")
    {
    }
    if (value == null && name == "selkfz")
    {
    }
    // -----------------------------------------------------------------------------
// Code Added for Htaccess- CHG-00000034063 on Feb 02/2011 
// -----------------------------------------------------------------------------
    return value;
}

// Check der angegebenen Kilometer/Meilen auf options.php
function CheckMileage(UCmileage, minmileage, maxmileage)
{
    var send_ok = true;

    if (document.getElementById('UCmileage').value != '') {
        var mileage = document.getElementById('UCmileage').value;
    }
    else {
        var mileage = UCmileage;
    }

    if (isNaN(mileage) || mileage.length == 0 || mileage.length > 6 || parseInt(mileage) < parseInt(document.op_list.mileage_min.value) || parseInt(mileage) > parseInt(document.op_list.mileage_max.value))
    {
        send_ok = false;
    }

    if (send_ok == false)
    {
        alert(document.op_list.mileage_text.value);
        //mileage.select();
        if (document.op_list.hdnglo_market.value == "UK" && document.op_list.UCvtype.value == 30) {
        }
        else {
            document.getElementById('UCmileage').focus();
            //document.getElementById('UCmileage').style.backgroundColor = "#FF979E";
            //document.getElementById('UCmileage').change( function() { document.getElementById('UCmileage').style.backgroundColor = "#FFFFFF";} );
        }
        return;
    } else {
        document.op_list.submit();
    }
}

// Check der angegebenen Kilometer/Meilen auf identnew.php
function CheckMileage_types(UCmileage, minmileage, maxmileage)
{
    var send_ok = true;

    if (document.getElementById('UCmileage').value != '') {
        var mileage = document.getElementById('UCmileage').value;
    }
    else {
        var mileage = UCmileage;
    }

    if (isNaN(mileage) || mileage.length == 0 || mileage.length > 6 || parseInt(mileage) < parseInt(document.UCVal.mileage_min.value) || parseInt(mileage) > parseInt(document.UCVal.mileage_max.value))
    {
        send_ok = false;
    }

    if (send_ok == false)
    {
        alert(document.UCVal.mileage_text.value);
        //mileage.select();
        if (document.UCVal.hdnglo_market.value == "UK" && document.UCVal.UCvtype.value == 30) {
        }
        else {
            document.getElementById('UCmileage').focus();
            //document.getElementById('UCmileage').style.backgroundColor = "#FF979E";
            //document.getElementById('UCmileage').change( function() { document.getElementById('UCmileage').style.backgroundColor = "#FFFFFF";} );
        }
        return false;
    } else {
        document.UCVal.submit();
    }
}




//console.log
function log(text) {
    if ($.browser.mozilla) {
        console.log(text);
    }
}
/******************************************************************************/
// This File gets the contents for the ListBoxes Plate, Make, Model,
// Fuel, Doors and Body.
// The Functions are at the bottom of this File.
/*****************************************************************************/
var models = '';
var types = '';

$(document).ready(function() {



$("#btnSendMail").click(function(e) {
e.preventDefault();
        var frm = document.getElementById('btnSendMail').form;
        if (!frm)
{
return;
}


var x = $("#frm_result_email").val();
        var send_ok = frm.etgDomElement != null && frm.etgDomElement.validateSubmit();
        var sess = get_query("sess");
        var frm_result_email = $("#frm_result_email").val();
        var el = document.getElementById('frm_result_email');
        // Anzeige des Progess-gif, wÃ¤hrend der Verarbeitung des Mails.
        //var progresspath = '/app/plugin_uc/img/all/progressindicator.gif';

        if (send_ok)
{
dom.setLoadingElement(el);
}
if (send_ok)
{
$.post("http://www.eurotaxglass.ch/app/plugin_uc/_src/email_newcws.php", {sess:sess, frm_result_email:frm_result_email}, function(j){
/*alert(j);*/

numbers = domDebug.extractAjaxRequest(j).split('#');
        sent = numbers[0];
        maximum = numbers[1];
        stopsend = numbers[2];
        dom.setLoadingElement(el, false);
        $("#frm_result_email").attr("style", "color:#FF0000;");
        if (stopsend == '1')
{
if (parseInt(sent) == parseInt(maximum))
{
dom.setInfoElement(el, 'max. zu versendeten e-Mails erreicht', 10000);
}
else
{
dom.setInfoElement(el, 'max. zu versendeten e-Mails erreicht', 10000);
}
}
else
{
dom.setInfoElement(el, 'E-Mail versendet', 10000);
}
}); < !-- end click-- >
}
});
        $("#frm_result_email").focus(function(e) {

$("#frm_result_email").val("");
        $("#frm_result_email").attr("style", "color:#00529F;");
});
        /************************************************************************************/
        //alert();
        /**************************************/
        // Enter bei Kilometerfeld unterdrÃ¼cken
        /**************************************/
        //$("#UCmileage").bind("keypress", function(event){
        //if (event.keyCode == 13){return false;}
        //});
        // ... und bei "Gutschein einlÃ¶sen"
        $("#voucher").bind("keypress", function(event){
if (event.keyCode == 13){return false; }
});
        //$("#app_table").hide();

        var UCvtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
UCvtype = get_query("UCvtype");
} else{
UCvtype = 10;
}


if ($("#UCyy").val() != - 1 && $("#UCmm").val() != - 1 &&
        $("#UCyy").val() != null &&
        $("#UCmm").val() != null){
$("#UCmake").attr("disabled", "");
        getMake();
} else if (($("#plate").val() != - 1 &&
        $("#plate").val() != null &&
        $("#plate").val() != '') || (get_query('plate') != '' && get_query('plate') != null && get_query('plate') != - 1)){
$("#UCmake").attr("disabled", "");
        if ($("#UCregion").val() != ''){
} else{
getMake();
}
} else{
$("#UCmake").attr("disabled", "disabled");
}


$("#app_progress").hide();
        $("#app_progress_index").hide();
        getPlate(get_query('plate'));
        /****************************************************************/
        // Marke gewÃ¤hlt, restliche Auswahllisten fÃ¼llen und aktivieren
        /****************************************************************/

        $("#UCmake").change(function(e) {
e.preventDefault();
        self.focus();
        $("#app_bubble_wrapper").hide();
        //$.ajaxTimeout( 3000 );
        getModel($("#UCmake").val(), $("#plate").val());
        getFueltype('', '', '', 0);
        getBodytype('', '', '', 0);
        getDoors('', '', '', 0);
        getTrim('', '', '', 0);
        getCounter('', '', $("#UCmake").val(), $("#UCyy").val(), $("#UCmm").val(), $("#UCmodel").val(), '', '', '', '', '', '', $("#plate").val());
        //$.ajaxTimeout( 0 );
}); < !-- end click-- >
        /***********************/
        // Model geÃ¤ndert
        /***********************/
        $("#UCmodel").change(function(e) {
e.preventDefault();
        self.focus();
        $("#UCfueltype").attr("disabled", "");
        $("#UCbodytype").attr("disabled", "");
        $("#UCdoors").attr("disabled", "");
        $("#UCTrim").attr("disabled", "");
        var sess = get_query("sess");
        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}

var UCyy = $("select#UCyy").val();
        var UCmm = $("select#UCmm").val();
        // Wenn $_glo_market == "UK",
        // mÃ¼ssen Datum und Monat aus der Plate-Listbox gefiltert werden.
        if (UCyy == null && UCmm == null)
{
var dat = $("#plate").val().split(';');
        UCyy = dat[0].substr(0, 4);
        UCmm = dat[0].substr(4, 2);
        var yy_s = dat[0].substr(0, 4);
        var mm_s = dat[0].substr(4, 2);
}

var UCmake = $("select#UCmake").val();
        var UCmileage = $("#UCmileage").val();
        var UCmodel = $("select#UCmodel").val();
        var UCdoors = $("select#UCdoors").val();
        var UCTrim = $("select#UCTrim").val();
        var UCvtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
UCvtype = get_query("UCvtype"); }
else{
UCvtype = 10; }

var plate = get_query("plate")

        if (plate == null || plate == - 1)
{
plate = $("#plate").val();
}

getFueltype('', $("#UCmake").val(), $("#UCmodel").val(), 0);
        getBodytype('', $("#UCmodel").val(), $("#UCmake").val(), 0);
        getDoors('', $("#UCmodel").val(), $("#UCmake").val(), 0);
        getTrim('', $("#UCmodel").val(), $("#UCmake").val(), 0);
        getCounter('', '', $("#UCmake").val(), $("#UCyy").val(), $("#UCmm").val(), $("#UCmodel").val(), '', '', '', '', '', '', $("#plate").val());
        UCTrim = '-1';
        // Typen werden auf der Startseite (Home) nicht geladen.
        // Nur auf der Startseite UC
        var str = window.location;
        var reg = new RegExp("index.php");
        if (reg.test(str)){
}
else{

// -----------------------------------------------------------------------------
// Code Added for Htaccess- CHG-00000034063 on Feb 02/2011 
// -----------------------------------------------------------------------------

getTypes(UCvtype, sess, UCmake, UCyy, UCmm, UCmodel, '', '', '', '', $("#plate").val(), '', '', '', get_query("selkfz"), get_query("UCnumberplate"), get_query("selschild"), get_query("UCregion"), get_query("new_natcode"), get_query("new_mm"), get_query("new_yy"));
        // getTypes(UCvtype, sess, UCmake, UCyy, UCmm, UCmodel, '', '', '', '',plate, '', '', '', get_query("selkfz"), get_query("UCnumberplate"), get_query("selschild"), get_query("UCregion"), get_query("new_natcode"), get_query("new_mm"), get_query("new_yy"));
        // -----------------------------------------------------------------------------
// Code End for Htaccess- CHG-00000034063 on Feb 02/2011 
// -----------------------------------------------------------------------------
}



}); < !-- end Aclick-- >
        /*******************************/
        // Kraftstofftyp geÃ¤ndert
        /*******************************/
        $("#UCfueltype").change(function(e) {
e.preventDefault();
        self.focus();
        var sess = get_query("sess");
        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}

var UCyy = $("select#UCyy").val();
        var UCmm = $("select#UCmm").val();
        // Wenn $_glo_market == "UK",
        // mÃ¼ssen Datum und Monat aus der Plate-Listbox gefiltert werden.
        if (UCyy == null && UCmm == null)
{
var dat = $("#plate").val().split(';');
        UCyy = dat[0].substr(0, 4);
        UCmm = dat[0].substr(4, 2);
        var yy_s = dat[0].substr(0, 4);
        var mm_s = dat[0].substr(4, 2);
}
var UCmake = $("select#UCmake").val();
        var UCmileage = $("#UCmileage").val();
        var UCfueltype = $("#UCfueltype").val();
        if (UCfueltype == null){UCfueltype = ''; }

var UCbodytype = $("#UCbodytype").val();
        if (UCbodytype == null){UCbodytype = ''; }

var UCdoors = $("select#UCdoors").val();
        if (UCdoors == null){UCdoors = ''; }

var UCTrim = $("select#UCTrim").val();
        if (UCTrim == null){UCTrim = ''; }

var UCmodel = $("select#UCmodel").val();
        var UCvtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
UCvtype = get_query("UCvtype"); }
else{
UCvtype = 10; }

getBodytype('', $("#UCmodel").val(), $("#UCmake").val(), 0);
        getDoors('', $("#UCmodel").val(), $("#UCmake").val(), 0);
        getTrim('', $("#UCmodel").val(), $("#UCmake").val(), 0);
        getCounter('', '', $("#UCmake").val(), $("#UCyy").val(), $("#UCmm").val(), $("#UCmodel").val(), $("#UCfueltype").val(), $("#UCbodytype").val(), $("#UCdoors").val(), $("#UCTrim").val(), '', '', $("#plate").val());
        UCbodytype = '-1';
        UCdoors = '-1';
        UCTrim = '-1';
        var str = window.location;
        var reg = new RegExp("index.php");
        if (reg.test(str)){
} else{
getTypes(UCvtype, sess, UCmake, UCyy, UCmm, UCmodel, UCfueltype, UCbodytype, UCdoors, $("#UCTrim").val(), $("#plate").val(), '', '', '', get_query("selkfz"), get_query("UCnumberplate"), get_query("selschild"), get_query("UCregion"), get_query("new_natcode"), get_query("new_mm"), get_query("new_yy"));
}
}); < !-- end click-- >
        /**********************************************/
        // Aufbautyp geÃ¤ndert
        /**********************************************/
        $("#UCbodytype").change(function(e) {
e.preventDefault();
        self.focus();
        var sess = get_query("sess");
        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}

var UCyy = $("select#UCyy").val();
        var UCmm = $("select#UCmm").val();
        // Wenn $_glo_market == "UK",
        // mÃ¼ssen Datum und Monat aus der Plate-Listbox gefiltert werden.
        if (UCyy == null && UCmm == null)
{
var dat = $("#plate").val().split(';');
        UCyy = dat[0].substr(0, 4);
        UCmm = dat[0].substr(4, 2);
        var yy_s = dat[0].substr(0, 4);
        var mm_s = dat[0].substr(4, 2);
}

var UCmake = $("select#UCmake").val();
        var UCmileage = $("#UCmileage").val();
        var UCfueltype = $("select#UCfueltype").val();
        if (UCfueltype == null){UCfueltype = ''; }

var UCbodytype = $("select#UCbodytype").val();
        if (UCbodytype == null){UCbodytype = ''; }

var UCdoors = $("select#UCdoors").val();
        if (UCdoors == null){UCdoors = ''; }

var UCTrim = $("select#UCTrim").val();
        if (UCTrim == null){UCTrim = ''; }

var UCmodel = $("select#UCmodel").val();
        var UCvtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
UCvtype = get_query("UCvtype"); }
else{
UCvtype = 10; }

getDoors('', $("#UCmodel").val(), $("#UCmake").val(), 0);
        getTrim('', $("#UCmodel").val(), $("#UCmake").val(), 0);
        getCounter('', '', $("#UCmake").val(), $("#UCyy").val(), $("#UCmm").val(), $("#UCmodel").val(), $("#UCfueltype").val(), $("#UCbodytype").val(), $("#UCdoors").val(), $("#UCTrim").val(), '', '', $("#plate").val());
        UCdoors = '-1';
        UCTrim = '-1';
        var str = window.location;
        var reg = new RegExp("index.php");
        if (reg.test(str)){
} else{
getTypes(UCvtype, sess, UCmake, UCyy, UCmm, UCmodel, UCfueltype, UCbodytype, UCdoors, UCTrim, $("#plate").val(), '', '', '', get_query("selkfz"), get_query("UCnumberplate"), get_query("selschild"), get_query("UCregion"), get_query("new_natcode"), get_query("new_mm"), get_query("new_yy"));
}
}); < !-- end change-- >
        /**********************************************/
        // Doors geÃ¤ndert
        /**********************************************/
        $("#UCdoors").change(function(e) {
e.preventDefault();
        self.focus();
        var sess = get_query("sess");
        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}

var UCyy = $("select#UCyy").val();
        var UCmm = $("select#UCmm").val();
        // Wenn $_glo_market == "UK",
        // mÃ¼ssen Datum und Monat aus der Plate-Listbox gefiltert werden.
        if (UCyy == null && UCmm == null)
{
var dat = $("#plate").val().split(';');
        UCyy = dat[0].substr(0, 4);
        UCmm = dat[0].substr(4, 2);
        var yy_s = dat[0].substr(0, 4);
        var mm_s = dat[0].substr(4, 2);
}

var UCmake = $("select#UCmake").val();
        var UCmileage = $("#UCmileage").val();
        var UCfueltype = $("select#UCfueltype").val();
        if (UCfueltype == null){UCfueltype = ''; }

var UCbodytype = $("select#UCbodytype").val();
        if (UCbodytype == null){UCbodytype = ''; }

var UCdoors = $("select#UCdoors").val();
        if (UCdoors == null){UCdoors = ''; }

var UCTrim = $("select#UCTrim").val();
        if (UCTrim == null){UCTrim = ''; }

var UCmodel = $("select#UCmodel").val();
        var UCvtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
UCvtype = get_query("UCvtype"); }
else{
UCvtype = 10; }

//              getBodytype('',$("#UCmodel").val(),$("#UCmake").val(),0);
//              getFueltype('',$("#UCmake").val(),$("#UCmodel").val(),0);
getTrim('', $("#UCmodel").val(), $("#UCmake").val(), 0);
        getCounter('', '', $("#UCmake").val(), $("#UCyy").val(), $("#UCmm").val(), $("#UCmodel").val(), $("#UCfueltype").val(), $("#UCbodytype").val(), $("#UCdoors").val(), $("#UCTrim").val(), '', '', $("#plate").val());
        UCTrim = '-1';
        var str = window.location;
        var reg = new RegExp("index.php");
        if (reg.test(str)){
} else{
getTypes(UCvtype, sess, UCmake, UCyy, UCmm, UCmodel, $("#UCfueltype").val(), $("#UCbodytype").val(), $("#UCdoors").val(), $("#UCTrim").val(), $("#plate").val(), '', '', '', get_query("selkfz"), get_query("UCnumberplate"), get_query("selschild"), get_query("UCregion"), get_query("new_natcode"), get_query("new_mm"), get_query("new_yy"));
}
}); < !-- end change-- >
        /**********************************************/
        // Trim geÃ¤ndert
        /**********************************************/
        $("#UCTrim").change(function(e) {
e.preventDefault();
        self.focus();
        var sess = get_query("sess");
        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}

var UCyy = $("select#UCyy").val();
        var UCmm = $("select#UCmm").val();
        // Wenn $_glo_market == "UK",
        // mÃ¼ssen Datum und Monat aus der Plate-Listbox gefiltert werden.
        if (UCyy == null && UCmm == null)
{
var dat = $("#plate").val().split(';');
        UCyy = dat[0].substr(0, 4);
        UCmm = dat[0].substr(4, 2);
        var yy_s = dat[0].substr(0, 4);
        var mm_s = dat[0].substr(4, 2);
}

var UCmake = $("select#UCmake").val();
        var UCmileage = $("#UCmileage").val();
        var UCfueltype = $("select#UCfueltype").val();
        if (UCfueltype == null){UCfueltype = ''; }

var UCbodytype = $("select#UCbodytype").val();
        if (UCbodytype == null){UCbodytype = ''; }

var UCdoors = $("select#UCdoors").val();
        if (UCdoors == null){UCdoors = ''; }

var UCTrim = $("select#UCTrim").val();
        if (UCTrim == null){UCTrim = ''; }

var UCmodel = $("select#UCmodel").val();
        var UCvtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
UCvtype = get_query("UCvtype"); }
else{
UCvtype = 10; }

getCounter('', '', $("#UCmake").val(), $("#UCyy").val(), $("#UCmm").val(), $("#UCmodel").val(), $("#UCfueltype").val(), $("#UCbodytype").val(), $("#UCdoors").val(), $("#UCTrim").val(), '', '', $("#plate").val());
        var str = window.location;
        var reg = new RegExp("index.php");
        if (reg.test(str)){
} else{
getTypes(UCvtype, sess, UCmake, UCyy, UCmm, UCmodel, $("#UCfueltype").val(), $("#UCbodytype").val(), $("#UCdoors").val(), $("#UCTrim").val(), $("#plate").val(), '', '', '', get_query("selkfz"), get_query("UCnumberplate"), get_query("selschild"), get_query("UCregion"), get_query("new_natcode"), get_query("new_mm"), get_query("new_yy"));
}
}); < !-- end change-- >
        /************************************/
        // Jahr geÃ¤ndert
        /************************************/
        $("#UCyy").change(function(e) {

e.preventDefault();
        self.focus();
        //alert('YY change');
        $("#app_bubble_wrapper").hide();
        var sess = get_query("sess");
        var l = get_query("l");
        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
if (l == null){l = 'de'}
var selkfz = get_query('selkfz');
        var UCyy = $("select#UCyy").val();
        //alert(UCyy);


        if ('')
{
var server_red = 'cws-live';
        if ($("#UCyy").val() == "redirectde" && $("#UCmm").val() != '-1' && $("#UCyy").val() != '-1')
        {
        if (server_red == "cws-dev")
        {
        document.getElementById('UCyy').value = - 1;
                window.open("http://cws-dev-new2.freienbach.eurotax.ch/various/help.php#5", "_blank"); exit;
        }
        else if (server_red == "cws-stage")
        {
        document.getElementById('UCyy').value = - 1;
                window.open("http://cws-stage-new2.eurotaxglass.com/various/help.php#5", "_blank"); exit;
        }
        else
        {
        document.getElementById('UCyy').value = - 1;
                window.open("http://www.schwacke.de/various/help.php#5", "_blank"); exit;
        }

        }

}



var UCmm = $("select#UCmm").val();
        if ('')
{
getMonth_cz();
}
var UCmake = $("select#UCmake").val();
        var UCmileage = $("#UCmileage").val();
        var UCfueltype = $("select#UCfueltype").val();
        if (UCfueltype == null){UCfueltype = ''; }

var UCbodytype = $("select#UCbodytype").val();
        if (UCbodytype == null){UCbodytype = ''; }

var UCdoors = $("select#UCdoors").val();
        if (UCdoors == null){UCdoors = ''; }

var UCTrim = $("select#UCTrim").val();
        if (UCTrim == null){UCTrim = ''; }

var UCmodel = $("select[@name='UCmodel']:selected").val();
        var UCvtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
UCvtype = get_query("UCvtype"); }
else{
UCvtype = 10; }

$("#pModels").html('');
        if (selkfz != 'y'){
if ($("#UCmm").val() != - 1 && $("#UCyy").val() != - 1){
$("#UCmake").attr("disabled", "");
        getMake(); }
else{
$("#UCmake").attr("disabled", "disabled"); }
}

$("#pModels").html('');
        $("#UCmodel").html('<option value="-1" selected="selected">Modell w&auml;hlen!</option>');
        if ($("#UCmake").val() != '-1'){
getFueltype('', '', '');
        getBodytype('', '', '');
        getDoors('', '', '');
        getTrim('', '', '');
}
$("#UCmodel").attr("disabled", "disabled");
        $("#UCfueltype").attr("disabled", "disabled");
        $("#UCbodytype").attr("disabled", "disabled");
        $("#UCdoors").attr("disabled", "disabled");
        $("#UCTrim").attr("disabled", "disabled");
        //------------------------------------------------------------------------------------
        //  The code is added to resolve ticket #INC-00001996993
        //  Checking that the element exist before performing any action to avoid javascript
        //  error
        //-------------------------------------------------------------------------------------
        // <Code start>
        if (document.getElementById('UCmake')) {
// <Code end>

for (xx in document.getElementById('UCmake').$events)
{
if (xx == 'change')
{
for (xxx in document.getElementById('UCmake').$events[xx])
{
document.getElementById('UCmake').$events[xx][xxx](e);
}
//document.getElementById('UCmake').$events[xx](e);
}
}
}
}); < !-- end change-- >
        /*******************************************/
        // Monat geÃ¤ndert
        /*******************************************/
        $("#UCmm").change(function(e) {


e.preventDefault();
        self.focus();
        $("#app_bubble_wrapper").hide();
        var sess = get_query("sess");
        var l = get_query("l");
        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
if (l == null){l = 'de'}
var selkfz = get_query('selkfz');
        var UCyy = $("select#UCyy").val();
        var UCmm = $("select#UCmm").val();
        var UCmake = $("select#UCmake").val();
        var UCmileage = $("#UCmileage").val();
        var UCfueltype = $("select#UCfueltype").val();
        if (UCfueltype == null){UCfueltype = ''; }

var UCbodytype = $("select#UCbodytype").val();
        if (UCbodytype == null){UCbodytype = ''; }

var UCdoors = $("select#UCdoors").val();
        if (UCdoors == null){UCdoors = ''; }

var UCTrim = $("select#UCTrim").val();
        if (UCTrim == null){UCTrim = ''; }

var UCmodel = $("select[@name='UCmodel']:selected").val();
        var UCvtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
UCvtype = get_query("UCvtype"); }
else{
UCvtype = 10; }

if (selkfz != 'y'){
if ($("#UCyy").val() != - 1 && $("#UCmm").val() != - 1){
$("#UCmake").attr("disabled", "");
        getMake(); }
else{
$("#UCmake").attr("disabled", "disabled"); }
}

$("#pModels").html('');
        $("#UCmodel").html('<option value="-1" selected="selected">Modell w&auml;hlen!</option>');
        if ($("#UCmake").val() != '-1'){
getFueltype('', '', '');
        getBodytype('', '', '');
        getDoors('', '', '');
        getTrim('', '', '');
}



if ('')
{

$("#UCyy").attr("disabled", "disabled");
        getYear();
}


if ('')
{

// $("#UCyy").attr("disabled","disabled");
// getMonth_cz();
}


$("#UCmodel").attr("disabled", "disabled");
        $("#UCfueltype").attr("disabled", "disabled");
        $("#UCbodytype").attr("disabled", "disabled");
        $("#UCdoors").attr("disabled", "disabled");
        $("#UCTrim").attr("disabled", "disabled");
}); < !-- end change-- >
        /********************************************/
        // NUR Uk
        // Plate geÃ¤ndert
        /********************************************/
        $("#plate").change(function(e) {
e.preventDefault();
        $("#app_bubble_wrapper").hide();
        var l = get_query("l");
        var sess = get_query("sess");
        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
if (l == null){l = 'de'}
var UCmake = $("select#UCmake").val();
        var UCmileage = $("#UCmileage").val();
        var UCfueltype = $("select#UCfueltype").val();
        if (UCfueltype == null){UCfueltype = ''; }

var UCbodytype = $("select#UCbodytype").val();
        if (UCbodytype == null){UCbodytype = ''; }

var UCdoors = $("select#UCdoors").val();
        if (UCdoors == null){UCdoors = ''; }

var UCTrim = $("select#UCTrim").val();
        if (UCTrim == null){UCTrim = ''; }

var UCmodel = $("select[@name='UCmodel']:selected").val();
        var UCvtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
UCvtype = get_query("UCvtype"); }
else{
UCvtype = 10; }

var dat = $("#plate").val().split(';');
        var yy_s = dat[0].substr(0, 4);
        var mm_s = dat[0].substr(4, 2);
        $("#pModels").html('');
        if ($("#plate").val() == '-1'){
$("#UCmake").attr("disabled", "disabled")}
else{
getMake(); }

getModel($("#UCmake").val(), $("#plate").val());
        if ($("#UCmake").val() != '-1'){
getFueltype('', '', '');
        getBodytype('', '', '');
        getDoors('', '', '');
        getTrim('', '', '');
}
$("#UCmodel").attr("disabled", "disabled");
        $("#UCfueltype").attr("disabled", "disabled");
        $("#UCbodytype").attr("disabled", "disabled");
        $("#UCdoors").attr("disabled", "disabled");
        $("#UCTrim").attr("disabled", "disabled");
}); < !-- end change-- >
        //$('#optionpaging').pager('div');

        /*************************************************************************************/
        // Dieser Teil steuert das Verhalten, wenn bereits auf der Startseite Werte erfasst
        // Und der "GO"-Button geklickt wurde.
        // In diesem Fall wird auf die Startseite UC weitergeleitet und die Typen angezeigt.

        // Variante mit Monat, Jahr, Marke, Modell, Kilometer

        if (get_query("UCmake") != null && get_query("UCmake") != "" || get_query("UCmodel") != null && get_query("UCmodel") != "")
{
$("#UCfueltype").attr("disabled", "");
        $("#UCbodytype").attr("disabled", "");
        $("#UCdoors").attr("disabled", "");
        $("#UCTrim").attr("disabled", "");
        var plate = get_query("plate");
        var vtype = get_query("UCvtype");
        var sess = get_query("sess");
        var make = get_query("UCmake");
        /**************************************************/
        // For Debuging
        /**************************************************/

        /*log("ready make:"+make);
         var arrtmp = document.forms[0].options;
         var tmps = $("#UCmake").val();
         log("selindx value:"+tmps);
         log("option length:"+arrtmp.length);*/
        /*for (i = 0; i < arrtmp.length; i++) {
         
         if (arrtmp[i].value == make) {
         log("test selected:"+document.forms["UCVal"]["UCmake"][i].selected);// = true;
         } else {
         log("no match");
         }
         }*/
        //$("#UCmake option:first").attr("selected","selected");
        /*$("#UCmake option").each( function(i) {
         if ($(this).attr("selected")=="selected") {
         var gd = i;
         log(gd);
         }
         });*/
        //document.forms["UCVal"]["UCmake"][2].selected = true;

        /**********************************************************/
        if (get_query("UCyy") != '' && get_query("UCyy") != null && get_query("UCyy") != 'undefined'){
var yy = get_query("UCyy"); }
else{
var yy = ''; }

if (get_query("UCmm") != '' && get_query("UCmm") != null && get_query("UCmm") != 'undefined'){
var mm = get_query("UCmm"); }
else{
var mm = ''; }
var model = get_query("UCmodel");
        var fuel = get_query("UCfueltype");
        var body = get_query("UCbodytype");
        var doors = get_query("UCdoors");
        var trim = get_query("UCTrim");
        var year = get_query("UCyy");
        getMake();
        if ('')
{
getYear();
}





getModel(get_query("UCmake"), plate);
        getFueltype(get_query("UCfueltype"), get_query("UCmake"), get_query("UCmodel"), 1);
        getBodytype(get_query("UCbodytype"), get_query("UCmodel"), get_query("UCmake"), 1);
        getDoors(get_query("UCdoors"), get_query("UCmodel"), get_query("UCmake"), 1);
        //if we want to show the types over the cardata selection we need to comment out the if condition
        if ($("#UCmodel").val() == - 1 && get_query("UCmodel") != ""){
getTrim(get_query("UCTrim"), get_query("UCmodel"), get_query("UCmake"), 1);
        getTypes(vtype, sess, make, yy, mm, model, fuel, body, doors, trim, plate, '', '', '', get_query("selkfz"), get_query("UCnumberplate"), get_query("selschild"), get_query("UCregion"), get_query("new_natcode"), get_query("new_mm"), get_query("new_yy"));
}
getCounter(vtype, sess, make, yy, mm, model, fuel, body, doors, trim, '', '', plate);
}

// Varianten mit Fahrzeugschein/Typenschein
if (get_query("natcode") != null)
{
var natcode = get_query("natcode");
        var vtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
vtype = get_query("UCvtype"); }
else{
vtype = 10; }
var sess = get_query("sess");
        var make = get_query("UCmake");
        var yy = get_query("UCyy");
        var mm = get_query("UCmm")
        var model = get_query("UCmodel");
        var fuel = get_query("UCfueltype");
        var body = get_query("UCbodytype");
        var doors = get_query("UCdoors");
        var trim = get_query("UCTrim");
        getTypes(vtype, sess, make, yy, mm, model, fuel, body, doors, trim, '', natcode, '', '', get_query("selkfz"), get_query("UCnumberplate"), get_query("selschild"), get_query("UCregion"), get_query("new_natcode"), get_query("new_mm"), get_query("new_yy"));
}

if (get_query("hsn") != null)
{
var hsn = get_query("hsn");
        var tsn = get_query("tsn");
        var vtype = get_query("UCvtype");
        var sess = get_query("sess");
        var make = get_query("UCmake");
        var yy = get_query("UCyy");
        var mm = get_query("UCmm")
        var model = get_query("UCmodel");
        var fuel = get_query("UCfueltype");
        var body = get_query("UCbodytype");
        var doors = get_query("UCdoors");
        var trim = get_query("UCTrim");
        getModel(get_query("UCmake"), plate);
        getFueltype(get_query("UCfueltype"), get_query("UCmake"), get_query("UCmodel"));
        getBodytype(get_query("UCbodytype"), get_query("UCmodel"), get_query("UCmake"));
        getDoors(get_query("UCdoors"), get_query("UCmodel"), get_query("UCmake"));
        getTrim(get_query("UCTrim"), get_query("UCmodel"), get_query("UCmake"));
        getTypes(vtype, sess, make, yy, mm, model, fuel, body, doors, trim, '', '', hsn, tsn, get_query("selkfz"), get_query("UCnumberplate"), get_query("selschild"), get_query("UCregion"));
}
/************************************************************************************/

// Varianten mit Autonummer
if (get_query("UCnumberplate") != null)
{
var UCnumberplate = get_query("UCnumberplate");
        var vtype;
        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
vtype = get_query("UCvtype"); }
else{
vtype = 10; }
var sess = get_query("sess");
        var make = get_query("UCmake");
        var yy = get_query("UCyy");
        var mm = get_query("UCmm")
        var model = get_query("UCmodel");
        var fuel = get_query("UCfueltype");
        var body = get_query("UCbodytype");
        var doors = get_query("UCdoors");
        var trim = get_query("UCTrim");
        var selkfz = get_query("selkfz");
        var selschild = get_query("selschild");
        var UCregion = get_query("UCregion");
        var new_natcode = get_query("new_natcode");
        var new_mm = get_query("new_mm");
        var new_yy = get_query("new_yy");
        getTypes(vtype, sess, make, yy, mm, model, fuel, body, doors, trim, '', '', '', '', selkfz, UCnumberplate, selschild, UCregion, new_natcode, new_mm, new_yy);
}

/************************************************************************************/




/************************************************************************************/
// Hier folgt die Kontrolle, wenn auf der Resultseite ein Mail versendet wird.
// - Die E-Mail Adresse muss korrekt formatiert sein.
// - Es darf nur eine e-Mail Adresse eingegeben werden.
// - Nach erreichen der max. Versandanzahl muss der Go-Button inaktiv gesetzt werden.
/************************************************************************************/
/**************************************/
// Enter bei Mail-Adressfeld unterdrÃ¼cken
/**************************************/
$("#frm_result_email").bind("keypress", function(event){
if (event.keyCode == 13){return false; }
});
        /************************************************************************************/
        // SPECIAL XMAS-PROMO
        // Hier folgt die Kontrolle, wenn auf der Resultseite ein Mail versendet wird.
        // - Die E-Mail Adresse muss korrekt formatiert sein.
        // - Es darf nur eine e-Mail Adresse eingegeben werden.
        // - Nach erreichen der max. Versandanzahl muss der Go-Button inaktiv gesetzt werden.
        /************************************************************************************/
        /**************************************/
        // Enter bei Mail-Adressfeld unterdrÃ¼cken
        /**************************************/
        $("#re_email").bind("keypress", function(event){
if (event.keyCode == 13){return false; }
});
        $("#btnSendMailXmas").click(function(e) {
e.preventDefault();
        var frm = document.getElementById('btnSendMailXmas').form;
        if (!frm)
{
return;
}

var x = $("#re_email").val();
        var send_ok = frm.etgDomElement != null && frm.etgDomElement.validateSubmit();
        var sess = get_query("sess");
        var re_email = $("#re_email").val();
        var re_name = $("#re_name").val();
        var se_name = $("#se_name").val();
        var se_txt = $("#se_txt").val();
        var el = document.getElementById('re_email');
        // Anzeige des Progess-gif, wÃ¤hrend der Verarbeitung des Mails.
        //var progresspath = '/app/plugin_uc/img/all/progressindicator.gif';
        if (send_ok)
{
dom.setLoadingElement(el);
}
if (send_ok)
{
$.post("http://www.eurotaxglass.ch/app/plugin_uc/_src/xmas_email.php", {sess:sess, re_email:re_email, re_name:re_name, se_name:se_name, se_txt:se_txt}, function(j){
/*alert(j);*/
numbers = domDebug.extractAjaxRequest(j).split('#');
        sent = numbers[0];
        maximum = numbers[1];
        stopsend = numbers[2];
        dom.setLoadingElement(el, false);
        $("#re_email").attr("style", "color:#FF0000;");
        if (stopsend == '1')
{
if (parseInt(sent) == parseInt(maximum))
{
dom.setInfoElement(el, 'max. zu versendeten e-Mails erreicht', 10000);
}
else
{
dom.setInfoElement(el, 'max. zu versendeten e-Mails erreicht', 10000);
}
}
else
{
dom.setInfoElement(el, 'E-Mail versendet', 10000);
}
}); < !-- end click-- >
}
});
        $("#re_email").focus(function(e) {

$("#re_email").val("");
        $("#re_email").attr("style", "color:#00529F;");
});
        /************************************************************************************/



        }); < !-- end ready-- >
        function getModel(make, plate, natcode, hsn, tsn)
                {
                $("#UCmodel").attr("disabled", "");
                        var sess = get_query("sess");
                        var selectedModel = get_query("UCmodel");
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}

                var UCyy = $("select#UCyy").val();
                        var UCmm = $("select#UCmm").val();
                        var UCmake = make;
                        < !--var UCmileage = $("#UCmileage").val();
                        // --------------------------------------------------------------------------
// Code END For Mileage Correction - CHG-00000042640 on FEb 23-2011
// ---------------------------------------------------------------------------->
                        var string = $("#UCmileage").val();
                        var string1 = string.replace(new RegExp(',', 'g'), '');
                        var UCmileage = string1.replace(new RegExp('.', 'g'), '');
                        < !--// --------------------------------------------------------------------------
// Code END For Mileage Correction - CHG-00000042640 on FEb 23-2011
// -------------------------------------------------------------------------- -->         
                        var UCfueltype = '-1';
                        var UCbodytype = '-1';
                        var UCdoors = '-1';
                        var UCTrim = '-1';
                        var plate = plate;
                        var natcode = natcode;
                        var hsn = hsn;
                        var tsn = tsn;
                        if (UCyy == null && UCmm == null)
                {
                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }
                var UCmodel = $("#UCmodel").val();
                        var UCvtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
                UCvtype = get_query("UCvtype"); }
                else{
                UCvtype = 10; }

                //showProgress();

                //set model as loading
                dom.setLoadingElement(document.getElementById('UCmodel'), true);
                        $("#pModels").html('');
                        $.post("./app/plugin_uc/_src/getModel.php", {l:l, UCvtype: UCvtype, sess: sess, UCmake: UCmake, UCyy: UCyy, UCmm: UCmm, UCmileage: UCmileage, UCfueltype: UCfueltype, UCbodytype: UCbodytype, UCdoors: UCdoors, UCTrim: UCTrim, plate: plate, selectedModel: selectedModel}, function(j){
                        models = domDebug.extractAjaxRequest(j);
                                //alert(models);
                                var srch = models;
                                models = models.replace(/_/g, "");
                                /*$("#app_progress").html('');
                                 $("#app_progress").hide();
                                 $("#app_progress_index").html('');
                                 $("#app_progress_index").hide();*/
                                $("#UCmodel").html(models);
                                dom.setLoadingElement(document.getElementById('UCmodel'), false);
                                // Dieser Code ist notwendig, damit im FireFox das Ã¼bergebene Model selektiert wird

                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = '_' + get_query("UCmodel") + '_';
                                var reg = new RegExp(search);
                                if (get_query("UCmodel") == null || get_query("UCmodel") == - 1 || get_query("UCmodel") == '')
                        {
                        //------------------------------------------------------------------------------------
                        //  The code is added to resolve ticket #INC-00001996993
                        //  Checking that the element exist before performing any action to avoid javascript
                        //  error
                        //-------------------------------------------------------------------------------------
                        if (document.getElementById('UCmodel')){

                        document.getElementById('UCmodel').selectedIndex = 0;
                        }
                        } else{
                        for (pos = 0; pos < len; pos++){
                        if (reg.test('_' + arrsrch[pos] + '_')){
                        document.getElementById('UCmodel').selectedIndex = pos - 1;
                        }
                        }
                        }
                        }); < !-- end post-- >
                        }

        function getFueltype(fuel, make, model, reselect)
                {
                $("#UCmodel").attr("disabled", "");
                        var sess = get_query("sess");
                        var selectedFuel = fuel;
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}

                var UCyy = $("select#UCyy").val();
                        var UCmm = $("select#UCmm").val();
                        var UCmake = make;
                        var UCmodel = model;
                        var UCmileage = $("#UCmileage").val();
                        var UCfueltype = fuel;
                        if (UCfueltype == ''){UCfueltype = '-1'; }

                var plate = $("select#plate").val();
                        if (plate == '' || plate == null)
                {
                var plate = get_query("plate");
                }

                if (UCyy == null && UCmm == null)
                {
                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }

                var UCbodytype = $("select#UCbodytype").val();
                        if (UCbodytype == null){UCbodytype = ''; }

                var UCdoors = $("select#UCdoors").val();
                        if (UCdoors == null){UCdoors = ''; }

                var UCTrim = $("select#UCTrim").val();
                        if (UCTrim == null){UCTrim = ''; }

                var UCvtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null)
                {
                UCvtype = get_query("UCvtype");
                }
                else
                {
                UCvtype = 10;
                }

                dom.setLoadingElement(document.getElementById('UCfueltype'), true);
                        $.post("./app/plugin_uc/_src/getFueltypes.php", {UCvtype: UCvtype, sess: sess, field: 'fueltype', selectedFuel: selectedFuel, UCmake: UCmake, UCmodel: UCmodel, UCyy: UCyy, UCmm: UCmm, plate: plate, UCbodytype: UCbodytype, UCdoors: UCdoors, UCTrim: UCTrim}, function(j){

                        fueltype = domDebug.extractAjaxRequest(j);
                                var srch = fueltype;
                                var startpos = fueltype.indexOf('#');
                                var selFueltype = fueltype.substr(startpos + 1, 1);
                                fueltype = fueltype.replace(/_/g, "");
                                $("#UCfueltype").html(fueltype);
                                dom.setLoadingElement(document.getElementById('UCfueltype'), false);
                                // Dieser Code ist notwendig, damit im FireFox der Ã¼bergebene Fueltype selektiert wird

                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = '_' + get_query("UCfueltype") + '_';
                                var reg = new RegExp(search);
                                if (get_query("UCfueltype") == null || get_query("UCfueltype") == - 1 || reselect == 0)
                        {
                        //------------------------------------------------------------------------------------
                        //  The code is added to resolve ticket #INC-00001996993
                        //  Checking that the element exist before performing any action to avoid javascript
                        //  error
                        //-------------------------------------------------------------------------------------
                        if (document.getElementById('UCfueltype')){
                        document.getElementById('UCfueltype').selectedIndex = 0; }
                        }

                        else{
                        var position = 0;
                                for (pos = 0; pos < len; pos++){
                        if (reg.test('_' + arrsrch[pos] + '_')){
                        position = pos - 1; }
                        }
                        document.getElementById('UCfueltype').selectedIndex = position;
                        }

                        // Bei nur einer Treibstoffart, wird diese direkt gewÃ¤hlt.
                        if (selFueltype == 1){
                        document.getElementById('UCfueltype').selectedIndex = selFueltype; }
                        }); < !-- end post-- >
                        }

        function getBodytype(body, model, make, reselect)
                {
                $("#UCmodel").attr("disabled", "");
                        var sess = get_query("sess");
                        var selectedBody = body;
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}


                var UCyy = $("select#UCyy").val();
                        var UCmm = $("select#UCmm").val();
                        var UCmodel = model;
                        var UCmake = make;
                        var UCmileage = $("#UCmileage").val();
                        var UCbodytype = body;
                        if (UCbodytype == ''){UCbodytype = '-1'; }

                var plate = $("select#plate").val();
                        if (plate == '' || plate == null)
                {
                plate = get_query("plate");
                }

                if (UCyy == null && UCmm == null)
                {
                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }

                var UCfueltype = $("select#UCfueltype").val();
                        if (UCfueltype == null){UCfueltype = ''; }

                var UCdoors = $("select#UCdoors").val();
                        if (UCdoors == null){UCdoors = ''; }

                var UCTrim = $("select#UCTrim").val();
                        if (UCTrim == null){UCTrim = ''; }

                var UCvtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
                UCvtype = get_query("UCvtype"); }
                else{
                UCvtype = 10; }
                dom.setLoadingElement(document.getElementById('UCbodytype'), true);
                        $.post("./app/plugin_uc/_src/getBodytype.php", {UCvtype: UCvtype, sess: sess, field: 'bodytype', selectedBody: selectedBody, UCmake: UCmake, UCmodel: UCmodel, UCyy: UCyy, UCmm: UCmm, plate: plate, UCTrim: UCTrim}, function(j){
                        bodytype = domDebug.extractAjaxRequest(j);
                                //alert(bodytype);
                                var srch = bodytype;
                                var startpos = bodytype.indexOf('#');
                                var selBodytype = bodytype.substr(startpos + 1, 1);
                                bodytype = bodytype.replace(/_/g, "");
                                $("#UCbodytype").html(bodytype);
                                dom.setLoadingElement(document.getElementById('UCbodytype'), false);
                                // Dieser Code ist notwendig, damit im FireFox der Ã¼bergebene Bodytype selektiert wird

                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = '_' + get_query("UCbodytype") + '_';
                                var reg = new RegExp(search);
                                if (get_query("UCbodytype") == null || get_query("UCbodytype") == - 1 || reselect == 0){
                        //------------------------------------------------------------------------------------
                        //  The code is added to resolve ticket #INC-00001996993
                        //  Checking that the element exist before performing any action to avoid javascript
                        //  error
                        //-------------------------------------------------------------------------------------
                        if (document.getElementById('UCbodytype')){
                        document.getElementById('UCbodytype').selectedIndex = 0; }
                        }
                        else{
                        var position = 0;
                                for (pos = 0; pos < len; pos++){
                        if (reg.test('_' + arrsrch[pos] + '_')){
                        position = pos - 1; }
                        }
                        document.getElementById('UCbodytype').selectedIndex = position;
                        }

                        // Bei nur einer Aufbauart, wird diese direkt gewÃ¤hlt.
                        if (selBodytype == 1){
                        document.getElementById('UCbodytype').selectedIndex = selBodytype; }
                        }); < !-- end post-- >
                        }

        function getDoors(doors, model, make, reselect)
                {
                $("#UCmodel").attr("disabled", "");
                        var sess = get_query("sess");
                        var selectedDoors = doors;
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}

                var UCyy = $("select#UCyy").val();
                        var UCmm = $("select#UCmm").val();
                        var UCmodel = model;
                        var UCmake = make;
                        var UCmileage = $("#UCmileage").val();
                        var UCdoors = doors;
                        if (UCdoors == ''){UCdoors = '-1'; }

                var plate = $("select#plate").val();
                        if (plate == '' || plate == null)
                {
                var plate = get_query("plate");
                }

                if (UCyy == null && UCmm == null)
                {
                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }

                var UCbodytype = $("select#UCbodytype").val();
                        if (UCbodytype == null){UCbodytype = ''; }

                var UCfueltype = $("select#UCfueltype").val();
                        if (UCfueltype == null){UCfueltype = ''; }

                var UCTrim = $("select#UCTrim").val();
                        if (UCTrim == null){UCTrim = ''; }

                var UCvtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
                UCvtype = get_query("UCvtype"); }
                else{
                UCvtype = 10; }

                dom.setLoadingElement(document.getElementById('UCdoors'), true);
                        $.post("./app/plugin_uc/_src/getDoors.php", {UCvtype: UCvtype, sess: sess, field: 'doors', selectedDoors: selectedDoors, UCmake: UCmake, UCmodel: UCmodel, UCyy: UCyy, UCmm: UCmm, plate: plate, UCbodytype: UCbodytype, UCTrim: UCTrim}, function(j){
                        doors = domDebug.extractAjaxRequest(j);
                                //alert(doors);
                                var srch = doors;
                                var startpos = doors.indexOf('#');
                                var selDoors = doors.substr(startpos + 1, 1);
                                doors = doors.replace(/_/g, "");
                                $("#UCdoors").html(doors);
                                dom.setLoadingElement(document.getElementById('UCdoors'), false);
                                // Dieser Code ist notwendig, damit im FireFox der Ã¼bergebene Doors selektiert wird

                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = '_' + get_query("UCdoors") + '_';
                                var reg = new RegExp(search);
                                if (get_query("UCdoors") == null || get_query("UCdoors") == - 1 || reselect == 0){
                        //------------------------------------------------------------------------------------
                        //  The code is added to resolve ticket #INC-00001996993
                        //  Checking that the element exist before performing any action to avoid javascript
                        //  error
                        //-------------------------------------------------------------------------------------

                        if (document.getElementById('UCdoors'))
                        {
                        document.getElementById('UCdoors').selectedIndex = 0; }
                        }
                        else{
                        var position = 0;
                                for (pos = 0; pos < len; pos++){
                        if (reg.test('_' + arrsrch[pos] + '_')){
                        position = pos - 1; }
                        }
                        document.getElementById('UCdoors').selectedIndex = position;
                        }

                        // Bei nur einer TÃ¼rvariante, wird diese direkt gewÃ¤hlt.
                        if (selDoors == 1){
                        document.getElementById('UCdoors').selectedIndex = selDoors; }
                        }); < !-- end post-- >
                        }

        function getTrim(trim, model, make, reselect)
                {
                $("#UCmodel").attr("disabled", "");
                        var sess = get_query("sess");
                        var selectedTrim = trim;
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}

                var UCyy = $("select#UCyy").val();
                        var UCmm = $("select#UCmm").val();
                        var UCmodel = model;
                        var UCmake = make;
                        var UCmileage = $("#UCmileage").val();
                        var UCfueltype = $("select#UCfueltype").val();
                        if (UCfueltype == null){UCfueltype = ''; }

                var plate = $("select#plate").val();
                        if (plate == '' || plate == null)
                {
                var plate = get_query("plate");
                }

                if (UCyy == null && UCmm == null)
                {
                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }

                var UCbodytype = $("select#UCbodytype").val();
                        if (UCbodytype == null){UCbodytype = ''; }

                var UCdoors = $("select#UCdoors").val();
                        if (UCdoors == null){UCdoors = ''; }

                var UCTrim = $("select#UCTrim").val();
                        if (UCTrim == null){UCTrim = ''; }

                var UCvtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
                UCvtype = get_query("UCvtype"); }
                else{
                UCvtype = 10; }

                dom.setLoadingElement(document.getElementById('UCTrim'), true);
                        $.post("./app/plugin_uc/_src/getTrim.php", {UCvtype: UCvtype, sess: sess, field: 'trim', selectedTrim: selectedTrim, UCmake: UCmake, UCmodel: UCmodel, UCyy: UCyy, UCmm: UCmm, plate: plate, UCbodytype: UCbodytype, UCfueltype: UCfueltype, UCdoors: UCdoors}, function(j){
                        trim = domDebug.extractAjaxRequest(j);
                                //alert(trim);
                                var srch = trim;
                                var startpos = trim.indexOf('#');
                                var selTrim = trim.substr(startpos + 1, 1);
                                trim = trim.replace(/_/g, "");
                                $("#UCTrim").html(trim);
                                dom.setLoadingElement(document.getElementById('UCTrim'), false);
                                // Dieser Code ist notwendig, damit im FireFox der Ã¼bergebene trim selektiert wird
                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = '_' + get_query("UCTrim") + '_';
                                var reg = new RegExp(search);
                                //do this just if the element exists!!
                                //sen 20090209
                                if (document.getElementById('UCTrim'))
                        {
                        if (get_query("UCTrim") == null || get_query("UCTrim") == - 1 || reselect == 0)
                        {
                        //------------------------------------------------------------------------------------
                        //  The code is added to resolve ticket #INC-00001996993
                        //  Checking that the element exist before performing any action to avoid javascript
                        //  error
                        //-------------------------------------------------------------------------------------

                        if (document.getElementById('UCTrim')){
                        document.getElementById('UCTrim').selectedIndex = 0;
                        }
                        }
                        else
                        {
                        var position = 0;
                                for (pos = 0; pos < len; pos++)
                        {
                        if (reg.test('_' + arrsrch[pos] + '_'))
                        {
                        position = pos - 1;
                        }
                        }

                        document.getElementById('UCTrim').selectedIndex = position;
                        }
                        }
                        // Bei nur einer Trimvariante, wird diese direkt gewÃ¤hlt.
                        /*
                         if (selTrim == 1){
                         document.getElementById('UCTrim').selectedIndex = selTrim;
                         }
                         */
                        }); < !-- end post-- >
                        }




        function getTypes(vtype, sess, make, yy, mm, model, fuel, body, doors, trim, plate, natcode, hsn, tsn, selkfz, UCnumberplate, selschild, UCregion, new_natcode, new_mm, new_yy)
                {

                $("#app_bubble_wrapper").removeAttr("style");
                        $("#app_bubble_wrapper").show();
                        $("#UCmodel").attr("disabled", "");
                        var sess = get_query("sess");
                        var h = get_query("h");
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}
                var d = sess;
                        if (d.length > 2)
                        {
                        var coumkt = d.substring(0, 2);
                                }
                var UCyy = yy;
                        var UCmm = mm;
                        var UCmake = make;
                        var UCmileage = $("#UCmileage").val();
                        var new_natcode = new_natcode;
                        if (new_natcode == null){new_natcode = ''; }
                var new_mm = new_mm;
                        if (new_mm == null){new_mm = ''; }
                var new_yy = new_yy;
                        if (new_yy == null){new_yy = ''; }


                var UCfueltype = fuel;
                        if (UCfueltype == null){UCfueltype = ''; }
                if (selkfz == null){selkfz = 'n'; }

                // Nur fÃ¼r UK
                if (plate != '' && plate != null && plate != 'undefined' && coumkt == 'UK')
                {
                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }

                var UCbodytype = body;
                        if (UCbodytype == null){UCbodytype = ''; }

                var UCdoors = doors;
                        if (UCdoors == null){UCdoors = ''; }

                var UCTrim = trim;
                        if (UCTrim == null){UCTrim = ''; }

                var UCmodel = model;
                        var UCvtype = vtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
                UCvtype = get_query("UCvtype"); }
                else{
                UCvtype = 10; }

                //showProgress();


                dom.setLoadingElement(document.getElementById('pModels'), true);
                        $.post("./app/plugin_uc/_src/getTypes.php", {h: h, l: l, UCvtype: UCvtype, sess: sess, UCmake: UCmake, UCyy: UCyy, UCmm:  UCmm, UCmodel: UCmodel, yy_s: yy_s, mm_s: mm_s, UCfueltype: UCfueltype, UCbodytype: UCbodytype, UCdoors: UCdoors, UCTrim: UCTrim, natcode: natcode, hsn: hsn, tsn: tsn, selkfz: selkfz, plate: plate, UCnumberplate:UCnumberplate, selschild:selschild, UCregion:UCregion, new_natcode:new_natcode, new_mm:new_mm, new_yy:new_yy}, function(j){
                        types = domDebug.extractAjaxRequest(j);
                                $("#pModels").html('');
                                $("#app_table").show();
                                $("#pModels").html(types);
                                dom.setLoadingElement(document.getElementById('pModels'), false);
                                /*$("#app_progress").html('');
                                 $("#app_progress").hide();
                                 $("#app_progress_index").html('');
                                 $("#app_progress_index").hide();*/

                                // Used for paging on Types
                                $('#typepaging').pager('div');
                                //autoscroll1();
                                var x, y;
                                if (self.pageYOffset) // all except Explorer
                        {
                        x = self.pageXOffset;
                                y = self.pageYOffset;
                        }
                        else if (document.documentElement && document.documentElement.scrollTop)
                                // Explorer 6 Strict
                                {
                                x = document.documentElement.scrollLeft;
                                        y = document.documentElement.scrollTop;
                                }
                        else if (document.body) // all other Explorers
                        {
                        x = document.body.scrollLeft;
                                y = document.body.scrollTop;
                        }
                        //if(y!=eval(zahl-1)){
                        //autoscroll2(zahl);
                        //}

                        }); < !-- end post-- >
                        self.focus();
                        }


        function autoscroll1(){
        var banner = document.getElementById('SuperBanner');
                if (banner){
        var hoehe = banner.offsetHeight;
                var zaehler = 60 + parseInt(hoehe) + 10 + 10;
        } else{
        var zaehler = 60;
        }
        zahl = zaehler;
                }

        function autoscroll2(za){
        var tot_za = za;
                for (var i = 0; i < tot_za; i++){
        eval("window.scrollTo(0," + i + ")");
        }
        }

        function getPlate(plate)
                {
                var sess = get_query("sess");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                dom.setLoadingElement(document.getElementById('plate'), true);
                        $.post("./app/plugin_uc/_src/plate_newcws.php", {sess: sess, plate: plate}, function(j){
                        plates = domDebug.extractAjaxRequest(j);
//      alert(plates);
//      document.write(plates);
                                $("#plate").html(plates);
                                dom.setLoadingElement(document.getElementById('plate'), false);
                                // Dieser Code ist notwendig, damit im FireFox die Ã¼bergebene Plate selektiert wird
                                var srch = plates;
                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = get_query("plate");
                                //alert(search);
                                var reg = new RegExp(search);
                                if (get_query("plate") == null || get_query("plate") == - 1)
                        {
                        if (document.getElementById('plate'))
                        {
                        document.getElementById('plate').selectedIndex = 0;
                        }
                        } else{
                        for (pos = 0; pos < len; pos++){
                        if (reg.test(arrsrch[pos])){
                        document.getElementById('plate').selectedIndex = pos - 1;
                        }
                        }
                        }


                        }); < !-- end post-- >
                        }

        function getMake()
                {

                var server_red = 'cws-live';
                        if ($("#UCyy").val() == "redirectde" && $("#UCmm").val() != '-1' && $("#UCyy").val() != '-1')
                        {
                        if (server_red == "cws-dev")
                        {
                        document.getElementById('UCyy').value = - 1;
                                window.open("http://cws-dev-new2.freienbach.eurotax.ch/various/help.php#5", "_blank"); exit;
                        }
                        else if (server_red == "cws-stage")
                        {
                        document.getElementById('UCyy').value = - 1;
                                window.open("http://cws-stage-new2.eurotaxglass.com/various/help.php#5", "_blank"); exit;
                        }
                        else
                        {
                        document.getElementById('UCyy').value = - 1;
                                window.open("http://http://www.schwacke.de/various/help.php#5", "_blank"); exit;
                        }

                        }
                $("#UCmake").attr("disabled", "");
                        //alert('get Makes');
                        var sess = get_query("sess");
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}
                var UCmake = get_query("UCmake");
                        var UCvtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
                UCvtype = get_query("UCvtype"); }
                else{
                UCvtype = 10; }

                var plate = $("select#plate").val();
                        if (plate == null)
                {

                var plate = get_query("plate");
                }
                // alert("/app/plugin_uc/_src/js/etg.js.php");

                var UCyy = $("select#UCyy").val();
                        var UCmm = $("select#UCmm").val();
                        if (UCyy == null && UCmm == null)
                {

                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }
                //document.write('plate: ' + plate);
                //showProgress();
                dom.setLoadingElement(document.getElementById('UCmake'), true);
                        $.post("./app/plugin_uc/_src/getMake.php", {UCvtype: UCvtype, sess: sess, l: l, UCmake: UCmake, UCyy: UCyy, UCmm: UCmm, plate: plate}, function(j){
                        make = domDebug.extractAjaxRequest(j);
                                //alert(make);
                                var srch = make;
                                make = make.replace(/_/g, "");
                                /*$("#app_progress").html('');
                                 $("#app_progress").hide();
                                 $("#app_progress_index").html('');
                                 $("#app_progress_index").hide();*/
                                $("#UCmake").html(make);
                                dom.setLoadingElement(document.getElementById('UCmake'), false);
                                // Dieser Code ist notwendig, damit im FireFox die Ã¼bergebene Marke selektiert wird

                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = '_' + get_query("UCmake") + '_';
                                var reg = new RegExp(search);
                                if (get_query("UCmake") == null || get_query("UCmake") == - 1 || get_query("UCmake") == ''){
                        //------------------------------------------------------------------------------------
                        //  The code is added to resolve ticket #INC-00001996993
                        //  Checking that the element exist before performing any action to avoid javascript
                        //  error
                        //-------------------------------------------------------------------------------------

                        if (document.getElementById('UCmake')){
                        document.getElementById('UCmake').selectedIndex = 0;
                        }

                        } else{
                        for (pos = 0; pos < len; pos++){
                        if (reg.test('_' + arrsrch[pos] + '_')){
                        document.getElementById('UCmake').selectedIndex = pos - 1;
                        }
                        }
                        }
                        }); < !-- end post-- >
                        }

        function getCounter(vtype, sess, make, yy, mm, model, fuel, body, doors, trim, selkfz, selschild, plate)
                {
                $("#UCmodel").attr("disabled", "");
                        var sess = get_query("sess");
                        var h = get_query("h");
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}
                var UCyy = yy;
                        var UCmm = mm;
                        var UCmake = make;
                        var plate = plate;
                        var UCmileage = $("#UCmileage").val();
                        var UCfueltype = fuel;
                        if (UCfueltype == null){UCfueltype = ''; }
                if (selkfz == null){selkfz = 'n'; }

                var plate = $("select#plate").val();
                        if (plate == '' || plate == null)
                {
                var plate = get_query("plate");
                }

                // Nur fÃ¼r UK
                if (plate != '' && plate != null && plate != 'undefined')
                {
                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }

                var UCbodytype = body;
                        if (UCbodytype == null){UCbodytype = ''; }

                var UCdoors = doors;
                        if (UCdoors == null){UCdoors = ''; }

                var UCTrim = trim;
                        if (UCTrim == null){UCTrim = ''; }

                var UCmodel = model;
                        var UCvtype = vtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
                UCvtype = get_query("UCvtype");
                } else{
                UCvtype = 10;
                }

                //showProgress();
                dom.setLoadingElement(document.getElementById('counter_content'), true);
                        $.post("./app/plugin_uc/_src/getCounter.php", {h: h, l: l, UCvtype: UCvtype, sess: sess, UCmake: UCmake, UCyy: UCyy, UCmm: UCmm, UCmodel: UCmodel, yy_s: yy_s, mm_s: mm_s, UCfueltype: UCfueltype, UCbodytype: UCbodytype, UCdoors: UCdoors, UCTrim: UCTrim, selkfz: selkfz, selschild: selschild, plate: plate}, function(j){

                        counter = domDebug.extractAjaxRequest(j); ;
                                //alert(counter);
                                //document.write(counter);
                                $("#counter_content").html(counter);
                                dom.setLoadingElement(document.getElementById('counter_content'), false);
                                /*$("#app_progress").html('');
                                 $("#app_progress").hide();
                                 $("#app_progress_index").html('');
                                 $("#app_progress_index").hide();*/

                        }); < !-- end post-- >
                        self.focus();
                        }




        function showProgress()
                {
                // Anzeige des Progress-Gif wÃ¤hrend Datenbank-Zugriff
                frm = document.getElementById('app_progress');
                        if (!frm){
                frm = document.getElementById('app_progress_option'); }

                var str = window.location;
                        var reg = new RegExp("options.php");
                        if (reg.test(str)){
                var off_set = 0; }
                else{
                var off_set = 10; }

                // Position des Progress-Indikators
                var pos = getPosition(document.getElementById('col1'));
                        xpos = pos.x + 335 + off_set + "px";
                        ypos = pos.y + 8 + "px";
                        frm.style.position = 'absolute';
                        frm.style.left = xpos;
                        frm.style.top = ypos;
                        $("#app_progress").show();
                        $("#app_progress_index").show('<div id="ident_progress_ct"><iframe id="ident_iframe_hack" src="" frameborder="0"></iframe><img id="ident_progress" src="/app/plugin_uc/img/all/progressindicator.gif" width="32" height="32" alt="" /></div>');
                        $("#app_progress_option").show();
                        $("#app_progress").html();
                        $("#app_progress_index").html('<div id="ident_progress_index_ct"><iframe id="ident_iframe_index_hack" src="" frameborder="0"></iframe><img id="ident_progress_index" src="/app/plugin_uc/img/all/progressindicator.gif" width="32" height="32" alt="" /></div>');
                        $("#app_progress_option").html('<p style="width:32px;height:32px;" align="center"><img style="vertical-align:middle;" src="/app/plugin_uc/img/all/progressindicator.gif"/></p>');
                        }

        function getPosition(obj) {
        var pos = { x:0, y:0 };
                do {
                pos.x += obj.offsetLeft;
                        pos.y += obj.offsetTop; }
        while (obj = obj.offsetParent);
                return pos;
                }

//Added by wipro ADM for ticket SR-3035014 - START
        function getValue(id)
        {
        var text = encodeURIComponent(document.getElementById(id).options[document.getElementById(id).selectedIndex].text);
                //alert(id+ ': '+text);
                document.getElementById(id + "_text").value = text;
                return text;
        }

        function getContentAdScript(make, model, ad_script)
        {
        ad_script1 = ad_script.replace(/prf\[make\]\=/g, "prf[make]=" + make);
                ad_script1 = ad_script1.replace(/prf\[model\]\=/g, "prf[model]=" + model);
                document.getElementById("ContentAd1_300x250").innerHTML = ad_script1;
                //alert(document.getElementById("ContentAd1_300x250").innerHTML);
        }

        function getYear()
                {



                $("#UCyy").attr("disabled", "");
                        //alert('get Makes');
                        var sess = get_query("sess");
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}
                var UCmake = get_query("UCmake");
                        var UCvtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
                UCvtype = get_query("UCvtype"); }
                else{
                UCvtype = 10; }

                var plate = $("select#plate").val();
                        if (plate == null)
                {

                var plate = get_query("plate");
                }
                // alert("/app/plugin_uc/_src/js/etg.js.php");

                var UCyy = $("select#UCyy").val();
                        var UCmm = $("select#UCmm").val();
                        if (UCyy == null && UCmm == null)
                {

                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }
                //document.write('plate: ' + plate);
                //showProgress();
                dom.setLoadingElement(document.getElementById('UCyy'), true);
                        $.post("./app/plugin_uc/_src/getYear.php", {UCvtype: UCvtype, sess: sess, l: l, UCmake: UCmake, UCyy: UCyy, UCmm: UCmm, plate: plate}, function(j){
                        year = domDebug.extractAjaxRequest(j);
                                var srch = year;
                                year = year.replace(/_/g, "");
                                /*$("#app_progress").html('');
                                 $("#app_progress").hide();
                                 $("#app_progress_index").html('');
                                 $("#app_progress_index").hide();*/
                                $("#UCyy").html(year);
                                dom.setLoadingElement(document.getElementById('UCyy'), false);
                                // Dieser Code ist notwendig, damit im FireFox die Ã¼bergebene Marke selektiert wird

                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = '_' + get_query("UCyy") + '_';
                                var reg = new RegExp(search);
                                if (get_query("UCyy") == null || get_query("UCyy") == - 1 || get_query("UCyy") == ''){
                        //------------------------------------------------------------------------------------
                        //  The code is added to resolve ticket #INC-00001996993
                        //  Checking that the element exist before performing any action to avoid javascript
                        //  error
                        //-------------------------------------------------------------------------------------

                        if (document.getElementById('UCyy')){
                        document.getElementById('UCyy').selectedIndex = 0;
                        }

                        } else{
                        for (pos = 0; pos < len; pos++){
                        if (reg.test('_' + arrsrch[pos] + '_')){
                        document.getElementById('UCyy').selectedIndex = pos - 1;
                        }
                        }
                        }
                        }); < !-- end post-- >
                        }


        function getMonth_cz()
                {



                $("#UCmm").attr("disabled", "");
                        var sess = get_query("sess");
                        var l = get_query("l");
                        if (sess == null){sess = 'CHucn0ebe9bb5391194eaf28ed75662ad61482862286'}
                if (l == null){l = 'de'}
                var UCmake = get_query("UCmake");
                        var UCvtype;
                        if (get_query("UCvtype") != '' && get_query("UCvtype") != null){
                UCvtype = get_query("UCvtype"); }
                else{
                UCvtype = 10; }

                var plate = $("select#plate").val();
                        if (plate == null)
                {

                var plate = get_query("plate");
                }
                // alert("/app/plugin_uc/_src/js/etg.js.php");

                var UCyy = $("select#UCyy").val();
                        var UCmm = $("select#UCmm").val();
                        if (UCyy == null && UCmm == null)
                {

                var dat = plate.split(';');
                        UCyy = dat[0].substr(0, 4);
                        UCmm = dat[0].substr(4, 2);
                        var yy_s = dat[0].substr(0, 4);
                        var mm_s = dat[0].substr(4, 2);
                }
                //document.write('plate: ' + plate);
                //showProgress();
                dom.setLoadingElement(document.getElementById('UCmm'), true);
                        $.post("./app/plugin_uc/_src/getMonth_cz.php", {UCvtype: UCvtype, sess: sess, l: l, UCmake: UCmake, UCyy: UCyy, UCmm: UCmm, plate: plate}, function(j){
                        year = domDebug.extractAjaxRequest(j);
                                var srch = year;
                                year = year.replace(/_/g, "");
                                /*$("#app_progress").html('');
                                 $("#app_progress").hide();
                                 $("#app_progress_index").html('');
                                 $("#app_progress_index").hide();*/
                                $("#UCmm").html(year);
                                dom.setLoadingElement(document.getElementById('UCmm'), false);
                                // Dieser Code ist notwendig, damit im FireFox die Ã¼bergebene Marke selektiert wird

                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = '_' + get_query("UCmm") + '_';
                                var reg = new RegExp(search);
                                if (get_query("UCmm") == null || get_query("UCmm") == - 1 || get_query("UCmm") == ''){
                        //------------------------------------------------------------------------------------
                        //  The code is added to resolve ticket #INC-00001996993
                        //  Checking that the element exist before performing any action to avoid javascript
                        //  error
                        //-------------------------------------------------------------------------------------

                        if (document.getElementById('UCmm')){
                        document.getElementById('UCmm').selectedIndex = 0;
                        }

                        } else{
                        for (pos = 0; pos < len; pos++){
                        if (reg.test('_' + arrsrch[pos] + '_')){
                        document.getElementById('UCmm').selectedIndex = pos - 1;
                        }
                        }
                        }
                        }); < !-- end post-- >
                        }


        function load_month_form()
                {


                var UCyy = document.getElementById('UCyy').value;
                        dom.setLoadingElement(document.getElementById('UCmm'), true);
                        $.post("./app/plugin_uc/_src/getMonth_cz.php", {UCyy: UCyy}, function(j){
                        year = domDebug.extractAjaxRequest(j);
                                var srch = year;
                                year = year.replace(/_/g, "");
                                /*$("#app_progress").html('');
                                 $("#app_progress").hide();
                                 $("#app_progress_index").html('');
                                 $("#app_progress_index").hide();*/
                                $("#UCmm").html(year);
                                dom.setLoadingElement(document.getElementById('UCmm'), false);
                                // Dieser Code ist notwendig, damit im FireFox die Ã¼bergebene Marke selektiert wird

                                srch = srch.replace(/<option/g, "");
                                srch = srch.replace(/<\/option>/g, "");
                                var arrsrch = srch.split('valu');
                                var len = arrsrch.length;
                                var search = '_' + get_query("UCmm") + '_';
                                var reg = new RegExp(search);
                                dom.setLoadingElement(document.getElementById('UCmm'), false);
                                if (get_query("UCmm") == null || get_query("UCmm") == - 1 || get_query("UCmm") == ''){
                        //------------------------------------------------------------------------------------
                        //  The code is added to resolve ticket #INC-00001996993
                        //  Checking that the element exist before performing any action to avoid javascript
                        //  error
                        //-------------------------------------------------------------------------------------

                        if (document.getElementById('UCmm')){
                        document.getElementById('UCmm').selectedIndex = 0;
                        }

                        } else{
                        for (pos = 0; pos < len; pos++){
                        if (reg.test('_' + arrsrch[pos] + '_')){
                        document.getElementById('UCmm').selectedIndex = pos - 1;
                        }
                        }
                        }
                        }); < !-- end post-- >
                        }


        $.fn.pager = function(clas, options) {

        var settings = {
        navId: 'nav',
                navId2: 'nav2',
                navClass: 'nav',
                navAttach: 'both',
                highlightClass: 'highlight',
                prevText: './img/arrow_uc_left.gif',
                nextText: './img/arrow_uc_right.gif',
                linkText: null,
                linkWrap: null,
                height: null
        }
        if (options) $.extend(settings, options);
                return this.each(function () {

                var me = $(this);
                        var size;
                        var i = 0;
                        var navid = '#' + settings.navId;
                        var navid2 = '#' + settings.navId2;
                        function init () {
                        size = $(clas, me).not(navid).size();
                                if (settings.height == null) {
                        settings.height = getHighest();
                        }
                        if (size > 1) {
                        makeNav();
                                show();
                                highlight();
                        }
                        //sizePanel();
                        if (settings.linkWrap != null) {
                        linkWrap();
                        }
                        }
                function makeNav ()
                {
                var str = '<br /><div id="' + settings.navId + '" class="' + settings.navClass + '">Seiten:&nbsp;';
                        str += '<a class="page arrow" href="#" rel="prev">&lt;</a>';
                        for (var i = 0; i < size; i++) {
                var j = i + 1;
                        str += '<a class="page" href="#" rel="' + j + '">';
                        str += (settings.linkText == null) ? j : settings.linkText[j - 1];
                        str += '</a>';
                }

                str += '<a class="page arrow" href="#" rel="next">&gt;</a>';
                        str += '</div>';
                        var str2 = '<br /><div id="' + settings.navId2 + '" class="' + settings.navClass + '">Seiten:&nbsp;';
                        str2 += '<a class="page arrow" href="#" rel="prev">&lt;</a>';
                        for (var i = 0; i < size; i++) {
                var j = i + 1;
                        str2 += '<a href="#" class="page" rel="' + j + '">';
                        str2 += (settings.linkText == null) ? j : settings.linkText[j - 1];
                        str2 += '</a>';
                }

                str2 += '<a class="page arrow" href="#" rel="next">&gt;</a>';
                        str2 += '</div>';
                        switch (settings.navAttach) {
                case 'before':
                        $(me).before(str);
                        break;
                        case 'after':
                        $(me).after(str);
                        break;
                        case 'prepend':
                        $(me).prepend(str);
                        break;
                        case 'both':
                        $(me).prepend(str);
                        $(me).append(str2);
                        break;
                        default:
                        $(me).append(str);
                        break;
                }
                }
                function show () {
                $(me).find(clas).not(navid).hide();
                        var show = $(me).find(clas).not(navid).get(i);
                        $(show).show();
                        $(navid2).show();
                }

                function highlight (){
                var as = null;
                        var toset = [navid, navid2];
                        for (y in toset)
                {
                as = $(me).find(toset[y]).find('a');
                        for (x = 1; x < as.length; x++)
                {
                //if(as.get(x).rel == 'prev' || as.get(x).rel == 'next') continue;

                as.get(x).className = 'page';
                        if (x == i + 1)
                {
                as.get(x).className += ' active';
                }
                else
                {
                as.get(x).className = 'page';
                }
                }
                }
                }

                function sizePanel () {
                if ($.browser.msie) {
                $(me).find(clas).not(navid).css({
                height: settings.height
                });
                        $(navid).css("height", "20px");
                        $(navid2).css("height", "20px");
                } else {
                $(me).find(clas).not(navid).css({
                minHeight: settings.height
                });
                        $(navid).css("height", "20px");
                        $(navid2).css("height", "20px");
                }
                }
                function getHighest () {
                var highest = 0;
                        $(me).find(clas).not(navid).each(function () {

                if (this.offsetHeight > highest) {
                highest = this.offsetHeight;
                }
                });
                        highest = highest + "px";
                        return highest;
                }
                function getNavHeight () {
                var nav = $(navid).get(0);
                        return nav.offsetHeight;
                }
                function linkWrap () {
                $(me).find(navid).find("a").wrap(settings.linkWrap);
                }
                init();
                        $(this).find(navid).find("a").click(function () {

                if ($(this).attr('rel') == 'next') {
                if (i + 1 < size) {
                i = i + 1;
                }
                } else if ($(this).attr('rel') == 'prev') {
                if (i > 0) {
                i = i - 1;
                }
                } else {
                var j = $(this).attr('rel');
                        i = j - 1;
                }
                show();
                        highlight();
                        return false;
                });
                        $(this).find(navid2).find("a").click(function () {

                if ($(this).attr('rel') == 'next') {
                if (i + 1 < size) {
                i = i + 1;
                }
                } else if ($(this).attr('rel') == 'prev') {
                if (i > 0) {
                i = i - 1;
                }
                } else {
                var j = $(this).attr('rel');
                        i = j - 1;
                }
                show();
                        highlight();
                        return false;
                });
                });
                }