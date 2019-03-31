<h3 class="h4 mb-4">{{ form.getTitle() }}</h3>

{% for element in form %}
    {% if element.getUserOption('special-element') %}
        {{ partial('_include/component/form/' ~ element.getUserOption('special-element'))  }}
    {% else %}
        {% include '_include/component/form/element.volt' %}
    {% endif %}
{% endfor %}

<button type="submit" class="btn btn-primary mb-2">{{ form.getSubmitText() }}</button>
