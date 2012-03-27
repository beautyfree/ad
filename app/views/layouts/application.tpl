<!DOCTYPE html>
<html>
<head>
    <title>{{ title }}</title>
    <!--<link href="/stylesheets/reset.css" rel="stylesheet" type="text/css" />-->
    <link href="/stylesheets/main.css" rel="stylesheet" type="text/css" />
    <link href="/stylesheets/smoothness/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css" />
    <link href="/stylesheets/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css" />
    <link href="/stylesheets/bootstrap.css" rel="stylesheet" type="text/css" />

    <script src="/javascripts/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="/javascripts/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
    <script src="/javascripts/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
    <script src="/javascripts/localization/jquery-ui-timepicker-ru.js" type="text/javascript"></script>
    <script src="/javascripts/bootstrap.js" type="text/javascript"></script>
</head>
<body>
    <div id="wrapper">
        <div id="container-header">
            <div id="header">
                <div class="button login">Войти</div>
            </div>
        </div>
        <div id="content">{{ main_content }}</div>
        <div id="footer"></div>
    </div>
</body>
</html>
