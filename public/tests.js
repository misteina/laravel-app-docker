// Test cases

window.onload = async function () {

    const csrfToken = document.getElementsByTagName('meta')[0].getAttribute('content');

    document.write('Reset database...<br><b>Request:</b> DELETE /users/1<br>');

    await fetch(
        'http://localhost:8080/users/1',
        { 
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
        }
    ).then(
        response => response.text()
    ).then(
        data => document.write(`<b>Response:</b> ${data}<br><br><br>`)
    );




    document.write('Add user...<br><b>Request:</b> POST /users<br>');

    await fetch(
        'http://localhost:8080/users',
        {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name: 'James Kenaan', email: 'james@xyz.com', password: 'rufhir84u' })
        }
    ).then(
        response => response.text()
    ).then(
        data => document.write(`<b>Response:</b> ${data}<br><br><br>`)
    );




    document.write('Get user using JWT authentication...<br><b>Request:</b> GET /users/{id}<br>');

    await fetch(
        'http://localhost:8080/users/1',
        {
            method: 'GET'
        }
    ).then(
        response => response.text()
    ).then(
        data => document.write(`<b>Response:</b> ${data}<br><br><br>`)
    ).catch(
        error => document.write(error)
    );

}