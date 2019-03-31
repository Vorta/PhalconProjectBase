{% extends "_include/template/default.volt" %}

{% block content %}

    <h1>{{ t('MSG_HOME_PAGE') }}</h1>

    <div class="row">
        <div class="col-6">
            {% if auth.getIdentity() is not null %}
                <a href="{{ url.get(['for': 'logout']) }}">{{ t('LBL_LOGOUT') }}</a>
            {% else %}
                <a href="{{ url.get(['for': 'register']) }}">{{ t('LBL_REGISTRATION') }}</a>
                <br>
                <a href="{{ url.get(['for': 'login']) }}">{{ t('LBL_LOGIN') }}</a>
            {% endif %}
        </div>
    </div>
{% endblock %}