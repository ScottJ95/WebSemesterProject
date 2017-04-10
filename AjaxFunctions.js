/* AjaxFunctions.js - Handy Ajax Setup Stuff
 *
 * Scott Jeffery
 * Original by: D Provine, 13 Feb 2013
 */

"use strict";

/* create an Ajax handle and return it */
function createXMLHttpRequest()
{
    var xmlreq;

    try { // for Firefox, IE7, Opera
        xmlreq = new XMLHttpRequest();
    }
    catch (e) {
        try { // for IE6
            xmlreq = new ActiveXObject('MSXML2.XMLHTTP.5.0');
        }
        catch (e) {
            xmlreq = null;
        }
    }
    return xmlreq;
}

/* fetch some data given a URL
 * (Modified from an example in _Learning JavaScript_ p161)
 */
function ajaxFetch(dataURL, saver)
{
    var xhr = createXMLHttpRequest();

    xhr.onreadystatechange =
        function () 
        {
            if (xhr.readyState === 4 && xhr.status === 200) {
                saver(xhr.responseText);
            }
        };

    // "false" here means "block until finished"
    xhr.open("GET", dataURL, false);
    xhr.send(null);
}
