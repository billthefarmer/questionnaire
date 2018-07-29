// quiz.js
//
// Version: 0.5
// Author: Bill Farmer

// Created by Bill Farmer
// Licence MIT
// Copyright (C) 2018 Bill Farmer

jQuery(document).ready(function($) {

    // Data
    let data = {};
    let intro = "";
    let questions = [];
    let last = "";
    let matrix = [];

    // Get data from page
    try
    {
        data = JSON.parse($("#quiz-data").html());
        intro = data.intro;
        questions = data.questions;
        last = data.last;
        matrix = data.matrix;
    }

    catch(e)
    {
        // console.error(e);
        return;
    }

    // Set up buttons
    $("input.quiz-button").button();

    // Set up progress bars
    $("#quiz-progress").progressbar({value: 6.25});
    $("#quiz-progress-max").progressbar({value: 100});

    // Set up radio buttons
    // $("input[type=radio]").checkboxradio();

    $("#quiz-intro").html(intro);

    let question = 0;
    let value = 0;
    
    let results = {A: [0, 0], B: 0, C: 0, D: 0, E: 0, F: 0, J: 0};

    // Process the start button
    $("#quiz-start").click(function() {
        question = 0;
        $("div.quiz-intro").fadeOut(function() {
            $("#quiz-progress").progressbar("option", "value", 6.25);
            $("#quiz-question").html(questions[question].q);
            if (Math.round(Math.random()))
            {
                $("#quiz-label-1").html(questions[question].a[0]);
                $("#quiz-radio-1").attr("value", questions[question].v[0]);
                $("#quiz-label-2").html(questions[question].a[1]);
                $("#quiz-radio-2").attr("value", questions[question].v[1]);
            }

            else
            {
                $("#quiz-label-2").html(questions[question].a[0]);
                $("#quiz-radio-2").attr("value", questions[question].v[0]);
                $("#quiz-label-1").html(questions[question].a[1]);
                $("#quiz-radio-1").attr("value", questions[question].v[1]);
            }
            $("input[type=radio]").prop("checked", false);
            $("div.quiz-question").fadeIn();
        });
    });

    // Process the back button
    $("#quiz-back").click(function() {
        question--;
        let type = questions[question].t;
        results[type] -= value;
        if (question == 0)
            $("input.quiz-back").css("display", "none");
        $("div.quiz-question").fadeOut(function() {
            let progress = (question + 1) * 6.25;
            $("#quiz-progress").progressbar("option", "value", progress);
            $("#quiz-question").html(questions[question].q);
            if (Math.round(Math.random()))
            {
                $("#quiz-label-1").html(questions[question].a[0]);
                $("#quiz-radio-1").attr("value", questions[question].v[0]);
                $("#quiz-label-2").html(questions[question].a[1]);
                $("#quiz-radio-2").attr("value", questions[question].v[1]);
            }

            else
            {
                $("#quiz-label-2").html(questions[question].a[0]);
                $("#quiz-radio-2").attr("value", questions[question].v[0]);
                $("#quiz-label-1").html(questions[question].a[1]);
                $("#quiz-radio-1").attr("value", questions[question].v[1]);
            }
            $("input[type=radio]").prop("checked", false);
            $(".question").fadeIn();
        });
    });

    // Process the prev button
    $("#quiz-prev").click(function() {
        question--;
        let type = questions[question].t;
        results[type] -= value;
        $("div.quiz-last").fadeOut(function() {
            let progress = (question + 1) * 6.25;
            $("#quiz-progress").progressbar("option", "value", progress);
            $("#quiz-question").html(questions[question].q);
            if (Math.round(Math.random()))
            {
                $("#quiz-label-1").html(questions[question].a[0]);
                $("#quiz-radio-1").attr("value", questions[question].v[0]);
                $("#quiz-label-2").html(questions[question].a[1]);
                $("#quiz-radio-2").attr("value", questions[question].v[1]);
            }

            else
            {
                $("#quiz-label-2").html(questions[question].a[0]);
                $("#quiz-radio-2").attr("value", questions[question].v[0]);
                $("#quiz-label-1").html(questions[question].a[1]);
                $("#quiz-radio-1").attr("value", questions[question].v[1]);
            }
            $("input[type=radio]").prop("checked", false);
            $("div.quiz-question").fadeIn();
        });
    });

    // Process the radio buttons
    $("input[type=radio].quiz-answer").click(function() {
        let type = questions[question].t;
        value = +$(this).attr("value");
        results[type] += value;
        question++;
        if (question < questions.length)
        {
            let type = questions[question].t;
            $("div.quiz-question").fadeOut(function() {
                let progress = (question + 1) * 6.25;
                $("#quiz-progress").progressbar("option", "value", progress);
                $("#quiz-question").html(questions[question].q);
                if (Math.round(Math.random()))
                {
                    $("#quiz-label-1").html(questions[question].a[0]);
                    $("#quiz-radio-1").attr("value", questions[question].v[0]);
                    $("#quiz-label-2").html(questions[question].a[1]);
                    $("#quiz-radio-2").attr("value", questions[question].v[1]);
                }

                else
                {
                    $("#quiz-label-2").html(questions[question].a[0]);
                    $("#quiz-radio-2").attr("value", questions[question].v[0]);
                    $("#quiz-label-1").html(questions[question].a[1]);
                    $("#quiz-radio-1").attr("value", questions[question].v[1]);
                }
                $("input[type=radio]").prop("checked", false);
                $("input.quiz-back").css("display", "block");
                $("div.quiz-question").fadeIn();
            });
        }

        else
        {
            $("div.question").fadeOut(function() {
                $("#quiz-last").html(last.q);
                $("#quiz-label-3").html(last.a[0]);
                $("#quiz-radio-3").attr("value", last.v[0]);
                $("#quiz-label-4").html(last.a[1]);
                $("#quiz-radio-4").attr("value", last.v[1]);
                $("#quiz-label-5").html(last.a[2]);
                $("#quiz-radio-5").attr("value", last.v[2]);
                $("#quiz-label-6").html(last.a[3]);
                $("#quiz-radio-6").attr("value", last.v[3]);
                $("input[type=radio]").prop("checked", false);
                $("div.last").fadeIn();
            });
        }
    });

    //Process the last radio buttons
    $("input[type=radio].quiz-last").click(function() {
        let type = last.t;
        value = +$(this).attr("value");
        results[type] += value;
        $("div.last").fadeOut(function() {
            results.A[0] = results.B;
            results.A[1] = results.E;
            let result = calculate(results, matrix);
            $("#quiz-arch").attr("value", result.A);
            $("#quiz-brain").attr("value", result.B);
            $("#quiz-comm").attr("value", result.C);
            $("#quiz-direct").attr("value", result.D);
            $("#quiz-exec").attr("value", result.E);
            $("#quiz-focus").attr("value", result.F);
            $("#quiz-journey").attr("value", result.J);
            $("div.result").fadeIn();
            console.log(results);
            console.log(calculate(results, matrix));
        });
    });

    // Process the again button
    $("#quiz-again").click(function() {
        $("div.result").fadeOut(function() {
            for (let [key, value] in results)
            {
                if (key == "A")
                    results[key] = [0, 0];

                else
                    results[key] = 0;
            }
            $("div.intro").fadeIn();
        });
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
