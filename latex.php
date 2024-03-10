<?php

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Latex</title>
    <link rel="stylesheet" href="assets/latex.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css"
          integrity="sha384-n8MVd4RsNIU0tAv4ct0nTaAbDJwPJzDEaqSD1odI+WdtXRGWt2kTvGFasHpSy3SV" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"
            integrity="sha384-XjKyOOlGwcjNTAIQHIpgOno0Hl1YQqzUOEleOLALmuqehneUG+vnGctmUb0ZY0l8"
            crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"
            integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05"
            crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            renderMathInElement(document.body, {
                // customised options
                // • auto-render specific keys, e.g.:
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                // • rendering keys, e.g.:
                throwOnError: false
            });
        });
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css"
          integrity="sha384-n8MVd4RsNIU0tAv4ct0nTaAbDJwPJzDEaqSD1odI+WdtXRGWt2kTvGFasHpSy3SV" crossorigin="anonymous">
    <script type="module">
        import renderMathInElement from "https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.mjs";

        renderMathInElement(document.body);
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Latex</h1>
            <p>Latex is a typesetting system. It is used to create documents with a professional look. Latex is used to
                create scientific and mathematical documents. It is also used to create books, reports, and letters.</p>
            <p>Latex is not a word processor. Instead, it is a markup language. It is used to define the structure of a
                document. For example, you can define the title, the chapters, the sections, the subsections, the
                paragraphs, the lists, etc.</p>
            <p>Latex is a powerful tool. It is used to create complex documents. It is used to create documents with
                complex mathematical equations. It is used to create documents with complex tables and figures.</p>
        </div>

        <form method="GET">
            <input type="text" value="<?php echo $_GET['latex'] ?? null; ?>" name="latex" id="latex"
                   class="form-control my-2">
            <p>Link: https://test.devkid.com/latex/?latex=<?php echo $_GET['latex'] ?? null ?></p>
            <button type="submit" class="btn btn-primary my-2 w-100">Save</button>
        </form>

        <script>
            document.getElementById('latex').addEventListener('input', function () {
                let latex = document.getElementById('latex').value;
                // Get element id display And change the value
                document.getElementById('display').innerHTML = "$$ " + latex + " $$";
                renderMathInElement(document.body, {
                    // customised options
                    // • auto-render specific keys, e.g.:
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false},
                        {left: '\\(', right: '\\)', display: false},
                        {left: '\\[', right: '\\]', display: true}
                    ],
                    // • rendering keys, e.g.:
                    throwOnError: false
                });
            });
        </script>

        <p class="qu my-2 justify-content-center" id="display">$$ <?php echo $_GET['latex'] ?? null; ?> $$</p>

        <h3 class="border-bottom">Math Problem</h3>

        <p class="qu my-2 justify-content-center">
            $$ d_1(f, g) = \int_{0}^{1} |f(x) - g(x)| \, dx = \int_{0}^{1} 0 \, dx = 0. $$ </p>
        <p class="qu my-2 justify-content-center">
            And, if $ f \neq g $, then $ F(x) = |f(x) - g(x)| $ is not identically zero. Hence, there exists $ x_0 $
            such that $ F(x_0) = 2\epsilon > 0 $. And by continuity, there exists $ \delta $ such that for all $ x $
            with $ x_0 - \delta < x < x_0 + \delta $, $ F(x) > \epsilon $. So,
        </p>
        <p class="qu my-2 justify-content-center">
            $$ d_1(f, g) = \int_{0}^{1} F(x) \, dx = \int_{0}^{x_0 - \delta} F(x) \, dx + \int_{x_0 - \delta}^{x_0 +
            \delta} F(x) \, dx + \int_{x_0 + \delta}^{1} F(x) \, dx \geq \int_{x_0 - \delta}^{x_0 + \delta} dx =
            2\epsilon \delta > 0. $$</p>
    </div>
</body>