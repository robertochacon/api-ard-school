<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin:0;
            background: #fafafa;
        }
        .swagger-ui .topbar {
            background-color: #2c3e50;
        }
        .swagger-ui .topbar .download-url-wrapper {
            display: none;
        }
        .swagger-ui .topbar .topbar-wrapper {
            max-width: none;
        }
        .swagger-ui .topbar .topbar-wrapper .link {
            content: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiByeD0iOCIgZmlsbD0iIzJjM2U1MCIvPgo8cGF0aCBkPSJNMjAgMTBDMTQuNDc3MiAxMCAxMCAxNC40NzcyIDEwIDIwQzEwIDI1LjUyMjggMTQuNDc3MiAzMCAyMCAzMEMyNS41MjI4IDMwIDMwIDI1LjUyMjggMzAgMjBDMzAgMTQuNDc3MiAyNS41MjI4IDEwIDIwIDEwWiIgZmlsbD0iI2ZmZiIvPgo8cGF0aCBkPSJNMjAgMTVDMTYuNjg2MyAxNSAxNCAxNy42ODYzIDE0IDIxQzE0IDI0LjMxMzcgMTYuNjg2MyAyNyAyMCAyN0MyMy4zMTM3IDI3IDI2IDI0LjMxMzcgMjYgMjFDMjYgMTcuNjg2MyAyMy4zMTM3IDE1IDIwIDE1WiIgZmlsbD0iIzJjM2U1MCIvPgo8L3N2Zz4K');
            width: 40px;
            height: 40px;
        }
        .swagger-ui .topbar .topbar-wrapper .link:after {
            content: "School Management API";
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            margin-left: 10px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: '/storage/api-docs/api-docs.json',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                validatorUrl: null,
                onComplete: function() {
                    console.log('Swagger UI loaded successfully');
                },
                onFailure: function(data) {
                    console.error('Swagger UI failed to load:', data);
                }
            });
        };
    </script>
</body>
</html><?php /**PATH /Users/robertochaconalcantara/Documents/Projects/ard/api-school/resources/views/swagger/index.blade.php ENDPATH**/ ?>