// report.js
//
// Version: 0.5
// Author: Bill Farmer

// Created by Bill Farmer
// Licence MIT
// Copyright (C) 2018 Bill Farmer

jQuery(document).ready(function($) {

    // Style button
    $("#download-report").button();

    // Create cookie, if not present
    if (readCookie("ClientEmail") != cookieValue)
        createCookie("ClientEmail", cookieValue, 30);

    // Create cookie
    function createCookie(name, value, days)
    {
	if (days)
        {
	    let date = new Date();
	    date.setTime(date.getTime() + (days*24*60*60*1000));
	    let expires = "; expires=" + date.toGMTString();
	}

	else
            let expires = "";

	document.cookie = name + "=" + value + expires + "; path=/";
    }

    // Read cookie
    function readCookie(name)
    {
	let nameEQ = name + "=";
	let ca = document.cookie.split(';');
        
	for(let i = 0; i < ca.length; i++)
        {
	    let c = ca[i];
	    while (c.charAt(0) == ' ')
                c = c.substring(1, c.length);
	    if (c.indexOf(nameEQ) == 0)
                return c.substring(nameEQ.length, c.length);
	}
	return null;
    }

    // Erase cookie
    function eraseCookie(name)
    {
	createCookie(name, "", -1);
    }
});
