// report.js
//
// Version: 0.5
// Author: Bill Farmer

// Created by Bill Farmer
// Licence MIT
// Copyright (C) 2018 Bill Farmer

jQuery(document).ready(function($) {

    // jsPDF
    const jsPDF = window.jspdf.jsPDF;

    // Page parameters
    const A = getURLParameter('A');
    const B = getURLParameter('B');
    const C = getURLParameter('C');
    const D = getURLParameter('D');
    const E = getURLParameter('E');
    const F = getURLParameter('F');
    const S = getURLParameter('S');

    let forename = getURLParameter('forename');
    let lastname = getURLParameter('lastname');

    if (forename == undefined)
        forename = "Cat";
    if (lastname == undefined)
        lastname = "LeBlanc";

    forename = forename.replace('+', ' ');
    lastname = lastname.replace('+', ' ');
    
    let name = forename + " " + lastname;

    let pages = data.pages;
    let answers = data.answers;
    let penult = data.penult;
    let last = data.last;

    // Dimensions in points
    let pageWidth = 595;
    let pageHeight = 842;
    let margin = 36;
    let textWidth = pageWidth - (margin * 2);

    // Style buttons
    $("#update, #report").button();

    // Add name
    $("#name").html(name);

    // Create document
    let doc = jsPDF({unit: 'pt',
                     compress: true});

    // Print front page and explanatory letter
    let pageno = 1;
    let images = 0;
    for (let page of pages)
    {
        pageno = page.pageno;
        if (pageno != 1)
            doc.addPage();

        for (let image of page.images)
            addImageObject(image, doc, pageno, update);

        let y = margin;
        for (let text of page.text)
            y = addTextObject(text, doc, y)
    }

    // Create report
    doc.addPage();
    let y = margin;
    pageno++;

    // B type
    if (B)
        y = addAnswer(answers['B'], B, y);

    // C type
    if (C)
        y = addAnswer(answers['C'], C, y);

    doc.addPage();
    y = margin;
    pageno++;

    // D type
        y = addAnswer(answers['D'], D, y);

    // E type
    if (E)
        y = addAnswer(answers['E'], E, y);

    doc.addPage();
    y = margin;
    pageno++;

    // F type
    if (F)
        y = addAnswer(answers['F'], F, y);

    // Penult page
    doc.addPage();
    y = margin;
    pageno++;

    // Images
    for (let image of penult.images)
        addImageObject(image, doc, pageno, update);

    // Text
    for (let text of penult.text)
        y = addTextObject(text, doc, y);

    // Last page
    doc.addPage();
    y = margin;
    pageno++;

    // Images
    for (let image of last.images)
        addImageObject(image, doc, pageno, update);

    // Text
    for (let text of last.text)
        y = addTextObject(text, doc, y);

    $("#update").click(update);

    $('#report').click(function() {
        doc.save(forename + '_' + lastname + '_Entrepreneurial_Design.pdf');
    });

    function update() {
        let string = doc.output('bloburi');
	$('#preview').attr('src', string);
    }

    /**
     * Gets URL parameter value.
     * @name  getURLParameter
     * @param param  Parameter to return
     * @returns Parameter value
     */
    function getURLParameter(param)
    {
        let pageURL = window.location.search.substring(1);
        let URLParameters = pageURL.split('&');
        for (let parameter of URLParameters)
        {
            let parameterName = parameter.split('=');
            if (parameterName[0] == param)
                return parameterName[1];
        }
    }

    function addAnswer(answer, value, y)
    {
        let desc = answer.desc;
        let type = answer[value].type;
        let text = answer[value].text;
        let image = answer[value].image
        addImage(image, 'png', doc, pageno, margin, y, textWidth);
        y += 92;
        y = addText(desc, doc, margin, y, textWidth) + doc.getLineHeight();
        doc.setFont('helvetica', 'bold');
        y = addText(type, doc, margin, y, textWidth) + doc.getLineHeight();
        doc.setFont('helvetica', 'normal');
        return addText(text, doc, margin, y, textWidth);
    }

    function addTextObject(text, doc, y)
    {
        if (text.size)
            doc.setFontSize(text.size);
        if (text.type)
            doc.setFont('helvetica', text.type);
        if (text.color)
        {
            if (Array.isArray(text.color))
                doc.setTextColor(text.color[0], text.color[1], text.color[2]);
            else
                doc.setTextColor(text.color);
        }
        y = text.y? text.y: y;
        width = text.width? text.width: textWidth;
        let string = text.text;
        if (string.match(/~[a-z]+~/))
            string = string.replace(/~forename~/g, forename)
            .replace(/~lastname~/g, lastname);
        return addText(string, doc, margin, y, width, text.link);
    }

    function addImageObject(image, doc, pageno, func)
    {
        let y = image.y;
        y = y? (y < 0)? -pageHeight + margin: y: margin;
        let x = image.x;
        x = x? (x < 0)? -pageWidth + margin: x: margin;
        let width = image.width;
        width = width? width: textWidth;
        addImage(image.src, image.type, doc, pageno, x, y,
                 width, image.height, image.link, func);
    }

    /**
     * Adds text to document.
     * @name  addText
     * @param text   Text to add
     * @param doc    jsPDF document
     * @param x      X location on page
     * @param y      Y location on page
     * @param width  Text width on page
     * @param link   Link to add to text
     * @returns Y location of bottom of text
     */
    function addText(text, doc, x, y, width, link) {
        let textLines = doc.splitTextToSize(text, width);
        if (link)
        {
            let options = {url: link};
            doc.textWithLink(text, x, y, options);
        }
        else
            doc.text(textLines, x, y);
        return y + (textLines.length * doc.getLineHeight());
    }

    /**
     * Adds image to document.
     * @name  addImage
     * @param src    Path to image to add
     * @param type   Type of image, 'png' or 'jpeg'
     * @param doc    jsPDF document
     * @param page   Page number to place image
     * @param x      X location on page
     * @param y      Y location on page
     * @param width  Image width on page
     * @param height Image height on  page
     * @param link   Link to add to image
     * @param func   Function to call after image added
     * @description
     * If the x parameter is negative, used as right edge of image.
     * If the y parameter is negative, used as bottom edge of image.
     * If the height is null or 0, height is calculated to
     * preserve image aspect ratio.
     */
    function addImage(src, type, doc, page, x, y, width, height, link, func) {
        images++;
        let img = new Image();
        img.src = baseURL + src;
        img.addEventListener('load', function(event) {
            let data = getDataUrl(event.currentTarget, type);
            height = height? height: width * data.height / data.width;
            x = x < 0? -x - width: x;
            y = y < 0? -y - height: y;
            doc.setPage(page);
            doc.addImage(data.url, type, x, y, width, height);
            if (link)
            {
                let options = {url: link};
                doc.link(x, y, width, height, options);
            }
            if (--images == 0 && func)
                func();
        });
    }

    /**
     * Gets data URL, width, height for image.
     * @name  getDataUrl
     * @param img  Image object
     * @param type Type of image, 'png' or 'jpeg'
     * @returns {url: data URL,
     *           width: image width,
     *           height: image height}
     */
    function getDataUrl(img, type) {
        let canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        let context = canvas.getContext('2d');
        context.drawImage(img, 0, 0);
        return {url: canvas.toDataURL('image/' + type),
                width: img.width,
                height: img.height};
    }
});
