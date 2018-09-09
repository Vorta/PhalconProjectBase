{{ flash.output() }}

<h1>{{ t._('LBL_LOGIN') }}</h1>

<form action="{{ url.get(['for': 'login']) }}" method="post">

    {% for element in form %}

        {{ element.getLabel() }}
        {{ element.render() }}
        <br><br>

    {% endfor %}

    <button type="submit">{{ t._('LBL_LOGIN') }}!</button>
</form>