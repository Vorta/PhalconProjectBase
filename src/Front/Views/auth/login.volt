{% extends "_include/template/default.volt" %}

{% block content %}
    <form action="{{ url.get(['for': 'login']) }}" method="post" class="border border-light p-4">
        {% include '_include/component/form.volt' %}
    </form>
{% endblock %}