<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Error — Naufal Febriansyah</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background: #0a0e14;
            color: #ededed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 24px;
        }

        .eyebrow {
            font-size: 14px;
            font-weight: 500;
            background-image: linear-gradient(90deg, #818cf8, #22d3ee);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 16px;
        }

        h1 {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        h1 span {
            background-image: linear-gradient(90deg, #818cf8, #22d3ee);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        p {
            color: #9ca3af;
            max-width: 420px;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            background-image: linear-gradient(90deg, #818cf8, #22d3ee);
            color: #0b0f19;
            transition: opacity 0.2s ease;
        }

        a:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <p class="eyebrow">500</p>
    <h1>Something Went <span>Wrong</span></h1>
    <p>
        An unexpected error occurred on the server. This has been logged, and
        I'll take a look as soon as possible. Please try again shortly.
    </p>
    <a href="/">Back to Home</a>
</body>

</html>
