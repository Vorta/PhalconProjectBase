{{ flash.output() }}

<h1>Register</h1>

<form action="register" method="post">

    {% for element in form %}

        {{ element.getLabel() }}
        {{ element.render() }}
        <br><br>

    {% endfor %}

    <button type="submit">Register!</button>
</form>