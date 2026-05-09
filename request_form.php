<!DOCTYPE html>
<html>
<head>
    <title>Request Peer</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Client Form</h2>
        <form action="submit_request.php" method="POST">
            <label style="text-align: left; display: block;">Name</label>
            <input type="text" name="form_name" placeholder="First name, Middle Initial, Surname">

            <label style="text-align: left; display: block;">Grade, Section, Curriculum</label>
            <input type="text" name="form_grade_section" placeholder="Example: 10, Einstein, STE">

            <label style="text-align: left; display: block;">Desired matters to talk about</label>
            <textarea name="form_topic" rows="4" placeholder="Be yourself! Your information is safe!"></textarea>

            <button type="submit" name="send_request" class="btn-primary">SEND REQUEST</button>
        </form>
    </div>
</body>
</html>