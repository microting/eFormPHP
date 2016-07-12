<?php
/*
The MIT License (MIT)

Copyright (c) 2016 microting

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/ ?>

<!doctype html>
<html>
<head>
    <title>Microting InSpection PHP expample</title>
    <link href=/eform/main.css rel=stylesheet>
</head>
<body>
    <h1>Microting InSpection PHP expample</h1>
    <fieldset>
        <legend>eForm</legend>
        <details>
            <summary>The Description of The eForm PHP</summary>
            <pre><?=file_get_contents('eform/readme.txt')?></pre>
        </details>
        <a href=/eform>The eForm Test Environment</a><br>
    </fieldset>
    <fieldset>
        <legend>Class Library</legend>
        <details>
            <summary>The Description of The Elements Class Library</summary>
            <pre><?=file_get_contents('elements/readme.txt')?></pre>
        </details>
        <a href=/elements/test.php>The Test of The Elements Class Library</a><br>
    </fieldset>
    <fieldset>
        <legend>About</legend>
        <details>
            <summary>Readme</summary>
            <pre><?=file_get_contents('readme.txt')?></pre>
        </details>
        (C) Microting, 2016                     
    </fieldset>
</body>
</html>