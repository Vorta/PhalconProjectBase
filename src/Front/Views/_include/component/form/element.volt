<div id="{{element.getName() ~ '-block'}}" class="form-group">
    <label for="{{element.getName()}}">{{ element.getLabel() }}</label>

    {% if form.hasMessagesFor(element.getName()) %}
        {{ form.render(element.getName(), ['class': 'form-control is-invalid']) }}
        {% for message in form.getMessagesFor(element.getName()) %}
            <div class="invalid-feedback" style="display:block;">
                {{ message.getMessage() }}
            </div>
        {% endfor %}
    {% else %}
        {{ form.render(element.getName(), ['class': 'form-control']) }}
    {% endif %}

    {% if element.getUserOption('help') %}
        <small class="form-text text-muted">
            {{ element.getUserOption('help') }}
        </small>
    {% endif %}
</div>