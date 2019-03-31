{% extends "_include/template/default.volt" %}

{% block content %}
    <form action="{{ url.get(['for': 'register']) }}" method="post" class="border border-light p-4">
        {% include '_include/component/form.volt' %}
    </form>
{% endblock %}