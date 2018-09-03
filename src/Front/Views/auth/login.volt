{{ flash.output() }}

<h1>Login</h1>

<form action="login" method="post">

    {% for element in form %}

        {{ element.getLabel() }}
        {{ element.render() }}
        <br><br>

    {% endfor %}

    <button type="submit">Login!</button>
</form>