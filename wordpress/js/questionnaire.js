// questionnaire.js
//
// Version: 0.5
// Author: Bill Farmer

// Created by Bill Farmer
// Licence MIT
// Copyright (C) 2018 Bill Farmer

jQuery(document).ready(function($) {

    // Set up data
    intro = data.intro;
    questions = data.questions;
    last = data.last;
    matrix = data.matrix;

    // Set up buttons
    $("input.questionnaire-button").button();

    // Set up progress bars
    $("#questionnaire-progress").progressbar({value: 6.25});
    $("#questionnaire-progress-max").progressbar({value: 100});

    // Set up radio buttons
    // $("input[type=radio]").checkboxradio();

    $("#questionnaire-intro").html(intro);

    let question = 0;
    let value = 0;
    
    let results = {A: [0, 0], B: 0, C: 0, D: 0, E: 0, F: 0, J: 0};

    // Process the start button
    $("#questionnaire-start").click(function() {
        question = 0;
        $("div.questionnaire-intro").fadeOut(function() {
            $("#questionnaire-progress").progressbar("option", "value", 6.25);
            $("#questionnaire-question").html(questions[question].q);
            if (Math.round(Math.random()))
            {
                $("#question-label-1").html(questions[question].a[0]);
                $("#question-radio-1").attr("value",
                                                 questions[question].v[0]);
                $("#question-label-2").html(questions[question].a[1]);
                $("#question-radio-2").attr("value",
                                                 questions[question].v[1]);
            }

            else
            {
                $("#question-label-2").html(questions[question].a[0]);
                $("#question-radio-2").attr("value",
                                                 questions[question].v[0]);
                $("#question-label-1").html(questions[question].a[1]);
                $("#question-radio-1").attr("value",
                                                 questions[question].v[1]);
            }
            $("input[type=radio]").prop("checked", false);
            // $("input[type=radio]").checkboxradio();
            $("div.questionnaire-question").fadeIn();
        });
    });

    // Process the back button
    $("#questionnaire-back").click(function() {
        question--;
        let type = questions[question].t;
        results[type] -= value;
        if (question == 0)
            $("#questionnaire-back").css("display", "none");
        $("div.questionnaire-question").fadeOut(function() {
            let progress = (question + 1) * 6.25;
            $("#questionnaire-progress").progressbar("option", "value",
                                                     progress);
            $("#questionnaire-question").html(questions[question].q);
            if (Math.round(Math.random()))
            {
                $("#question-label-1").html(questions[question].a[0]);
                $("#question-radio-1").attr("value",
                                                 questions[question].v[0]);
                $("#question-label-2").html(questions[question].a[1]);
                $("#question-radio-2").attr("value",
                                                 questions[question].v[1]);
            }

            else
            {
                $("#question-label-2").html(questions[question].a[0]);
                $("#question-radio-2").attr("value",
                                                 questions[question].v[0]);
                $("#question-label-1").html(questions[question].a[1]);
                $("#question-radio-1").attr("value",
                                                 questions[question].v[1]);
            }
            $("input[type=radio]").prop("checked", false);
            // $("input[type=radio]").checkboxradio("refresh");
            $("div.questionnaire-question").fadeIn();
        });
    });

    // Process the prev button
    $("#questionnaire-prev").click(function() {
        question--;
        let type = questions[question].t;
        results[type] -= value;
        $("div.questionnaire-last").fadeOut(function() {
            let progress = (question + 1) * 6.25;
            $("#questionnaire-progress").progressbar("option", "value",
                                                     progress);
            $("#questionnaire-question").html(questions[question].q);
            if (Math.round(Math.random()))
            {
                $("#question-label-1").html(questions[question].a[0]);
                $("#question-radio-1").attr("value",
                                                 questions[question].v[0]);
                $("#question-label-2").html(questions[question].a[1]);
                $("#question-radio-2").attr("value",
                                                 questions[question].v[1]);
            }

            else
            {
                $("#question-label-2").html(questions[question].a[0]);
                $("#question-radio-2").attr("value",
                                                 questions[question].v[0]);
                $("#question-label-1").html(questions[question].a[1]);
                $("#question-radio-1").attr("value",
                                                 questions[question].v[1]);
            }
            $("input[type=radio]").prop("checked", false);
            // $("input[type=radio]").checkboxradio("refresh");
            $("div.questionnaire-question").fadeIn();
        });
    });

    // Process the radio buttons
    $("input[type=radio].question-radio").click(function() {
        let type = questions[question].t;
        value = +$(this).attr("value");
        results[type] += value;
        question++;
        if (question < questions.length)
        {
            let type = questions[question].t;
            $("div.questionnaire-question").fadeOut(function() {
                let progress = (question + 1) * 6.25;
                $("#questionnaire-progress").progressbar("option", "value",
                                                         progress);
                $("#questionnaire-question").html(questions[question].q);
                if (Math.round(Math.random()))
                {
                    $("#question-label-1").html(questions[question].a[0]);
                    $("#question-radio-1").attr("value",
                                                     questions[question].v[0]);
                    $("#question-label-2").html(questions[question].a[1]);
                    $("#question-radio-2").attr("value",
                                                     questions[question].v[1]);
                }

                else
                {
                    $("#question-label-2").html(questions[question].a[0]);
                    $("#question-radio-2").attr("value",
                                                     questions[question].v[0]);
                    $("#question-label-1").html(questions[question].a[1]);
                    $("#question-radio-1").attr("value",
                                                     questions[question].v[1]);
                }
                $("input[type=radio]").prop("checked", false);
                // $("input[type=radio]").checkboxradio("refresh");
                $("#questionnaire-back").css("display", "block");
                $("div.questionnaire-question").fadeIn();
            });
        }

        else
        {
            $("div.questionnaire-question").fadeOut(function() {
                $("#questionnaire-last").html(last.q);
                $("#last-label-1").html(last.a[0]);
                $("#last-radio-1").attr("value", last.v[0]);
                $("#last-label-2").html(last.a[1]);
                $("#last-radio-2").attr("value", last.v[1]);
                $("#last-label-3").html(last.a[2]);
                $("#last-radio-3").attr("value", last.v[2]);
                $("#last-label-4").html(last.a[3]);
                $("#last-radio-4").attr("value", last.v[3]);
                $("#last-label-5").html(last.a[4]);
                $("#last-radio-5").attr("value", last.v[4]);
                $("input[type=radio]").prop("checked", false);
                // $("input[type=radio]").checkboxradio("refresh");
                $("div.questionnaire-last").fadeIn();
            });
        }
    });

    //Process the last radio buttons
    $("input[type=radio].question-last").click(function() {
        let type = last.t;
        value = +$(this).attr("value");
        results[type] += value;
        $("div.questionnaire-last").fadeOut(function() {
            results.A[0] = results.B;
            results.A[1] = results.E;
            $("div.questionnaire-contact").fadeIn();
            console.log(results);
            console.log(calculate(results, matrix));
        });
    });

    // Process the again button
    $("#questionnaire-again").click(function() {
        $("div.questionnaire-contact").fadeOut(function() {
            for (let [key, value] in results)
            {
                if (key == "A")
                    results[key] = [0, 0];

                else
                    results[key] = 0;
            }
            $("div.questionnaire-intro").fadeIn();
        });
    });

    function calculate(results, matrix) {
        let b = (results.B / 2) - 3;
        let c = (results.C / 2) - 3;
        let d = (results.D / 2) - 3;
        let e = (results.E / 2) - 3;
        let f = (results.F / 2) - 3;
        let j = (results.J / 2) - 1;

        let result = {A: matrix.A[Math.trunc(b / 2)][Math.trunc(e / 2)],
                      B: matrix.B[b],
                      C: matrix.C[c],
                      D: matrix.D[d],
                      E: matrix.E[e],
                      F: matrix.F[f],
                      J: matrix.J[j]};

        return result;
    }
});
