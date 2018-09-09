{{ flash.output() }}

<h1>{{ t._('LBL_REGISTER') }}</h1>

<form action="{{ url.get(['for': 'register']) }}" method="post">

    {% for element in form %}

        {{ element.getLabel() }}
        {{ element.render() }}
        <br><br>

    {% endfor %}

    <button type="submit">{{ t._('LBL_REGISTER') }}!</button>
</form>