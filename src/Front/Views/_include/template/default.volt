{{ get_doctype() }}
<html lang="en">
<head>
    <base href="{{ url() }}">

    <meta charset="utf-8">
    <meta name="rating" content="general">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#444">

    {{ stylesheet_link('css/bootstrap.min.css') }}

    {{ javascript_include('js/jquery-3.3.1.min.js') }}
    {{ javascript_include('js/bootstrap.min.js') }}

    {{ get_title() }}
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-12">
                {{ flash.output() }}
            </div>
        </div>
    </div>
</header>
<main>
    <div class="container">
        {% block content %}
        {% endblock %}
    </div>
</main>
</body>
</html>
