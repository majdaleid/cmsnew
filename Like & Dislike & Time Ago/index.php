<?php
session_start();

$loggedIn = false;

if (isset($_SESSION['loggedIn']) && isset($_SESSION['name'])) {
    $loggedIn = true;
}

$conn = new mysqli('localhost', 'root', '', 'ytCommentSystem');

function createCommentRow($data, $isReply = false) {
    global $conn;

    if ($isReply)
        $isReply = 'yes';
    else
        $isReply = 'no';

    $response = '
            <div id="comment_'.$data['id'].'" class="comment">
                <div class="user">'.$data['name'].' <span class="time timeago" data-date="'.$data['createdOn'].'"></span></div>
                <div class="userComment">'.$data['comment'].'</div>
                <div class="reply">
                    <i class="fas fa-thumbs-up" data-isReply="'.$isReply.'" onclick="react(this,'.$data['id'].', \'up\')"></i>
                    <i class="fas fa-thumbs-down" data-isReply="'.$isReply.'" onclick="react(this,'.$data['id'].', \'down\')"></i>
                    <a href="javascript:void(0)" data-commentID="'.$data['id'].'" onclick="reply(this)">REPLY</a>
                </div>
                <div class="replies">';

    $sql = $conn->query("SELECT replies.id, name, comment, replies.createdOn FROM replies INNER JOIN users ON replies.userID = users.id WHERE replies.commentID = '".$data['id']."' ORDER BY replies.id DESC LIMIT 1");
    while($dataR = $sql->fetch_assoc())
        $response .= createCommentRow($dataR, true);

    $response .= '
                        </div>
            </div>
        ';

    return $response;
}

if (isset($_POST['getUserReactions'])) {
    $reactions = [];
    $sql = $conn->query("SELECT commentID, type, isReply FROM reactions");
    while($data = $sql->fetch_assoc())
        $reactions[] = array("commentID" => $data['commentID'], "type" => $data['type'], "isReply" => $data['isReply']);

    exit(json_encode($reactions));
}

if (isset($_POST['getAllComments'])) {
    $start = $conn->real_escape_string($_POST['start']);

    $response = "";
    $sql = $conn->query("SELECT comments.id, name, comment, comments.createdOn FROM comments INNER JOIN users ON comments.userID = users.id ORDER BY comments.id DESC LIMIT $start, 20");
    while($data = $sql->fetch_assoc())
        $response .= createCommentRow($data);

    exit($response);
}

if (isset($_POST['react'])) {
    $commentID = $conn->real_escape_string($_POST['commentID']);
    $type = $conn->real_escape_string($_POST['type']);
    $isReply = $conn->real_escape_string($_POST['isReply']);

    $sql = $conn->query("SELECT id FROM reactions WHERE commentID='$commentID' && userID='".$_SESSION['userID']."'");
    if ($sql->num_rows > 0) {
        $status = "updated";
        $conn->query("UPDATE reactions SET type='$type' WHERE commentID='$commentID' && userID='".$_SESSION['userID']."'");
    } else {
        $status = "inserted";
        $conn->query("INSERT INTO reactions (isReply,type,commentID, userID) VALUES ('$isReply','$type', '$commentID', '".$_SESSION['userID']."')");
    }

    exit(json_encode(array('status' => $status)));
}

if (isset($_POST['addComment'])) {
    $comment = $conn->real_escape_string($_POST['comment']);
    $isReply = $conn->real_escape_string($_POST['isReply']);
    $commentID = $conn->real_escape_string($_POST['commentID']);

    if ($isReply != 'false') {
        $conn->query("INSERT INTO replies (comment, commentID, userID, createdOn) VALUES ('$comment', '$commentID', '".$_SESSION['userID']."', NOW())");
        $sql = $conn->query("SELECT replies.id, name, comment, replies.createdOn FROM replies INNER JOIN users ON replies.userID = users.id ORDER BY replies.id DESC LIMIT 1");
    } else {
        $conn->query("INSERT INTO comments (userID, comment, createdOn) VALUES ('".$_SESSION['userID']."','$comment',NOW())");
        $sql = $conn->query("SELECT comments.id, name, comment, comments.createdOn FROM comments INNER JOIN users ON comments.userID = users.id ORDER BY comments.id DESC LIMIT 1");
    }

    $data = $sql->fetch_assoc();
    exit(createCommentRow($data));
}

if (isset($_POST['register'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($sql->num_rows > 0)
            exit('failedUserExists');
        else {
            $ePassword = password_hash($password, PASSWORD_BCRYPT);
            $conn->query("INSERT INTO users (name,email,password,createdOn) VALUES ('$name', '$email', '$ePassword', NOW())");

            $sql = $conn->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
            $data = $sql->fetch_assoc();

            $_SESSION['loggedIn'] = 1;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['userID'] = $data['id'];

            exit('success');
        }
    } else
        exit('failedEmail');
}

if (isset($_POST['logIn'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = $conn->query("SELECT id, password, name FROM users WHERE email='$email'");
        if ($sql->num_rows == 0)
            exit('failed');
        else {
            $data = $sql->fetch_assoc();
            $passwordHash = $data['password'];

            if (password_verify($password, $passwordHash)) {
                $_SESSION['loggedIn'] = 1;
                $_SESSION['name'] = $data['name'];
                $_SESSION['email'] = $email;
                $_SESSION['userID'] = $data['id'];

                exit('success');
            } else
                exit('failed');
        }
    } else
        exit('failed');
}

$sqlNumComments = $conn->query("SELECT id FROM comments");
$numComments = $sqlNumComments->num_rows;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>YouTube Comment System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
        .comment {
            margin-bottom: 20px;
        }

        .user {
            font-weight: bold;
            color: black;
        }

        .time, .reply {
            color: gray;
        }

        .userComment {
            color: #000;
        }

        .replies .comment {
            margin-top: 20px;

        }

        .replies {
            margin-left: 20px;
        }

        #registerModal input, #logInModal input {
            margin-top: 10px;
        }

        .fas {
            margin-right: 10px;
        }
    </style>
    <link rel="stylesheet" href="fontawesome-free-5.9.0-web/css/fontawesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-5.9.0-web/css/solid.min.css">
</head>
<body>

<div class="modal" id="registerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registration Form</h5>
            </div>
            <div class="modal-body">
                <input type="text" id="userName" class="form-control" placeholder="Your Name">
                <input type="email" id="userEmail" class="form-control" placeholder="Your Email">
                <input type="password" id="userPassword" class="form-control" placeholder="Password">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="registerBtn">Register</button>
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="logInModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log In Form</h5>
            </div>
            <div class="modal-body">
                <input type="email" id="userLEmail" class="form-control" placeholder="Your Email">
                <input type="password" id="userLPassword" class="form-control" placeholder="Password">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="loginBtn">Log In</button>
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top:50px;">
    <div class="row">
        <div class="col-md-12" align="right">
            <?php
            if (!$loggedIn)
                echo '
                        <button class="btn btn-primary" data-toggle="modal" data-target="#registerModal">Register</button>
                        <button class="btn btn-success" data-toggle="modal" data-target="#logInModal">Log In</button>
                ';
            else
                echo '
                    <a href="logout.php" class="btn btn-warning">Log Out</a>
                ';
            ?>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;margin-bottom: 20px;">
        <div class="col-md-12" align="center">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/u2O_QyPfdpE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <textarea class="form-control" id="mainComment" placeholder="Add Public Comment" cols="30" rows="2"></textarea><br>
            <button style="float:right" class="btn-primary btn" onclick="isReply = false;" id="addComment">Add Comment</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2><b id="numComments"><?php echo $numComments ?> Comments</b></h2>
            <div class="userComments">

            </div>
        </div>
    </div>
</div>

<div class="row replyRow" style="display:none">
    <div class="col-md-12">
        <textarea class="form-control" id="replyComment" placeholder="Add Public Comment" cols="30" rows="2"></textarea><br>
        <button style="float:right" class="btn-primary btn" onclick="isReply = true;" id="addReply">Add Reply</button>
        <button style="float:right" class="btn-default btn" onclick="$('.replyRow').hide();">Close</button>
    </div>
</div>

<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="js/jquery.timeago.js"></script>
<script type="text/javascript">
    var isReply = false, commentID = 0, max = <?php echo $numComments ?>;

    $(document).ready(function () {
        $("#addComment, #addReply").on('click', function () {
            var comment;

            if (!isReply)
                comment = $("#mainComment").val();
            else
                comment = $("#replyComment").val();

            if (comment.length > 5) {
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        addComment: 1,
                        comment: comment,
                        isReply: isReply,
                        commentID: commentID
                    }, success: function (response) {
                        max++;
                        $("#numComments").text(max + " Comments");

                        if (!isReply) {
                            $(".userComments").prepend(response);
                            $("#mainComment").val("");
                        } else {
                            commentID = 0;
                            $("#replyComment").val("");
                            $(".replyRow").hide();
                            $('.replyRow').parent().next().append(response);
                        }

                        calcTimeAgo();
                    }
                });
            } else
                alert('Please Check Your Inputs');
        });

        $("#registerBtn").on('click', function () {
            var name = $("#userName").val();
            var email = $("#userEmail").val();
            var password = $("#userPassword").val();

            if (name != "" && email != "" && password != "") {
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        register: 1,
                        name: name,
                        email: email,
                        password: password
                    }, success: function (response) {
                        if (response === 'failedEmail')
                            alert('Please insert valid email address!');
                        else if (response === 'failedUserExists')
                            alert('User with this email already exists!');
                        else
                            window.location = window.location;
                    }
                });
            } else
                alert('Please Check Your Inputs');
        });

        $("#loginBtn").on('click', function () {
            var email = $("#userLEmail").val();
            var password = $("#userLPassword").val();

            if (email != "" && password != "") {
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        logIn: 1,
                        email: email,
                        password: password
                    }, success: function (response) {
                        if (response === 'failed')
                            alert('Please check your login details!');
                        else
                            window.location = window.location;
                    }
                });
            } else
                alert('Please Check Your Inputs');
        });

        getAllComments(0, max);
        getAllUserReactions();
    });

    function getAllUserReactions() {
        $.ajax({
            url: 'index.php',
            method: 'POST',
            dataType: 'json',
            data: {
                getUserReactions: 1
            }, success: function (response) {
                for (var i=0; i < response.length; i++) {
                    $('i[onclick="react(this,'+response[i].commentID+', \''+response[i].type+'\')"]').each(function () {
                        if (response[i].isReply === $(this).attr('data-isReply'))
                            $(this).css('color', 'blue');
                    });
                }
            }
        });
    }

    function reply(caller) {
        commentID = $(caller).attr('data-commentID');
        $(".replyRow").insertAfter($(caller));
        $('.replyRow').show();
    }

    function calcTimeAgo() {
        $('.timeago').each(function () {
            var timeAgo = $.timeago($(this).attr('data-date'));
            $(this).text(timeAgo);
            $(this).removeClass('timeago');
        });
    }

    function react(caller, commentID, type) {
        $.ajax({
            url: 'index.php',
            method: 'POST',
            dataType: 'json',
            data: {
                react: 1,
                commentID: commentID,
                type: type,
                isReply: $(caller).attr('data-isReply')
            }, success: function (response) {
                if (response.status === 'updated') {
                    if (type === 'up')
                        $(caller).next().css('color', '');
                    else
                        $(caller).prev().css('color', '');
                }

                $(caller).css('color', 'blue');
            }
        });
    }

    function getAllComments(start, max) {
        if (start > max) {
            calcTimeAgo();
            return;
        }

        $.ajax({
            url: 'index.php',
            method: 'POST',
            dataType: 'text',
            data: {
                getAllComments: 1,
                start: start
            }, success: function (response) {
                $(".userComments").append(response);
                getAllComments((start+20), max);
            }
        });
    }
</script>
</body>
</html>