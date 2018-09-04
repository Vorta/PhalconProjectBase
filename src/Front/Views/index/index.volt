<h1>Home page</h1>

{{ flash.output() }}
<br>

{% if auth.getIdentity() is not null %}
    {{ auth.getIdentity().getUsername() }}
    <br>
    <a href="/logout">Logout</a>
{% else %}
    <a href="/register">Register</a>
    <br>
    <a href="/login">Login</a>
{% endif %}
<br>

