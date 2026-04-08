<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }

        .header {
            width: 100%;
            padding: 10px 40px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        img {
            height: 40px;
            width: auto;
            display: block;
        }
    </style>
</head>
<body>
<div class="header">
    <img
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('media/nhs-logo.png'))) }}"
            alt="NHS logo"
    >
</div>
</body>
</html>