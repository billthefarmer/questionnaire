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

    // Style button
    $("#report").button();

    // Add name
    $("#name").html(name);

    // Create HTML preview
    createHTMLPreview();

    // Create PDF document
    createPDFdocument();

    // createHTMLPreview
    function createHTMLPreview()
    {
        // Create front page and explanatory letter
        for (let page of pages)
        {
            let pageno = page.pageno;
            switch (pageno)
            {
                // First page
                case 1:
                addHTMLImage(page.images[0], "#preview");
                for (let text of page.text)
                    addHTMLText(text, "#preview");
                addHTMLImage(page.images[1], "#preview");
                addHTMLBreak("#preview");
                break;

                // Second page
                case 2:
                addHTMLImage(page.images[0], "#preview");
                for (let text of page.text)
                    addHTMLText(text, "#preview", true);
                addHTMLImage(page.images[1], "#preview");
                addHTMLBreak("#preview");
                break;

                // Third page
                case 3:
                addHTMLImage(page.images[0], "#preview");
                for (let text of page.text)
                    addHTMLText(text, "#preview");
                addHTMLImage(page.images[1], "#preview");
                addHTMLBreak("#preview");
                break;
            }
        }

        // Create report
        // B type
        if (B)
            addHTMLAnswer(answers['B'], B, "#preview");

        // C type
        if (C)
            addHTMLAnswer(answers['C'], C, "#preview");

        // D type
        if (D)
            addHTMLAnswer(answers['D'], D, "#preview");

        // E type
        if (E)
            addHTMLAnswer(answers['E'], E, "#preview");

        // F type
        if (F)
            addHTMLAnswer(answers['F'], F, "#preview");
        addHTMLBreak("#preview");

        // Penult page
        addHTMLText(penult.text[0], "#preview");
        addHTMLImage(penult.images[0], "#preview");
        addHTMLText(penult.text[1], "#preview");
        addHTMLBreak("#preview");

        // Last page
        addHTMLText(last.text[0], "#preview");
        if (S)
            addHTMLStage(answers['S'], S, '#preview');
        addHTMLText(last.text[4], "#preview");

        // addHTMLBreak
        function addHTMLBreak(element)
        {
            $(element).append("<div><br /></div>");
        }

        //addHTMLStage
        function addHTMLStage(answer, value, element)
        {
            let color = answer.color;
            let stage = answer[value].stage;
            let steps = answer[value].steps;
            let clazz = answer[value].class;
            let link = answer[value].link;
            let image = answer[value].image;
            let text = answer[value].text;
            $(element).append("<p>" + stage + "</p>");
            $(element).append("<p>" + steps + "</p>");
            $(element).append("<p><a href='" + link +
                              "' style='font-weight: bold; color: rgb(" +
                              color[0] + "," + color[1] + "," + color[2] +
                              ");'>" + clazz + "</a></p>");
            text = text.replace(/\n\n/g, "</p><p>");
            $(element).append("<p>" + text + "</p>");
            $(element).append("<a href='" + link + "'><img src='" +
                              baseURL + image + "'></a>");
        }

        // addHTMLAnswer
        function addHTMLAnswer(answer, value, element)
        {
            let desc = answer.desc;
            let type = answer[value].type;
            let text = answer[value].text;
            let image = answer[value].image
            $(element).append("<img src='" + baseURL + image + "'>");
            $(element).append("<p>" + desc + "</p>");
            $(element).append("<p style='font-weight: bold;'>" + type + "</p>");
            text = text.replace(/\n\n/g, "</p><p>");
            $(element).append("<p>" + text + "</p>");
        }

        // addHTMLText
        function addHTMLText(text, element, blanks)
        {
            let size = text.size;
            let type = text.type;
            let color = text.color;
            let link = text.link;
            let style = "";
            if (size || type || color)
            {
                style = " style='";
                if (size && type != "normal")
                    style += "font-size: " + size + "px;";
                if (type)
                    style += "font-weight: " + type + ";";
                if (color)
                {
                    if (Array.isArray(text.color))
                        style += "color: rgb(" + color[0] + "," + color[1] +
                        "," + color[2] + ");";
                    else
                        style += "color: rgb(" + color + "," + color +
                        "," + color + ");";
                }
                style += "'";
            }
            let string = text.text;
            if (string.match(/~[a-z]+~/))
                string = string.replace(/~forename~/g, forename)
                .replace(/~lastname~/g, lastname);
            if (string.match(/\n/))
                string = string.replace(/\n\n/g, "</p><p>")
                .replace(/^\n/, "").replace(/\n$/, "");
            if (blanks)
                string = string.replace(/\n/g, " ");
            else
                string = string.replace(/\n/g, "<br />");
            if (link)
                $(element).append("<p><a href='" + link + "'" + style + ">" +
                                  string + "</a></p>");
            else
                $(element).append("<div" + style + "><p>" + string +
                                  "</p></div>");
        }

        // addHTMLImage
        function addHTMLImage(image, element)
        {
            let x = image.x;
            let width = image.width;
            let link = image.link;
            let style = "";
            if (x || width)
            {
                style = " style='";
                if (width)
                    style += "width: " + Math.round(width / pageWidth * 100) +
                    "%;";
                if (x && (x < 0))
                    style += "float: right; margin-left: 10px;";
                style += "'";
            }
            if (link)
                $(element).append("<a href='" + link + "'><img src='" +
                                  baseURL + image.src + "'" + style + "></a>");
            else
                $(element).append("<img src='" + baseURL + image.src + "'" +
                                  style + ">");
        }
    }

    // createPDFdocument
    function createPDFdocument()
    {
        // Create PDF document
        let doc = jsPDF({unit: 'pt',
                         compress: true});

        // Print front page and explanatory letter
        let pageno = 1;
        for (let page of pages)
        {
            pageno = page.pageno;
            if (pageno != 1)
                doc.addPage();

            for (let image of page.images)
                addImageObject(image, doc, pageno);

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
            addImageObject(image, doc, pageno);

        // Text
        for (let text of penult.text)
            y = addTextObject(text, doc, y);

        // Last page
        doc.addPage();
        y = margin;
        pageno++;

        y = addTextObject(last.text[0], doc, y);
        if (S)
            y = addStage(answers['S'], S, y);
        y = addTextObject(last.text[4], doc, y);

        // Download report
        $('#report').click(function() {
            doc.save(forename + '_' + lastname + '_Entrepreneurial_Design.pdf');
        });

        // addStage
        function addStage(answer, value, y)
        {
            let color = answer.color;
            let type = answer.type;
            let stage = answer[value].stage;
            let steps = answer[value].steps;
            let clazz = answer[value].class;
            let link = answer[value].link;
            let image = answer[value].image;
            let text = answer[value].text;
            doc.setFont('helvetica', 'normal');
            y += doc.getLineHeight();
            y = addText(stage, doc, margin, y, textWidth) + doc.getLineHeight();
            y = addText(steps, doc, margin, y, textWidth) + doc.getLineHeight();
            doc.setFont('helvetica', 'bold');
            if (Array.isArray(color))
                doc.setTextColor(color[0], color[1], color[2]);
            else
                doc.setTextColor(color);
            y = addText(clazz, doc, margin, y, textWidth, link) +
                doc.getLineHeight();
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(0, 0, 0);
            y = addText(text, doc, margin, y, textWidth);
            addImage(image, type, doc, pageno, margin, y, textWidth, null, link);
            return y;
        }

        // addAnswer
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

        // addTextObject
        function addTextObject(text, doc, y)
        {
            let size = text.size;
            if (size)
                doc.setFontSize(size);
            let type = text.type;
            if (type)
                doc.setFont('helvetica', type);
            let color = text.color;
            if (color)
            {
                if (Array.isArray(color))
                    doc.setTextColor(color[0], color[1], color[2]);
                else
                    doc.setTextColor(color);
            }
            y = text.y? text.y: y;
            let width = text.width;
            width = width? width: textWidth;
            let string = text.text;
            if (string.match(/~[a-z]+~/))
                string = string.replace(/~forename~/g, forename)
                .replace(/~lastname~/g, lastname);
            return addText(string, doc, margin, y, width, text.link);
        }

        // addImageObject
        function addImageObject(image, doc, pageno)
        {
            let y = image.y;
            y = y? (y < 0)? -pageHeight + margin: y: margin;
            let x = image.x;
            x = x? (x < 0)? -pageWidth + margin: x: margin;
            let width = image.width;
            width = width? width: textWidth;
            addImage(image.src, image.type, doc, pageno, x, y,
                     width, image.height, image.link);
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
         * @description
         * If the x parameter is negative, used as right edge of image.
         * If the y parameter is negative, used as bottom edge of image.
         * If the height is null or 0, height is calculated to
         * preserve image aspect ratio.
         */
        function addImage(src, type, doc, page, x, y, width, height, link, func) {
            let img = new Image();
            img.src = baseURL + src;
            img.addEventListener('load', function(event) {
                let url = getDataUrl(img, type);
                height = height? height: width * img.height / img.width;
                x = x < 0? -x - width: x;
                y = y < 0? -y - height: y;
                doc.setPage(page);
                doc.addImage(url, type, x, y, width, height);
                if (link)
                {
                    let options = {url: link};
                    doc.link(x, y, width, height, options);
                }
            });
        }
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

    /**
     * Gets data URL, width, height for image.
     * @name  getDataUrl
     * @param img  Image object
     * @param type Type of image, 'png' or 'jpeg'
     * @returns url: data URL
     */
    function getDataUrl(img, type) {
        let canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        let context = canvas.getContext('2d');
        context.drawImage(img, 0, 0);
        return canvas.toDataURL('image/' + type);
    }
});
