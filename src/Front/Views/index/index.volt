<h1>{{ t._('MSG_HOME_PAGE') }}</h1>

{{ flash.output() }}
<br>

{% if auth.getIdentity() is not null %}
    {{ auth.getIdentity().getUsername() }}
    <br>
    <a href="{{ url.get(['for': 'logout']) }}">{{ t._('LBL_LOGOUT') }}</a>
{% else %}
    <a href="{{ url.get(['for': 'register']) }}">{{ t._('LBL_REGISTER') }}</a>
    <br>
    <a href="{{ url.get(['for': 'login']) }}">{{ t._('LBL_LOGIN') }}</a>
{% endif %}
<br>

